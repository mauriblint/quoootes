<template id="v-app-drawer-template">
    <div class="gallery">
        <div class="gallery__nav">
            <span v-on:click="galleryTabActive=1" :class="{active: galleryTabActive==1}">Templates</span>
            <span v-on:click="galleryTabActive=2" :class="{active: galleryTabActive==2}">Customizer</span>
            <span v-on:click="galleryTabActive=3" :class="{active: galleryTabActive==3}">Quotes</span>
        </div>

        <div class="gallery__content" v-if="galleryTabActive===1">
            <div class="templates-list">
                <div v-for="item in templateList"
                    class="templates-list__item template-item template-item__sm"
                    v-on:click="selectTemplate(item.id)">
                    <div class="templates-list__item-overlay">
                        {{item.id}}
                    </div>
                    <img class="templates-list__item-img" :src="'/generator/layouts/thumbs/tn_' + item.id + '.jpg?nocache1'" width="100%" alt="">
                </div>
            </div>
        </div>
        <app-customizer v-if="galleryTabActive===2"></app-customizer>
        <app-quotes v-if="galleryTabActive===3"></app-quotes>
    </div>
</template>

<style>
.gallery {
	width: 300px;
	height: 100%;
	background-color: #FFF;
    color: #222;
	padding: 10px;
    display: flex;
    flex-direction: column;
}
    .gallery__content {
    	flex: 1 1 auto;
    	overflow-y: auto;
    	height: 100%;
    	padding-right: 5px;
    }
    .gallery__content::-webkit-scrollbar {
        width: 6px;
        height: 6px;
        background-color:#eee;
    }
    .gallery__content::-webkit-scrollbar-thumb {
        background-color:#c2c2c2;
        border-radius: 6px;
    }

    .gallery__nav {
    	color: #333;
    	font-weight: 400;
    	padding: 5px 0px;
    	font-size: 15px;
        margin-bottom: 15px;
    }
    .gallery__nav span {
        cursor: pointer;
        padding: 4px 12px;
        border-radius: 27px;
        background-color: #ebebeb;
    }
    .gallery__nav span:hover {
        background-color: #e1e1e1;
    }
    .gallery__nav span.active {
        background-color: #d52269;
        color: #FFF;
    }
</style>
