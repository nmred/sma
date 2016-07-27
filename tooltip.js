$( function()
{
    var targets = $( 'f' ),
        target  = false,
        tooltip = false,
        title   = false;
 
    targets.bind( 'click', function()
    {
        target  = $(this);
		targetName = $(this).text();
		console.info(fanyiData);
		tip = "";
		if (fanyiData.hasOwnProperty(targetName)) {
			var value = fanyiData[targetName];
			var bdos = value.bdos;
			var basics = value.basics;
			var sents = value.sents;
			if (bdos.length == 2) {
				tip += '<p>英：' + bdos[0] + ' 美：' + bdos[1] + '</p>';
			} else if (bdos.length == 1) {
				tip += '<p>美：' + bdos[0] + '</p>';
			}

			for (var i = 0; i < basics.length; i++) {
				tip += '<p>' + basics[i] + '</p>'		
			} 
			for (var i = 0; i < sents.length; i++) {
				if (i > 2) {
					break;	
				}
				tip += '<p>' + sents[i] + '</p>'		
			} 
		} else {
			return false;	
		}
		if ($("#tooltip")) {
			$("#tooltip").remove();	
		}
        tooltip = $( '<div id="tooltip"></div>' );
 
        if( !tip || tip == '' )
            return false;
 
        target.removeAttr( 'title' );
        tooltip.css( 'opacity', 0 )
               .html( tip )
               .appendTo( 'body' );
 
        var init_tooltip = function()
        {
            if( $( window ).width() < tooltip.outerWidth() * 1.5 )
                tooltip.css( 'max-width', $( window ).width() / 2 );
            else
                tooltip.css( 'max-width', 340 );
 
            var pos_left = target.offset().left + ( target.outerWidth() / 2 ) - ( tooltip.outerWidth() / 2 ),
                pos_top  = target.offset().top - tooltip.outerHeight() - 20;
 
            if( pos_left < 0 )
            {
                pos_left = target.offset().left + target.outerWidth() / 2 - 20;
                tooltip.addClass( 'left' );
            }
            else
                tooltip.removeClass( 'left' );
 
            if( pos_left + tooltip.outerWidth() > $( window ).width() )
            {
                pos_left = target.offset().left - tooltip.outerWidth() + target.outerWidth() / 2 + 20;
                tooltip.addClass( 'right' );
            }
            else
                tooltip.removeClass( 'right' );
 
            if( pos_top < 0 )
            {
                var pos_top  = target.offset().top + target.outerHeight();
                tooltip.addClass( 'top' );
            }
            else
                tooltip.removeClass( 'top' );
 
            tooltip.css( { left: pos_left, top: pos_top } )
                   .animate( { top: '+=10', opacity: 1 }, 50 );
        };
 
        init_tooltip();
        $( window ).resize( init_tooltip );
 
        var remove_tooltip = function()
        {
            tooltip.animate( { top: '-=10', opacity: 0 }, 50, function()
            {
                $(this).remove();
            });
        };
 
        tooltip.bind( 'click', remove_tooltip );
    });
});
