<?php
    $this->disableAutoLayout();

    // echo '<pre>';
    // var_dump($template);
    // echo $template;
    // echo json_encode($template, JSON_UNESCAPED_UNICODE);
    // die;
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quoootes - Studio</title>
    <?= $this->Html->css('/bootstrap4/css/bootstrap.min.css') ?>
    <!-- <link rel="stylesheet" href="/css/vendor/normalize.css"> -->
    <!-- <link rel="stylesheet" href="/css/vendor/skeleton.css"> -->
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Tangerine|Droid+Sans|Open+Sans:400,700|Josefin+Slab|Arvo|Lato|Vollkorn|Abril+Fatface|Ubuntu|Old+Standard+TT|Lobster|Bevan|Berkshire+Swash">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.0/font/bootstrap-icons.min.css" referrerpolicy="no-referrer" />
    <?= $this->Html->css('bs-reset.css') ?>

    <link rel="stylesheet" href="/fonts/kredit/styles.css">
    <link rel="stylesheet" href="/fonts/1942/stylesheet.css">
    <link rel="stylesheet" href="/fonts/team_spirit/stylesheet.css">
    <link rel="stylesheet" href="/fonts/the-bold-font/stylesheet.css">
    <link rel="stylesheet" href="/fonts/cocogoose/stylesheet.css">
    <link rel="stylesheet" href="/fonts/brook/stylesheet.css">
    <link rel="stylesheet" href="/fonts/french-love-simplicity/stylesheet.css">
    <link rel="stylesheet" href="/fonts/obelix-pro/stylesheet.css">

    <style>
        [v-cloak] { display: none; }
    </style>
</head>
<body>


    <div id="studio" class="studio" v-cloak>

        <div class="studio__header">
            <a href="/">
                <!-- <img src="/img/logo.png?new3" alt="Quoootes - Free Quote Maker & Generator" width="120" class="d-none d-md-inline"> -->
                <img src="/img/logo5.png" alt="Quoootes - Free Quote Maker and Generator" height="30" class="logo-desktop d-none d-md-inline">
                <img src="/favicon.ico" alt="Quoootes - Free Quote Maker & Generator" width="30" class="d-md-none">
            </a>

            <div class="ml-auto">
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
                <button @click="onViewTemplates()" class="btn btn-light mb-0 btn-upper" v-if="mode === 'create'">Templates</button>
                <button class="btn btn-primary mb-0 ml-3 btn-upper" @click="saveDesign" v-if="mode === 'create'">Save</button>
                <button class="btn btn-primary mb-0 ml-3 btn-upper" @click="saveTemplate" v-if="mode === 'edit_template'">Save Changes</button>
                <button class="btn btn-primary mb-0 ml-3 btn-upper" @click="saveTemplate" v-if="mode === 'create_template'">Save Template</button>

                <button class="btn btn-success mb-0 ml-3 btn-upper" @click="createNewTemplate()" v-if="mode === 'edit_template'">New Template</button>
            </div>
        </div>

        <div class="studio__body" v-if="page === 'editor'">
            <div class="sidebar">

                <!--  Text -->
                <div class="form-group">
                    <label for="">Text</label>
                    <textarea rows="3" v-model="text" class="form-control"></textarea>
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
                    <label>Background</label>

                    <div class="backgrounds-list">
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


                <!-- <div class="form-group">
                    <label>Size</label>
                    <div class="dropdown">
                        <button class="btn btn-dark btn-block dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="text-capitalize">{{ template.layout }}</span>
                        </button>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" v-for="layout in layouts" style="font-size: 18px;" href="#" @click="template.layout = layout.key;">{{ layout.name }}</a>
                        </div>
                    </div>
                </div> -->


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
                            <div v-html="getHeader()" v-if="template.showHeader" class="studio__canvas__text-header"></div>
                            <div v-html="getText()" class="studio__canvas__text-main"></div>
                            <div v-html="getFooter()" v-if="template.showFooter" class="studio__canvas__text-footer"></div>
                        </div>
                    </div>

                    <div class="studio__watermark" :class="'pos-' + template.watermarkPosition">QUOOOTES.COM</div>

                    <div class="studio__canvas__overlay">
                        <div class="text-position" @click="template.boxPosition = 'left-top'" data-toggle="tooltip" data-title="Move Text Here" data-placement="top"></div>
                        <div class="text-position" @click="template.boxPosition = 'center-top'"><div class="pointer"></div></div>
                        <div class="text-position" @click="template.boxPosition = 'right-top'"></div>

                        <div class="text-position" @click="template.boxPosition = 'left-center'"></div>
                        <div class="text-position" @click="template.boxPosition = 'center-center'"></div>
                        <div class="text-position" @click="template.boxPosition = 'right-center'"></div>

                        <div class="text-position" @click="template.boxPosition = 'left-bottom'"></div>
                        <div class="text-position" @click="template.boxPosition = 'center-bottom'"></div>
                        <div class="text-position" @click="template.boxPosition = 'right-bottom'"></div>
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
                        <img :src="template.preview_url" class="img-fluid" @click="loadTemplate(template.id)">
                    </div>
                </div>
            </div>
        </div>

        <div class="studio__body mt-5" v-if="page === 'start'">
            <div class="container mt-5">
                <div class="row justify-content-center">
                    <div class="col-10">
                        <h1>Let's create something beautiful.</h1>
                        <p>Enter your text (you can edit it later)</p>
                    </div>
                </div>

                <div class="row justify-content-center">
                    <div class="col-10 mt-4">
                        <textarea class="form-control form-control-lg initial-text" v-model="text" rows="6"></textarea>
                        <div class="text-center">
                            <button class="button-primary button-lg" @click="page='templates'">Continue</button>
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

    :root {
        --header-height: 60px;
        --color-gray: #4A5568;
        --slider-color: #4A5568;
        --watermark-offset: 14px;
    }

    [v-cloak] { display: none !important; }

    html,body {
        box-sizing: border-box;
        width: 100%;
        height: 100%;
    }

    *, *:before, *:after {
        box-sizing: inherit;
    }

    h1 { font-weight: 900; font-size: 40px;}

    body {
        background-color: #EBEFF2;
    }

    .logo-desktop {
        position: relative;
        top: 2px;
    }

    .studio {
        display: flex;
        flex-direction: column;
        width: 100%;
        height: 100%;
    }
    .studio__header {
        height: var(--header-height);
        min-height: var(--header-height);
        background-color: #FFF;
        display: flex;
        align-items: center;
        padding:  0 30px;
    }
    .studio__body {
        display: flex;
        flex: 1;
        height: calc(100% - var(--header-height));
    }

    .studio__editor {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }
    .studio__canvas {
        width: 600px;
        height: 600px;
        background-color: #7BD4AA;
        padding: 30px;
        min-width: 600px;
        transition: .15s ease-out;
        box-shadow: 0 2px 10px rgb(13,19,43,.1);
        position: relative;
        /* max-height: 600px;
        max-width: 600px; */
        /* aspect-ratio: 0.5625 / 1; */
    }

    .studio__canvas__inner {
        width: 100%;
        height: 100%;
        /* background-color: white; */
        display: flex;
        /* align-items: center;
        justify-content: center; */
    }

    .studio__canvas__text {
        background-color: transparent;
        display: inline-block;
        padding: 20px;
        white-space: pre-wrap;
        word-break: break-word;
        max-height: 100%;
        max-width: 100%;
        overflow: hidden;
    }

    .studio__canvas__overlay {
        width: 100%;
        height: 100%;
        background-color: rgb(205,222,255,.0);
        position: absolute;
        top: 0;
        left: 0;
        display: flex;
        flex-wrap: wrap;
        justify-content: space-around;
        align-items: center;
    }

    .text-position {
        width: 28%;
        min-width: 28%;
        height: 28%;
        border: 3px solid transparent;
    }

    .text-position:hover {
        border: 2px solid rgba(0,0,0,0.1);
        background-color: rgba(255,255,255,0.2);
        cursor: pointer;
    }

    .studio__canvas__text {
        display: flex;
        flex-direction: column;
    }

    .studio__canvas__text-footer {
        font-size: 80%;
        margin-top: 20px;
        display: inline-block;
    }

    .studio__canvas__text-header {
        font-size: 120%;
        margin-bottom: 20px;
        display: inline-block;
    }

    textarea.initial-text {
        font-size: 16px;
        padding: 20px;
        min-height: 150px;
        resize: none;
        border-radius: 6px;
    }

    .studio__watermark {
        font-family: "Open Sans";
        font-weight: 700;
        width: 100px;
        background-color: rgb(0,0,0,.7);
        padding: 5px 8px;
        font-size: 10px;
        border-radius: 4px;
        text-align: center;
        display: inline-block;
        position: absolute;
        color: white !important;
    }
    .studio__watermark.pos-1 { top: var(--watermark-offset); left: var(--watermark-offset);}
    .studio__watermark.pos-2 { top: var(--watermark-offset); right: var(--watermark-offset);}
    .studio__watermark.pos-3 { bottom: var(--watermark-offset); left: var(--watermark-offset);}
    .studio__watermark.pos-4 { bottom: var(--watermark-offset); right: var(--watermark-offset);}
    .studio__watermark.pos-5 { top: var(--watermark-offset); left: 50%; margin-left: -50px;}
    .studio__watermark.pos-6 { bottom: var(--watermark-offset); left: 50%; margin-left: -50px;}
</style>
<style>
    .btn {
        border-radius: 4px;
        border: 0;
        padding: 8px 16px;
    }
    .btn-upper {
        text-transform: uppercase;
        font-size: 12px;
        font-weight: 600;
    }
    .btn-lg.btn-upper {
        font-size: 13px;
        padding: 10px 20px;
    }

    .btn-dark {
        background-color: #4A5568;
        color: #d8d8d8;
    }
    .btn-primary { background-color:  var(--color-gray);}
    .btn-primary:hover {
        color: #fff;
    background-color: #23272b;
    border-color: #1d2124;
    }

    .button-lg { transform: scale(1.3);}
    input[type="checkbox"] {
        width: 20px;
        height: 20px;
    }
    .label-w-50 { width: 250px; }
    .form-group-mh { min-height: 30px;}

    .dropdown-menu { width: 100%; max-height: 250px; overflow-y: auto; background-color: var(--color-gray);}
    .dropdown-menu .dropdown-item {
        margin: 8px 16px;
        width: auto;
        border-radius: 4px;
        padding: 6px 11px;
        color: white;
    }
    .dropdown-item:focus, .dropdown-item:hover {
        color: white;
        text-decoration: none;
        background-color: #333;
    }
    .range-value {
        font-size: 11px;
    color: #ccc;
    min-width: 23px;
    text-align: right;
    display: block;
    }
</style>
<style>
    .color-picker { position: relative; }
    .color-picker__swatch {
        width: 30px;
        height: 30px;
        background: rgb(123, 32, 175);
        border: 1px solid rgba(255,255,255,.47);
        border-radius: 4px;
    }
    .color-picker__box {
        position: absolute;
        z-index: 999;
        top: 40px;
        left: -215px;
        padding: 10px;
        background: white;
        border-radius: 6px;
    }
    .vc-chrome-fields-wrap {display: none;}
    .vc-chrome-color-wrap {display: none;}
    .vc-chrome-saturation-wrap .vc-saturation-circle { width: 20px; height: 20px; box-shadow: 0 0 0 2px #fff, inset 0 0 1px 1px rgb(0,0,0,.3), 0 0 1px 2px rgb(0,0,0,.4);}
    .vc-chrome { box-shadow: none!important;}
    .vc-chrome-body { padding: 16px 0 12px; }
    .vc-chrome-hue-wrap .vc-hue, .vc-chrome-alpha-wrap .vc-alpha-gradient { border-radius: 6px; }
    .vc-chrome-hue-wrap, .vc-chrome-alpha-wrap { height: 16px; }
    .vc-hue-picker, .vc-alpha-picker { width: 18px!important; height: 18px!important; border-radius: 30px !important;}
    .vc-chrome-saturation-wrap { border-radius: 6px;}
</style>
<style>
    .sidebar {
        display: flex;
        flex-direction: column;
        width: 330px;
        background-color: #2C313D;
        color: white;
        padding: 24px;
        height: 100%;
        position: relative;
        overflow-y: auto;
    }

    .sidebar__inner {
        /* flex: 1 1 auto; */
        overflow-y: auto;
        height: 100%;
        padding-right: 5px;
        -webkit-overflow-scrolling: touch;
    }

    .sidebar .more-options {
        background: #232425;
        padding: 8px 12px;
        border-radius: 4px;
    }
    .btn-group-icons .btn { padding: 4px 12px;}
    .btn-group-icons i.bi { font-size: 18px;}

    .text-position-selector { display: flex; max-width: 190px; flex-wrap: wrap;}
    .text-position-selector > div { width: 50px; text-align: center; padding: 4px 0; color: var(--color-gray)}
    .text-position-selector > div > .active { color: white;}

    .sidebar .backgrounds-list { max-height: 250px; overflow-y: auto; display: flex; flex-direction: row; flex-wrap: wrap; justify-content: space-between;}
    .sidebar .backgrounds-list img { width: 31%; min-width: 31%; height: 80px; object-fit: cover; margin-bottom: 10px; border-radius: 3px;}

    .sidebar label { color: #9b9fb5; font-size: 14px;}

    input[type="range"]{-webkit-appearance:none;margin:0;width:100%;background-color:transparent;padding:0; margin: 10px 0;}
    input[type="range"]:focus{outline:none}
    input[type="range"]::-webkit-slider-runnable-track{width:100%;height:3px;cursor:pointer;background:var(--slider-color);border-radius:100px;-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box}
    input[type="range"]::-webkit-slider-thumb{border:1px solid rgba(0,0,0,0.3);height:12px;width:12px;border-radius:100px;background-color:#fff;cursor:pointer;-webkit-appearance:none;margin-top:-5px;-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box}
    input[type="range"]:focus::-webkit-slider-runnable-track{background:var(--slider-color);-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box}
    input[type="range"]::-moz-range-track{width:100%;height:3px;cursor:pointer;background:var(--slider-color);border-radius:100px;-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box}
    input[type="range"]::-moz-range-thumb{border:1px solid rgba(0,0,0,0.3);height:12px;width:12px;border-radius:100px;background-color:#fff;cursor:pointer;-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box}
    input[type="range"]::-moz-focus-outer{border:0}
    input[type="range"]::-ms-track{width:100%;height:20px;cursor:pointer;background:transparent;border-color:transparent;color:transparent;border-width:6px 0;-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box}
    input[type="range"]::-ms-tooltip{display:none}
    input[type="range"]::-ms-fill-lower{background:#eee;border-radius:100px}
    input[type="range"]::-ms-fill-upper{background:#eee;border-radius:100px}
    input[type="range"]::-ms-thumb{border:1px solid rgba(0,0,0,0.3);height:12px;width:12px;border-radius:100px;background-color:#fff;cursor:pointer}
    input[type="range"]:focus::-ms-fill-lower{background:#eee}
    input[type="range"]:focus::-ms-fill-upper{background:#eee}
</style>
<style>
@media (max-width: 767px) {
    .studio__header {
        overflow-y: hidden;
    }
    .studio__body {
        flex-direction: column-reverse;
        height: 100%;
        min-height: 570px;
    }

    .studio__editor {
        min-height: 250px;
        margin: 10px;
    }

    .sidebar {
        width: 100%;
    }
}
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
<?= $this->Html->script([
        'https://cdn.jsdelivr.net/npm/vue@2.6.14/dist/vue.js',
        'vendor/jquery.min.js',
        'vendor/popper.min.js',
        '/bootstrap4/js/bootstrap.min.js',
        'studio/html2canvas.min.js',
        'studio/vue-color.js',
        'studio/polyfills.js',
        'studio/fonts.js',
        'studio/studio.js',
    ], ['defer' => true]);
?>

<?php // $this->Html->script([
        //'studio.vendors.build.js',
        //'studio.soft.build.js',
    //], ['defer' => true]);
?>

<script>

</script>