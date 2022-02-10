<?php $this->layout = 'app';?>
<div id="vue-app">

	<div class="main" v-cloak>

		<div class="creator">
            <app-sidebar>
                <textarea placeholder="Start typing.."
                    v-model="quote.text"
                    v-on:focus="blurInput"
                    v-on:blur="onInputBlur"
                    id="textinput" style="width:100%;" class="form-control" rows="6"></textarea>
            </app-sidebar>

            <app-drawer></app-drawer>

			<div class="creator__stage">
				<div class="editor">
					<div id="__quote" class="kanvas" :class="canvasClasses" :style="canvasStyles">
                        <div v-on:click="blurInput" v-click-outside="unSelect" id="textarea" class="text">
                            <span class="text-content" v-html="textHtml"></span>
                        </div>
					</div>
				</div>
			</div>

            <div class="creator__ad">
                <div class="text-center">
                    <?=$this->element('ads/ad-editor') ?>
                </div>
            </div>
		</div>
		<div class="foot">
		    <!-- <p class="homeText"><strong>CreaTuFrase.net</strong> es un portal web donde podrás crear tus propias <strong>imágenes con frases para compartir</strong></p> -->
		</div>

        <app-save-modal></app-save-modal>
	</div>
</div>
<!-- vue-app -->

<div id="cloneCan" class="d-none kanvas" :class="canvasClasses">
    <div id="cloneText" class="text"></div>
</div>

<style>
.ui-resizable-handle {
    position: absolute;
    top: 50%;
    width: 18px;
    height: 18px;
    border-radius: 50%;
    background-color: rgba(0,0,0,.3);
    display: none !important;
    cursor: pointer;
 }
.ui-resizable-handle:hover {
    background-color: rgba(0,0,0,.5);
}
 .ui-resizable-handle.ui-resizable-e {
    right: -10px;
 } .ui-resizable-handle.ui-resizable-w {
    left: -10px;
 }
#textarea.active .ui-resizable-handle {
    display: block !important;
}
</style>
