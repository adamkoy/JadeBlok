<!doctype html>
<html lang="en">
	<head>
		<!-- Required meta tags -->
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

		<!-- Bootstrap CSS -->
		<link rel="stylesheet" href="/bootstrap-4.4.1-dist/css/bootstrap.min.css">
		<!-- Optional JavaScript -->
		<script src="/js/jquery-3.4.1.min.js"></script>
		<script src="/bootstrap-4.4.1-dist/js/bootstrap.bundle.min.js"></script>
		{block name='scripts'}{/block}

		<title>JadeBlok</title>
	</head>
	<body>
		<nav class="navbar navbar-expand-lg navbar-light bg-light">
			<a class="navbar-brand" href="/">JadeBlok</a>
			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>
			<div class="collapse navbar-collapse" id="navbarSupportedContent">
				<ul class="navbar-nav mr-auto">
					{foreach from=$navLinks key=k item=i}
					{assign var='active' value=$k|lower==$navSelect|lower}
					<li class="nav-item{if $active} active{/if}">
						<a class="nav-link" href="{$i}">{$k|escape} {if $active}<span class="sr-only">(current)</span>{/if}</a>
					</li>
					{/foreach}
				</ul>
			</div>
		</nav>

		<div class="container">
			{block name='contentContainer'}{/block}
		</div>
	</body>
</html>