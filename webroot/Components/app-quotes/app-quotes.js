Vue.component('app-quotes', {
    template: '#v-app-quotes-template',

    data: function () {
        return {
            quotes: [],
            q: '',
        }
    },

    mounted: function () {
        var _this = this;

        this.$http.get('/frases/search').then(function (response) {
            _this.quotes = response.body.results;
        });
    },

    methods: {
        setQuote: function (quote) {
            this.$root.$data.quote.text = quote.text;
            this.$root.$data.frase_id = quote.id;
            this.$root.$data.frase_tag_id = quote.tag_id;
        },

        search: function () {
            this.$http.get('/frases/search/' + this.q).then(function (response) {
                this.quotes = response.body.results;
            });
        }
    }
});
