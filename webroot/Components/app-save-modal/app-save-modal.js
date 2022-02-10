Vue.component('app-save-modal', {
    template: '#v-app-save-modal-template',

    data: function () {
        return {
            showModal: false,
            imageData: false,
            loading: false,
            tags: _.sortBy(App.tags, 'name'),
            selectedCategory: '',
        }
    },

    mounted: function () {
        var _this = this;

        bus.$on('SAVE', function () {
            _this.imageData = null;
            _this.showModal = true;
            _this.selectedCategory = _this.$root.$data.frase_tag_id;
            Canvas.generate(_this.handleImage);
        });
    },

    methods: {
        handleImage: function (image) {
            this.imageData = image;
        },

        save: function () {
            var _this = this;

            if (this.selectedCategory === '') {
                this.$swal('Please, choose a Category before save.');
                return;
            }

            _this.loading = true;

            $.ajax({
               type: "POST",
               url: '/quotes/save',
               dataType: 'json',
               data: {
                    image: _this.imageData,
                    layout: _this.$root.$data.selectedTemplate,
                    tag_id: _this.selectedCategory,
                    text: _this.$root.$data.quote.text,
                    frase_id: _this.frase_id,
                    user_id: (App.User) ? App.User.id : false
               }
            })
            .done(function (response) {
                // window.open('/quote-' + response.data.id, '_blank');
                return false;
                window.location.href = '/quote-' + response.data.id;
            })
        },
    }
});
