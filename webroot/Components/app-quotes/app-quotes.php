<template id="v-app-quotes-template">
    <div class="app-quotes">
        <div class="form-group">
             <input type="text" v-model="q" @keyup.enter="search" class="form-control" placeholder="Search">
        </div>
        <div class="gallery__content">
            <div>
                <div class="app-quotes__item" v-for="quote in quotes" v-on:click="setQuote(quote)">
                    <span v-html="quote.text"></span>
                </div>
            </div>
        </div>
    </div>
</template>

<style>
.app-quotes {
    padding: 5px;
    display: flex;
    flex-direction: column;
}
.app-quotes__item {
    padding: 10px;
    background: #f2f2f2;
    margin-bottom: 10px;
    font-size: 13px;
    cursor: pointer;
}
.app-quotes__item:hover {
    background-color: #f8f8f8;
}
</style>
