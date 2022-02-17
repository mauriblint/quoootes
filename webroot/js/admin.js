
Vue.use(Toasted, {
    theme: "bubble",
    position: "top-center",
    duration : 3000
});

var app = new Vue({
    el: '#admin',
    data: {
        page: 'designs',
        templates: [],
        backgrounds: [],
        designs: [],
        tags: [],
        pagination: {},
        filters: {
            status: 0,
            page: 1,
        }
    },

    created: function () {

        if (window.location.hash) {
            this.page = window.location.hash.replace('#', '');
        }

        this.navigate(this.page);

        setTimeout(this._initTooltips, 1000);
        setInterval(this._initTooltips, 8000);

        this.tags = __NEXT_DATA__.tags;
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

        fetchDesigns: function (clear_all) {
            if (clear_all) {
                this.designs = [];
                this.filters.page = 1;
            }

            $.ajax({
                type: "GET",
                url: '/ajax/designs',
                data: {
                    status: this.filters.status,
                    page: this.filters.page,
                },
                dataType: 'json',
            })
            .done((r) => {
                this.designs = this.designs.concat(r.data);
                this.pagination = r.pagination;
            });
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
                    this.fetchDesigns(true);
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
                app.$toasted.show('Changes successfully saved!');
            })
        },

        onDeleteTemplate: function () {

        },

        onEditDesign: function (design) {
            window.open('/studio/' + design.id + '?action=edit_design');
        },

        updateDesignValue: function (design, prop, value, e) {
            var data = {
                design_id: design.id
            };

            data[prop] = value;

            $.ajax({
                url: "/ajax/update-design",
                type: 'POST',
                dataType: 'json',
                data: data
            })
            .done((response) => {
                design[prop] = value;
                app.$toasted.show('Changes successfully saved!');
            })
        },

        loadNextPage: function () {
            this.filters.page ++;
            this.fetchDesigns(false);
        }
    },
});