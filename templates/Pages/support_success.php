<div class="row justify-content-center">
	<div class="col-12 col-md-10 col-lg-8 col-xl-6 my-5">
		<div class="card mb-0">
			<div class="card-body p-5">
				<div class="text-center">
					<img src="/img/email-sent.png" width="110" alt="">
					<h1>We received your message</h1>
					<p class="text-muted">We will be responding as soon as possible, normally in the next few hours. You will receive the answer in your email:</p>

					<p><strong><?= $sent_to ?></strong></p>

					<p>Have a good day, <br> Quoootes Support Team</p>
				</div>
			</div>
		</div>
	</div>
</div>

<style>
	form {
		max-width: 420px;
		margin: 50px auto;
	}

	form label {
		font-weight: 700;
	}
</style>

<script>
	(function() {
		var forms = document.querySelectorAll('.needs-validation');

		Array.prototype.slice.call(forms)
			.forEach(function(form) {

				if (form.dataset.bsAction) {
					form.action = form.dataset.bsAction;
					form.method = form.dataset.bsMethod;
					delete form.dataset.bsAction;
					delete form.dataset.bsMethod;
				}

				form.addEventListener('submit', function(event) {
					if (!form.checkValidity()) {
						event.preventDefault()
						event.stopPropagation()
						form.classList.add('was-validated')
					}
				}, false)
			})
	})()
</script>