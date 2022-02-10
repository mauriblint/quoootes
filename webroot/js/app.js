Vue.use(EmojiPicker);

var bus = new Vue();

var app = new Vue({
    el: '#vue-app',

    data: function () {
        return {
            selectedTemplate: 0,
            layoutSettings: {},
            showModal: false,
            customFontSize: false,
            frase_id: null,
            frase_tag_id: null,
            quote: {
                text: 'Type your text. Click to move around.'
            },
            styles: {
                backgroundColor: '#F00',
                fontFamily: 'Rhodium Libre',
                backgroundImage: false,
                color: '#000',
                textAlign: 'center',
                fontSize: 20,
            },
        }
    },

    created: function () {
        var templatesCount = Object.keys(Template.layouts).length -1;
        var randomTemplate = Math.floor(Math.random() * templatesCount) + 1;
        this.selectTemplate(randomTemplate);
        // this.selectTemplate(38);

        if (App.frase) {
            this.quote.text = App.frase.text;
            this.frase_id = App.frase.id;
        }
    },

    mounted: function () {
        var _this = this;

        bus.$on('TEMPLATE_SELECTED', function (template) {
            _this.selectTemplate(template);
        });
    },

    computed: {
        charLeft: function () {
            var char = this.quote.text.length;
            var limit = 250;

            return (limit - char);
        },
        canvasStyles: function () {
            var styles = _.extend({}, this.styles);
            var fz = styles.fontSize;

            styles.fontSize =  fz + 'px';

            if (!this.customFontSize && this.layoutSettings.getFontSize) {
                styles.fontSize = this.layoutSettings.getFontSize(this.quote.text.length) + 'px';
            }

            return styles;
        },
        textHtml: function () {
            this.selectedTemplate;
            if (this.quote.text === '') {
                return 'Start typing..';
            }
            return Canvas.write(this.quote.text);
        },

        canvasClasses: function () {
            var classes = ['preset-' + this.selectedTemplate];

            if (this.layoutSettings.center) {
                classes.push('__center');
            }

            if (this.layoutSettings.frame) {
                classes.push('kanvas--' + this.layoutSettings.frame);
            }

            if (this.layoutSettings.align) {
                switch(this.layoutSettings.align) {
                    case 'bottom-right':
                        classes.push('__align_rb');
                        break;
                    case 'top-left':
                        classes.push('__align_top_left');
                        break;
                }
            }

            return classes.join(' ');
        },
    },
    methods: {
        unSelect: function () {
            $('#textarea').removeClass('active');
        },

        append(emoji) {
          this.quote.text += emoji
        },

        blurInput: function () {
            $('#textinput').focus();
            if (this.quote.text === 'Type your text. Click to move around.') {
                this.quote.text = '';
            }
        },

        onInputBlur: function () {
            if (this.quote.text === '') {
                this.quote.text = 'Type your text. Click to move around.';
            }
        },

        selectTemplate: function (id) {
            Template.current = 'layout_' + id;
            var layout_config = Template.get_config();
            this.selectedTemplate = id;
            this.layoutSettings = layout_config;
            this.customFontSize = false;

            if (layout_config.styles) {
                var fontSize = this.styles.fontSize;

                if (layout_config.getFontSize) {
                    fontSize = this.layoutSettings.getFontSize(this.quote.text.length);
                }

                if (!layout_config.styles.fontSize) {
                    fontSize = 25;
                }

                var finalStyles = _.extend(
                    { backgroundImage: '', },
                    layout_config.styles,
                    { fontSize: fontSize }
                );

                this.styles = finalStyles;
            } else {
                this.styles = {};
            }

            $("#textarea").attr('style', 'position: relative;');

            setTimeout(function () {
                $("#textarea").resizable({
                    handles: {
                        e: '.resize-handle._r',
                    },
                    handles: 'e, w',
                    resize: function( event, ui ) {
                        ui.size.height = 'auto';
                    },
                    stop: function( event, ui ) {
                        ui.size.height = 'auto';
                    },
                    start: function( event, ui ) {
                        ui.size.height = 'auto';
                    }
                });
                var zoomF = $('#textarea').css('zoom');
                $("#textarea").draggable({
                    containment:"parent",
                    drag: function(evt,ui) {
                         var factor = (1 / zoomF) - 1
                         ui.position.top += Math.round((ui.position.top - ui.originalPosition.top) * factor)
                         ui.position.left += Math.round((ui.position.left - ui.originalPosition.left) * factor)
                    },
                    stop: function (event, ui) {
                        event.stopPropagation();
                    }
                });

                $(document).mouseup(function(e) {
                    var container = $("#textarea");
                    if (!container.is(e.target)) {
                        container.removeClass('active');
                        $("#textarea").draggable({
                            containment:"parent",
                        });

                    }
                });

                // $('#textarea').on('click', function (e) {
                //     // $(this).draggable("destroy");
                //     // $(this).attr('contenteditable', true);
                //     // this.focus();
                // }).on('blur',function(){
                //     LayerFocusOut();
                //     // $(this).draggable();
                //     // $(this).attr('contenteditable', false);
                // });
                var LayerFocusOut = function () {
                    $('.layer').removeClass('active');
                    $("#textarea").draggable({
                        containment:"parent",
                    });
                };

                $('body').on('click', '#textarea', function (event) {
                    $component = $(event.currentTarget);
                    $component.addClass('active');
                    if ($("#textarea").data('ui-draggable')) {
                        $("#textarea").draggable("destroy");
                    }
                });


            }, 1000)
        },
    }
});
