class UserCancelledError extends Error {};

async function waitForClickAndDisable(okElem, cancelElm){
	return new Promise((resolve, reject) => {
		//TODO: Could click one then the other reusing the resolve?
		$(okElem).one('click', function(ev){
			ev.target.disabled = true;
			resolve({value: true, event: ev});
		});
		$(cancelElm).one('click', function(ev){
			resolve({value: false, event: ev});
		});
	});
}

async function signProposedAsset(data){
	data = JSON.parse(data);
	const assetJson = data['asset'];
	const assetHmac = data['hmac'];
	const formattedData = JSON.stringify(JSON.parse(assetJson), null, "\t");
	$('#modalSignProposedAsset .modal-body pre').text(formattedData);
	$('#modalSignProposedAsset').modal('show');

	const trueBtn = $('#inputSignButton');
	const falseBtn = $('#inputSignCancel');

	//TODO: Update to watch modal for other ways to close
	var {value: btnStatus} = await waitForClickAndDisable(trueBtn, falseBtn);
	if(!btnStatus) throw new UserCancelledError("User cancelled the signature");

	const file = $('#inputKeySign')[0].files[0];
	const privateKey = await readKeyFromFile(file);	

	return openpgp.sign({
		message: openpgp.cleartext.fromText(assetJson),
		privateKeys: [privateKey],
		detached: true
	}).then(({signature: sig}) => {
		$('#modalSignProposedAsset').modal('hide');
		return {
			asset: assetJson,
			signature: sig,
			publickey: privateKey.toPublic().armor(),
			hmac: assetHmac
		};
	});
}

async function readTextFromFile(file){
	const reader = new FileReader();
	return new Promise((resolve, reject) => {
		reader.onerror = () => {
			reader.abort();
			reject(new DOMException('Error loading key from file'));
		};
		reader.onload  = () => {
			resolve(reader.result);
		};
		reader.readAsText(file);
	});
}

async function readKeyFromFile(file){
	const privateKeyArmored = await readTextFromFile(file);
	const { keys: [privateKey] } = await openpgp.key.readArmored(privateKeyArmored);
	//await privateKey.decrypt(passphrase);
	return privateKey;
}

async function addAsset(signedAsset){
	return $.ajax('/api/addAsset.php', {
		type: 'POST',
		data: signedAsset
	})
	.then((data, textStatus, xhr) => console.log("Added: ", data))
	.catch(e => { console.error("Error adding asset", e); throw e; });
}


$(function(){
	$('#formAddAsset').on('submit', function(ev){
		var formData = new FormData(ev.target);
		$.ajax('/api/proposeAsset.php', {
			type: 'POST',
			processData: false,
			contentType: false,
			data: formData,
			dataType: "text"
		}).then((data, textStatus, xhr) => {
			return signProposedAsset(data);
		}).then(signedAsset => {
			return addAsset(signedAsset);
		}).then(r => {
			alert("Added!");
		}).catch((ex) => {
			if(ex instanceof UserCancelledError) return;
			console.error("Error adding asset: ", ex);
			var msg = ex;
			if('responseJSON' in ex && 'error' in ex.responseJSON){
				msg = ex.responseJSON.error
			}
			alert("Error adding asset: " + msg)
		});
		ev.preventDefault();
	});

	$('#inputKeyCreate').on('click', function(ev){
		const form = ev.target.form;
		const name = form.elements['firstname'].value + ' '
			+ form.elements['lastname'].value;
		(async() => {
			const { privateKeyArmored, publicKeyArmored, revocationCertificate } = await openpgp.generateKey({
				userIds: [{
					name: name,
					description: form.elements['description']
				}],
			    curve: 'P-256'
			});

			var blob = new Blob([privateKeyArmored], {type: "text/plain;charset=utf-8"});
			saveAs(blob, "Jadeblok-" + name + ".asc");
		})();
	});

	$('#inputKeySign').on('change', function(ev){
		const file = ev.target.files[0];
		readKeyFromFile(file).then((key) => {
			if(key){
				$('#inputSignButton').prop('disabled', false);
			}else{
				console.error("Key error without exception");
			}
		}).catch(e => console.error(e));
	}).trigger("change");
});