var Frames = {};
Frames.SQUARE = 'square';
Frames.LANDSCAPE = 'landscape';
Frames.PORTRAIT = 'portrait';

var Aligns = {};
Aligns.CENTER_BOTTOM = 'align_cb';

var TextAlign = {}
TextAlign.CENTER = 'center';
TextAlign.LEFT = 'left';
TextAlign.RIGHT = 'right';

var layout_default =  {
	text_type: 'default',
	lineMaxChars: 15,
	offset: 0,
	center: 1,
	frame: Frames.SQUARE,
	classes: [
		'__center',
	]
};

getDefaultFontSize = function (n) {
    if (n <= 25) { return 58; }
    if (n <= 60) { return 48; }
    if (n <= 85) { return 44; }
    if (n <= 110) { return 40; }
    if (n <= 140) { return 38; }
    if (n <= 180) { return 36; }
    if (n <= 230) { return 30; }
    return 26;
};

// .kanvas .text.text-xl { font-size: 65px; }
// .kanvas .text.text-lg { font-size: 50px; }
// .kanvas .text.text-md { font-size: 35px; }
// .kanvas .text.text-sm { font-size: 25px; }
// .kanvas .text.text-xs { font-size: 20px; }



var Template = {

	get_config: function() {
		return ( Template.layouts.hasOwnProperty(Template.current) ) ? Template.layouts[Template.current] : layout_default;
	},

	layouts: {
		layout_0: {
			text_type: 'default',
			align: 'bottom-right',
			frame: Frames.PORTRAIT,
            styles: {
                fontSize: 20,
                color: '#94627e',
                backgroundImage: 'url(/generator/layouts/quote__00.jpg)',
                textAlign: TextAlign.RIGHT,
                fontFamily: 'Boogaloo',
            },
            getFontSize: function (n) { return getDefaultFontSize(n) * 1; }
		},
		layout_1: {
			text_type: 'default',
			center: 1,
			frame: Frames.SQUARE,
            getFontSize: function (n) { return getDefaultFontSize(n) * 0.9; },
            styles: {
                fontSize: 20,
                color: '#1a1a1a',
                backgroundColor: '#E4AFB4',
                textAlign: TextAlign.CENTER,
                fontFamily: 'Work Sans',
            }
		},
		layout_2: {
			text_type: 'default',
			center: 1,
			frame: Frames.SQUARE,
            styles: {
                fontSize: 25,
                color: '#ff156c',
                backgroundImage: 'url(/generator/layouts/quote__02.jpg)',
                textAlign: TextAlign.CENTER,
                fontFamily: 'Lobster',
            },
            getFontSize: function (n) { return getDefaultFontSize(n) * 1; }
		},
		layout_3: {
			text_type: 'default',
			center: 1,
            frame: Frames.SQUARE,
            getFontSize: function (n) { return getDefaultFontSize(n) * 0.7; },
            styles: {
                fontSize: 25,
                color: '#FFF',
                backgroundImage: 'url(/assets/backgrounds/35de2df4d950d9e2d92417509cc783af.jpg)',
                textAlign: TextAlign.CENTER,
                fontFamily: 'Luckiest Guy',
            }
		},
		layout_4: {
			text_type: 'lines',
			lineMaxChars: 20,
            center: 1,
			lineMaxCharsByTextSize: function (charCount) {
	            if (charCount <= 25) { return '15'; }
	            if (charCount <= 60) { return '19'; }
	            if (charCount <= 110) { return '20'; }
	            if (charCount <= 180) { return '30'; }

	            return '36';
			},
			frame: Frames.SQUARE,
            // getFontSize: function (n) { return getDefaultFontSize(n) * 1; },
            styles: {
                fontSize: 25,
                color: '#FFF',
                backgroundColor: '#DEC6D4',
                fontFamily: 'Cutive Mono',
                backgroundImage: '',
            }
		},
		layout_5: {
			text_type: 'default',
			lineMaxChars: 15,
			offset: 0,
			center: 1,
            getFontSize: function (n) { return getDefaultFontSize(n) * 1; },
            styles: {
                fontSize: 25,
                color: '#FAFAFA',
                backgroundColor: '#F00',
                backgroundImage: 'url(/generator/layouts/quote__05.jpg)',
                textAlign: TextAlign.CENTER,
                fontFamily: 'Bree Serif',
            }
		},
		layout_6: {
			text_type: 'default',
			center: 1,
			frame: Frames.SQUARE,
            getFontSize: function (n) { return getDefaultFontSize(n) * 0.6; },
            styles: {
                fontSize: 25,
                color: '#FAFAFA',
                backgroundColor: '#F00',
                backgroundImage: 'url(/generator/layouts/quote__06.jpg)',
                textAlign: TextAlign.CENTER,
                fontFamily: 'Bree Serif',
            }
		},
		layout_7: {
            frame: Frames.SQUARE,
			text_type: 'default',
			lineMaxChars: 15,
			offset: 0,
			center: 1,
            getFontSize: function (n) { return getDefaultFontSize(n) * 0.6; },
            styles: {
                fontSize: 25,
                color: '#333A',
                backgroundColor: '',
                backgroundImage: 'url(/generator/layouts/quote__07.jpg)',
                textAlign: TextAlign.CENTER,
                fontFamily: 'Didact Gothic',
            }
		},
		layout_8: {
            frame: Frames.SQUARE,
			text_type: 'default',
			center:1,
            getFontSize: function (n) { return getDefaultFontSize(n) * 0.6; },
            styles: {
                fontSize: 25,
                color: '#FAFAFA',
                backgroundColor: '',
                backgroundImage: 'url(/generator/layouts/quote__08.jpg)',
                textAlign: TextAlign.CENTER,
                fontFamily: 'Chelsea Market',
            }
		},
		layout_9: {
            frame: Frames.SQUARE,
			text_type: 'default',
			lineMaxChars: 15,
			offset: 0,
			center:1,
            getFontSize: function (n) { return getDefaultFontSize(n) * 0.6; },
            styles: {
                fontSize: 25,
                color: '#EEE',
                backgroundColor: '',
                backgroundImage: 'url(/generator/layouts/800x800_cian_birds.jpg)',
                textAlign: TextAlign.CENTER,
                fontFamily: 'Coming Soon',
            }
		},
		layout_10: {
			text_type: 'default',
            frame: Frames.PORTRAIT,
			center: 1,
            getFontSize: function (n) { return getDefaultFontSize(n) * 0.9; },
            styles: {
                fontSize: 25,
                color: '#FFF',
                backgroundColor: '#bffb46',
                backgroundImage: 'url(/assets/backgrounds/bdebd55756dc1027f6b2852d22990f7d.jpg)',
                textAlign: TextAlign.CENTER,
                fontFamily: 'NexaRust',
            }
		},
		layout_11: {
            frame: Frames.PORTRAIT,
			text_type: 'default',
			center: 1,
            getFontSize: function (n) { return getDefaultFontSize(n) * 0.6; },
            styles: {
                fontSize: 25,
                color: '#DD5578',
                backgroundColor: '#DFE7EA',
                backgroundImage: '',
                textAlign: TextAlign.CENTER,
                fontFamily: 'Special Elite',
            }
		},
		layout_12: {
            frame: Frames.PORTRAIT,
			text_type: 'default',
			center:1,
            getFontSize: function (n) { return getDefaultFontSize(n) * 0.6; },
            styles: {
                fontSize: 25,
                color: '#FFF',
                backgroundColor: '#73D1CB',
                backgroundImage: 'url(/generator/layouts/quote__12_v2.jpg)',
                textAlign: TextAlign.CENTER,
                fontFamily: 'PoetsenOne',
            }
		},
		layout_13: {
			text_type: 'default',
			lineMaxChars: 20,
			center: 1,
			frame: Frames.SQUARE,
            getFontSize: function (n) { return getDefaultFontSize(n) * 0.6; },
            styles: {
                fontSize: 25,
                color: '#e6f986',
                backgroundColor: '#894caf',
                backgroundImage: '',
                textAlign: TextAlign.CENTER,
                fontFamily: 'Lato',
            }
		},
		layout_14: {
            frame: Frames.SQUARE,
			text_type: 'default',
			center: 1,
            getFontSize: function (n) { return getDefaultFontSize(n) * 0.6; },
            styles: {
                fontSize: 25,
                color: '#333',
                backgroundColor: '',
                backgroundImage: 'url(/generator/layouts/650x400_liteblue_gradient.jpg)',
                textAlign: TextAlign.CENTER,
                fontFamily: 'Montserrat',
            }
		},
		layout_15: {
			text_type: 'default',
			lineMaxChars: 15,
			offset: 0,
			center: 1,
            getFontSize: function (n) { return getDefaultFontSize(n) * 0.8; },
            styles: {
                fontSize: 25,
                color: '#1A1A1A',
                backgroundColor: '',
                backgroundImage: 'url(/generator/layouts/500x650_grey_gradient.jpg)',
                textAlign: TextAlign.CENTER,
                fontFamily: 'Itim',
            }
		},
		layout_16: {
			text_type: 'default',
			frame: Frames.PORTRAIT,
			align: 'top-left',
            getFontSize: function (n) { return getDefaultFontSize(n) * 0.8; },
            styles: {
                fontSize: 25,
                color: '#cada8f',
                backgroundColor: '#3e4852',
                backgroundImage: '',
                textAlign: TextAlign.LEFT,
                fontFamily: 'Indie Flower',
            }
		},
		layout_17: {
			text_type: 'lines',
			lineMaxChars: 18,
			offset: 0,
			center: 1,
			lineMaxCharsByTextSize: function (charCount) {
	            if (charCount <= 25) { return '15'; }
	            if (charCount <= 60) { return '19'; }
	            if (charCount <= 110) { return '20'; }
	            if (charCount <= 180) { return '25'; }

	            return '30';
			},
            getFontSize: function (n) { return getDefaultFontSize(n) * 0.5; },
            styles: {
                fontSize: 25,
                color: '#FFF',
                backgroundColor: '#3e4852',
                backgroundImage: 'url(/generator/layouts/quote__17.jpg)',
                fontFamily: 'Rhodium Libre',
            }
		},
		layout_18: {
			text_type: 'words',
			lineMaxChars: 15,
			offset: 0,
			center: 1,
			frame: Frames.PORTRAIT,
            getFontSize: function (n) { return getDefaultFontSize(n) * 0.5; },
            styles: {
                fontSize: 25,
                color: '#FFF',
                backgroundColor: '#1a1a1a',
                backgroundImage: '',
                fontFamily: 'Rhodium Libre',
            }
		},
		layout_19: {
			text_type: 'words',
			lineMaxChars: 15,
			offset: 0,
			center: 1,
			frame: Frames.PORTRAIT,
            getFontSize: function (n) { return getDefaultFontSize(n) * 0.5; },
            styles: {
                fontSize: 25,
                color: '#f5f5f5',
                backgroundColor: '#f5f5f5',
                backgroundImage: '',
                fontFamily: 'Rhodium Libre',
            }
		},
		layout_20: {
			text_type: 'default',
			center: 1,
			frame: Frames.SQUARE,
            getFontSize: function (n) { return getDefaultFontSize(n) * 0.8; },
            styles: {
                fontSize: 25,
                color: '#282828',
                backgroundColor: '#f5f5f5',
                backgroundImage: 'url(/generator/layouts/quote__20.jpg)',
                fontFamily: 'Quattrocento',
                textAlign: TextAlign.CENTER,
            }
		},
		layout_21: {
			text_type: 'words',
			lineMaxChars: 15,
			offset: 0,
			center: 1,
			frame: Frames.PORTRAIT,
            getFontSize: function (n) { return getDefaultFontSize(n) * 0.8; },
            styles: {
                fontSize: 25,
                color: '#f5f5f5',
                backgroundColor: '#f5f5f5',
                backgroundImage: '',
                fontFamily: 'Rhodium Libre',
                textAlign: TextAlign.CENTER,
            }
		},
		layout_22: {
            frame: Frames.PORTRAIT,
			text_type: 'default',
			lineMaxChars: 15,
			offset: 0,
			center: 0,
            getFontSize: function (n) { return getDefaultFontSize(n) * 0.8; },
            styles: {
                fontSize: 25,
                color: '#444',
                backgroundColor: '#f5f5f5',
                backgroundImage: 'url(/generator/layouts/patterns/seamless_paper_texture.png)',
                fontFamily: 'Arvo',
                textAlign: TextAlign.LEFT,
            }
		},
		layout_23: {
			text_type: 'lines',
			lineMaxChars: 10,
			offset: 0,
			center: 1,
			lineMaxCharsByTextSize: function (charCount) {
	            if (charCount <= 25) { return '10'; }
	            if (charCount <= 60) { return '13'; }
	            if (charCount <= 110) { return '18'; }
	            if (charCount <= 180) { return '20'; }

	            return '23';
			},
            getFontSize: function (n) { return getDefaultFontSize(n) * 0.6; },
            styles: {
                fontSize: 25,
                color: '#f2c511',
                backgroundColor: '#1a1a1a',
                backgroundImage: 'url(/generator/layouts/500x650_dark_gradient.jpg)',
                fontFamily: 'Montserrat',
                textAlign: TextAlign.CENTER,
            }
		},
		layout_24: {
			text_type: 'default',
			center: 1,
			frame: Frames.SQUARE,
            getFontSize: function (n) { return getDefaultFontSize(n) * 0.8; },
            styles: {
                fontSize: 25,
                color: '#FFF',
                backgroundColor: '#B6D6CB',
                backgroundImage: '',
                fontFamily: 'Shrikhand',
                textAlign: TextAlign.CENTER,
            }
		},
		layout_25: {
			name: 'Landscape Grey',
			text_type: 'default',
			frame: Frames.LANDSCAPE,
			center: 1,
            getFontSize: function (n) { return getDefaultFontSize(n) * 0.5; },
            styles: {
                fontSize: 25,
                color: '#FFF',
                backgroundColor: '#9ECC72',
                backgroundImage: 'url(/generator/layouts/650x450_oldgreen_love.jpg)',
                fontFamily: 'HandyAndy',
                textAlign: TextAlign.CENTER,
            }
		},
		layout_26: {
			frame: Frames.LANDSCAPE,
			text_type: 'default',
            align: 'bottom-right',
            getFontSize: function (n) { return getDefaultFontSize(n) * 0.6; },
            styles: {
                fontSize: 22,
                color: '#c5e0dc',
                backgroundColor: '#493736',
                backgroundImage: '',
                fontFamily: "Type Report",
                textAlign: TextAlign.RIGHT,
            }
		},

		layout_27: {
			text_type: 'default',
			offset: 0,
			center: 0,
            getFontSize: function (n) { return getDefaultFontSize(n) * 0.7; },
            styles: {
                fontSize: 25,
                color: '#444',
                backgroundColor: '#333',
                backgroundImage: 'url(/generator/layouts/quote__27.jpg)',
                fontFamily: 'Merriweather Sans',
                textAlign: TextAlign.LEFT,
            }
		},

		layout_28: {
            frame: Frames.SQUARE,
			text_type: 'default',
			lineMaxChars: 10,
			offset: 0,
			center: 0,
            getFontSize: function (n) { return getDefaultFontSize(n) * 1; },
            styles: {
                fontSize: 25,
                color: '#f6cd4f',
                backgroundColor: '#333',
                backgroundImage: 'url(/generator/layouts/quote__28.jpg)',
                fontFamily: 'Montserrat',
                textAlign: TextAlign.RIGHT,
            }
		},



		layout_30: {
			text_type: 'default',
			lineMaxChars: 15,
			offset: 0,
			center: 1,
			slab: 0,
            getFontSize: function (n) { return getDefaultFontSize(n) * 0.5; },
            styles: {
                fontSize: 25,
                color: '#002d0d',
                backgroundColor: '#333',
                backgroundImage: 'url(/generator/layouts/650x450_green_love.jpg)',
                fontFamily: 'Coming Soon',
                textAlign: TextAlign.RIGHT,
            }
		},

		layout_31: {
			name: 'Rotated Library',
			text_type: 'words',
			lineMaxChars: 15,
			center: 0,
			slab: 1,
			frame: Frames.PORTRAIT,
            getFontSize: function (n) { return getDefaultFontSize(n) * 1; },
            styles: {
                fontSize: 25,
                color: '#5E7687',
                backgroundColor: '#E9E44B',
                fontFamily: 'Luckiest Guy',
                textAlign: TextAlign.RIGHT,
            }
		},
		layout_32: {
            frame: Frames.SQUARE,
			text_type: 'default',
			center: 1,
            getFontSize: function (n) { return getDefaultFontSize(n) * 0.7; },
            styles: {
                fontSize: 25,
                color: '#FFF',
                backgroundColor: '#1d1a2e',
                fontFamily: 'NexaRust',
                textAlign: TextAlign.CENTER,
                backgroundImage: 'url(/assets/backgrounds/25beeb3967f12fbd807154e8ae5033f9.jpg)',
            }
		},
		layout_33: {
			text_type: 'words',
			center: 1,
			frame: Frames.SQUARE,
            getFontSize: function (n) { return getDefaultFontSize(n) * 0.5; },
            styles: {
                fontSize: 25,
                color: '#FFF',
                backgroundColor: '#F5C0AE',
                backgroundImage: '',
                fontFamily: 'Montserrat',
                textAlign: TextAlign.LEFT,
            }
		},

		layout_34: {
			text_type: 'words',
            center: 1,
			frame: Frames.PORTRAIT,
            getFontSize: function (n) { return getDefaultFontSize(n) * 0.5; },
            styles: {
                fontSize: 25,
                color: '#000000',
                backgroundColor: '#abd6c3',
                // backgroundImage: 'url(/assets/backgrounds/4eb4d20139272d19415919f9d280b5b4.jpg)',
                fontFamily: 'Montserrat',
                textAlign: TextAlign.LEFT,
            }
		},

		layout_35: {
			text_type: 'default',
			center: 1,
			frame: Frames.LANDSCAPE,
            getFontSize: function (n) { return getDefaultFontSize(n) * 0.5; },
            styles: {
                fontSize: 25,
                color: '#FFF',
                backgroundColor: '#545454',
                backgroundImage: '',
                fontFamily: 'Special Elite',
                textAlign: TextAlign.LEFT,
            }
		},

		layout_36: {
			text_type: 'default',
			center: 1,
			frame: Frames.PORTRAIT,
            getFontSize: function (n) { return 22; },
            styles: {
                fontSize: 22,
                color: '#000',
                backgroundColor: '#FFD342',
                backgroundImage: '',
                fontFamily: 'Barrio',
                textAlign: TextAlign.CENTER,
            }
		},

		layout_37: {
			text_type: 'default',
			center: 1,
			frame: Frames.PORTRAIT,
            getFontSize: function (n) { return 22; },
            styles: {
                fontSize: 22,
                color: '#FFF',
                backgroundColor: '#1a1a1a',
                backgroundImage: '',
                fontFamily: 'PT Sans',
                textAlign: TextAlign.CENTER,
            }
		},

        layout_38: {
			text_type: 'default',
			frame: Frames.PORTRAIT,
            center: 1,
            getFontSize: function (n) { return getDefaultFontSize(n) * 1; },
            styles: {
                fontSize: 25,
                color: '#FFF',
                backgroundColor: '#F00',
                backgroundImage: 'url(/assets/backgrounds/8b9a51e9aa922b05f1d1a06bab4d1bd1.jpg)',
                textAlign: TextAlign.CENTER,
                fontFamily: 'Lobster',
            }
		},
	}
};

Template.layouts.layout_39 = {
    text_type: 'default',
    frame: Frames.PORTRAIT,
    center: 1,
    getFontSize: function (n) { return getDefaultFontSize(n) * 0.8; },
    styles: {
        fontSize: 25,
        color: '#FFF',
        backgroundColor: '#7DBCCD',
        backgroundImage: 'url(/assets/layouts_only_background/clouds.png)',
        textAlign: TextAlign.CENTER,
        fontFamily: 'Arvo',
        textTransform: 'uppercase',
        fontWeight: 'bold',
    }
};

Template.layouts.layout_40 = {
    text_type: 'default',
    frame: Frames.SQUARE,
    center: 1,
    getFontSize: function (n) { return getDefaultFontSize(n) * 0.9; },
    styles: {
        fontSize: 25,
        color: '#FFF',
        backgroundColor: '#FFBFBD',
        backgroundImage: '',
        textAlign: TextAlign.CENTER,
        fontFamily: 'Arvo',
        textTransform: 'uppercase',
        fontWeight: 'bold',
    }
};

Template.layouts.layout_29 ={
    text_type: 'words',
    center: 0,
    frame: Frames.PORTRAIT,
    getFontSize: function (n) {
        if (n <= 25) { return 58; }
        if (n <= 60) { return 48; }
        if (n <= 85) { return 44; }
        if (n <= 110) { return 40; }
        if (n <= 140) { return 38; }
        if (n <= 180) { return 36; }
        if (n <= 230) { return 32; }
        return 30;
    },
    styles: {
        fontSize: 25,
        color: '#333',
        backgroundColor: '#F5F5F5',
        backgroundImage: '',
        fontFamily: 'Anton',
        textAlign: TextAlign.RIGHT,
    }
};

Template.layouts.layout_41 = {
    text_type: 'words',
    frame: Frames.SQUARE,
    center: 1,
    getFontSize: function (n) { return getDefaultFontSize(n) * 1; },
    styles: {
        fontSize: 25,
        color: '#222',
        backgroundColor: '#FFF',
        backgroundImage: '',
        textAlign: TextAlign.CENTER,
        fontFamily: 'Fredoka One',
    }
};

var TemplatesData = [];

Object.keys(Template.layouts).map(function(key, index) {
   var template = Template.layouts[key];
   template.hash = key;
   template.id = index;
   TemplatesData.push(template);
});
