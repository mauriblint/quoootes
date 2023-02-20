(function($) {
    $.fn.textfill = function(options) {
        var fontSize = options.maxFontPixels;
        var ourText = $('.fillthis:visible:first', this);
        var maxHeight = $(this).height();
        var maxWidth = $(this).width();
        var textHeight;
        var textWidth;
        do {
            ourText.css('font-size', fontSize);
            textHeight = ourText.height();
            textWidth = ourText.width();
            fontSize = fontSize - 0.5;
        } while ((textHeight > maxHeight || textWidth > maxWidth) && fontSize > 3);

        if (options.callback) {
            options.callback(fontSize);
        }
        return this;
    }
})(jQuery);