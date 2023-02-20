<?php
    // $text = 'There is one pain, I often feel, which you will never know. It’s caused by the absence of you.';
    // $text = '';
    $quick_editor_faqs = array();

    // $quick_editor_faqs[] = [
    //     'q' => '222How to send message on WhatsApp without adding to contacts?',
    //     'a' => '222To send a message on whatsapp without adding the number to your contacts, simply enter the phone number on our <strong>free tool to send whatsapp messages</strong> (<a href="https://send-whatsapp.com/">Send-Whatsapp.com</a>) and click on "Send Message". The WhatsApp app will be opened and you will be able to start a conversion, withour previusly saving that number to your contacts!'
    // ];

    echo $this->Html->css('quick-studio.css');
    echo $this->Html->css('vendor/vue-loading.css');
?>


<div id="studio" v-cloak>


<div class="quick-studio">
    <!-- Header -->
    <div class="quick-studio__header">
        <div>
            <a class="navbar-brand d-none d-md-block" href="/">
                <img class="logo" src="/img/logo.png" alt="Quoootes - Free Quote Maker and Generator" height="26" width="161">
            </a>
            <a class="navbar-brand d-md-none" href="/">
                <img class="isologo" src="/img/isologo.svg?v=1" alt="Quoootes - Free Quote Maker and Generator" width="40">
            </a>
        </div>
        <div v-if="!show_wizard">
            <button class="btn btn-light me-2" @click="onEdit()">Edit Quote</button>
            <button class="btn btn-primary" @click="onSave()">Save</button>
        </div>
    </div>

    <!-- Wizard -->
    <div class="quick-studio__wizard" v-if="show_wizard">
        <h1 class="mb-4">Let's create something beautiful.</h1>
        <div class="mb-3">
            <label class="form-label">Quote</label>
            <!-- {{ text.length }} / 300 -->
            <textarea class="form-control w-100" placeholder="Enter your quote" maxlength="500" name="text" style="height: 100px;" v-model="text" @input="onTextChange()"></textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Author</label>
            <input class="form-control w-100" placeholder="Author (optional)" maxlength="40" name="author" v-model="text_footer" @input="onTextChange()"></input>
        </div>
        <div>
            <button class="w-100 btn btn-lg btn-brand mt-4" @click="onStart()">Continue →</button>
        </div>
    </div>

    <!-- Preview -->
    <div class="quick-studio__top" v-if="!show_wizard">
        <div class="quick-studio__preview">
            <img v-if="rendered_image" :src="rendered_image" alt="">
            <div v-else class="loader-wrap">
                <div class="loader"></div>
            </div>

            <!-- Premium Options -->
            <div class="quick-studio__pro" v-if="rendered_image">
                <div>
                    <button class="btn btn-link btn-sm p-0 me-4" @click="onAddLogo()">
                        <i class="bi bi-star-fill"></i> Upload your logo
                    </button>
                </div>
                <!-- <div>
                    <button class="btn btn-link btn-sm p-0">Remove Watermark</button>
                </div> -->
            </div>
        </div>

        <div class="quick-studio__options">
            <!-- <button class="btn btn-link" @click="onEdit()">Edit Quote</button> -->
            <!-- <div class="mb-3">
                <label class="form-label">Quote {{ text.length }} / 300</label>
                <textarea class="form-control w-100" placeholder="Enter your quote" name="text" style="height: 100px;" v-model="text" @input="onTextChange()"></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Author</label>
                <input class="form-control w-100" placeholder="Enter your quote" name="author" v-model="text_footer" @input="onTextChange()"></input>
            </div> -->

            <div class="mb-3">
                <i class="bi bi-lock-fill mr-1"></i>
                <label class="form-label">Upload your Logo</label>
                <div class="form-group" @click="onAddLogo()">
                    <div class="d-grid gap-2" v-if="!template.logoImage">
                        <button class="btn btn-block btn-outline-primary">
                            <i class="bi bi-cloud-arrow-up mr-1"></i> Upload
                        </button>
                    </div>
                    <div class="quick-studio__options-logo" v-if="template.logoImage">
                        <img class="bg-thumb" :src="template.logoImage" alt="">
                        <button class="remove-btn btn btn-link btn-sm" @click.stop="template.logoImage=''">
                            <i class="bi bi-x-lg ml-2" data-toggle="tooltip" data-title="Remove"></i>
                        </button>
                    </div>
                    <input type="file" ref="upload_logo_image_input" @change="onLogoImageSelected($event)" class="form-control-hidden-file">
                </div>
            </div>


            <div v-if="template.logoImage">
                <label class="form-label">Logo Size</label>
                <select class="form-select" aria-label="Default select example" v-model="template.logoSize" @change="refreshPreview()">
                    <option value="40">Small</option>
                    <option value="60" selected>Medium</option>
                    <option value="90">Large</option>
                    <option value="150">Extra Large</option>
                </select>
            </div>

            <!-- <div class="form-check form-switch" v-if="is_free">
                <label class="form-check-label" for="flexSwitchCheckDefault"><i class="bi bi-lock-fill mr-1"></i> Remove Watermark</label>
                <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault">
            </div> -->

            <!-- <div class="mb-3" @click="onRemoveWatermark()" v-if="is_free">
                <i class="bi bi-lock-fill mr-1"></i>
                <label class="form-label">Remove Watermark</label>
            </div> -->
        </div>
    </div>

    <!-- Templates -->
    <div class="quick-studio__templates" v-if="!show_wizard">
        <div class="row">
            <h3 class="mb-2">Templates</h3>
            <div class="templates__categories mb-4 d-none">
                <div class="badge text-bg-light">Nature</div>
                <div class="badge text-bg-light">Love</div>
            </div>
            <div v-for="template in templates" class="col-6 col-md-3 col-lg-2 mb-2">
                <div class="template-card">
                    <div class="template-card-inner">
                        <img :src="template.preview_url" class="img-fluid" @click="onTemplateSelected(template)">
                        <!-- <div class="is-premium badge badge-light" v-if="template.is_premium"><i class="bi bi-star-fill mr-1"></i> Premium</div> -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="pt-2 pb-4 px-3 text-center" v-if="!show_wizard">
        Looking the Advanced editor? <br><a href="/studio?ref=quick-editor">Open Advanced Editor</a>
    </div>

    <!-- <div style="display:block; position:fixed; bottom:0; left: 0; "> -->
    <div style="display:block; position:fixed; bottom:0; left: -1000px;">
        <div class="quick-studio__canvas" :style="getEditorStyles()">
            <div class="quick-studio__canvas-filter" v-if="template.filter" :style="{background: template.filter, opacity: template.filterOpacity}"></div>
            <div class="quick-studio__canvas-bg" v-if="template.backgroundImage" :style="getEditorBackgroundStyles()"></div>

            <div class="quick-studio__canvas-text jtextfill" id="StudioCanvasText" :style="getTextStyles()">
                <div class="quick-studio__canvas-text-container fillthis" id="StudioCanvasTextContainer">
                    <div id="StudioCanvasTextDiv">{{ text }}</div>
                    <div class="quick-studio__canvas-text-footer" :style="getAuthorStyles()">{{ text_footer }}</div>
                </div>
            </div>
            <!-- <div class="studio__watermark" v-if="is_free" :class="'pos-' + template.watermarkPosition">QUOOOTES.COM</div> -->
            <img class="studio__watermark" src="/img/watermark_3.png" v-if="is_free" :class="'pos-' + template.watermarkPosition">
            <div v-if="!is_free && template.logoImage" class="studio__logo" :class="'pos-' + template.watermarkPosition" :style="getLogoStyles()">
                <img :src="template.logoImage" :style="getLogoStyles()">
            </div>
        </div>
    </div>
</div>
    <?= $this->element('modals/upgrade'); ?>
</div>
<!-- end #app -->

<!-- <div class="row home-section justify-content-center">
	<div class="col-12 col-md-10 col-lg-8 text-center">
        <h2>Quoootes Maker <br> Frequently Asked Questions</h2>
        <p class="lead mt-4"></p>
	</div>

    <div class="col-12 col-xl-10 col-xxl-8 px-4 py-3">
        <?php //echo $this->element('general/faqs', ['data' => $quick_editor_faqs]); ?>
    </div>
</div> -->

<script>
    var __NEXT_DATA__ = {
        template: <?php echo json_encode($template); ?>,
        design: <?php echo json_encode($design); ?>,
        text: "<?= addslashes($text); ?>",
        author: "<?= $author; ?>",
        backgrounds: <?php echo json_encode($backgrounds); ?>,
        templates: <?php echo json_encode($templates); ?>,
        tags: <?php echo json_encode($category_list); ?>,
        popular_tags: <?php echo json_encode($popular_tags); ?>,
        user: <?php echo json_encode($user); ?>,
    }

    <?php if ($action): ?>
        __NEXT_DATA__.mode = '<?= $action ?>';
    <?php endif; ?>
</script>

<?php
$this->start('script_from_view');
if ($env === 'DEV') {
    echo $this->Html->script([
        'studio/vue.js',
        'vendor/jquery.min.js',
        'vendor/jquery.textfill.js',
        'studio/html2canvas.min.js',
        'vendor/vue-loading.js',
        'studio/polyfills.js',
        'studio/fonts.js',
        'studio/studio.mixin.js',
        'studio/studio.quick.js',
    ], ['defer' => true]);
}

if ($env === 'DEVa') {
    echo $this->Html->script([
        'qs.vendors.build.js',
        'qs.app.build.js',
    ], ['defer' => true]);
}
$this->end();
?>

<!-- <script src="https://cdn.jsdelivr.net/npm/vue-loading-overlay@3"></script> -->
<!-- Init the plugin and component-->
<script>

</script>