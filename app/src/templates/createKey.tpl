{extends file='base.tpl'}


{block name='scripts'}
	<script src="/js/openpgp.min.js"></script>
	<script src="/js/filesaver.min.js"></script>
	<script src="/js/asset.js"></script>
{/block}

{block name='contentContainer'}
	<div class="row">
		<div class="pricing-header px-3 py-3 pt-md-5 pb-md-4 mx-auto text-center">
		<h1 class="display-4">Create a key</h1>
		<p class="lead">#1 Create a personal key to protect your assets.
			This creates a digital key for you to store - this proves you own the assets.
			Be careful wth it - it can't be retrieved if lost!</p>
		</div>
	</div>
	<div class="row" style="margin-top:1em;">
		<div class="col offset-md-3 col-md-6">
			<div class="card h-100">
				<div class="card-header">
					#1 Create identity
				</div>
				<div class="card-body">
					<form>
						<div class="form-row">
							<div class="form-group col-md-6">
								<label for="inputKeyFirstname">Firstname</label>
								<input type="text" class="form-control" id="inputKeyFirstname" name="firstname">
							</div>
							<div class="form-group col-md-6">
								<label for="inputKeyLastname">Lastname</label>
								<input type="text" class="form-control" id="inputKeyLastname" name="lastname">
							</div>
						</div>
						<div class="form-group">
							<label for="inputKeyMisc">Misc</label>
							<input type="text" class="form-control" id="inputKeyMisc" name="name">
						</div>
						<button type="button" class="btn btn-primary" id="inputKeyCreate">Create</button>
					</form>
				</div>
			</div>
		</div>
	</div>
{/block}