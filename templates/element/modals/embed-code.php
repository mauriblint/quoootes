<div class="modal modal-md" tabindex="-1" role="dialog" id="embed-code-modal">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Get Embed Code</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<!-- Nav tabs -->
				<ul class="nav nav-pills nav-fill" id="myTab" role="tablist">
					<li class="nav-item">
						<a class="nav-link" id="home-tab" data-bs-toggle="tab" href="#code-sm" role="tab">Small</a>
					</li>
					<li class="nav-item">
						<a class="nav-link active" id="profile-tab" data-bs-toggle="tab" href="#code-md" role="tab">Medium</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" id="messages-tab" data-bs-toggle="tab" href="#code-lg" role="tab">Large</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" id="settings-tab" data-bs-toggle="tab" href="#code-rs" role="tab">Responsive</a>
					</li>
				</ul>
			</div>

			<div class="modal-body">
				<!-- Tab panes -->
				<div class="tab-content">
					<div class="tab-pane" id="code-sm" role="tabpanel" aria-labelledby="home-tab">
						<div class="code"><samp><?= getEmbedCode($quote['id'], 250, 440) ?></samp></div>
					</div>
					<div class="tab-pane active" id="code-md" role="tabpanel" aria-labelledby="profile-tab">
						<div class="code"><samp><?= getEmbedCode($quote['id'], 400, 440) ?></samp></div>
					</div>
					<div class="tab-pane" id="code-lg" role="tabpanel" aria-labelledby="messages-tab">
						<div class="code"><samp><?= getEmbedCode($quote['id'], 600, 440) ?></samp></div>
					</div>
					<div class="tab-pane" id="code-rs" role="tabpanel" aria-labelledby="settings-tab">
						<div class="code"><samp><?= getEmbedCode($quote['id'], '100%', 440) ?></samp></div>
					</div>
				</div>
			</div>

			<div class="modal-footer p-4">
				<button type="button" class="btn btn-light btn-block" data-bs-dismiss="modal">Got it!</button>
			</div>
		</div>
	</div>
</div>