
function wordwrap(str, int_width, str_break, cut) {
	result = str.split(/\s+/)
	.reduce(function(prev, curr) {
	    if (prev.length && (prev[prev.length - 1] + ' ' + curr).length <= int_width) {
	        prev[prev.length - 1] += ' ' + curr;
	    }
	    else {
	        prev.push(curr);
	    }
	    return prev;
	}, [])

	return result;
}

var Canvas = {

	write: function(textInput) {
	  	var t = textInput;
		var x = '';
		var tChars = textInput.length;


		layout_config = Template.get_config();

	  	switch( layout_config.text_type ) {
	  		case 'default':
				x = t.replace(/\r\n|\r|\n/g,"<br />");
				break;

	  		case 'slab':
			    var lines = t.split(/\n/);
			    // console.log(lines);
			    if( lines.length == 1 ) {
			    	lines = wordwrap(t, layout_config.lineMaxChars,'<br>', false);
			    	// console.log(lines);
			    }

			    for (var i = 0; i < lines.length; i++) {
			    	line = lines[i];
			    	if( line == '' ) {
			    		// x += '<br />';
			    	}else{
			    		x += '<span class="slabtext asas">' + line + '</span>';
			    	}
			    };


			    break;

			case 'lines':
			    var lines = t.split(/\n/);
			    var charsByLine = layout_config.lineMaxCharsByTextSize ? layout_config.lineMaxCharsByTextSize(tChars) : layout_config.lineMaxChars;
			    if( lines.length == 1 ) {
			    	lines = wordwrap(t, charsByLine,'</span></br><span class="line">', false);
			    }

			    for (var i = 0; i < lines.length; i++) {
			    	line = lines[i];
			    	if( line == '' ) {
			    		x += '<br />';
			    	}else{
			    		x += '<span class="line">' + line + '</span> <br />';
			    	}
			    };
			    break;

	  		case 'words':
	  			o = t.replace(/\r\n|\r|\n/g," ");
			    var words = o.split(' ');
                var j = 0;
			    for (var i = 0; i < words.length; i++) {
                    j++;
                    if (j>5) j=1;
			    	line = $.trim(words[i]);
                    var klass = 'wc' + j;
			    	if( line != '') {
			    		x += '<span class="word '+klass+'">' + line + ' </span>';
			    	} else{
			    		x += '<br/>';
			    	}
			    };
				break;

	  	}

	  	// console.log('html', x)
	  	return x;
	},

	generate:function(callback) {
		t = $.trim($('#textarea').val());
		var Quote = $('#__quote');
		$('#clonedCanvas').remove();
		var $clone = Quote.clone().attr('id', 'clonedCanvas');
		$('body').append($clone);

		html2canvas( $clone ,{
			letterRendering:true,
			onrendered:function(canvas) {
				var img=canvas.toDataURL("image/jpeg",1);
				$("#previewImage").attr("src",img);
				callback(img);
			}
		});
	},
};



/*! Copyright 2011, Ben Lin (http://dreamerslab.com/)
* Licensed under the MIT License (LICENSE.txt).
*
* Version: 1.1.1
*
* Requires: jQuery 1.2.6+
*/
;(function($,window){var get_win_size=function(){if(window.innerWidth!=undefined)return[window.innerWidth,window.innerHeight];else{var B=document.body;var D=document.documentElement;return[Math.max(D.clientWidth,B.clientWidth),Math.max(D.clientHeight,B.clientHeight)]}};$.fn.center=function(opt){var $w=$(window);var scrollTop=$w.scrollTop();return this.each(function(){var $this=$(this);var configs=$.extend({against:"window",top:false,topPercentage:0.5,resize:true},opt);var centerize=function(){var against=configs.against;var against_w_n_h;var $against;if(against==="window")against_w_n_h=get_win_size();else if(against==="parent"){$against=$this.parent();against_w_n_h=[$against.width(),$against.height()];scrollTop=0}else{$against=$this.parents(against);against_w_n_h=[$against.width(),$against.height()];scrollTop=0}var x=(against_w_n_h[0]-$this.outerWidth())*0.5;var y=(against_w_n_h[1]-$this.outerHeight())*configs.topPercentage+scrollTop;if(configs.top)y=configs.top+scrollTop;$this.css({"left":x,"top":y})};centerize();if(configs.resize===true)$w.resize(centerize)})}})(jQuery,window);
