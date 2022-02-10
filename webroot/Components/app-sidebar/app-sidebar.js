Vue.component('app-sidebar', {
    template: '#v-app-sidebar-template',

    data: function () {
        return {
            charLeft: 190,
        }
    },

    methods: {
        saveDesign: function () {
            var text = this.$root.$data.quote.text;
            if (text === '' || text.length<'5' || text === 'Type your text. Click to move around.') {
                this.$swal('Please, write some text before save. Enter some text in the area  up to the save button.');
                return false;
            }
            bus.$emit('SAVE');
        },
    }
});
