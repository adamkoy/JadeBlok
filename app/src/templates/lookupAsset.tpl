{extends file='base.tpl'}


{block name='scripts'}
	<script src="/js/openpgp.min.js"></script>
	<script src="/js/filesaver.min.js"></script>
	<script src="/js/asset.js"></script>
{/block}

{block name='contentContainer'}
	<div class="row">
		<div class="pricing-header px-3 py-3 pt-md-5 pb-md-4 mx-auto text-center">
		<h1 class="display-4">Lookup asset</h1>
		<p class="lead">#3 Lookup an asset.
			This lets you find out information about assets the
			system is tracking. You can either upload the asset
			or give it's hash. If you give both the hash takes
			priority.</p>
		</div>
	</div>
	<div class="row " style="margin-top:1em;">
		<div class="col offset-md-3 col-md-6">
			<div class="card h-100">
				<div class="card-header">
					#3 Lookup asset
				</div>
				<div class="card-body">
					<form method="POST" action="/api/getAsset.php" enctype="multipart/form-data" >
						<div class="form-check form-check-inline">
							<input class="form-check-input" type="radio" name="format" id="inputFormatHTML" value="html" checked="true">
							<label class="form-check-label" for="inputFormatHTML">HTML</label>
						</div>
						<div class="form-check form-check-inline">
							<input class="form-check-input" type="radio" name="format" id="inputFormatJson" value="json">
							<label class="form-check-label" for="inputFormatJson">JSON</label>
						</div>
						<div class="form-group">
							<label for="inputAssetFile">By asset</label>
							<input type="file" class="form-control-file" id="inputAssetFile" name="assetFile"/>
						</div>
						<div class="form-group">
							<label for="inputHash">By hash</label>
							<input type="text" class="form-control" id="inputHash" name="sha256"/>
						</div>
						<button type="submit" class="btn btn-primary">Submit</button>
					</form>
				</div>
			</div>
		</div>
	</div>
	<div cass="row">
		<h1 class="py-3">API</h1>
		<h2>Request</h2>
		<table class="table">
			<thead>
				<tr>
					<th colspan="3">URL: /api/getAsset.php</th>
				</tr>
				<tr>
					<th>Parameter</th>
					<th>Arguments</th>
					<th>Description</th>
				</tr>
			</thead>
			<tbody>
				<tr><td>format</td><td>html | json</td><td>Response format</td></tr>
				<tr><td>assetFile</td><td>[file]</td><td>The asset to search for</td></tr>
				<tr><td>sha256</td><td>[hash]</td><td>The sha256 hash of the asset to search for</td></tr>
			</tbody>
		</table>
		<h2>Response format (json)</h2>
		<p>
			Documentation for the retrieval api. Note there are two levels of signature.
			The first is the chronicle based signature signed by Jadeblok to record the transactions.
			The second is the signing of an individual asset by the uploader.
			Jadeblok will reject attempts to add assets where the asset has already been registered under a different key.
		</p>
		<pre><code>
		Main response
		[
			{
				contents: &lt;JSON encoded signedAsset&gt;,
				pev: &lt;previousHash&gt;, 
				hash: &lt;currentHash&gt;, 
				summary: &lt;summaryHash&gt;, 
				created: &lt;creationDate&gt;, 
				publickey: &lt;jadeblok publickey&gt;, 
				signature: &lt;jadeblok signature&gt;
			}
			{ ... }
			{ ... }
		]

		
		signedAsset
		{
			asset: &lt;JSON encoded asset&gt;,
			signature: &lt;Armored PGP signature&gt;, 
			publickey: &lt;Armored PGP publickey&gt;
		}

		asset
		{
			version: &lt;version identifier&gt;,
			asset: {
				sha256: &lt;sha256 hash of asset&gt;
			},
			date: &lt;date formatted as RFC2822&gt;
			rating: &lt;null|U|PG|12|18&gt;
			uploader: {
				firstname: &lt;uploader first name&gt;
				lastname: &lt;uploader last name&gt;
			},
			description: &lt;description&gt;
		}
		</code></pre>
	</div>
{/block}