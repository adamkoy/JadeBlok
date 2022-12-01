{extends file='base.tpl'}
{block name='contentContainer'}
	<div class="row">
		<div class="col-xs-12">
			<h1>View chain</h1>
		</div>
	</div>
	<div class="row">
		{foreach $chain as $link}
			<div class="col" style="margin-bottom: 1em">
				<div class="card">
					<div class="card-header">
						{$link['created']|escape}
						-- {$link['prev']|truncate:10|escape}
						-> {$link['hash']|truncate:10|escape}
					</div>
					<div class="card-body">
						{* {$link['contents']|escape} *}
						<form>
							<div class="form-group row">
								<label for="inputName{$link@iteration}" class="col-sm-2 col-form-label">Name</label>
								<div class="col-sm-10">
									<input type="text" class="form-control-plaintext" aria-label="Name" readonly="readonly" id="inputName{$link@iteration}"
										value="{$link['contents']->getUploaderFirstname()|escape} {$link['contents']->getUploaderLastname()|escape}" />
								</div>
							</div>
							<div class="form-group row">
								<label for="inputAssetsha256{$link@iteration}" class="col-sm-2 col-form-label">Asset</label>
								<div class="col-sm-10">
									<input type="text" class="form-control-plaintext" aria-label="Asset hash" readonly="readonly" id="inputAssetsha256{$link@iteration}"
										value="{$link['contents']->getSha256()|escape}"/>
								</div>
							</div>
							<div class="form-group row">
								<label for="inputDate{$link@iteration}" class="col-sm-2 col-form-label">Date</label>
								<div class="col-sm-10">
									<input type="text" class="form-control-plaintext" readonly="readonly" id="inputDate{$link@iteration}"
										aria-label="Date" value="{$link['contents']->getDate()->format(DateTime::RFC2822)|escape}"/>
								</div>
							</div>
							<div class="form-group row">
								<label for="inputRating{$link@iteration}" class="col-sm-2 col-form-label">Rating</label>
								<div class="col-sm-10">
									<input type="text" class="form-control-plaintext" readonly="readonly" id="inputRating{$link@iteration}"
										aria-label="Date" value="{$link['contents']->getRating()|escape}"/>
								</div>
							</div>
							<div class="form-group row">
								<label for="inputMisc{$link@iteration}" class="col-sm-2 col-form-label">Description</label>
								<div class="col-sm-10">
									<textarea class="form-control" readonly="readonly" id="inputMisc{$link@iteration}"
										aria-label="Date">{$link['contents']->getDescription()|escape}</textarea>
								</div>
							</div>
						</form>
					</div>				
					<div class="card-footer text-muted">
						{$link['signature']|escape}
					</div>
				</div>
			</div>
		{/foreach}
	</div>
{/block}