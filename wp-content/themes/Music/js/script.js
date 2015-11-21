/***
 * Script - jQuery de todo el tema (aqui se llama libreria o plugins)
 * @version 0.3 (2012_11_14)
 * @author Laurent Perroteau
 * @package Enfusion Theme
 */

jQuery(window).on('load', function() {

//evitar conflicto en wordpress
	var $ = jQuery.noConflict();

//eliminar p vacios
	$('p').each(function() {
		if ($(this).html().replace(/\s|&nbsp;/g, '').length == 0)
			$(this).remove();
	});

//use featured image for background
	var img = $('.wp-post-image').attr('src');
	var background = 'url(' + img + ') repeat center center';
	$('body').css('background', background); 

//quand laisse un commentaire, active reply et contenu
	$('.single .reply a').click(function() { showComment();	});

//quand on arrive de la home en voulant laisser un commentaire
	if( $('.single .reply').hasClass('comment') ) { showComment(); };

// reduir les commentaires et les montrer comme tooltip
	$('#comments article').each(function() {

		var $com = $(this).find('.comment-content > p');
		// couper le text
		var com = $com.text();
		newCom = com.substr(0,50)+'...';
		// le changer
		$com.text(newCom)
		// le montrer
		$(this).show();
		// ajouter le tooltip
		$(this).append('<div class="tooltip animated">' + com + '</div>');
		// l'animer
		$(this).hover(
			function() {
				$(this).find('.tooltip')
					   .show()
					   .removeClass('fadeOutRight')
					   .addClass('fadeInLeft');
			},
			function() {
				$(this).find('.tooltip')
				       .removeClass('fadeInLeft')
				       .addClass('fadeOutRight')
				       .delay(500)
		       		   .queue(function(next) { 
		       		   		$(this).hide(); next(); 
		       		   	});
			}
		);
	});


//avertissement ie
	if( $('html').hasClass('lte9') ) {
		alert("Ton browser il est 'has been', j'te conseil de download Firefox, Chrome ou Opera, OK!");
	}

});


jQuery(window).on('load resize', function() {

	var $ = jQuery.noConflict();

	relacheContent();

	showPrePost();

});

function showComment() {

	jQuery('.single #respond').slideDown();
	jQuery('.single .reply').hide();
	jQuery('#content > p').css({
		'opacity':'1',
		'maxHeight': '150px'
	});
	relacheContent();
}

//Function pour relacher le content si plus haut que la fenêtre
function relacheContent() {

	var h = jQuery(window).height();
	var hContent = jQuery('#content').outerHeight();

	if( hContent + 200 > h ) {
		
		jQuery('#content').css({
			position: 'relative',
			marginTop: '200px'
		})
	}
	else {

		jQuery('#content').css({
			position: 'fixed',
			marginTop: '0'
		})
	}
}

//afficher les anciens post quand curseur a droit de l'écran
function showPrePost() {

	var w = jQuery(window).width();
	var posRight = w - 250;

	jQuery('html').mousemove(function(e){

		if( e.pageX > posRight) {
			
			jQuery('#secondary').css('right', '0');
		}
		else {
			jQuery('#secondary').css('right', '-250px');
		}
	});
}