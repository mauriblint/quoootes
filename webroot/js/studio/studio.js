var Fonts = {};

Fonts.insertFonts = function () {
    var FontList = this.getFontList();
    var gfonts_url = "https://fonts.googleapis.com/css2?" + FontList + "&display=swap";
    $("head").append("<link href='" + gfonts_url + "' rel='stylesheet' type='text/css'>");
};

Fonts.getFontsArray = function () {
    return FontsData;
    var result = [];
    FontsData.forEach(function (o) {
        result.push(o.name);
    });
    return result;
};

Fonts.getFontList = function () {
    var list = [];

    $.each(FontsData, function (index, font) {
        if (!font.custom) {
            var name = font.name.split(' ').join('+');
            var weight = (font.weight) ? ':wght@' + font.weight : '';
            var r = name + weight;

            list.push(r);
        }
    });

    return 'family=' + list.join('&family=');
};


Fonts.insertFonts();

var app = new Vue({
    el: '#studio',
    data: {
        mode: 'create',
        page: 'start',
        onboarding_step: 1,
        sidebar_open: null,
        templates: [],
        backgrounds: [],
        user: null,
        tags: [],
        tag_id: '',
        keywords: '',
        saved_session: false,
        is_free: true,

        scale_factor: 1,
        text: '',
        text_header: '',
        text_footer: '',
        show_textbox_area: false,
        saving: false,
        error_tag: false,

        search_image_query: '',
        photos: [],
        loading: false,

        popovers: {
            color_picker_1_open: false,
            color_picker_2_open: false,
            color_picker_3_open: false,
        },

        text_positions: [
            'left-top',
            'center-top',
            'right-top',
            'left-center',
            'center-center',
            'right-center',
            'left-bottom',
            'center-bottom',
            'right-bottom',
        ],

        fonts: [],
        filters: [],

        template_id: null,

        template: {},
        initial_template: {
            layout: 'square',
            backgroundColor: '#FFFFFF',
            backgroundImage: '',
            backgroundSize: 'cover',
            backgroundPosition: 'center',
            color: '#1a1a1a',

            boxVisible: false,
            boxBackgroundColor: '#228c5f',
            boxPosition: 'center-center',
            boxBorderRadius: 4,
            boxPadding: 20,

            textAlign: 'center',
            lineHeight: 1,
            fontFamily: 'Open Sans',
            fontSize: 20,
            fontWeight: 400,
            letterSpacing: 1,
            textUppercase: false,
            textOffset: 0,

            footerVisible: false,
            headerVisible: false,
            watermarkPosition: 4,

            filter: '',
            filterOpacity: 0.65,

            logoImage: '', // https://quoootes.com/img/isologo_color.png
            logoSize: 38,
            logoBorderRadius: 8,

            textboxWidth: 250,
            textboxHeight: 250,
        },

        canvas_width: 0,
        canvas_height: 0,

        // text_max_width: 0,
        // text_min_width: 0,
        // text_max_height: 0,
        // text_min_height: 0,

        show_advanced_options: false,

        layouts: [
            { name: 'Square', key: 'square', width: 500, height: 500, textboxWidth: 290, textboxHeight: 290, text_min_width: 0.3, text_min_height: 0.3, text_max_width: 0.8, text_max_height: 0.8 },
            { name: 'Story', key: 'story', width: 383, height: 680, textboxWidth: 215, textboxHeight: 340, text_min_width: 0.4, text_min_height: 0.4, text_max_width: 0.7, text_max_height: 0.7   },
            { name: 'Landscape', key: 'landscape', width: 700, height: 400, textboxWidth: 470, textboxHeight: 170, text_min_width: 0.5, text_min_height: 0.35, text_max_width: 0.82, text_max_height: 0.55 },
            { name: 'Portrait', key: 'portrait', width: 450, height: 650, textboxWidth: 220, textboxHeight: 340, text_min_width: 0.4, text_min_height: 0.4, text_max_width: 0.7, text_max_height: 0.7   }
        ]
    },

    watch: {
        'template.fontFamily': function () {
            setTimeout(this.resizeText, 100);
            setTimeout(this.resizeText, 200);
        },
        'template.lineHeight': function () {
            setTimeout(this.resizeText, 100);
            setTimeout(this.resizeText, 200);
        },
        'template.letterSpacing': function () {
            setTimeout(this.resizeText, 100);
            setTimeout(this.resizeText, 200);
        }
    },

    mounted: function () {
    },

    created: function () {
        // this.fetchTemplates();

        this.template = Object.assign({}, this.initial_template);

        if (window.__NEXT_DATA__ && window.__NEXT_DATA__.text) {
            this.text = window.__NEXT_DATA__.text;
        }

        if (window.__NEXT_DATA__ && window.__NEXT_DATA__.backgrounds) {
            this.backgrounds = window.__NEXT_DATA__.backgrounds;
        }

        if (window.__NEXT_DATA__ && window.__NEXT_DATA__.templates) {
            this.templates = window.__NEXT_DATA__.templates;
        }

        if (window.__NEXT_DATA__ && window.__NEXT_DATA__.template) {
            this.setTemplate(window.__NEXT_DATA__.template, true);
        }

        if (window.__NEXT_DATA__ && window.__NEXT_DATA__.mode) {
            this.mode = window.__NEXT_DATA__.mode;
        }

        if (window.__NEXT_DATA__ && window.__NEXT_DATA__.tags) {
            this.tags = window.__NEXT_DATA__.tags;
        }

        if (window.__NEXT_DATA__ && window.__NEXT_DATA__.user) {
            this.user = window.__NEXT_DATA__.user;
            this.is_free = (this.user.is_free);
        }

        if (this.mode === 'create') {
            mixpanel.track("ViewStudio");
        }

        if (this.mode === 'create_template' || this.mode === 'edit_template') {
            this.text = 'Life is what happens when you’re busy making other plans.';
            this.page = 'editor';
        }

        if (this.mode === 'edit_design') {
            this.page = 'editor';
            this.text = __NEXT_DATA__.design.text;
            this.text_header = __NEXT_DATA__.design.text_header;
            this.text_footer = __NEXT_DATA__.design.text_footer;
            this.tag_id = __NEXT_DATA__.design.tag.id;
            if (__NEXT_DATA__.design && __NEXT_DATA__.design.template) {
                this.setTemplate(__NEXT_DATA__.design.template, true);
            }
        }

        if (this.hasSavedSession()) {
            this.saved_session = true;
            this.restoreWorkSession();
        }

        setTimeout(this.onResize, 100);
        setTimeout(this.onFocusTextarea, 100);

        this.fonts = Fonts.getFontsArray();
        this.filters = FiltersData;

        if (window.location.search && window.location.search.indexOf('payment_success') > -1) {
            setTimeout(function () {
                $('#SuccessPaymentModal').modal();
            }, 1000);
        }
    },

    methods: {
        onTextChange: function (type) {
            this.resizeText();
            if (type === 'text_paste') {
                setTimeout(this.resizeText, 100);
            }
        },

        saveDesign: function () {
            $('#saveModal').modal();
            mixpanel.track("OpenedSaveDesign");
        },

        onSaveDesign: function () {
            if (!this.tag_id) {
                this.error_tag = true;
                return;
            }
            this.saving = true;
            this.generate(this.onDesignGenerated, 1);
        },

        onDesignGenerated: function (imageData, width, height) {
            var design_id = (__NEXT_DATA__.design && __NEXT_DATA__.design.id) ? __NEXT_DATA__.design.id : null;
            var action_url = (design_id) ? '/ajax/edit' : '/ajax/save';

            $.ajax({
                type: "POST",
                url: action_url,
                dataType: 'json',
                xhrFields: {
                    withCredentials: true
                },
                data: {
                    template: this.template,
                    template_id: this.template_id,
                    image: imageData,
                    width: width,
                    height: height,
                    text: this.text,
                    text_header: (this.template.headerVisible) ? this.text_header : '',
                    text_footer: (this.template.footerVisible) ? this.text_footer : '',
                    tag_id: this.tag_id,
                    keywords: this.keywords,
                    design_id: (__NEXT_DATA__.design && __NEXT_DATA__.design.id) ? __NEXT_DATA__.design.id : null
                }
             })
             .done(function (response) {
                mixpanel.track("SavedDesign");
                window.location.href = '/quote-' + response.data.id;
             })
        },

        saveTemplate: function () {
            this.generate(this.onTemplatePreviewGenerated, 0.5);
        },

        onTemplatePreviewGenerated: function (imageData) {
            $.ajax({
                type: "POST",
                url: (this.template_id) ? '/templates/update' : '/templates/create',
                dataType: 'json',
                data: {
                    styles: this.template,
                    thumb: imageData,
                    template_id: this.template_id,
                    layout: this.template.layout
                }
             })
             .done(function (response) {
                // console.log(response);
                // return false;
                window.location.href = '/studio/' + response.data.hash + '?action=edit_template';
             })
        },

        createNewTemplate: function () {
            window.location.href = '/studio/?action=create_template';
        },

        onViewTemplates: function () {
            this.fetchTemplates();
            this.page = 'templates';
        },

        fetchTemplates: function () {
            if (!this.templates.length) {
                $.ajax({
                    type: "GET",
                    url: '/templates/list',
                    dataType: 'json',
                })
                .done(this.onTemplatesFetched);
            }
        },

        onTemplatesFetched: function (response) {
            this.templates = response.data;

            if (!this.template_id && this.mode !== 'create_template'  && this.mode !== 'edit_design' && !this.saved_session) {
                this.setRandomTemplate();
            }
        },

        onTemplateSelected: function (id) {
            this.loadTemplate(id);
            this.page = 'editor';
        },

        loadTemplate: function (id) {
            $.ajax({
                type: "GET",
                url: '/templates/config',
                dataType: 'json',
                data: {
                    id: id
                }
             })
             .done(this.onTemplateLoaded);
        },

        onTemplateLoaded: function (response) {
            if (response.success && response.data.styles) {
                this.setTemplate(response.data);
            }
        },

        setRandomTemplate: function (data) {
            var item = this.templates[Math.floor(Math.random()*this.templates.length)];
            this.loadTemplate(item.id);
        },

        setTemplate: function (data, back_to_editor) {
            var template_styles = (data.styles) ? data.styles : data;
            this.template = Object.assign({}, this.initial_template, this._parseTemplateJSON(template_styles));
            this.template_id = data.id;

            setTimeout(this.updateResizerPosition, 200);
            setTimeout(this.resizeText, 50);
            setTimeout(this.resizeText, 150);

            if (back_to_editor) {
                this.page = 'editor';
            }
        },

        setLayout: function (layout) {
            this.template.layout = layout.key;
            var layout = this._getLayout(layout.key);
            this.template.textboxWidth = layout.textboxWidth;
            this.template.textboxHeight = layout.textboxHeight;
            this.template.textboxLeft = ((layout.width - layout.textboxWidth) / 2);
            this.template.textboxTop = ((layout.height - layout.textboxHeight) / 2);
        },

        _parseTemplateJSON: function (template) {
            if (!template) {
                return {};
            }
            Object.keys(template).forEach((key) => {
                if (template[key] === 'false') {
                    template[key] = false;
                }
                else if (template[key] === 'true') {
                    template[key] = true;
                }
                else if (template[key] === 'null') {
                    template[key] = null;
                }
                else if (!isNaN(parseFloat(template[key]))) {
                  template[key] = parseFloat(template[key]); // This is bad, '1abc' will be 1
                }
            });

            if (template.boxWidth) {
                delete template.boxWidth;
            }

            var template_layout = this._getLayout(template.layout);

            if (!template.textboxWidth) {
                template.textboxWidth = template_layout.textboxWidth;
                template.textboxHeight = template_layout.textboxHeight;
                template.textboxLeft = ((template_layout.width - template_layout.textboxWidth) / 2);
                template.textboxTop = ((template_layout.height - template_layout.textboxHeight) / 2);
            }

            if (this.template.footerVisible) {
                template.footerVisible = true;
            }
            if (this.template.headerVisible) {
                template.headerVisible = true;
            }

            return template;
        },

        getText: function () {
            if (!this.text && !this.text_footer && !this.text_header) {
                return 'Write something..'
            }

            if (this.template.textUppercase) {
                return this.text.replace(/<\/?[^>]+(>|$)/g, "").toUpperCase();
            }

            return this.text;
        },

        getHeader: function () {
            if (this.text_header && this.template.headerVisible) {
                if (this.template.textUppercase) {
                    return this.text_header.toUpperCase();
                }
                return this.text_header;
            }

            return '';
        },

        getFooter: function () {
            if (this.text_footer && this.template.footerVisible) {
                var ft = this.text_footer;
                var ft_start = '— ';
                var ft_end = ' —';

                if (this.template.textUppercase) {
                    ft = this.text_footer.toUpperCase();
                }
                if (this.template.textAlign === 'left') {
                    ft_end = '';
                }
                if (this.template.textAlign === 'right') {
                    ft_start = '';
                }
                return ft_start + ft + ft_end;
            }

            return '';
        },

        getEditorStyles: function () {
            var dimensions = this.getLayoutDimensions(this.template.layout);

            return {
                backgroundImage: this.template.backgroundImage && 'url(' + this.template.backgroundImage + ')',
                backgroundSize: this.template.backgroundSize,
                backgroundPosition: this.template.backgroundPosition,
                backgroundColor: this.template.backgroundColor,
                color: this.template.color,
                transform: 'scale(' + this.scale_factor + ')',
                width: dimensions.width + 'px',
                minWidth: dimensions.width + 'px',
                height: dimensions.height + 'px',
            };
        },

        getBoxStyles: function () {
            var position = this.template.boxPosition.split('-');
            var position_x = position[0];
            var position_y = position[1];

            if (position_y === 'top') position_y = 'flex-start';
            if (position_y === 'bottom') position_y = 'flex-end';

            return {
                alignItems: position_y,
                justifyContent: position_x
            };
        },

        getLogoStyles: function () {
            var marginLeft = 0;
            if (this.template.logoPosition === '5' || this.template.logoPosition === '6') {
                marginLeft = (parseInt(this.template.logoSize)/2) * -1;
            }
            return {
                width: this.template.logoSize + 'px',
                borderRadius: this.template.logoBorderRadius + '%',
                marginLeft: marginLeft
            };
        },

        getTextStyles: function () {
            var boxWidth = this.template.textboxWidth;
            var boxHeight = this.template.textboxHeight;
            var boxTop = this.template.textboxTop;
            var boxLeft = this.template.textboxLeft;

            if (!this.template.textboxWidth) {
                boxWidth = Math.round(parseInt(this.canvas_width * 0.5));
            }
            if (!this.template.textboxHeight) {
                boxHeight = Math.round(parseInt(this.canvas_height * 0.5));
            }
            return {
                backgroundColor: (this.template.boxVisible) ? this.template.boxBackgroundColor : 'transparent',
                padding: (this.template.boxVisible) ? parseInt(this.template.boxPadding) + 'px' : '20px',
                borderRadius: parseInt(this.template.boxBorderRadius) + 'px',
                textAlign: this.template.textAlign,
                width: boxWidth + 'px',
                height: boxHeight + 'px',
                top: boxTop + 'px',
                left: boxLeft + 'px',
                textAlign: this.template.textAlign,
                lineHeight: this.template.lineHeight,
                fontFamily: this.template.fontFamily,
                fontSize: parseInt(this.template.fontSize) + 'px',
                letterSpacing: parseFloat(this.template.letterSpacing) + 'px',
                // marginTop: (this.template.textOffset) ? parseInt(this.template.textOffset) + 'px' : '',
                borderColor: (this.show_textbox_area) ? this.template.color : 'transparent',
            };
        },

        _getLayout: function (layout) {
            var find = this.layouts.find(function (item) {
                return item.key === layout;
            });

            return find;
        },

        getLayoutDimensions: function (layout) {
            var find = this._getLayout(layout);

            return find;
        },

        generate: function (callback, scaleFactor) {
            var _this = this;
            this.destroyMoveable();
            html2canvas($('.studio__canvas')[0], {
                // useCORS: true,
                proxy: '/ajax/proxy',
                scale: scaleFactor || window.devicePixelRatio,
                onclone: function (cloned) {
                    var a = cloned.getElementsByClassName('studio__canvas')[0];
                    // if(a.style.backgroundImage) {
                    //     a.style.backgroundImage = a.style.backgroundImage.replace('")', '?version='+Date.now()+'")');
                    // }
                    if (a) {
                        a.style.boxShadow = 'none';
                        a.style.transform = 'none';
                    }
                    if (_this.mode === 'edit_template' || _this.mode === 'create_template') {
                        $watermark = cloned.getElementsByClassName('studio__watermark');
                        if ($watermark[0]) {
                            $watermark[0].remove();
                        }
                    }
                 }
            }).then(function (canvas) {
				var imgData = canvas.toDataURL("image/jpeg", 1);
                var img = document.createElement('img');
                img.src = imgData;
                document.body.appendChild(img);

                if (callback) {
                    callback(imgData, canvas.width, canvas.height);
                }
            });
        },

        onFocusTextarea: function (e) {
            if (this.$refs.textInput) {
                this.$refs.textInput.focus();
            }
        },

        onResize: function (e) {
            if (!this.$refs.studio) {
                return false;
            }
            var studio_width = this.$refs.studio.offsetWidth;
            var studio_height = this.$refs.studio.offsetHeight;
            var canvas_width = this.$refs.canvas.offsetWidth;
            var canvas_height = this.$refs.canvas.offsetHeight;

            var scale_factor = 1;

            if (canvas_width > (studio_width*0.9)) {
                scale_factor = (studio_width * 0.9) / canvas_width;
            }
            if(canvas_height > (studio_height*0.9)) {
                var sf = (studio_height * 0.9) / canvas_height;
                if (sf < scale_factor) {
                    scale_factor = sf;
                }
            }

            this.scale_factor = scale_factor;
        },

        onClick: function (e) {
            if (e.target.className.indexOf('studio__editor') > -1 ||
                e.target.className.indexOf('sidebar') > -1 ||
                e.target.className.indexOf('form-group') > -1 ||
                e.target.className.indexOf('swatch') > -1 ||
                e.target.nodeName.indexOf('LABEL') > -1
            ) {
                this.popovers.color_picker_1_open = false;
                this.popovers.color_picker_2_open = false;
                this.popovers.color_picker_3_open = false;
            }

            if (e.target.className.indexOf('moveable') === -1 &&
                e.target.className.indexOf('studio__canvas__text') === -1 &&
                e.target.className.indexOf('studio__canvas_resizer') === -1
            ) {
                this.destroyMoveable();
            }

        },

        onBackgroundSelected: function (bg_url) {
            this.template.backgroundImage = bg_url;
        },

        getLayoutFullName: function (layout) {
            var Layout = this._getLayout(layout);

            return Layout.name + ' (' + Layout.width + 'x' + Layout.height + ')';
        },

        onGoToOboardingStep: function (step) {
            var _this = this;
            this.onboarding_step=step;
            if (step === 2) {
                mixpanel.track("StudioWizardStarted");
                setTimeout(function () {
                    _this.$refs.onboardingInput.focus()
                }, 200);
            }

            if (step === 3) {
                mixpanel.track("StudioWizardAddedText");
            }
        },

        onOnboardingSkipped: function () {
            this.page = 'editor';
            // setTimeout(this.repositionTextBox, 200);
            setTimeout(this.onResize, 200);
            mixpanel.track("StudioWizardSkipped");
        },

        onWizardTemplateSelected: function (template_id) {
            this.onTemplateSelected(template_id);
            mixpanel.track("StudioWizardTemplateSelected");
        },

        onShowTextBoxDimensions: function (a) {
            this.show_textbox_area = a;
        },

        onKeyPress: function (e) {
            if (this.page === 'start' && this.onboarding_step === 1) {
                this.onGoToOboardingStep(2);
                // window.removeEventListener('keyup', window.app.onKeyPress, true);
            }

            if(e.which == 27) {
                this.closeSidebar();
            }
        },

        resetSearch: function () {
            this.photos = [];
            this.search_image_query = '';
            this.folderOpen = false;
            this.folderContent = [];
        },

        searchPixImages: function (event) {
            // if (!query) {
            //     this.photos = [];
            //     return;
            // }
            this.loading = true;
            this.photos = [];
            var page = Math.floor((Math.random() * 100) + 1);
            var url = 'https://pixabay.com/api/?key=4442277-0ac9e784a7feed1c4455d5775&per_page=24&lang=es&image_type=photo&q='
            var _this = this;

            $.get(url + this.search_image_query)
                .done(response => {
                    _this.photos = _this.$transformPixabayImages(response.hits);
                })
                .always(() => {
                    _this.loading = false;
                });
        },

        $transformPixabayImages: function (images) {
            return images.map(function (obj) {
                return {
                    thumb: obj.previewURL,
                    image: obj.webformatURL,
                }
            });
        },

        openSidebar: function (type) {
            this.sidebar_open = type;
            setTimeout(function () { $('[data-toggle="tooltip"]').tooltip(); }, 500);
        },

        closeSidebar: function () {
            this.sidebar_open = false;
            $('.tooltip').remove();
        },

        setFilter: function (filter) {
            this.template.filter = filter.color;
        },

        onTextMouseHover: function () {
            if (!window.MoveableInstance) {
                window.initMovable();
            }
        },

        onTextMouseLeave: function (e) {
            if (e && e.toElement && e.toElement.className.indexOf('moveable') === -1) {
                this.destroyMoveable();
            }
        },

        destroyMoveable: function () {
            if (window.MoveableInstance && !window.MoveableDragging) {
                window.MoveableInstance.destroy();
                window.MoveableInstance = null;
            }
        },

        updateResizerPosition: function (direction) {
            // console.log('updateResizerPosition');
            if (window.MoveableInstance) {
                return false;
            }
            $StudioCanvasText = document.getElementById('StudioCanvasText');
            $StudioCanvasTextResizer = document.getElementById('StudioCanvasTextResizer');

            if (!$StudioCanvasText || !$StudioCanvasTextResizer) {
                return false;
            }

            var StudioCanvasTextRect = $StudioCanvasText.getBoundingClientRect();
            var StudioCanvasTextPosition = $($StudioCanvasText).position();

            $StudioCanvasTextResizer.style.top = StudioCanvasTextPosition.top + 'px';
            $StudioCanvasTextResizer.style.left = StudioCanvasTextPosition.left + 'px';
            $StudioCanvasTextResizer.style.width = StudioCanvasTextRect.width + 'px';
            $StudioCanvasTextResizer.style.height = StudioCanvasTextRect.height + 'px';
        },

        centerTextOnCanvas: function (_position) {
            var position = (_position || this.template.boxPosition || 'center-center').split('-');
            var position_x = position[0];
            var position_y = position[1];
            var left = 0;
            var top = 0;
            var $StudioCanvas = $('#StudioCanvas');
            var $StudioCanvasText = $('#StudioCanvasText');

            if (position_x === 'center') {
                left = ($StudioCanvas.outerWidth()/2 - $StudioCanvasText.outerWidth()/2)
            }
            if (position_x === 'left') {
                left = 20;
            }
            if (position_x === 'right') {
                left = (($StudioCanvas.outerWidth()/2 - $StudioCanvasText.outerWidth()/2)*2)-20;
            }

            if (position_y === 'center') {
                top = ($StudioCanvas.outerHeight()/2 - $StudioCanvasText.outerHeight()/2);
            }
            if (position_y === 'top') {
                top = 20;
            }
            if (position_y === 'bottom') {
                top = (($StudioCanvas.outerHeight()/2 - $StudioCanvasText.outerHeight()/2)*2)-20;
            }

            $StudioCanvasText.css({
                top: top,
                left: left
            }) ;

            this.destroyMoveable();
        },

        resizeText: function () {
            var a = document.getElementById('StudioCanvasText');
            var b = document.getElementById('StudioCanvasTextContainer');

            if (!a || !b) { return false; }
            if (!this.text) { return false; }
            get_is_h_overflown = () => {
                var available_h = a.getBoundingClientRect().height - (this.template.boxPadding * 2);
                var text_h = b.getBoundingClientRect().height;
                return (text_h > available_h);
            }

            get_is_h_subbflown = () => {
                var available_h = a.getBoundingClientRect().height - (this.template.boxPadding * 2);
                var text_h = b.getBoundingClientRect().height;
                return (text_h < available_h);
            }

            var MAX_FONT_SIZE = 60;
            var MIN_FONT_SIZE = 8;
            var new_font_size = 0;

            var is_h_overflown = get_is_h_overflown();
            var while_steps = 0;

            while (is_h_overflown && while_steps < 1000 && new_font_size < MAX_FONT_SIZE) {
                console.log('while_steps', while_steps, 'is_h_overflown', is_h_overflown);
                new_font_size = (parseFloat(a.style.fontSize.replace('px')) - 0.2);
                a.style.fontSize = (new_font_size + 'px');
                is_h_overflown = get_is_h_overflown();
                while_steps++;
            }

            var is_h_subbflown = get_is_h_subbflown();
            var while_steps = 0;

            while (is_h_subbflown && while_steps < 1000 && new_font_size < MAX_FONT_SIZE) {
                console.log('while_steps', while_steps, 'is_h_subbflown', is_h_subbflown);
                new_font_size = (parseFloat(a.style.fontSize.replace('px')) + 0.2);
                a.style.fontSize = (new_font_size + 'px');

                is_h_subbflown = get_is_h_subbflown();
                while_steps++;
            }

            // var is_w_overflown = get_is_w_overflown();
            // var while_steps = 0;

            // while (is_w_overflown && while_steps < 1000 && new_font_size > MIN_FONT_SIZE) {
            //     console.log('while_steps', while_steps, 'is_w_overflown', is_w_overflown);
            //     new_font_size = (parseFloat(a.style.fontSize.replace('px')) - 0.2);
            //     a.style.fontSize = (new_font_size + 'px');
            //     is_w_overflown = get_is_w_overflown();
            //     while_steps++;
            // }

            // console.log('Done in ' + while_steps + ' steps! New font size is: ' + new_font_size);

            if (new_font_size) {
                this.template.fontSize = new_font_size;
            }
        },

        onAddLogo: function () {
            if (this.is_free) {
                this.showUpgrade('logo');
                return false;
            }

            this.$refs.upload_logo_image_input.click();

        },

        onRemoveWatermark: function () {
            this.showUpgrade('watermark');
        },

        showUpgrade: function (feature) {
            if (this.user && !this.user.is_free) {
                return false;
            }

            mixpanel.track("ShowedUpgrade", {
                feature: feature
            });

            $('#UpgradeModal').modal({
                backdrop: 'static',
                keyboard: false
            });

            this.saveCurrentWorkSession(feature);
        },

        onLogoImageSelected: function (event) {
            var _this = this;
            if (event.target.files.length) {
                // this.design.logo = URL.createObjectURL(event.target.files[0]);
                var reader = new FileReader();
                reader.onload = function (e) {
                    // _this.template.logo_image = e.target.result;
                    Vue.set(_this.template, 'logoImage', e.target.result);
                    // mixpanel.track("AS_WheelUserAction", {'action': 'Uploaded_Logo'});

                    _this.resizeDownDataUrlKeepProportions(e.target.result, 300, 300, function (data) {
                        // _this.template.logoImage = data;
                        Vue.set(_this.template, 'logoImage', data);
                    });
                }
                // reader.onerror = e => rej(e);
                reader.readAsDataURL(event.target.files[0]);
            }
        },

        resizeDownDataUrlKeepProportions: function(datas, maxWidth, maxHeight, callback, quality) {
            // https://jsfiddle.net/Doskii/gfeq70rt/50/
            quality = quality || 0.9;

            var img = document.createElement('img');

            img.addEventListener("load", function() {
                var canvas = document.createElement('canvas');
                var ctx = canvas.getContext('2d');

                if (img.width > maxWidth || img.height > maxHeight) {
                    if (img.width == img.height) {
                        canvas.width = (maxHeight >= maxWidth ? maxWidth : maxHeight);
                        canvas.height = (maxHeight >= maxWidth ? maxWidth : maxHeight);
                    } else if (img.width > img.height) {
                        canvas.width = maxWidth;
                        canvas.height = (maxWidth / img.width) * img.height;
                    } else if (img.height > img.width) {
                        canvas.height = maxHeight;
                        canvas.width = (maxHeight / img.height) * img.width;
                    }
                } else {
                    return false;
                }

                ctx.drawImage(this, 0, 0, canvas.width, canvas.height);

                var image_type = 'image/jpeg';

                if (/^data:image\/png[,;]/.test(datas) ) {
                  image_type = 'image/png';
                }
                var dataURI = canvas.toDataURL(image_type, quality);
                callback && callback(dataURI);
            });

            img.src = datas;
        },

        goToCheckout: function () {
            if (this.user && this.user.id) {
                window.location.href = "/subscriptions/pay/pro";
            } else {
                $('#UpgradeModal').modal('hide');
                $('#LoginModal').modal();
                mixpanel.track("ViewLoginModal");
            }
        },

        restoreWorkSession: function () {
            var session_data = localStorage.getItem('quoootes::design');
            var _this = this;

            if (session_data) {
                var data = JSON.parse(session_data);
                this.text = data.text;
                this.text_footer = data.text_footer;
                this.text_header = data.text_header;
                this.template = data.template;

                if (data.upgrade_showed) {
                    if (window.location.search && window.location.search.indexOf('payment_cancel') === -1 && window.location.search.indexOf('payment_success') === -1) {
                        setTimeout(function () {
                            _this.showUpgrade(data.upgrade_showed);
                        }, 1000);
                    }
                }

                localStorage.removeItem('quoootes::design');
            }
        },

        hasSavedSession: function () {
            var session_data = localStorage.getItem('quoootes::design');
            return (session_data) ? JSON.parse(session_data) : null;
        },

        saveCurrentWorkSession: function (upgrade_showed) {
            var work_data = {
                text: this.text,
                text_footer: this.text_footer,
                text_header: this.text_header,
                template: this.template,
                upgrade_showed: upgrade_showed || false,
            };

            localStorage.setItem('quoootes::design', JSON.stringify(work_data));
        },

        closeModal: function (modal_id) {
            $('#' + modal_id).modal('hide');
        }
    },
});

window.addEventListener('resize', window.app.onResize, true);
window.addEventListener('click', window.app.onClick, true);
window.addEventListener('keyup', window.app.onKeyPress, true);

$(function () {
    $('[data-toggle="popover"]').popover()
});

$(function () {
    $('[data-toggle="tooltip"]').tooltip()
});

setInterval(function () {
    $('[data-toggle="tooltip"]').tooltip()
}, 1000);


window.MoveableInstance = null;
window.MoveableDragging = null;

initMovable = function() {
    var parent = $('.studio__canvas')[0].getBoundingClientRect();
    var bounds = {
        left: parent.left,
        right: parent.left + parent.width,
        top: parent.top,
        bottom: parent.top + parent.height,
    };

    const moveable = new Moveable(document.body, {
        target: document.getElementById("StudioCanvasTextResizer"),
        container: document.querySelector(".studio__canvas")[0],
        draggable: true,
        resizable: true,
        snappable: true,
        zoom: 1,
        renderDirections: ["e", "w"],
        renderDirections: ["e", "se"],
        origin: false,
        keepRatio: false,
        bounds: bounds
    });

    /* draggable */
    moveable.on("dragStart", ({}) => {
        window.MoveableDragging = true;
    });

    moveable.on("drag", ({target,transform,left,top}) => {
        target.style.left = `${left}px`;
        target.style.top = `${top}px`;
        $target = document.getElementById("StudioCanvasText");
        $target.style.left = left + 'px';
        $target.style.top = top + 'px';
        // console.log("onDrag translate", dist);
        // target!.style.transform = transform;
    });
    moveable.on("dragEnd", (e) => {
        window.MoveableDragging = false;
        // console.log(e);
        Vue.set(app.template, 'textboxTop', Math.max(0, e.lastEvent.top));
        Vue.set(app.template, 'textboxLeft', Math.max(0, e.lastEvent.left));
        // app.updateCanvasTextboxSizeAndPosition();
    });

    /* resizable */
    moveable.on("resizeStart", (e) => {
        // e.setMin([app.text_min_width, app.text_min_height]);
        // e.setMax([app.text_max_width, app.text_max_height]);
        e.setMin([100, 100]);
        window.MoveableDragging = true;
    });

    moveable.on("resize", ({target,width,height,dist,delta}) => {
        // console.log("onResize", width, height);
        $target = document.getElementById("StudioCanvasTextResizer");
        delta[0] && ($target.style.width = width + 'px');
        delta[1] && ($target.style.height = height + 'px');
    });
    moveable.on("resizeEnd", (e) => {
        // console.log("onResizeEnd", e);
        window.MoveableDragging = false;
        Vue.set(app.template, 'textboxWidth', e.lastEvent.width);
        Vue.set(app.template, 'textboxHeight', e.lastEvent.height);

        Vue.nextTick(() => {
            app.resizeText();
        });
    });

    window.MoveableInstance = moveable;
}