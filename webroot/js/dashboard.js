var app = new Vue({
    el: '#vapp',

    data: function () {
        return {
            designs: [],
        }
    },

    created: function () {
        // var templatesCount = Object.keys(Template.layouts).length -1;
        // var randomTemplate = Math.floor(Math.random() * templatesCount) + 1;
        // this.selectTemplate(randomTemplate);
        // // this.selectTemplate(38);

        // if (App.frase) {
        //     this.quote.text = App.frase.text;
        //     this.frase_id = App.frase.id;
        // }
    },

    mounted: function () {
        // var _this = this;

        this.designs = __APP_DATA__.user_designs;

        // bus.$on('TEMPLATE_SELECTED', function (template) {
        //     _this.selectTemplate(template);
        // });
    },

    methods: {
        onEdit: function (event, item, index) {
            var _this = this;
            window.open('/studio/' + item.id + '?action=edit_design');
        },
        onDelete: function (event, item, index) {
            var _this = this;

            if (window.confirm("Do you really want to leave?")) {
                $.ajax({
                    type: "DELETE",
                    url: '/ajax/delete/' + item.id,
                    dataType: 'json',
                })
                .done(function (response) {
                    if (response.data.deleted) {
                        _this.designs.splice(index, 1);
                    }
                })
            }
        },
    }
});
