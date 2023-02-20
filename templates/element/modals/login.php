<div class="modal upgrade-modal login-modal modal-sm" tabindex="-1" role="dialog" id="login-modal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

            <div class="modal-body">

                <div class="text-center mt-3">
                    <div class="mb-4">
                        <img class="logo" src="/img/isologo.svg" alt="Quoootes - Free Quote Maker and Generator" width="60">
                    </div>
                    <span class="h1"><span>Welcome to <br>Quoootes.com</span></span>
                    <p class="text-muted mt-3">Create a free account and get all the power of Quoootes without any limitations.</p>
                </div>
                <div class="mt-5 mb-4 mx-auto d-flex justify-content-center">
                    <div class="g_id_signin"
                        @click="onGoogleLoginClicked()"
                        data-type="standard"
                        data-size="large"
                        data-width="240"
                        data-callback="handleToken"
                        data-theme="filled_blue"
                        data-text="continue_with"
                        data-shape="rectangular"
                        data-logo_alignment="left">
                    </div>
                    <!-- filled_black |  filled_blue | outline -->
                    <!-- https://developers.google.com/identity/gsi/web/reference/html-reference?hl=es-419 -->
                </div>
            </div>
        </div>
    </div>
</div>