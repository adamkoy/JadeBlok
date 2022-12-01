{extends file='base.tpl'}


{block name='scripts'}
	<script src="/js/openpgp.min.js"></script>
	<script src="/js/filesaver.min.js"></script>
	<script src="/js/asset.js"></script>
{/block}

{block name='contentContainer'}
	<div class="row">
		<div class="pricing-header px-3 py-3 pt-md-5 pb-md-4 mx-auto text-center">
		<h1 class="display-4">Jadeblok</h1>
		<p class="lead">Register digital assets with Jadeblok</p>
		</div>
	</div>
	<div class="row">
		<div class="card-deck col-12 text-center">
			<div class="card mb-4 box-shadow">
				<div class="card-header">
					<h4 class="my-0 font-weight-normal">Create key</h4>
				</div>
				<div class="card-body">
					<p>Create a unique personal key to manage your assets</p>
					<a href="createKey.php" class="btn btn-lg btn-block btn-primary">Create a key</a>
				</div>
			</div>
			<div class="card mb-4 box-shadow">
				<div class="card-header">
					<h4 class="my-0 font-weight-normal">Add an asset</h4>
				</div>
				<div class="card-body">
					<p>Upload and edit your existing assets to the system</p>
					<a href="/addAsset.php" class="btn btn-lg btn-block btn-primary">Add asset</a>
				</div>
			</div>
			<div class="card mb-4 box-shadow">
				<div class="card-header">
					<h4 class="my-0 font-weight-normal">Lookup asset</h4>
				</div>
				<div class="card-body">
					<p>Lookup a registered asset to get its information and history</p>
					<a href="/lookupAsset.php" class="btn btn-lg btn-block btn-primary">Lookup asset</a>
				</div>
			</div>
		</div>
	</div>

{/block}