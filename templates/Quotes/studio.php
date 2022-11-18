<?php
    use Cake\Routing\Router;
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
    <link rel="canonical" href="<?= Router::url('/studio', true); ?>" />
    <?= $this->Html->css('/bootstrap4/css/bootstrap.min.css') ?>
    <?= $this->Html->css('studio.css') ?>
    <link href="/favicon.ico?version=3" type="image/x-icon" rel="icon"/>
    <link href="/favicon.ico?version=3" type="image/x-icon" rel="shortcut icon"/>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.9.1/font/bootstrap-icons.min.css" referrerpolicy="no-referrer" media="print" onload="this.onload=null;this.removeAttribute('media');" />
    <link rel="stylesheet" href="/fonts/kredit/styles.css" media="print" onload="this.onload=null;this.removeAttribute('media');">
    <link rel="stylesheet" href="/fonts/1942/stylesheet.css" media="print" onload="this.onload=null;this.removeAttribute('media');">
    <link rel="stylesheet" href="/fonts/team_spirit/stylesheet.css" media="print" onload="this.onload=null;this.removeAttribute('media');">
    <link rel="stylesheet" href="/fonts/the-bold-font/stylesheet.css" media="print" onload="this.onload=null;this.removeAttribute('media');">
    <link rel="stylesheet" href="/fonts/cocogoose/stylesheet.css" media="print" onload="this.onload=null;this.removeAttribute('media');">
    <link rel="stylesheet" href="/fonts/brook/stylesheet.css" media="print" onload="this.onload=null;this.removeAttribute('media');">
    <link rel="stylesheet" href="/fonts/french-love-simplicity/stylesheet.css" media="print" onload="this.onload=null;this.removeAttribute('media');">
    <link rel="stylesheet" href="/fonts/obelix-pro/stylesheet.css" media="print" onload="this.onload=null;this.removeAttribute('media');">
    <link rel="stylesheet" href="/fonts/sifonn-basic-outline/stylesheet.css" media="print" onload="this.onload=null;this.removeAttribute('media');">
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
                        <a class="dropdown-item" v-for="layout in layouts" style="font-size: 16px;" href="#" @click="setLayout(layout)">{{ layout.name }}</a>
                    </div>
                </div>
            </div>
            <div class="studio__header-options">
                <button @click="onViewTemplates()" class="btn btn-light mb-0 btn-upper" v-if="mode === 'create' || mode === 'edit_design'"><i class="bi bi-star-fill mr-1 text-warning"></i> Templates</button>
                <button class="btn btn-primary mb-0 ml-3 btn-upper" @click="saveDesign" v-if="mode === 'create' || mode === 'edit_design'" :disabled="!text && !text_footer && !text_header">Save</button>
                <button class="btn btn-primary mb-0 ml-3 btn-upper" @click="saveTemplate" v-if="mode === 'edit_template'">Save Changes</button>
                <button class="btn btn-primary mb-0 ml-3 btn-upper" @click="saveTemplate" v-if="mode === 'create_template'">Save Template</button>

                <button class="btn btn-success mb-0 ml-3 btn-upper" @click="createNewTemplate()" v-if="mode === 'edit_template'">New Template</button>

                <div class="studio__header-user-menu" v-if="user">
                    <button class="dropdown-toggle btn btn-link" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <img class="user-profile-pic" :src="user.picture" alt="">
                    </button>
                    <div class="dropdown-menu">
                        <a href="/dashboard?ref=studio" class="dropdown-item">My Quotes</a>
                        <a href="/logout" class="dropdown-item">Logout</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="studio__body editor" v-if="page === 'editor'">

            <!-- Editor Sidebar -->
            <div class="sidebar scrollable" v-if="!sidebar_open">

                <!--  Text -->
                <div class="form-group">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <label class="mb-0">Text</label>
                        <span class="label-count">{{ text.length }}/500</span>
                    </div>
                    <textarea rows="4" v-model="text" ref="textInput" class="form-control" @input="onTextChange('text')" maxlength="500" @paste="onTextChange('text_paste')"></textarea>
                </div>

                <div class="form-group">
                    <div class="d-flex justify-content-between align-items-center">
                        <label class="mb-0 label-w-50">Author</label>
                        <div>
                            <input type="checkbox" class="form-controla" v-model="template.footerVisible" @input="onTextChange('text_paste')">
                        </div>
                    </div>
                    <input v-if="template.footerVisible" type="text" v-model="text_footer" class="form-control mt-2" maxlength="30" @input="onTextChange('footer')">
                </div>

                <div class="form-group">
                    <div class="d-flex justify-content-between align-items-center">
                        <label class="mb-0 label-w-50">Title</label>
                        <div>
                            <input type="checkbox" class="form-controla" v-model="template.headerVisible">
                        </div>
                    </div>
                    <input v-if="template.headerVisible" type="text" v-model="text_header" class="form-control mt-2" maxlength="30" @input="onTextChange('header')">
                </div>

                <div class="form-group">
                    <label>Font</label>
                    <div class="dropdown">
                        <button class="btn btn-dark btn-block dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span :style="{fontFamily: template.fontFamily}" >{{ template.fontFamily }}</span>
                        </button>
                        <div class="dropdown-menu font-picker-dropdown-menu">
                            <a class="dropdown-item" v-for="font in fonts" style="position:relative;font-size: 18px;" href="#" :style="{fontFamily: font.name, fontWeight: (font.weight) ? font.weight : 400}" @click="onSelectFont(font)">
                                {{ font.title || font.name }}
                                <i class="bi bi-lock-fill ml-2" v-if="is_free && font.premium"></i>
                                <i class="bi bi-lightning-charge-fill mr-2" v-if="!is_free && font.premium"></i>
                            </a>
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
                            <color-picker :value="template.backgroundColor" @input="(c) => { template.backgroundColor = c.hex8; template.backgroundImage = ''; }"></color-picker>
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



                <!-- <div class="form-group d-flex justify-content-between align-items-center form-group-mh">
                    <label class="mb-0 label-w-50">Font Size</label>
                    <input type="range" v-model="template.fontSize" step="2" min="10" max="80" class="form-control-range">
                    <span class="ml-1r range-value">{{ template.fontSize }}</span>
                </div> -->

                <!-- <div class="form-group d-flex justify-content-between align-items-center form-group-mh">
                    <label class="mb-0 label-w-50">Text Width</label>
                    <input type="range" v-model="template.boxWidth" min="45" max="100" @mousedown="onShowTextBoxDimensions(true)" @mouseup="onShowTextBoxDimensions(false)">
                    <span class="ml-1r range-value">{{ template.boxWidth }}</span>
                </div> -->

                <div class="form-group clickeable" @click="openSidebar('images')">
                    <div class="d-flex justify-content-between align-items-center">
                        <label class="mb-0 label-w-50">Background Image</label>
                        <div v-if="template.backgroundImage" class="d-flex flex-row align-items-center">
                            <img class="bg-thumb" :src="template.backgroundImage" alt="">
                            <i class="bi bi-x-lg ml-2" data-toggle="tooltip" data-title="Remove" @click.stop="template.backgroundImage=''"></i>
                        </div>
                        <div v-else>
                            <i class="bi bi-chevron-double-right"></i>
                        </div>
                    </div>
                </div>

                <div class="form-group clickeable" @click="openSidebar('filters')">
                    <div class="d-flex justify-content-between align-items-center">
                        <label class="mb-0 label-w-50">Filters</label>
                        <div class="d-flex flex-row align-items-center" v-if="template.filter">
                            <div class="gradient-item sm">
                                <div class="gradient-preview">
                                    <div class="gradient-preview-inner">
                                        <div class="gradient-image"></div>
                                        <div class="gradient-color" :style="{background: template.filter }"></div>
                                    </div>
                                </div>
                            </div>
                            <i class="bi bi-x-lg ml-2" data-toggle="tooltip" data-title="Remove" @click.stop="template.filter=''"></i>
                        </div>
                        <div v-else>
                            <i class="bi bi-chevron-double-right"></i>
                        </div>
                    </div>
                </div>

                <div class="form-group clickeable d-flex justify-content-between align-items-center" @click="onRemoveWatermark()" v-if="is_free">
                    <label class="mb-0" style="width: 180px;">
                    <i class="bi bi-lock-fill mr-1"></i> Remove Watermark</label>
                    <div>
                    <i class="bi bi-chevron-double-right"></i>
                    </div>
                </div>

                <div class="form-group clickeable d-flex justify-content-between align-items-center" @click="onAddLogo()">
                    <label class="mb-0" style="width: 180px;">
                        <i class="bi bi-lock-fill mr-1" v-if="is_free"></i>
                        <i class="bi bi-lightning-charge-fill mr-1" v-else></i>

                        Add Brand Logo
                    </label>
                    <div v-if="template.logoImage" class="d-flex flex-row align-items-center">
                        <img class="bg-thumb" :src="template.logoImage" alt="">
                        <i class="bi bi-x-lg ml-2" data-toggle="tooltip" data-title="Remove" @click.stop="template.logoImage=''"></i>
                    </div>
                    <div v-else>
                        <i class="bi bi-chevron-double-right"></i>
                    </div>
                    <input type="file" ref="upload_logo_image_input" @change="onLogoImageSelected($event)" class="form-control-hidden-file">
                </div>

                <div class="more-options mb-4" v-if="!is_free && template.logoImage">
                    <div class="form-group d-flex justify-content-between align-items-center">
                        <label>Logo Position</label>
                        <div class="text-position-selector">
                            <div class="text-position-selector-item" @click="template.watermarkPosition='1'" :class="{active: template.watermarkPosition==='1'}"><div class="dot"></div></div>
                            <div class="text-position-selector-item" @click="template.watermarkPosition='5'" :class="{active: template.watermarkPosition==='5'}"><div class="dot"></div></div>
                            <div class="text-position-selector-item" @click="template.watermarkPosition='2'" :class="{active: template.watermarkPosition==='2'}"><div class="dot"></div></div>
                            <div class="text-position-selector-item disabled"><div class="dot"></div></div>
                            <div class="text-position-selector-item disabled"><div class="dot"></div></div>
                            <div class="text-position-selector-item disabled"><div class="dot"></div></div>
                            <div class="text-position-selector-item" @click="template.watermarkPosition='3'" :class="{active: template.watermarkPosition==='3'}"><div class="dot"></div></div>
                            <div class="text-position-selector-item" @click="template.watermarkPosition='6'" :class="{active: template.watermarkPosition==='6'}"><div class="dot"></div></div>
                            <div class="text-position-selector-item" @click="template.watermarkPosition='4'" :class="{active: template.watermarkPosition==='4'}"><div class="dot"></div></div>
                        </div>
                    </div>

                    <div class="form-group mb-3 d-flex justify-content-between align-items-center">
                        <label class="mb-0" style="width: 180px;">Logo Rounded</label>
                        <input type="range" v-model="template.logoBorderRadius" step="1" min="0" max="50" style="max-width: 100px;" class="form-control-range">
                        <span class="ml-1r range-value">{{ template.logoBorderRadius }}</span>
                    </div>

                    <div class="form-group mb-3 d-flex justify-content-between align-items-center">
                        <label class="mb-0" style="width: 180px;">Logo Size</label>
                        <input type="range" v-model="template.logoSize" step="1" min="20" max="180" style="max-width: 100px;" class="form-control-range">
                        <span class="ml-1r range-value">{{ template.logoSize }}</span>
                    </div>
                </div>

                <div class="form-group">
                    <div class="d-flex justify-content-between align-items-center">
                        <label class="mb-0 label-w-50">Uppercase Text</label>
                        <div>
                            <input type="checkbox" class="form-controla" v-model="template.textUppercase">
                        </div>
                    </div>
                </div>

                <div class="form-group d-flex justify-content-between align-items-center">
                    <label class="mb-0" style="width: 180px;">Show Advanced Options</label>
                    <div>
                        <input type="checkbox" class="form-controla" v-model="show_advanced_options">
                    </div>
                </div>

                <div class="form-group d-flex justify-content-between align-items-center form-group-mh" v-if="show_advanced_options">
                    <label class="mb-0 label-w-50">Line Spacing</label>
                    <input type="range" v-model="template.lineHeight" step="0.1" min="0.6" max="2" class="form-control-range">
                    <span class="ml-1r range-value">{{ template.lineHeight }}</span>
                </div>

                <div class="form-group d-flex justify-content-between align-items-center form-group-mh" v-if="show_advanced_options">
                    <label class="mb-0 label-w-50">Letter Spacing</label>
                    <input type="range" v-model="template.letterSpacing" step="1" min="-2" max="5">
                    <span class="ml-1r range-value">{{ template.letterSpacing }}</span>
                </div>

                <div class="form-group d-flex justify-content-between align-items-center" v-if="show_advanced_options">
                    <label class="mb-0" style="width: 180px;">Text Box</label>
                    <div>
                        <input type="checkbox" class="form-controla" v-model="template.boxVisible">
                    </div>
                </div>

                <div class="more-options mb-4" v-if="show_advanced_options && template.boxVisible">
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


                <div class="form-group" v-if="mode === 'create_template' || mode === 'edit_template'">
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

            <!-- Editor Sidebar: Images -->
            <div class="sidebar" v-if="sidebar_open === 'images'">
                <div class="sidebar__content">
                    <div class="sidebar__header">
                        <h4>Background Images</h4>
                        <div class="sidebar__close" data-toggle="tooltip" data-title="(or press scape)" @click="closeSidebar()">
                            <i class="bi bi-chevron-double-left"></i>
                        </div>
                        <div class="form-group">
                            <div class="search-input">
                                <input type="text" v-model="search_image_query" @keyup.enter="searchPixImages" class="form-control" placeholder="Search dogs, love, peace..">
                                <i class="bi  bi-search" v-if="!photos.length" @click="searchPixImages"></i>
                                <i class="bi bi-x-lg" v-else @click="resetSearch"></i>
                            </div>
                        </div>
                    </div>
                    <div class="sidebar__body scrollable">
                        <div v-if="loading">
                            <div class="loader"></div>
                        </div>
                        <div class="backgrounds-list backgrounds-list-full" v-if="!photos.length && !loading">
                            <img :src="bg.url" v-for="bg in backgrounds" @click="onBackgroundSelected(bg.url)">
                        </div>
                        <div class="backgrounds-list backgrounds-list-full" v-if="photos.length && !loading">
                            <img :src="bg.thumb" v-for="bg in photos" @click="onBackgroundSelected(bg.image)">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Editor Sidebar: Filters -->
            <div class="sidebar" v-if="sidebar_open === 'filters'">
                <div class="sidebar__content">
                    <div class="sidebar__header">
                        <h4>Filters & Effecs</h4>
                        <div class="sidebar__close" data-toggle="tooltip" data-title="(or press scape)" @click="closeSidebar()">
                            <i class="bi bi-chevron-double-left"></i>
                        </div>
                    </div>
                    <div class="sidebar__body scrollable">
                        <div>
                            <div class="slider form-group" :class="{disabled: !template.filter}">
                                <label class="mb-0">Intensity</label>
                                <div class="form-group slider--input" style="flex:1">
                                    <input class="form-control-range" v-model="template.filterOpacity" type="range" step="0.05" min="0.15" max="0.85" />
                                </div>
                                <span class="ml-2 range-value">
                                    {{ Math.round(template.filterOpacity*100) }}%
                                </span>
                            </div>
                            <div class="">
                                <div v-for="(gradient) in filters" v-on:click="setFilter(gradient)" class="gradient-item" :class="{active: gradient.color===template.filter}">
                                    <div class="gradient-preview">
                                        <div class="gradient-preview-inner">
                                            <div class="gradient-image"></div>
                                            <div class="gradient-color" :style="{background: gradient.color }"></div>
                                        </div>
                                    </div>
                                    <div class="gradient-name">{{ gradient.name }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Editor Canvas -->
            <div class="studio__editor" ref="studio">
                <div id="StudioCanvas" class="studio__canvas" ref="canvas" :style="getEditorStyles()" @mouseleave="onTextMouseLeave($event)" >
                    <div class="studio__filter" v-if="template.filter" :style="{background: template.filter, opacity: template.filterOpacity}"></div>
                    <div class="studio__canvas__inner" :style="getBoxStyles()">
                        <div id="StudioCanvasText" class="studio__canvas__text" :style="getTextStyles()" @mouseover="updateResizerPosition()">
                            <div class="studio__canvas__text__container" id="StudioCanvasTextContainer">
                                <div v-html="getHeader()" v-if="template.headerVisible && text_header" class="studio__canvas__text-header"></div>
                                <div v-html="getText()" class="studio__canvas__text-main"></div>
                                <div v-html="getFooter()" v-if="template.footerVisible && text_footer" class="studio__canvas__text-footer"></div>
                            </div>
                        </div>
                        <div id="StudioCanvasTextResizer" class="studio__canvas_resizer" @click="onTextMouseHover()" @mouseover="updateResizerPosition()"></div>
                    </div>

                    <div class="studio__watermark" v-if="is_free" :class="'pos-' + template.watermarkPosition">QUOOOTES.COM</div>
                    <div v-if="!is_free && template.logoImage" class="studio__logo" :class="'pos-' + template.watermarkPosition" :style="getLogoStyles()">
                        <img :src="template.logoImage" :style="getLogoStyles()">
                    </div>
                </div>
            </div>
        </div>

        <div class="studio__body" v-if="page === 'templates'">
            <div class="container mt-5">
                <div class="row">
                    <div class="col-12 templates-page-header mb-4">
                        <h1>Templates</h1>
                        <i class="bi bi-x-lg" @click="page = 'editor'"></i>
                    </div>
                </div>

                <div class="row">
                    <div v-for="template in templates" class="col-6 col-md-3 mb-2">
                        <div class="template-card">
                            <div class="template-card-inner">
                                <img :src="template.preview_url" class="img-fluid" @click="onTemplateSelected(template)">
                                <div class="is-premium badge badge-light" v-if="template.is_premium"><i class="bi bi-star-fill mr-1"></i> Premium</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="studio__body" :class="{'align-items-center': onboarding_step !== 3}" v-if="page === 'start'">
            <div class="container">
                <div class="row justify-content-center" v-if="onboarding_step === 1">
                    <div class="col-10 text-center" >
                        <h1>Let's create a beautiful quote.</h1>
                        <p class="lead">Press Enter or click on Start to create an amazing quote!</p>
                        <div class="text-center mt-5">
                            <button class="btn btn-lg btn-link text-dark" @click="onOnboardingSkipped()">Skip</button>
                            <button class="btn btn-success btn-lg" @click="onGoToOboardingStep(2)" ref="startButton">Start</button>
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
                            <img :src="template.preview_url" class="img-fluid" @click="onWizardTemplateSelected(template)">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal" tabindex="-1" role="dialog" id="saveModal">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header" v-if="!saving">
                        <h5 class="modal-title">Save</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <i class="bi bi-x"></i>
                        </button>
                    </div>
                    <div class="modal-body" v-if="saving">
                        <div class="my-5 text-center">
                            <!-- <h2 class="mx-4" style="font-weight: 700;">We are creating our awesome quote!</h2> -->
                            <img src="/img/loader.gif" width="100%">
                        </div>
                    </div>
                    <div class="modal-body" v-else>
                        <div class="form-group">
                            <label>Topic</label>
                            <select v-model="tag_id" class="form-control form-control-lg custom-select" :class="{'is-valid': tag_id, 'is-invalid': error_tag}" @change="error_tag=false;">
                                <option value="">Select a Topic</option>
                                <option v-for="tag in tags" :value="tag.id">{{tag.name}}</option>
                            </select>
                            <div class="valid-feedback" v-if="tag_id">Looks good!</div>
                            <div class="invalid-feedback" v-if="error_tag">Please choose a Topic.</div>
                        </div>
                        <div class="form-group">
                            <label>Keywords</label>
                            <input type="text" v-model="keywords" class="form-control form-control-lg" style="height: 48px;" placeholder="#love #life #sad #instagram">
                        </div>
                    </div>
                    <div class="modal-footer" v-if="!saving">
                        <button type="button" class="btn btn-primary btn-upper btn-lg" @click="onSaveDesign()" :disabled="saving">
                            <span v-if="!saving">Save</span>
                            <span v-else>Generating quote...</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <?= $this->element('studio/upgrade'); ?>
        <?= $this->element('studio/login-modal'); ?>
        <?= $this->element('studio/payment-success-modal'); ?>

        <pre class="template-debugger" v-if="debug">{{ template }}</pre>
    </div>

    <?php if (!$user): ?>
    <script src="https://accounts.google.com/gsi/client" async defer></script>
        <div id="g_id_onload"
        data-client_id="443859332061-cl9dab1raicl1ernph96mot32jpkhlk2.apps.googleusercontent.com"
        data-login_uri="<?= Router::url('/google/callback?redirect_to=/studio', true); ?>">
    </div>
    <?php endif; ?>
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

.form-control-hidden-file{width:0;height:0;opacity:0;position:absolute;overflow:hidden;padding:0;border:0}
.upgrade-modal .modal-title span{color:#ef7907;background:-webkit-linear-gradient(#ef7907,#f34200);-webkit-background-clip:text;-webkit-text-fill-color:transparent}
.upgrade-modal .modal-title{font-size:36px;font-weight:900;line-height:31px;margin:32px 0 14px;padding:4px}
.upgrade-modal .modal-title p{margin-bottom:30px}
.upgrade-modal .modal-content{border-radius:8px}
.upgrade-modal .modal-dialog{max-width:460px}
.payment-success-modal .modal-dialog{max-width:400px}
.login-modal .modal-dialog{max-width:430px}
.login-modal .modal-title{font-size:34px;font-weight:900;line-height:31px;margin:32px 0 20px;padding:4px}
.upgrade-modal .close{position:absolute;right:10px;top:10px;z-index:9999}
.upgrade-modal .features{display:flex;justify-content:center}
.upgrade-modal .features-list{display:inline-block}
.upgrade-modal .features-list li{padding:7px 0}
.upgrade-modal .features-list li>i{color:#1d9739;color:#f25603}
.upgrade-modal .btn-upgrade{border-bottom:3px solid #13772a;font-weight:700;padding:11px 27px;font-size:18px;margin-top:28px;margin-bottom:16px}

.templates-page-header .bi{font-size:30px;position:absolute;right:24px;top:6px}
.templates-page-header .bi:hover{cursor:pointer;opacity:.9;color:#eb5956}
.template-card{background-color:#eef0f6;text-align:center;border-radius:4px;padding:8px;position:relative;width:100%;height:100%;display:flex;align-items:center;justify-content:center}
.template-card-inner{position:relative;display:flex;align-items:center;justify-content:center}
.template-card-inner .is-premium{position:absolute;left:10px;top:10px;padding:7px 10px}
.template-card img{border-radius:4px}
.template-card:hover{cursor:pointer;background-color:#d9deea}

.font-picker-dropdown-menu .dropdown-item { position:relative;}
.font-picker-dropdown-menu .dropdown-item .bi { position: absolute; right: 0; top: 8px;}
</style>
<script>
    var __NEXT_DATA__ = {
        template: <?php echo json_encode($template); ?>,
        design: <?php echo json_encode($design); ?>,
        text: "<?= $text; ?>",
        backgrounds: <?php echo json_encode($backgrounds); ?>,
        templates: <?php echo json_encode($templates); ?>,
        tags: <?php echo json_encode($category_list); ?>,
        user: <?php echo json_encode($user); ?>,
    }

    <?php if ($action): ?>
        __NEXT_DATA__.mode = '<?= $action ?>';
    <?php endif; ?>
</script>

<?php
if ($env === 'DEV') {
    echo $this->Html->script([
        'studio/vue.js',
        'studio/moveable.min.js',
        'vendor/jquery.min.js',
        'vendor/popper.min.js',
        '/bootstrap4/js/bootstrap.min.js',
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