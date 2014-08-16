$(document).ready(function () {
    
    $('.carousel').carousel();
    $("[rel=tooltip]").tooltip();

    var $container = $('.product-container');
    $container.imagesLoaded( function(){
    	$container.masonry();
	});

	$('#search-panel').on('click',function(e){
		e.preventDefault();
		console.log(e);
	});
    
});