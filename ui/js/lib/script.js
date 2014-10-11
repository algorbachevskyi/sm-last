$(document).ready(function () {

    // get basketData:

    $('#product-count').html(getBasketLength());
    console.log(getBasketLength(), getToBasket());
    basketProducts = getToBasket();



    $('.carousel').carousel();
    $("[rel=tooltip]").tooltip();

    var $container = $('.product-container');
    $container.imagesLoaded( function(){
    	$container.masonry();
	});

	$('#search-panel').on('click',function(e){
        e.stopPropagation();
		console.log(e);
	});

    $.jStorage.listenKeyChange("products", function(key, action){
        console.log(key + " has been " + action);
    });

});

var saveToBasket = function(product) {
    var products = $.jStorage.get('products', []);
    products.push(product);
    $.jStorage.set('products', products);

    $('#product-count').html(getBasketLength());
    $('#to-basket').html('<i class="fa fa-check"></i> товар в кошику!').attr('class','btn btn-sm btn-danger');
    var notice = new PNotify({
        title: 'Кошик',
        text: 'Товар додано до кошика!',
        type: 'success',
        icon: 'fa fa-shopping-cart',
        buttons: {
            closer: false,
            sticker: false
        }
    });
    notice.get().click(function() {
        notice.remove();
    });
};

var getToBasket = function() {
    return $.jStorage.get('products', []);
};

var deleteFromBasket = function(id) {
    var products = getToBasket(),
        newProducts = [];
    products.forEach(function(product){
        if (product.id != id) {
            newProducts.push(product);
        }
    });

    $.jStorage.set('products', newProducts);

    $('#product-count').html(getBasketLength());
    $('#to-basket').html('<i class="fa fa-check"></i> товар в кошику!').attr('class','btn btn-sm btn-danger');
    var notice = new PNotify({
        title: 'Кошик',
        text: 'Товар видалено з кошика!',
        type: 'warning',
        icon: 'fa fa-shopping-cart',
        buttons: {
            closer: false,
            sticker: false
        }
    });
    notice.get().click(function() {
        notice.remove();
    });
};

var getBasketLength = function() {
    var products = $.jStorage.get('products', []);
    return products.length;
};

var productInBasket = function(id) {
    var products = getToBasket();

    inBasket = false;
    products.forEach(function(product){
        console.log(product);
       if (product.id == id) {
           inBasket = true;
       }
    });

    return inBasket;

};

