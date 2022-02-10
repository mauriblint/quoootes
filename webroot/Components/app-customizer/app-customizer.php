<template id="v-app-customizer-template">
    <div class="gallery__content">
        <div class="tools" v-if="!toolOpen">
            <div class="form-group">
                <label class="customizer__label">Font Size</label>
                <input v-on:change="validateTextSize"
                    v-on:input="taintFontSize"
                    class="form-control-range"
                    v-model="styles.fontSize" type="range" value="20" min="15" max="200" />
            </div>
            <div class="form-group">
                <div class="btn-group btn-group-block" role="group">
                  <button v-on:click="styles.textAlign='left'" type="button" class="btn btn-light"><i class="fa fa-align-left"></i></button>
                  <button v-on:click="styles.textAlign='center'" type="button" class="btn btn-light"><i class="fa fa-align-center"></i></button>
                  <button v-on:click="styles.textAlign='right'" type="button" class="btn btn-light"><i class="fa fa-align-right"></i></button>
                  <!-- <button v-on:click="alert" type="button" class="btn btn-secondary"><i class="fa fa-align-justify"></i></button> -->
                </div>
            </div>
            <div class="color-input" v-on:click="toolOpen='color'">
                <span class="color-input__swatch" :style="{backgroundColor: styles.color}"></span>
                <span class="color-input__label">Text Color</span>
            </div>
            <div class="color-input" v-on:click="toolOpen='backgroundColor'">
                <span class="color-input__swatch" :style="{backgroundColor: styles.backgroundColor}"></span>
                <span class="color-input__label">Background Color</span>
            </div>
            <div class="color-input" v-on:click="toolOpen='font'">
                <span class="color-input__swatch color-input__font">Ag</span>
                <span class="color-input__label">Typography</span>
            </div>
            <div class="color-input" v-on:click="toolOpen='image'">
                <span class="color-input__swatch color-input__image" :style="{backgroundImage: styles.backgroundImage}"></span>
                <span class="color-input__label">Background Image</span>
            </div>
        </div>
        <div class="tools-actions" v-if="toolOpen">
            <button class="btn btn-default" v-on:click="toolOpen=false">
                <i class="fa fa-chevron-left"></i> Back
            </button>
        </div>
        <swatches v-if="toolOpen=='color'" :colors="colorsArray"  v-model="styles.color" @input="onTextColorChange" inline></swatches>
        <swatches v-if="toolOpen=='backgroundColor'" :colors="colorsArray" v-model="styles.backgroundColor" @input="onBgColorChange" inline></swatches>
        <div v-if="toolOpen=='font'" class="font-picker">
            <span v-for="font in fonts"
                class="font-picker__font"
                :style="{fontFamily: font.name}"
                v-on:click="selectFont(font.name)">{{font.name}}</span>
        </div>
        <div class="image-picker" v-if="toolOpen=='image'">
            <div v-for="bg in App.images" class="image-picker__item" v-on:click="selectBg(bg)" :style="{backgroundImage: 'url(/assets/backgrounds/'+bg+')'}">
            </div>
        </div>
    </div>
</template>

<style>
.tools {
    padding: 0 15px;
}
    .customizer__label {
        color: #333;
    }
    .customizer__input {

    }

    .btn-group-block {
        display: flex;
    }
    .btn-light {
        flex: 1 !important;
        background-color: #e9f0f7;
    }
</style>
