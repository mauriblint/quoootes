<div class="row justify-content-center">
	<div class="col-12 col-md-10 col-lg-8 col-xl-6 my-5">
		<div class="card mb-0">
			<div class="card-body p-5">
				<div class="text-center">
					<h1>Need Help?</h1>
					<p class="text-muted">We are here to help you, please write a message and include all relevant information.</p>
				</div>

				<form id="contactForm" novalidate class="needs-validation" data-bs-action="/support" data-bs-method="POST">
					<div class="mb-4">
						<label for="exampleInputEmail1" class="form-label">What can we help with?</label>
						<select name="topic" class="form-select" required>
							<option value="">Select a topic</option>
							<option value="I have questions before signing up">I have questions before signing up</option>
							<option value="I’d like to request a feature">I’d like to request a feature</option>
							<option value="I’d like to report an error">I’d like to report an error</option>
							<option value="I have a question or feedback">I have a question or feedback</option>
							<option value="Something else is on my mind">Something else is on my mind</option>
						</select>
					</div>
					<div class="mb-4">
						<label for="contactName" class="form-label" >Your Name</label>
						<input type="text" class="form-control" name="name" required id="contactName" aria-describedby="nameHelp">
					</div>
					<div class="mb-4">
						<label for="exampleInputEmail1" class="form-label">Your email address</label>
						<input type="email" value="<?= ($user) ? $user['email'] : '' ?>" name="email" required class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
						<small class="text-muted">We will reply to this email</small>
					</div>
					<div class="mb-4">
						<label for="exampleFormControlTextarea1" class="form-label">What’s your question or issue?</label>
						<small class="d-block mb-3 text-muted">Tell us about the issue or question in detail. The more detail you provide, the faster we can assign the case and answer it.</small>
						<textarea required minlength="10" name="message" class="form-control" id="exampleFormControlTextarea1" rows="5"></textarea>
					</div>
					<div>
						<button class="btn btn-primary" type="submit">Send</button>
					</div>
				</form>
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