{extends file='base.tpl'}


{block name='scripts'}
	<script src="/js/openpgp.min.js"></script>
	<script src="/js/filesaver.min.js"></script>
	<script src="/js/asset.js"></script>
{/block}

{block name='contentContainer'}
	<div class="modal" tabindex="-1" role="dialog" id="modalSignProposedAsset">
		<div class="modal-dialog modal-lg" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Sign proposed asset</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<pre>Error loading data</pre>
					<div class="form-group">
						<label for="inputKeySign">Upload key to sign and confirm</label>
						<input type="file" class="form-control-file" id="inputKeySign" name="key">
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-success" data-dismiss="modal" disabled="disabled" id="inputSignButton">Sign</button>
					<button type="button" class="btn btn-danger" data-dismiss="modal" id="inputSignCancel">Cancel</button>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="pricing-header px-3 py-3 pt-md-5 pb-md-4 mx-auto text-center">
		<h1 class="display-4">Add an asset</h1>
		<p class="lead">#2 Upload an asset to the system.
			After filling in the information you'll be shown the
			result and asked to sign it with the key you created in
			the first step. You'll need the same key later if you
			ever want to make changes to the asset information.
		</p>
		</div>
	</div>
	<div class="row " style="margin-top:1em;">
		<div class="col offset-md-3 col-md-6">
			<div class="card h-100">
				<div class="card-header">
					#2 Add asset
				</div>
				<div class="card-body">
					<form method="POST" action="/api/addAsset.php" enctype="multipart/form-data" id="formAddAsset">
						<div class="form-row">
							<div class="form-group col-md-6">
								<label for="inputFirstname">Firstname</label>
								<input type="text" class="form-control" id="inputFirstname" name="firstname" required="true">
							</div>
							<div class="form-group col-md-6">
								<label for="inputLastname">Lastname</label>
								<input type="text" class="form-control" id="inputLastname" name="lastname" required="true">
							</div>
						</div>
						<div class="form-group">
							<label for="inputAssetFile">Upload asset</label>
							<input type="file" class="form-control-file" id="inputAssetFile" name="assetFile" required="required">
						</div>
						<div class="form-group">
							<label for="inputAssetRating">Rating</label>
							<select class="form-control" id="inputAssetRating" name="rating">
								<option value="" selected="true">Unrated</option>
								<option>U</option>
								<option>PG</option>
								<option>12</option>
								<option>18</option>
							</select>
						</div>
						<div class="form-group">
							<label for="inputMisc">Other notes</label>
							<textarea class="form-control" id="inputMisc" rows="3" name="description"></textarea>
						</div>
						<button type="submit" class="btn btn-primary">Submit</button>
					</form>
				</div>
			</div>
		</div>
	</div>
{/block}