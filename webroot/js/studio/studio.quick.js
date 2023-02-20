
Vue.use(VueLoading);

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
    mixins: [StudioMixin],
    data: {
        mode: 'create',
        page: 'start',
        onboarding_step: 1,
        sidebar_open: null,
        quotes: [],
        templates: [],
        backgrounds: [],
        user: null,
        tags: [],
        popular_tags: [],
        quotes_selected_tag: null,
        quotes_total_pages: 0,
        quotes_search_params: {
            tag: 49, // Motivational
            page: 1,
            count: 12,
        },
        tag_id: '',
        keywords: '',
        is_private: false,
        saved_session: false,
        is_free: true,

        scale_factor: 1,
        text: '',
        text_header: '',
        text_footer: '',
        show_textbox_area: false,
        error_tag: false,

        saving: false,
        loading: false,
        debug: false,


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
            textboxTop: 125,
            textboxLeft: 125,
        },

        rendered_image: null,
        rendered_image_width: 0,
        rendered_image_height: 0,

        vue_loader: null,
        canvas_width: 250,
        canvas_height: 250,
        initial_textbox_width: 250,
        initial_textbox_height: 250,

        // text_max_width: 0,
        // text_min_width: 0,
        // text_max_height: 0,
        // text_min_height: 0,

        show_advanced_options: false,
        is_automatic_layout: true,

        layouts: [
            { name: 'Square', key: 'square', width: 500, height: 500, textboxWidth: 290, textboxHeight: 290, text_min_width: 0.3, text_min_height: 0.3, text_max_width: 0.8, text_max_height: 0.8 },
            { name: 'Story', key: 'story', width: 383, height: 680, textboxWidth: 215, textboxHeight: 340, text_min_width: 0.4, text_min_height: 0.4, text_max_width: 0.7, text_max_height: 0.7   },
            { name: 'Landscape', key: 'landscape', width: 700, height: 400, textboxWidth: 470, textboxHeight: 170, text_min_width: 0.5, text_min_height: 0.35, text_max_width: 0.82, text_max_height: 0.55 },
            { name: 'Portrait', key: 'portrait', width: 450, height: 650, textboxWidth: 220, textboxHeight: 340, text_min_width: 0.4, text_min_height: 0.4, text_max_width: 0.7, text_max_height: 0.7   }
        ],

        show_wizard: false,
        step: 'wizard', // wizard | preview |
        saved_session: null,
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

        //  Load from URL
        if (window.__NEXT_DATA__ && window.__NEXT_DATA__.text) {
            this.text = window.__NEXT_DATA__.text;
            this.text_footer = window.__NEXT_DATA__.author;
        }

        this.saved_session = this.getSavedQuickEditorSession();

        // Saved session and no text, restore session
        if (this.saved_session && !this.text) {
            this.text = this.saved_session.text;
            this.text_footer = this.saved_session.author;
            if (window.location.pathname === '/quick-studio') {
                this.onTemplateSelected(this.saved_session.template_id);
            } else {
                this.setTemplate(window.__NEXT_DATA__.template, false);
            }
            // if (saved_session.template_id) {
            //     this.onTemplateSelected(saved_session.template_id);
            // } else {
            //     if (window.__NEXT_DATA__ && window.__NEXT_DATA__.template) {
            //         this.setTemplate(window.__NEXT_DATA__.template, true);
            //     }
            // }
            // this.onStart();
        } else {
            this.setTemplate(window.__NEXT_DATA__.template, false);
        }

        if (!this.text) {
            this.show_wizard = true;
        }

        // if (window.__NEXT_DATA__ && window.__NEXT_DATA__.backgrounds) {
        //     this.backgrounds = window.__NEXT_DATA__.backgrounds;
        // }

        if (window.__NEXT_DATA__ && window.__NEXT_DATA__.templates) {
            this.templates = window.__NEXT_DATA__.templates;
            this.popular_tags = window.__NEXT_DATA__.popular_tags;
            var templates_orders = ['landscape', 'square', 'story', 'portrait'];
            this.templates.sort(function (a,b) {
                var a_weight = templates_orders.indexOf(a.layout);
                var b_weight = templates_orders.indexOf(b.layout);
                return (a_weight - b_weight);
            });
        }

        // if (window.__NEXT_DATA__ && window.__NEXT_DATA__.mode) {
        //     this.mode = window.__NEXT_DATA__.mode;
        // }

        // if (window.__NEXT_DATA__ && window.__NEXT_DATA__.tags) {
        //     this.tags = window.__NEXT_DATA__.tags;
        // }

        if (window.__NEXT_DATA__ && window.__NEXT_DATA__.user) {
            this.user = window.__NEXT_DATA__.user;
            this.is_free = (this.user.is_free);
        }

        // setTimeout(this.onResize, 100);
        setTimeout(this.onFocusTextarea, 100);

        this.fonts = Fonts.getFontsArray();
        this.filters = FiltersData;

        if (window.location.search && window.location.search.indexOf('payment_success') > -1) {
            setTimeout(function () {
                $('#SuccessPaymentModal').modal();
            }, 1000);
        }

        mixpanel.track("ViewQuickStudio");
    },

    methods: {
        onTextChange: function (type) {
            this.resizeText();
            setTimeout(this.resizeText, 50);
            if (type === 'text_paste') {
                setTimeout(this.resizeText, 100);
            }
        },

        saveDesign: function () {
            $('#saveModal').modal();
            mixpanel.track("OpenedSaveDesign");
        },

        downloadDesign: function () {
            this.generate(this.onDesignGeneratedForDownload, 1);
            mixpanel.track("StudioDownloadedDesign");
        },

        onSaveDesign: function () {
            if (!this.tag_id) {
                this.error_tag = true;
                return;
            }
            this.saving = true;
            this.generate(this.onDesignGenerated, 1);
        },

        onDesignGeneratedForDownload: function (imageData, width, height, canvas) {
            canvas.toBlob(function(blob) {
                var hash = new Date().toJSON().replace('T', ' ').slice(0,19);
                window.saveAs(blob, 'quoootes.com_' + hash + '.jpg');
            });
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
                    is_private: this.is_private ? 1 : 0,
                    design_id: (__NEXT_DATA__.design && __NEXT_DATA__.design.id) ? __NEXT_DATA__.design.id : null
                }
             })
             .done(function (response) {
                mixpanel.track("SavedDesign");
                window.location.href = '/quote-' + response.data.id;
             })
        },

        // saveTemplate: function () {
        //     this.generate(this.onTemplatePreviewGenerated, 0.5);
        // },

        // onTemplatePreviewGenerated: function (imageData) {
        //     $.ajax({
        //         type: "POST",
        //         url: (this.template_id) ? '/templates/update' : '/templates/create',
        //         dataType: 'json',
        //         data: {
        //             styles: this.template,
        //             thumb: imageData,
        //             template_id: this.template_id,
        //             layout: this.template.layout
        //         }
        //      })
        //      .done(function (response) {
        //         // console.log(response);
        //         // return false;
        //         window.location.href = '/studio/' + response.data.hash + '?action=edit_template';
        //      })
        // },

        // createNewTemplate: function () {
        //     window.location.href = '/studio/?action=create_template';
        // },

        // onViewTemplates: function () {
        //     mixpanel.track('ViewStudioTemplates');
        //     this.fetchTemplates();
        //     this.page = 'templates';
        // },

        // fetchTemplates: function () {
        //     if (!this.templates.length) {
        //         $.ajax({
        //             type: "GET",
        //             url: '/templates/list',
        //             dataType: 'json',
        //         })
        //         .done(this.onTemplatesFetched);
        //     }
        // },

        // onTemplatesFetched: function (response) {
        //     this.templates = response.data;

        //     if (!this.template_id && this.mode !== 'create_template'  && this.mode !== 'edit_design' && !this.saved_session) {
        //         this.setRandomTemplate();
        //     }
        // },

        // onViewQuotes: function () {
        //     if (!this.quotes_selected_tag) {
        //         var tag = this.popular_tags.find(function (t) { return (t.id === 49) });
        //         this.onTagFilterChange(tag);
        //         this.page = 'quotes';
        //         return true;
        //     }
        //     this.fetchQuotes();
        //     this.page = 'quotes';
        //     mixpanel.track('ViewStudioQuotes');
        // },

        // fetchQuotes: function () {
        //     // if (!this.quotes.length) {
        //         this.loading_quotes = true;
        //         $.ajax({
        //             type: "GET",
        //             url: '/ajax/quotes',
        //             data: this.quotes_search_params,
        //             dataType: 'json',
        //         })
        //         .done(this.onQuotesFetched);
        //     // }
        // },

        // onQuotesFetched: function (response) {
        //     this.quotes = this.quotes.concat(response.data);
        //     this.loading_quotes = false;
        // },

        // onQuoteSelected: function (quote) {
        //     this.text = quote.content;
        //     if (quote.quotes_author && quote.quotes_author.name) {
        //         this.text_footer = quote.quotes_author.name;
        //         this.template.footerVisible = true;
        //     }
        //     this.page = 'editor';
        //     setTimeout(this.onTextChange, 100);
        // },

        // onTagFilterChange: function (tag) {
        //     this.quotes_selected_tag = tag;
        //     this.quotes_total_pages = Math.ceil(tag.quotes_count / 12);
        //     this.quotes_search_params.tag = tag.id;
        //     this.quotes = [];
        //     this.fetchQuotes();
        // },

        // onLoadMoreQuotes: function () {
        //     if (this.is_free) {
        //         this.showUpgrade('more_quotes');
        //         return false;
        //     }

        //     if (this.quotes_search_params.page < this.quotes_total_pages) {
        //         this.quotes_search_params.page++;
        //         this.fetchQuotes();
        //         mixpanel.track('StudioQuotesLoadNextPage', {page: this.quotes_search_params.page});
        //     }
        // },

        onTemplateSelected: function (template) {
            // if (template.is_premium) {
            //     return this.showUpgrade('premium_template');
            // }
            var template_id = (template.id) ? template.id : template;
            // TODO: HERE
            this.showLoader();
            this.loadTemplate(template_id);
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

        setTemplate: function (data, stop_render) {
            var _this = this;
            this.is_automatic_layout = true;
            var template_styles = (data.styles) ? data.styles : data;
            this.template = Object.assign({}, this.initial_template, this._parseTemplateJSON(template_styles));
            this.template_id = data.id;
            // this.template.logoImage = 'https://logodownload.org/wp-content/uploads/2014/09/facebook-logo-0.png';
            // this.template.logoImage = '/img/isologo.png';
            if (this.template.logoSize && this.template.logoSize !== 60) {
                this.template.logoSize = 60;
            }
            if (this.is_free) {
                this.template.logoImage = '';
            }

            // if (back_to_editor) {
            //     this.page = 'editor';
            // }

            if (!stop_render) {
                this.preRenderQuick();
            }
        },

        preRenderQuick: function () {
            if (!this.text) {
                return false;
            }
            this.showLoader();

            setTimeout(this.resizeText, 50);
            setTimeout(this.resizeText, 150);
            setTimeout(this.resizeText, 250);

            // TODO: HERE
            if(this.template.backgroundImage) {
                this.renderQuickImage(1000);
            } else {
                this.renderQuickImage(260);
            }
        },

        refreshPreview: function () {
            this.showLoader();
            this.renderQuickImage(1);
        },

        renderQuickImage: function (timeout) {
            _t = timeout || 1000;
            // TODO: HERE
            var _this = this;
            _this.rendering_image = true;
            setTimeout(function () {
                _this.generate(function (imageData, image_width, image_height) {
                    _this.rendered_image = imageData;
                    _this.rendered_image_width = image_width;
                    _this.rendered_image_height = image_height;
                    _this.rendering_image = false;
                    _this.hideLoader();
                    window.scrollTo(0,0)
                }, 2);
            }, _t);

            this.saveCurrentQuickSession();
        },

        setLayout: function (layout) {
            this.template.layout = layout.key;
            var layout = this._getLayout(layout.key);
            this.is_automatic_layout = true;
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

            if (!template.textboxWidth || !template.textboxLeft) {
                // this.resizeTextbox(template_layout);
                template.textboxWidth = template_layout.textboxWidth;
                template.textboxHeight = template_layout.textboxHeight;
                template.textboxLeft = ((template_layout.width - template_layout.textboxWidth) / 2);
                template.textboxTop = ((template_layout.height - template_layout.textboxHeight) / 2);
            }

            this.canvas_width = template_layout.width;
            this.canvas_height = template_layout.height;
            this.initial_textbox_width = template.textboxWidth;
            this.initial_textbox_height = template.textboxHeight;

            if (this.template.footerVisible) {
                template.footerVisible = true;
            }
            if (this.template.headerVisible) {
                template.headerVisible = true;
            }

            // this.resizeTextbox(template_layout);

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

        getFooter: function () {
            if (this.text_footer && this.template.footerVisible) {
                var ft = this.text_footer;
                var ft_start = '— ';
                var ft_end = ' —';
                ft_end = '';
                ft_start = '';
                var ft_fz = 16;

                if (this.template.textUppercase) {
                    ft = this.text_footer.toUpperCase();
                }

                if (ft.length > 14) { ft_fz = 14; }
                if (ft.length > 20) { ft_fz = 12; }
                return '<span style="font-size: ' + ft_fz + 'px;">' + ft_start + ft + ft_end + '</span>';
            }

            return '';
        },

        getEditorBackgroundStyles: function () {
            return {
                backgroundImage: this.template.backgroundImage && 'url(' + this.template.backgroundImage + ')',
                backgroundSize: this.template.backgroundSize,
                backgroundPosition: this.template.backgroundPosition
            };
        },

        getEditorStyles: function () {
            var dimensions = this.getLayoutDimensions(this.template.layout);
            return {
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

        getAuthorStyles: function () {
            var _fs = '70%';
            if (this.template.fontSize) {
                _fs = this.template.fontSize * 0.7;
                if (_fs < 15) {
                    _fs = this.template.fontSize;
                }
                if (_fs > 20) {
                    _fs = 20;
                }
            }

            return {
                marginTop: '26px',
                fontSize: _fs + 'px',
            }
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

            var dim = this.getTextBoxDimensions();
            boxWidth = dim.width;
            boxHeight = dim.height;
            boxTop = dim.top;
            boxLeft = dim.left;

            return {
                backgroundColor: (this.template.boxVisible) ? this.template.boxBackgroundColor : 'transparent',
                padding: (this.template.boxVisible) ? parseInt(this.template.boxPadding) + 'px' : '0px',
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
            // if (this.page !== 'editor') {
            //     return false;
            // }
            // this.destroyMoveable();
            html2canvas($('.quick-studio__canvas')[0], {
                useCORS: true,
                // proxy: '/ajax/proxy',
                scale: scaleFactor || window.devicePixelRatio,
                onclone: function (cloned) {
                    var a = cloned.getElementsByClassName('studio__canvas')[0];
                    cloned.querySelector(".quick-studio__templates").remove();
                    // if(a.style.backgroundImage) {
                    //     a.style.backgroundImage = a.style.backgroundImage.replace('")', '?version='+Date.now()+'")');
                    // }
                    if (a) {
                        a.style.boxShadow = 'none';
                        a.style.transform = 'none';
                        a.style.visibility = 'visible';
                        a.style.display = 'block';
                    }
                    // if (_this.mode === 'edit_template' || _this.mode === 'create_template') {
                    //     $watermark = cloned.getElementsByClassName('studio__watermark');
                    //     if ($watermark[0]) {
                    //         $watermark[0].remove();
                    //     }
                    // }
                 }
            }).then(function (canvas) {
				var imgData = canvas.toDataURL("image/jpeg", 1);
                // var img = document.createElement('img');
                // img.src = imgData;
                // document.body.appendChild(img);

                if (callback) {
                    callback(imgData, canvas.width, canvas.height, canvas);
                }
            });
        },

        onFocusTextarea: function (e) {
            if (this.$refs.textInput) {
                this.$refs.textInput.focus();
            }
        },

        getLayoutFullName: function (layout) {
            var Layout = this._getLayout(layout);

            return Layout.name + ' (' + Layout.width + 'x' + Layout.height + ')';
        },

        resizeText: function () {
            var _this = this;
            $('.jtextfill').textfill({ maxFontPixels: 170, callback: function (fs) {
                _this.template.fontSize = fs;
            } });
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

            get_modal('upgrade').show();
            // var UpgradeModal = new bootstrap.Modal(document.getElementById('UpgradeModal'));
            // UpgradeModal.show();
            mixpanel.track("ShowedUpgrade", {
                feature: feature,
                editor: 'quick-editor',
            });

            // $('#UpgradeModal').modal({
            //     backdrop: 'static',
            //     keyboard: false
            // });

            // this.saveCurrentWorkSession(feature);
        },

        onLogoImageSelected: function (event) {
            var _this = this;
            if (event.target.files.length) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    Vue.set(_this.template, 'logoImage', e.target.result);
                    _this.showLoader();
                    _this.renderQuickImage();

                    _this.resizeDownDataUrlKeepProportions(e.target.result, 300, 300, function (data) {
                        Vue.set(_this.template, 'logoImage', data);
                    });
                }
                // reader.onerror = e => rej(e);
                reader.readAsDataURL(event.target.files[0]);
            }
        },

        goToCheckout: function () {
            mixpanel.track("ClickedOnUpgrade");
            if (this.user && this.user.id) {
                window.location.href = "/subscriptions/pay/pro";
            } else {
                $('#UpgradeModal').modal('hide');
                get_modal('upgrade').hide();
                get_modal('login').show();
                set_cookie('redirect_to_checkout', 1, 1);
                mixpanel.track("ViewLoginModal");
            }
        },

        getSavedQuickEditorSession: function () {
                // return null;
                // return {
                //     'text': 'esto es una prueba',
                //     'author': 'Pituflo',
                //     'template_id': 5,
                // }
            var session_data = localStorage.getItem('quoootes::quick');
            return (session_data) ? JSON.parse(session_data) : null;
        },

        saveCurrentQuickSession: function (upgrade_showed) {
            var work_data = {
                text: this.text,
                author: this.text_footer,
                template_id: this.template_id,
            };

            localStorage.setItem('quoootes::quick', JSON.stringify(work_data));
        },

        closeModal: function (modal_id) {
            $('#' + modal_id).modal('hide');
        },

        onSelectFont: function (font) {
            if (this.is_free && font.premium) {
                return this.showUpgrade('premium_font');
            }
            this.template.fontFamily = font.name;
        },

        onSwitchPrivateDesign: function (event) {
            if (this.is_free) {
                event.preventDefault();
                $('#saveModal').modal('hide');
                this.showUpgrade('private_design');
                return false;
            }
        },

        onStart: function () {
            this.show_wizard = false;
            this.preRenderQuick();
        },

        onEdit: function () {
            this.show_wizard = true;
        },

        onSave: function () {
            if (this.saving) {
                return false;
            }

            this.showLoader();
            this.saving = true;

            $.ajax({
                type: "POST",
                url: '/ajax/save',
                dataType: 'json',
                xhrFields: {
                    withCredentials: true
                },
                data: {
                    template_id: this.template_id,
                    image: this.rendered_image,
                    width: this.rendered_image_width,
                    height: this.rendered_image_height,
                    text: this.text,
                    text_footer: this.text_footer,
                    text_header: '',
                }
            })
            .done(function (response) {
                mixpanel.track("SavedDesign", { 'editor': 'quick_editor' });
                localStorage.removeItem('quoootes::quick');

                if (response.data.shortcode) {
                    window.location.href = '/p/' + response.data.shortcode;
                } else {
                    window.location.href = '/quote-' + response.data.id;
                }
            })
        },

        showLoader: function () {
            if (!this.rendered_image) {
                return false;
            }
            if (!this.vue_loader || !this.vue_loader.isActive) {
                this.vue_loader = Vue.$loading.show({});
            }
        },

        hideLoader: function () {
            if (this.vue_loader && this.vue_loader.isActive) {
                this.vue_loader.hide();
            }
        },
    },
});