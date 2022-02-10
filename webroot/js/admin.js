
var app = new Vue({
    el: '#admin',
    data: {
        page: 'templates',
        page: 'backgrounds',
        templates: [],
        backgrounds: [],
        // scale_factor: 1,

        // text: '',
        // text_footer: '',

        // template_id: null,
        // template: {
        //     layout: 'story',
        //     backgroundColor: '#7BD4AA',
        //     backgroundImage: 'https://images.pexels.com/photos/10176829/pexels-photo-10176829.jpeg?auto=compress&cs=tinysrgb&dpr=2&h=750&w=1260',
        //     backgroundImage: '',
        //     backgroundSize: 'cover',
        //     backgroundPosition: 'center',
        //     color: '#333333',
        //     boxBackgroundColor: '',
        //     boxPosition: 'center-center',
        //     boxWidth: 65,
        //     textAlign: 'center',
        //     lineHeight: 1,
        //     fontFamily: 'Open Sans',
        //     fontSize: 20,
        // },

        // layouts: [
        //     { name: 'Square', key: 'square', width: 500, height: 500 },
        //     { name: 'Story', key: 'story', width: 383, height: 680 },
        //     { name: 'Landscape', key: 'landscape', width: 700, height: 400 }
        // ]
    },

    created: function () {

        if (window.location.hash) {
            this.page = window.location.hash.replace('#', '');
        }
        // this.showEnterText
        this.fetchTemplates();
        this.fetchBackgrounds();

        // if (window.__NEXT_DATA__ && window.__NEXT_DATA__.text) {
        //     this.text = window.__NEXT_DATA__.text;
        // }

        // if (window.__NEXT_DATA__ && window.__NEXT_DATA__.template) {
        //     this.setTemplate(window.__NEXT_DATA__.template);
        // }
    },

    methods: {
        fetchTemplates: function () {
            if (!this.templates.length) {
                $.ajax({
                    type: "GET",
                    url: '/templates/all',
                    dataType: 'json',
                })
                .done(this.onTemplatesFetched);
            }
        },
        fetchBackgrounds: function () {
                $.ajax({
                    type: "GET",
                    url: '/ajax/backgrounds',
                    dataType: 'json',
                })
                .done((r) => this.backgrounds = r.data);
        },

        onTemplatesFetched: function (response) {
            this.templates = response.data;
        },

        onEditTemplate: function (template) {
            window.open('/studio/' + template.hash + '?action=edit_template');
        },

        onTemplateLoaded: function (response) {
            if (response.success && response.data.styles) {
                this.setTemplate(response.data);
            }
        },

        setTemplate: function (data) {
            if (!data.styles.layout) {
                data.styles.layout = 'square';
            }
            this.template = data.styles;
            this.template_id = data.id;

            this.page = 'editor';
        },

        navigate: function (slug) {
            this.page = slug;
        },


        onImagesSelected: function (event) {
            var files = (event.dataTransfer) ? event.dataTransfer.files : event.target.files;
            for (var i = 0; i < files.length; i++) {
                var file_data = files[i];
                var form_data = new FormData();
                form_data.append("file", file_data);

                this.uploading = true;

                $.ajax({
                    url: "/ajax/upload-background",
                    type: 'POST',
                    cache: false,
                    contentType: false,
                    dataType: 'json',
                    processData: false,
                    data: form_data,
                    success: (r) => {
                        this.backgrounds.push(r.data);
                    }
                });
            };
        },

        onDeleteBackground: function (bg) {
            if (window.confirm("Do you really want to DELETE?")) {
                $.ajax({
                    url: "/ajax/delete-background",
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        id: bg.id
                    }
                })
                .done((response) => {
                    this.fetchBackgrounds();
                })
            }
        }

    },
});

