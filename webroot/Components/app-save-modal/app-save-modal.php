<template id="v-app-save-modal-template">
    <div v-if="showModal" class="creator__overlay" v-on:click.self="showModal=false">
        <div class="creator__save-preview">
            <div class="creator__save-image-preview">
                <div v-if="!imageData" class="loader"></div>
                <img v-if="imageData" :src="imageData" alt="" id="previewImage" />
            </div>
            <div class="creator__save-inputs">
                <label for="">Category</label>
                <select v-model="selectedCategory" class="form-control">
                    <option value="">Select Category</option>
                    <option :value="tag.id" v-for="tag in tags">
                        {{tag.name}}
                    </option>
                </select>
                <button :disabled="loading || !imageData"
                    class="creator__save-btn btn-lg btn btn-block btn-bold btn-success"
                    v-on:click="save">
                    Create Image
                </button>
            </div>
        </div>
    </div>
</template>
