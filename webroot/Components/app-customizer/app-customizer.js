Vue.component('app-customizer', {
    template: '#v-app-customizer-template',
    components: { 'swatches': window.VueSwatches.default },
    data: function () {
        return {
            toolOpen: false,
            galleryTabActive: 1,
            styles: this.$root.$data.styles,
            fonts: FontsData,
            colorsArray: [["#FFF","#AAA","#555","#333","#000"],["#69d2e7","#a7dbd8","#e0e4cc","#f38630","#fa6900"],["#fe4365","#fc9d9a","#f9cdad","#c8c8a9","#83af9b"],["#ecd078","#d95b43","#c02942","#542437","#53777a"],["#556270","#4ecdc4","#c7f464","#ff6b6b","#c44d58"],["#774f38","#e08e79","#f1d4af","#ece5ce","#c5e0dc"],["#e8ddcb","#cdb380","#036564","#033649","#031634"],["#490a3d","#bd1550","#e97f02","#f8ca00","#8a9b0f"],["#594f4f","#547980","#45ada8","#9de0ad","#e5fcc2"],["#00a0b0","#6a4a3c","#cc333f","#eb6841","#edc951"],["#e94e77","#d68189","#c6a49a","#c6e5d9","#f4ead5"],["#3fb8af","#7fc7af","#dad8a7","#ff9e9d","#ff3d7f"],["#d9ceb2","#948c75","#d5ded9","#7a6a53","#99b2b7"],["#ffffff","#cbe86b","#f2e9e1","#1c140d","#cbe86b"],["#efffcd","#dce9be","#555152","#2e2633","#99173c"],["#343838","#005f6b","#008c9e","#00b4cc","#00dffc"],["#413e4a","#73626e","#b38184","#f0b49e","#f7e4be"],["#ff4e50","#fc913a","#f9d423","#ede574","#e1f5c4"],["#99b898","#fecea8","#ff847c","#e84a5f","#2a363b"],["#655643","#80bca3","#f6f7bd","#e6ac27","#bf4d28"],["#00a8c6","#40c0cb","#f9f2e7","#aee239","#8fbe00"],["#351330","#424254","#64908a","#e8caa4","#cc2a41"],["#554236","#f77825","#d3ce3d","#f1efa5","#60b99a"],["#ff9900","#424242","#e9e9e9","#bcbcbc","#3299bb"],["#5d4157","#838689","#a8caba","#cad7b2","#ebe3aa"],["#8c2318","#5e8c6a","#88a65e","#bfb35a","#f2c45a"],["#fad089","#ff9c5b","#f5634a","#ed303c","#3b8183"],["#ff4242","#f4fad2","#d4ee5e","#e1edb9","#f0f2eb"],["#d1e751","#ffffff","#000000","#4dbce9","#26ade4"],["#f8b195","#f67280","#c06c84","#6c5b7b","#355c7d"],["#1b676b","#519548","#88c425","#bef202","#eafde6"],["#bcbdac","#cfbe27","#f27435","#f02475","#3b2d38"],["#5e412f","#fcebb6","#78c0a8","#f07818","#f0a830"],["#452632","#91204d","#e4844a","#e8bf56","#e2f7ce"],["#eee6ab","#c5bc8e","#696758","#45484b","#36393b"],["#f0d8a8","#3d1c00","#86b8b1","#f2d694","#fa2a00"],["#f04155","#ff823a","#f2f26f","#fff7bd","#95cfb7"],["#2a044a","#0b2e59","#0d6759","#7ab317","#a0c55f"],["#bbbb88","#ccc68d","#eedd99","#eec290","#eeaa88"],["#b9d7d9","#668284","#2a2829","#493736","#7b3b3b"],["#b3cc57","#ecf081","#ffbe40","#ef746f","#ab3e5b"],["#a3a948","#edb92e","#f85931","#ce1836","#009989"],["#67917a","#170409","#b8af03","#ccbf82","#e33258"],["#e8d5b7","#0e2430","#fc3a51","#f5b349","#e8d5b9"],["#aab3ab","#c4cbb7","#ebefc9","#eee0b7","#e8caaf"]],
        }
    },

    watch: {

    },

    methods: {
        taintFontSize: function () {
            if (this.$root.$data.customFontSize === false) {
                this.$root.$data.customFontSize = true;
            }
        },
        validateTextSize: function () {
            var text = $('#textarea');
            var canv = $('#__quote');

            if (text.height() > canv.height()*0.9) {
                this.styles.fontSize = 30;
            }

            this.$root.$data.customFontSize = true;
        },

        onTextColorChange: function (color) {
        },
        onBgColorChange: function (color) {
            // this.$root.$data.styles.backgroundColor = color;
            // delete this.$root.$data.styles.backgroundImage;
            this.styles.backgroundImage = '';
        },
        selectBg: function (img) {
            this.styles.backgroundImage = 'url(/assets/backgrounds/' + img + ')';
            delete this.styles.backgroundColor;
        },
        selectFont: function (font) {
            this.styles.fontFamily = font;
        },
    }
});
