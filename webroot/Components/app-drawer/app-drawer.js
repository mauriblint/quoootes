Vue.component('app-drawer', {
    template: '#v-app-drawer-template',
    components: { 'swatches': window.VueSwatches.default },
    data: function () {
        return {
            toolOpen: false,
            galleryTabActive: 1,
            styles: {}
        }
    },

    watch: {

    },

    computed: {
        templateList: function () {
            return _.shuffle(TemplatesData);
        }
    },

    methods: {
        selectTemplate: function (template) {
            bus.$emit('TEMPLATE_SELECTED', template);
        },
    }
});
