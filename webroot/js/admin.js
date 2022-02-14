
var app = new Vue({
    el: '#admin',
    data: {
        page: 'designs',
        templates: [],
        backgrounds: [],
        designs: [],
    },

    created: function () {

        if (window.location.hash) {
            this.page = window.location.hash.replace('#', '');
        }

        this.navigate(this.page);

        setTimeout(this._initTooltips, 1000);
        setInterval(this._initTooltips, 8000);
    },

    methods: {
        _initTooltips: function () {
            $('[data-toggle="tooltip"]').tooltip();
        },

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
            if (!this.backgrounds.length) {
                $.ajax({
                    type: "GET",
                    url: '/ajax/backgrounds',
                    dataType: 'json',
                })
                .done((r) => this.backgrounds = r.data);
            }
        },

        fetchDesigns: function () {
            if (!this.designs.length) {
                $.ajax({
                    type: "GET",
                    url: '/ajax/designs',
                    dataType: 'json',
                })
                .done((r) => this.designs = r.data);
            }
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

            switch (this.page) {
                case 'templates':
                    this.fetchTemplates();
                    break;
                case 'backgrounds':
                    this.fetchBackgrounds();
                    break;
                case 'designs':
                    this.fetchDesigns();
                    break;
            }
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
        },

        onUploadBackgrounds: function () {
            this.$refs.fileUploadInput.click();
        },

        onChangeActive: function (template, status) {
            console.log(status);
            var _this = this;

            $.ajax({
                url: "/ajax/update-template",
                type: 'POST',
                dataType: 'json',
                data: {
                    template_id: template.id,
                    active: status
                }
            })
            .done((response) => {
                template.active = status;
            })
        },

        onChangeDesignStatus: function (design, status) {
            var _this = this;

            $.ajax({
                url: "/ajax/update-design",
                type: 'POST',
                dataType: 'json',
                data: {
                    design_id: design.id,
                    status: status
                }
            })
            .done((response) => {
                design.status = status;
            })
        },

        onDeleteTemplate: function () {

        }
    },
});