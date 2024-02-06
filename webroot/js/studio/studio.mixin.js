var StudioMixin = {
    created: function () {
        console.log('created Studio Mixin');
    },
    data: {
        layouts: [
            { name: 'Square', key: 'square', width: 500, height: 500, textboxWidth: 290, textboxHeight: 290, text_min_width: 0.3, text_min_height: 0.3, text_max_width: 0.8, text_max_height: 0.8 },
            { name: 'Story', key: 'story', width: 383, height: 680, textboxWidth: 215, textboxHeight: 340, text_min_width: 0.4, text_min_height: 0.4, text_max_width: 0.7, text_max_height: 0.7   },
            { name: 'Landscape', key: 'landscape', width: 700, height: 400, textboxWidth: 470, textboxHeight: 170, text_min_width: 0.5, text_min_height: 0.35, text_max_width: 0.82, text_max_height: 0.55 },
            { name: 'Portrait', key: 'portrait', width: 450, height: 650, textboxWidth: 220, textboxHeight: 340, text_min_width: 0.4, text_min_height: 0.4, text_max_width: 0.7, text_max_height: 0.7   },
            { name: 'Cover', key: 'cover', width: 820, height: 312, textboxWidth: 570, textboxHeight: 180, text_min_width: 0.4, text_min_height: 0.4, text_max_width: 0.7, text_max_height: 0.7   },
            { name: 'Reel', key: 'reel', width: 383, height: 680, textboxWidth: 215, textboxHeight: 340, text_min_width: 0.4, text_min_height: 0.4, text_max_width: 0.7, text_max_height: 0.7   },
        ],

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
            filterType: 'background',
            filterOpacity: 0.65,

            logoImage: '', // https://quoootes.com/img/isologo_color.png
            logoSize: 38,
            logoBorderRadius: 8,
            logoOpacity: 90,
            logoMargin: 12,

            textboxWidth: 250,
            textboxHeight: 250,
            textboxTop: 125,
            textboxLeft: 125,

            borderType: '',
            borderWidth: 6,
            borderMargin: 10,
            borderColor: 'white',
        },
    },
    methods: {
        isCus: function () {
            return _sharedData.app.is_cus;
        },
        isFree: function () {
            return !this.isCus();
        },

        generate: function (callback, scaleFactor, to_blob) {
            var $StudioCanvas = $('#StudioCanvas');
            var scale = scaleFactor || 1.5;
            var canvas_width = $StudioCanvas.width() * scale;
            var canvas_height = $StudioCanvas.height() * scale;

            var DomToImage = domtoimage.toJpeg;
            if (to_blob) {
                DomToImage = domtoimage.toBlob;
            }
            DomToImage($StudioCanvas[0], {
                quality: 0.98,
                width: canvas_width,
                height: canvas_height,
                style: {
                    transform: 'scale('+scale+')',
                    transformOrigin: 'top left',
                }
            })
            .then(function (dataUrl, a, b, c) {
                if (callback) {
                    callback(dataUrl, canvas_width, canvas_height);
                }
                // var img = new Image();
                // img.src = dataUrl;
                // document.body.appendChild(img);
            })
            .catch(function (error) {
                console.error('oops, something went wrong!', error);
            });
        },

        onDesignGeneratedForDownload: function (blob) {
            var hash = new Date().toJSON().replace('T', ' ').slice(0,19);
            window.saveAs(blob, 'quoootes.com_' + hash + '.jpg');
        },

        getTextBoxDimensions: function () {
            debugger;
            var box_scale_factor = 1;

            if (this.text.length < 20) { box_scale_factor = 0.75; }
            else if (this.text.length < 40) { box_scale_factor = 0.8; }
            else if (this.text.length < 50) { box_scale_factor = 0.85; }
            else if (this.text.length < 100) { box_scale_factor = 0.85; }
            else if (this.text.length < 200) { box_scale_factor = 1; }
            else if (this.text.length < 300) { box_scale_factor = 1.1; }
            else if (this.text.length < 400) { box_scale_factor = 1.2; }
            else { box_scale_factor = 1.3; };

            console.log('box_scale_factor', box_scale_factor);

            var currentTextboxWidth = this.template.textboxWidth;
            var currentTextboxHeight= this.template.textboxHeight;
            console.log('currentTextboxWidth', this.template.textboxWidth);
            var newTextboxWidth = (this.initial_textbox_width*box_scale_factor);
            var newTextboxHeight = (this.initial_textbox_height*box_scale_factor);
            var diffTextboxWidth = (currentTextboxWidth - newTextboxWidth) / 2;
            var diffTextboxHeight = (currentTextboxHeight - newTextboxHeight) / 2;
            var newTextboxLeft = this.template.textboxLeft + diffTextboxWidth;
            var newTextboxTop = this.template.textboxTop + diffTextboxHeight;

            if (newTextboxLeft < 0) { newTextboxLeft = 0; }

            boxWidth = newTextboxWidth;
            boxHeight = newTextboxHeight;
            boxLeft = newTextboxLeft;
            boxTop = newTextboxTop;

            return {
                width: newTextboxWidth,
                height: newTextboxHeight,
                left: newTextboxLeft,
                top: newTextboxTop,
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

        getBorderStyles: function () {
            var border = this.template.borderWidth;
            var margin = this.template.borderMargin;
            var border_type = this.template.borderType;

            if (!this.template.borderType) {
                return {};
            }

            return {
                border: border + 'px ' + border_type + ' ' + this.template.borderColor,
                width: 'calc(100% - ' + (margin*2) + 'px)',
                height: 'calc(100% - ' + (margin*2) + 'px)',
                margin: (margin) + 'px',
            };
        },

        getLogoStyles: function () {
            var pos = parseInt(this.template.watermarkPosition);
            var offset = parseInt(this.template.logoMargin);

            if (this.template.borderType) {
                offset += parseInt(this.template.borderMargin) + parseInt(this.template.borderWidth);
            }

            var styles = {
                width: this.template.logoSize + 'px',
                borderRadius: this.template.logoBorderRadius + '%',
                opacity: (parseInt(this.template.logoOpacity)/100),
            };

            if (pos === 1) { styles.top = offset + 'px'; styles.left =  offset + 'px' };
            if (pos === 2) { styles.top = offset + 'px'; styles.right =  offset + 'px' };
            if (pos === 3) { styles.bottom = offset + 'px'; styles.left =  offset + 'px' };
            if (pos === 4) { styles.bottom = offset + 'px'; styles.right =  offset + 'px' };
            if (pos === 5) { styles.top = offset + 'px'; styles.left =  '50%'; styles.transform =  'translateX(-50%)' };
            if (pos === 6) { styles.bottom = offset + 'px'; styles.left =  '50%', styles.transform = 'translateX(-50%)' };

            return styles;
        },

        getBackgroundStyles: function () {
            var styles = {
                backgroundImage: this.template.backgroundImage && 'url(' + this.template.backgroundImage + ')',
                backgroundSize: this.template.backgroundSize,
                backgroundPosition: this.template.backgroundPosition,
                backgroundColor: this.template.backgroundColor,
                // filter: 'grayscale(1) blur(10px)',
            };

            if (this.template.filterType === 'filter') {
                styles.filter = this.template.filter;
            }

            if (this.template.filterType === 'filter') {
                if (this.template.filter.indexOf('blur') > -1) {
                    styles.width = '110%';
                    styles.height = '110%';
                    styles.top = '-5%';
                    styles.left = '-5%';
                }
            }

            return styles;
        },

        getFilterStyles: function () {
            if (this.template.filterType === 'background') {
                return {
                    background: this.template.filter,
                    opacity: this.template.filterOpacity,
                }
            }

            return {};
        },

        getWatermarkStyles: function () {
            var pos = this.template.watermarkPosition;
            var offset = 12;
            if (this.template.borderType) {
                offset = 8;
                offset += parseInt(this.template.borderMargin) + parseInt(this.template.borderWidth);
            }

            if (pos === 1) return { top: offset + 'px', left: offset + 'px' };
            if (pos === 2) return { top: offset + 'px', right: offset + 'px' };
            if (pos === 3) return { bottom: offset + 'px', left: offset + 'px' };
            if (pos === 4) return { bottom: offset + 'px', right: offset + 'px' };
            if (pos === 5) return { top: offset + 'px', left: '50%', transform: 'translateX(-50%)' };
            if (pos === 6) return { bottom: offset + 'px', left: '50%', transform: 'translateX(-50%)' };
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
    }
};