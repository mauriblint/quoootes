var Fonts = {};

Fonts.insertFonts = function () {
    var FontList = this.getFontList();

    $("head").append("<link href='https://fonts.googleapis.com/css?family=" + FontList + "' rel='stylesheet' type='text/css'>");
};

Fonts.getFontList = function () {
    var list = [];

    $.each(FontsData, function (index, font) {
        var name = font.name.split(' ').join('+');
        var weight = (font.weight) ? ':' + font.weight : '';
        var r = name + weight;

        list.push(r);
    });

    return list.join('|');
};

Fonts.insertFonts();