$(document).ready(function () {

    // get basketData:

    $('#product-count').html(getBasketLength());
    basketProducts = getToBasket();



    $('.carousel').carousel();
    $("[rel=tooltip]").tooltip();

    var $container = $('.product-container');
    $container.imagesLoaded( function(){
    	$container.masonry();
	});

	$('#search-panel').on('click',function(e){
        e.stopPropagation();
	});

    $.jStorage.listenKeyChange("products", function(key, action){
        console.log(key + " has been " + action);
    });

    // search logic:
    $('#search-category span').on('click',function(){
        $('#search-category span').attr('class','');
        $('#'+ this.id).attr('class','active');
    });

    $('#search-price span').on('click',function(){
        $('#search-price span').attr('class','');
        $('#'+ this.id).attr('class','active');
    });

    $('#search-recom span').on('click',function(){
        $('#search-recom span').attr('class','');
        $('#'+ this.id).attr('class','active');
    });


});

var saveToBasket = function(product) {
    var products = $.jStorage.get('products', []);
    products.push(product);
    $.jStorage.set('products', products);

    $('#product-count').html(getBasketLength());
    $('#to-basket').html('<i class="fa fa-check"></i> товар в кошику!').attr('class','btn btn-sm btn-danger');
    $('#count-select-group').addClass('hidden');

    updateCartWidget();

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

var clearBasket = function() {
    $.jStorage.flush();
    $('#product-count').html(getBasketLength());
};

var getBasketLength = function() {
    var products = $.jStorage.get('products', []);
    return products.length;
};

var updateProductAmount = function(id, amount) {
    var products = $.jStorage.get('products', []),
        totalPrice = $('#total-price').html()*1;
    products.forEach(function(product){
        if (product.id == id) {
            product.amount = amount;
            totalPrice -= $('#product-price-' + product.id).html()*1;
            $('#product-price-' + product.id).html(product.amount*product.price*1);
            $('#total-price').html(totalPrice*1 + product.amount*product.price*1);

        }
    });

    $.jStorage.set('products', products);



};

var productInBasket = function(id) {
    var products = getToBasket();

    inBasket = false;
    products.forEach(function(product){
       if (product.id == id) {
           inBasket = true;
       }
    });

    return inBasket;

};

var addSpiner = function(DOMelID) {
    var spinner = '<div class="spinner-wrapper"><svg class="spinner" width="65px" height="65px" viewBox="0 0 66 66" xmlns="http://www.w3.org/2000/svg">' +
                    '<circle class="path" fill="none" stroke-width="6" stroke-linecap="round" cx="33" cy="33" r="30"></circle>' +
                  '</svg></div>';
    $('#' + DOMelID).html(spinner);
};

