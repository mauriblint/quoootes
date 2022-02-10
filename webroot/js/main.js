$('.card').on('mousedown',function(e) {
    if (e.ctrlKey) {
        e.preventDefault();

        $.get('/quotes/setInactive/')
   }
});

var Sharer = {

	init:function() {
		$('a.share').click(function(e) {
		    e.preventDefault();
		    window.open(this.href,'_blank','width=750, height=500');
		    id = $(this).attr('data-id');
		    $('.shares-counter').html(parseInt($('.shares-counter').html(), 10)+1);
		    Sharer.like( id );
		});


        $('.share-download').click(function(event) {
            id = $(this).attr('data-id');
            Sharer.like( id );
		    // $('.shares-counter').html(parseInt($('.shares-counter').html(), 10)+1);
        });
	},

    like: function(_id) {
        $.post('/quotes/like', {id: _id}, function(data, textStatus, xhr) {
        	// alert(data);
        });
    }
}
Sharer.init();


// if( $('.grid').length > 0 ) {
// 	$('.grid').imagesLoaded( function() {
// 		$('.grid').masonry({
// 			itemSelector: '.grid-item',
// 			columnWidth: '.grid-item',
// 		});
// 	});
// }
