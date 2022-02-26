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
        tags: [],
        tag_id: '',
        keywords: '',

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
            boxWidth: 65,
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
        },

        layouts: [
            { name: 'Square', key: 'square', width: 500, height: 500 },
            { name: 'Story', key: 'story', width: 383, height: 680 },
            { name: 'Landscape', key: 'landscape', width: 700, height: 400 },
            { name: 'Portrait', key: 'portrait', width: 450, height: 650 }
        ]
    },

    created: function () {
        // this.showEnterText
        this.fetchTemplates();

        this.template = Object.assign({}, this.initial_template);

        if (window.__NEXT_DATA__ && window.__NEXT_DATA__.text) {
            this.text = window.__NEXT_DATA__.text;
        }

        if (window.__NEXT_DATA__ && window.__NEXT_DATA__.backgrounds) {
            this.backgrounds = window.__NEXT_DATA__.backgrounds;
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
            this.tag_id = __NEXT_DATA__.design.tag.id;
            if (__NEXT_DATA__.design && __NEXT_DATA__.design.template) {
                this.setTemplate(__NEXT_DATA__.design.template, true);
            }
        }

        setTimeout(this.onResize, 100);
        setTimeout(this.onFocusTextarea, 100);

        this.fonts = Fonts.getFontsArray();
        this.filters = FiltersData;
    },

    methods: {

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

            if (!this.template_id && this.mode !== 'create_template'  && this.mode !== 'edit_design') {
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

            if (back_to_editor) {
                this.page = 'editor';
            }
        },


        setLayout: function (layout) {
            this.template.layout = layout.key;
            $('.studio__canvas__text').css('top', '').css('left', '');
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
                if (this.template.textUppercase) {
                    return this.text_footer.toUpperCase();
                }
                return this.text_footer;
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

        getTextStyles: function () {
            return {
                backgroundColor: (this.template.boxVisible) ? this.template.boxBackgroundColor : 'transparent',
                padding: (this.template.boxVisible) ? parseInt(this.template.boxPadding) + 'px' : '20px',
                borderRadius: parseInt(this.template.boxBorderRadius) + 'px',
                textAlign: this.template.textAlign,
                width: parseInt(this.template.boxWidth) + '%',
                textAlign: this.template.textAlign,
                lineHeight: this.template.lineHeight,
                fontFamily: this.template.fontFamily,
                fontSize: parseInt(this.template.fontSize) + 'px',
                letterSpacing: parseFloat(this.template.letterSpacing) + 'px',
                marginTop: (this.template.textOffset) ? parseInt(this.template.textOffset) + 'px' : '',
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

            return {
                width: find.width,
                height: find.height,
            }
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
                        cloned.getElementsByClassName('studio__watermark')[0].remove();
                    }
                 }
            }).then(function (canvas) {
				var imgData = canvas.toDataURL("image/jpeg", 0.9);
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
                e.target.className.indexOf('studio__canvas__text') === -1
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
    console.log(bounds);
    const moveable = new Moveable(document.body, {
        target: document.querySelector(".studio__canvas__text"),
        // If the container is null, the position is fixed. (default: parentElement(document.body))
        container: document.querySelector(".studio__canvas")[0],
        draggable: true,
        rotatable: true,
        resizable: true,
        scalable: false,
        warpable: false,
        snappable: true,
        renderDirections: ["e", "w"],
        // Enabling pinchable lets you use events that
        // can be used in draggable, resizable, scalable, and rotateable.
        pinchable: true, // ["resizable", "scalable", "rotatable"]
        origin: true,
        keepRatio: true,
        // Resize, Scale Events at edges.
        edge: false,
        throttleDrag: 0,
        throttleResize: 0,
        throttleScale: 0,
        throttleRotate: 0,
        bounds: bounds
    });

    /* draggable */
    moveable.on("dragStart", ({
        target,
        clientX,
        clientY
    }) => {
        // console.log("onDragStart", target);
        window.MoveableDragging = true;
    }).on("drag", ({
        target,
        transform,
        left,
        top,
        right,
        bottom,
        beforeDelta,
        beforeDist,
        delta,
        dist,
        clientX,
        clientY,
    }) => {
        // console.log("onDrag left, top", left, top);
        target.style.left = `${left}px`;
        target.style.top = `${top}px`;
        // console.log("onDrag translate", dist);
        // target!.style.transform = transform;
    }).on("dragEnd", ({
        target,
        isDrag,
        clientX,
        clientY
    }) => {
        // console.log("onDragEnd", target, isDrag);
        window.MoveableDragging = false;
    });

    /* resizable */
    moveable.on("resizeStart", ({
        target,
        clientX,
        clientY
    }) => {
        console.log("onResizeStart", target);
        window.MoveableDragging = true;

    }).on("resize", ({
        target,
        width,
        height,
        dist,
        delta,
        clientX,
        clientY
    }) => {
        console.log("onResize", width, height);
        let scale = (width * 100) / target.offsetWidth;
        scale = target.offsetWidth / width;
        // console.log("onResize", scale, target.offsetWidth, target.offsetHeight);
        delta[0] && (target.style.width = `${width}px`);
        // delta[1] && (target.style.height = `${height}px`);
        // target.style.transform = 'scale(' + scale + ')';
    }).on("resizeEnd", ({
        target,
        isDrag,
        clientX,
        clientY
    }) => {
        // console.log("onResizeEnd", target, isDrag);
        window.MoveableDragging = false;
    });

    /* rotatable */
    moveable.on("rotateStart", ({
        target,
        clientX,
        clientY
    }) => {
        // console.log("onRotateStart", target);
    }).on("rotate", ({
        target,
        beforeDelta,
        delta,
        dist,
        transform,
        clientX,
        clientY
    }) => {
        // console.log("onRotate", dist);
        target.style.transform = transform;
    }).on("rotateEnd", ({
        target,
        isDrag,
        clientX,
        clientY
    }) => {
        // console.log("onRotateEnd", target, isDrag);
    });

    window.MoveableInstance = moveable;
}