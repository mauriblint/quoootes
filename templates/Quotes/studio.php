<?php
    $this->disableAutoLayout();

    // echo '<pre>';
    // var_dump($template);
    // echo $template;
    // echo json_encode($template, JSON_UNESCAPED_UNICODE);
    // die;
    $env = env('ENV', 'DEV');
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quoootes - Studio</title>
    <?= $this->Html->css('/bootstrap4/css/bootstrap.min.css') ?>
    <?= $this->Html->css('studio.css') ?>
    <link href="/favicon.ico?version=3" type="image/x-icon" rel="icon"/>
    <link href="/favicon.ico?version=3" type="image/x-icon" rel="shortcut icon"/>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.0/font/bootstrap-icons.min.css" referrerpolicy="no-referrer" media="print" onload="this.onload=null;this.removeAttribute('media');" />
    <link rel="stylesheet" href="/fonts/kredit/styles.css" media="print" onload="this.onload=null;this.removeAttribute('media');">
    <link rel="stylesheet" href="/fonts/1942/stylesheet.css" media="print" onload="this.onload=null;this.removeAttribute('media');">
    <link rel="stylesheet" href="/fonts/team_spirit/stylesheet.css" media="print" onload="this.onload=null;this.removeAttribute('media');">
    <link rel="stylesheet" href="/fonts/the-bold-font/stylesheet.css" media="print" onload="this.onload=null;this.removeAttribute('media');">
    <link rel="stylesheet" href="/fonts/cocogoose/stylesheet.css" media="print" onload="this.onload=null;this.removeAttribute('media');">
    <link rel="stylesheet" href="/fonts/brook/stylesheet.css" media="print" onload="this.onload=null;this.removeAttribute('media');">
    <link rel="stylesheet" href="/fonts/french-love-simplicity/stylesheet.css" media="print" onload="this.onload=null;this.removeAttribute('media');">
    <link rel="stylesheet" href="/fonts/obelix-pro/stylesheet.css" media="print" onload="this.onload=null;this.removeAttribute('media');">
    <style>
        [v-cloak] { display: none; }
    </style>
    <?php echo $this->element('meta/analitycs');  ?>
</head>
<body>


    <div id="studio" class="studio" v-cloak>

        <div class="page-info" v-if="onboarding_step === 9">
            <h1>Free Quote Maker and Generator Online</h1>
            <p>Quoootes's quote editor is the best, simple, and free <strong>online quote generator for Instagram</strong>, Pinterest, Facebook and any social network</p>
            <p>You will be able to create beatiful quotes about Life, Love, Positive Quotes, Sad Quotes, just by enter a few lines of text and choose a pre designed template.</p>
            <p>Of course, you always can use your imagination to <strong>create a custom quote</strong> or edit a pre-designed quote template.</p>
        </div>

        <div class="studio__header" v-if="page !== 'start'">
            <a href="/">
                <img src="/img/logo.png" alt="Quoootes - Free Quote Maker and Generator" class="logo-desktop d-none d-md-inline">
                <img src="/favicon.ico" alt="Quoootes - Free Quote Maker & Generator" width="30" class="d-md-none">
            </a>

            <div class="ml-auto d-none d-md-block">
                <div class="dropdown" data-toggle="tooltip" data-title="Canvas Size" data-placement="right">
                    <button class="btn btn-dark btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="text-capitalize">{{ getLayoutFullName(template.layout) }}</span>
                    </button>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" v-for="layout in layouts" style="font-size: 16px;" href="#" @click="template.layout = layout.key;">{{ layout.name }}</a>
                    </div>
                </div>
            </div>
            <div class="ml-auto">
                <button @click="onViewTemplates()" class="btn btn-light mb-0 btn-upper" v-if="mode === 'create'"><i class="bi bi-star-fill mr-1 text-warning"></i> Templates</button>
                <button class="btn btn-primary mb-0 ml-3 btn-upper" @click="saveDesign" v-if="mode === 'create'">Save</button>
                <button class="btn btn-primary mb-0 ml-3 btn-upper" @click="saveTemplate" v-if="mode === 'edit_template'">Save Changes</button>
                <button class="btn btn-primary mb-0 ml-3 btn-upper" @click="saveTemplate" v-if="mode === 'create_template'">Save Template</button>

                <button class="btn btn-success mb-0 ml-3 btn-upper" @click="createNewTemplate()" v-if="mode === 'edit_template'">New Template</button>
            </div>
        </div>

        <div class="studio__body" v-if="page === 'editor'">
            <div class="sidebar scrollable">

                <!--  Text -->
                <div class="form-group">
                    <label for="">Text</label>
                    <textarea rows="3" v-model="text" ref="textInput" class="form-control"></textarea>
                </div>

                <div class="form-group">
                    <div class="d-flex justify-content-between align-items-center">
                        <label class="mb-0 label-w-50">Header</label>
                        <div>
                            <input type="checkbox" class="form-controla" v-model="template.headerVisible">
                        </div>
                    </div>
                    <input v-if="template.headerVisible" type="text" v-model="text_header" class="form-control mt-2" maxlength="30">
                </div>

                <div class="form-group">
                    <div class="d-flex justify-content-between align-items-center">
                        <label class="mb-0 label-w-50">Footer</label>
                        <div>
                            <input type="checkbox" class="form-controla" v-model="template.footerVisible">
                        </div>
                    </div>
                    <input v-if="template.footerVisible" type="text" v-model="text_footer" class="form-control mt-2" maxlength="30">
                </div>

                <div class="form-group">
                    <label>Font</label>
                    <div class="dropdown">
                        <button class="btn btn-dark btn-block dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span :style="{fontFamily: template.fontFamily}" >{{ template.fontFamily }}</span>
                        </button>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" v-for="font in fonts" style="font-size: 18px;" href="#" :style="{fontFamily: font.name, fontWeight: (font.weight) ? font.weight : 400}" @click="template.fontFamily = font.name;">{{ font.title || font.name }}</a>
                        </div>
                    </div>
                </div>

                <div class="form-group d-flex justify-content-between align-items-center form-group-mh">
                    <label class="mb-0 label-w-50">Text Color</label>
                    <div class="color-picker">
                        <div class="color-picker__swatch" @click="popovers.color_picker_3_open=!popovers.color_picker_3_open" :style="{ background: template.color }"></div>
                        <div class="color-picker__box" v-if="popovers.color_picker_3_open">
                            <color-picker :value="template.color" @input="(c) => { template.color = c.hex8 }"></color-picker>
                        </div>
                    </div>
                </div>

                <div class="form-group d-flex justify-content-between align-items-center">
                    <label class="mb-0" style="width: 180px;">Background Color</label>
                    <div class="color-picker">
                        <div class="color-picker__swatch" @click="popovers.color_picker_2_open=!popovers.color_picker_2_open" :style="{ background: template.backgroundColor }"></div>
                        <div class="color-picker__box" v-if="popovers.color_picker_2_open">
                            <color-picker :value="template.backgroundColor" @input="(c) => { template.backgroundColor = c.hex8 }"></color-picker>
                        </div>
                    </div>
                </div>

                <div class="form-group d-flex justify-content-between align-items-center">
                    <label class="mb-0">Text Align</label>
                    <div class="btn-group btn-group-icons" role="group">
                        <button type="button" class="btn btn-dark" :class="{'active': template.textAlign === 'left'}" @click="template.textAlign='left'"><i class="bi bi-text-left"></i></button>
                        <button type="button" class="btn btn-dark" :class="{'active': template.textAlign === 'center'}" @click="template.textAlign='center'"><i class="bi bi-text-center"></i></button>
                        <button type="button" class="btn btn-dark" :class="{'active': template.textAlign === 'right'}" @click="template.textAlign='right'"><i class="bi bi-text-right"></i></button>
                    </div>
                </div>

                <div class="form-group d-flex justify-content-between align-items-center form-group-mh">
                    <label class="mb-0 label-w-50">Line Spacing</label>
                    <input type="range" v-model="template.lineHeight" step="0.1" min="0.6" max="2" class="form-control-range">
                    <span class="ml-1r range-value">{{ template.lineHeight }}</span>
                </div>

                <div class="form-group d-flex justify-content-between align-items-center form-group-mh">
                    <label class="mb-0 label-w-50">Font Size</label>
                    <input type="range" v-model="template.fontSize" step="2" min="10" max="80" class="form-control-range">
                    <span class="ml-1r range-value">{{ template.fontSize }}</span>
                </div>

                <div class="form-group d-flex justify-content-between align-items-center form-group-mh">
                    <label class="mb-0 label-w-50">Text Width</label>
                    <input type="range" v-model="template.boxWidth" min="45" max="100">
                    <span class="ml-1r range-value">{{ template.boxWidth }}</span>
                </div>

                <div class="form-group d-flex justify-content-between align-items-center form-group-mh">
                    <label class="mb-0 label-w-50">Letter Spacing</label>
                    <input type="range" v-model="template.letterSpacing" step="1" min="-2" max="5">
                    <span class="ml-1r range-value">{{ template.letterSpacing }}</span>
                </div>

                <div class="form-group">
                    <div class="d-flex justify-content-between align-items-center">
                        <label class="mb-0 label-w-50">Uppercase Text</label>
                        <div>
                            <input type="checkbox" class="form-controla" v-model="template.textUppercase">
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label>Background Image</label>

                    <div class="backgrounds-list scrollable">
                        <img :src="bg.url" v-for="bg in backgrounds" @click="onBackgroundSelected(bg.url)">
                    </div>
                </div>

                <div class="form-group d-flex justify-content-between align-items-center">
                    <label class="mb-0" style="width: 180px;">Text Box</label>
                    <div>
                        <input type="checkbox" class="form-controla" v-model="template.boxVisible">
                    </div>
                </div>

                <div class="more-options mb-4" v-if="template.boxVisible">
                    <div class="form-group d-flex justify-content-between align-items-center">
                        <label class="mb-0" style="width: 180px;">Box Color</label>
                        <div class="color-picker">
                            <div class="color-picker__swatch" @click="popovers.color_picker_1_open=!popovers.color_picker_1_open" :style="{ background: template.boxBackgroundColor }"></div>
                            <div class="color-picker__box" v-if="popovers.color_picker_1_open">
                                <color-picker :value="template.boxBackgroundColor" @input="(c) => { template.boxBackgroundColor = c.hex8 }"></color-picker>
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-2 d-flex justify-content-between align-items-center">
                        <label class="mb-0" style="width: 180px;">Rounded</label>
                        <input type="range" v-model="template.boxBorderRadius" step="1" min="0" max="60" class="form-control-range">
                    </div>

                    <div class="form-group mb-2 d-flex justify-content-between align-items-center">
                        <label class="mb-0" style="width: 180px;">Spacing</label>
                        <input type="range" v-model="template.boxPadding" step="1" min="10" max="60" class="form-control-range">
                    </div>
                </div>


                <div class="form-group d-md-none">
                    <label>Size</label>
                    <div class="dropdown">
                        <button class="btn btn-dark btn-block dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="text-capitalize">{{ template.layout }}</span>
                        </button>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" v-for="layout in layouts" style="font-size: 18px;" href="#" @click="template.layout = layout.key;">{{ layout.name }}</a>
                        </div>
                    </div>
                </div>


                <div class="form-group">
                    <label>Text Position</label>
                    <div class="text-position-selector">
                        <div><i class="bi bi-arrow-up-left":class="{active: template.boxPosition==='left-top'}" @click="template.boxPosition='left-top'"></i></div>
                        <div><i class="bi bi-arrow-up":class="{active: template.boxPosition==='center-top'}" @click="template.boxPosition='center-top'"></i></div>
                        <div><i class="bi bi-arrow-up-right":class="{active: template.boxPosition==='right-top'}" @click="template.boxPosition='right-top'"></i></div>

                        <div><i class="bi bi-arrow-left":class="{active: template.boxPosition==='left-center'}" @click="template.boxPosition='left-center'"></i></div>
                        <div><i class="bi bi-circle":class="{active: template.boxPosition==='center-center'}" @click="template.boxPosition='center-center'"></i></div>
                        <div><i class="bi bi-arrow-right":class="{active: template.boxPosition==='right-center'}" @click="template.boxPosition='right-center'"></i></div>

                        <div><i class="bi bi-arrow-down-left":class="{active: template.boxPosition==='left-bottom'}" @click="template.boxPosition='left-bottom'"></i></div>
                        <div><i class="bi bi-arrow-down":class="{active: template.boxPosition==='center-bottom'}" @click="template.boxPosition='center-bottom'"></i></div>
                        <div><i class="bi bi-arrow-down-right":class="{active: template.boxPosition==='right-bottom'}" @click="template.boxPosition='right-bottom'"></i></div>
                    </div>
                </div>


            </div>

            <div class="studio__editor" ref="studio">
                <div class="studio__canvas" ref="canvas" :style="getEditorStyles()">
                    <div class="studio__canvas__inner" :style="getBoxStyles()">
                        <div class="studio__canvas__text" :style="getTextStyles()">
                            <div v-html="getHeader()" v-if="template.headerVisible && text_header" class="studio__canvas__text-header"></div>
                            <div v-html="getText()" class="studio__canvas__text-main"></div>
                            <div v-html="getFooter()" v-if="template.footerVisible && text_footer" class="studio__canvas__text-footer"></div>
                        </div>
                    </div>

                    <div class="studio__watermark" :class="'pos-' + template.watermarkPosition">QUOOOTES.COM</div>

                    <div class="studio__canvas__overlay">
                        <!-- <div class="text-position" @click="template.boxPosition = 'left-top'" data-toggle="tooltip" data-title="Move Text Here" data-placement="top"></div>
                        <div class="text-position" @click="template.boxPosition = 'center-top'"><div class="pointer"></div></div>
                        <div class="text-position" @click="template.boxPosition = 'right-top'"></div>

                        <div class="text-position" @click="template.boxPosition = 'left-center'"></div>
                        <div class="text-position" @click="template.boxPosition = 'center-center'"></div>
                        <div class="text-position" @click="template.boxPosition = 'right-center'"></div>

                        <div class="text-position" @click="template.boxPosition = 'left-bottom'"></div>
                        <div class="text-position" @click="template.boxPosition = 'center-bottom'"></div>
                        <div class="text-position" @click="template.boxPosition = 'right-bottom'"></div> -->
                    </div>
                </div>
            </div>
        </div>

        <div class="studio__body" v-if="page === 'templates'">
            <div class="container mt-5">
                <div class="row">
                    <div class="col-12">
                        <h1>Templates</h1>
                    </div>
                </div>

                <div class="row">
                    <div v-for="template in templates" class="col-3">
                        <img :src="template.preview_url" class="img-fluid" @click="onTemplateSelected(template.id)">
                    </div>
                </div>
            </div>
        </div>

        <div class="studio__body" :class="{'align-items-center': onboarding_step !== 3}" v-if="page === 'start'">
            <div class="container">
                <div class="row justify-content-center" v-if="onboarding_step === 1">
                    <div class="col-10 text-center" >
                        <h1>Let's create a beautiful quote.</h1>
                        <p class="lead">Using this simple quote maker</p>
                        <div class="text-center mt-5">
                            <button class="btn btn-lg btn-link text-dark" @click="onOnboardingSkipped()">Skip</button>
                            <button class="btn btn-success btn-lg" @click="onGoToOboardingStep(2)">Start</button>
                        </div>
                    </div>
                </div>

                <div class="row justify-content-center" v-if="onboarding_step === 2">
                    <div class="col-12 col-md-9 mt-4">
                        <div class="text-center">
                            <h1>1. Write your quote</h1>
                            <p class="lead">You can change the text later</p>
                        </div>
                        <div class="start-page__inputas mt-4">
                            <!-- <label><i class="bi bi-quote"></i> Your Quote</label> -->
                            <textarea ref="onboardingInput" class="form-control form-control-lg initial-text" v-model="text" rows="4" placeholder="Enter text here.."></textarea>
                            <!-- <label class="mt-4"><i class="bi bi-person"></i> Author (Optional)</label>
                            <input class="form-control form-control-lg" v-model="text_footer" style="font-size: 14px;" placeholder="Enter author's name" /> -->
                        </div>
                        <div class="text-center  mt-3">
                            <button class="btn btn-success btn-lg" @click="onGoToOboardingStep(3)">Continue</button>
                        </div>
                    </div>
                </div>

                <div class="row justify-content-center" v-if="onboarding_step === 3">
                    <div class="col-12 col-md-9 mt-4">
                        <div class="text-center" style="margin-top: 100px;">
                            <h1>2. Pick a Template</h1>
                            <p class="lead">You can customize it later (or choose another)</p>
                        </div>
                    </div>
                    <div class="templates-grid scrollable mt-4">
                        <div v-for="template in templates" class="template template__square">
                            <img :src="template.preview_url" class="img-fluid" @click="onTemplateSelected(template.id)">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal" tabindex="-1" role="dialog" id="saveModal">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Save</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <i class="bi bi-x"></i>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Topic</label>
                            <select v-model="tag_id" class="form-control custom-select">
                                <option value="">Select a Topic</option>
                                <option v-for="tag in tags" :value="tag.id">{{tag.name}}</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Keywords</label>
                            <input type="text" v-model="keywords" class="form-control" placeholder="#love #life #sad #instagram">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary btn-upper btn-lg" @click="onSaveDesign()">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>

<style>
.vc-chrome-fields-wrap{display:none}
.vc-chrome-color-wrap{display:none}
.vc-chrome-saturation-wrap .vc-saturation-circle{width:20px;height:20px;box-shadow:0 0 0 2px #fff,inset 0 0 1px 1px rgba(0,0,0,.3),0 0 1px 2px rgba(0,0,0,.4)}
.vc-chrome{box-shadow:none!important}
.vc-chrome-body{padding:16px 0 12px}
.vc-chrome-hue-wrap .vc-hue,.vc-chrome-alpha-wrap .vc-alpha-gradient{border-radius:6px}
.vc-chrome-hue-wrap,.vc-chrome-alpha-wrap{height:16px}
.vc-hue-picker,.vc-alpha-picker{width:18px!important;height:18px!important;border-radius:30px!important}
.vc-chrome-saturation-wrap{border-radius:6px}
</style>
<script>
    var __NEXT_DATA__ = {
        template: <?php echo json_encode($template); ?>,
        text: "<?= $text; ?>",
        backgrounds: <?php echo json_encode($backgrounds); ?>,
        tags: <?php echo json_encode($category_list); ?>,
    }

    <?php if ($action): ?>
        __NEXT_DATA__.mode = '<?= $action ?>';
    <?php endif; ?>
</script>
<?php
if ($env === 'DEV') {
    echo $this->Html->script([
        'https://cdn.jsdelivr.net/npm/vue@2.6.14/dist/vue.js',
        'vendor/jquery.min.js',
        'vendor/popper.min.js',
        '/bootstrap4/js/bootstrap.min.js',
        // 'studio/html2canvas.js',
        'studio/html2canvas.min.js',
        'studio/vue-color.js',
        'studio/polyfills.js',
        'studio/fonts.js',
        'studio/studio.js',
    ], ['defer' => true]);
}
if ($env === 'PROD') {
    echo $this->Html->script([
        'studio.vendors.build.js',
        'studio.soft.build.js',
    ], ['defer' => true]);
}
?>