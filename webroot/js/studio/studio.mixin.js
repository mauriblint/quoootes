var StudioMixin = {
    created: function () {
        console.log('created Studio Mixin');
    },
    methods: {
        isCus: function () {
            return _sharedData.app.is_cus;
        },
        isFree: function () {
            return !this.isCus();
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
    }
};