$(function() {
    updateCartWidget();

    $('.form-group').on('click',function(){
        var errorClass = ' has-error';
        this.className = this.className.replace(errorClass,"");
    });

    if (getBasketLength() == 0) {
        $('#order-form-wrapper').addClass('hidden');
        $('#order-button').addClass('hidden');
        $('#back-button').addClass('hidden');
        $('#back-to-products').removeClass('hidden');
    } else {
        $('#order-form-empty').addClass('hidden');
    }

});

var updateCartWidget = function() {
    var widgetList = $('#cart-widget-list'),
        widgetTotal = $('#cart-widget-total');

    if (getBasketLength() == 0) {
        widgetList.html('<h5 class="text-center">Кошик пустий</h5>');
    } else {
        var products = getToBasket(),
            totalPrice = 0,
            widgetListHTML = '',
            widgetTotalHTML = '';

        products.forEach(function(product){
            widgetListHTML += '<li>' +
                '<div class="row">' +
                '<div class="col-sm-7 col-xs-7"><strong>' + product.amount + '</strong> x <a href="/product/' + product.id + '">' + product.name + '</a></div>' +
                '<div class="col-sm-5 col-xs-5 text-right"><strong>' + (product.amount*1)*(product.price*1) + '.00 грн</strong> <a href="#"></a></div>' +
                '</div>' +
                '</li>';

            totalPrice += (product.amount*1)*(product.price*1);

        });

        widgetList.html(widgetListHTML);

        widgetTotalHTML = '<li>' +
            '<div class="row">' +
            '<div class="col-sm-6 col-xs-8">Загальна сума</div>' +
            '<div class="col-sm-6 col-xs-4 text-right">' + totalPrice + '.00 грн</div>' +
            '</div>' +
            '</li>' +
            '<li id="widget-cart-buttons">' +
            '<div class="row">' +
            '<div class="col-sm-6 col-xs-6">' +
            '<a class="btn btn-default" href="/cart">До кошика</a>' +
            '</div>' +
            '<div class="col-sm-6 col-xs-6 text-right">' +
            '<a class="btn btn-primary" href="/order">Оформити</a>' +
            '</div>' +
            '</div>' +
            '</li>';

        widgetTotal.addClass('total-price');
        widgetTotal.html(widgetTotalHTML);

    }
};

var formOrder = function() {

    // collect user info:
    var userName = $('#order-user-name'),
        userTel = $('#order-user-tel'),
        userEmail = $('#order-user-email'),
        orderAddress = $('#order-address'),
        orderDetails = $('#order-details'),
        orderProducts = {};

    var validName = isValid(userName),
        validTel = isValid(userTel),
        validMail = isValid(userEmail),
        validAddress = isValid(orderAddress);

    // validation:
    if (!validName || !validTel || !validMail || !validAddress) return;

    // hide order form:
    addSpiner('order-form-wrapper');
    $('#order-button').addClass('hidden');
    $('#back-button').addClass('hidden');

    // get cart info:
    var products = getToBasket(),
        totalPrice = 0;

    products.forEach(function(product){
        totalPrice += (product.price*1)*(product.amount*1);
        orderProducts[product.id] = product.amount;
    });

    $.ajax({
        url: "/form-order",
        type: "POST",
        data: {
            'userName' : userName.val(),
            'userTel' : userTel.val(),
            'userEmail' : userEmail.val(),
            'orderAddress' : orderAddress.val(),
            'orderDetails' : orderDetails.val(),
            'orderProducts' : orderProducts,
            'totalPrice' : totalPrice
        },
        success: function(){
            clearBasket();

            $('#back-to-products').removeClass('hidden');
            $('#order-form-wrapper').html('<h3 class="text-center">Дякуємо! <br/> Ваше замовлення успішно збережено! <br/> Наші кур\'єри обов\'язково звяжуться з Вами та уточнять всі деталі доставки.</h3>')
        }
    });

};

var isValid = function(field) {
    var valid = true;
    if (field.val() == '') {
        valid = false;
        field.parent().addClass('has-error');
    }
    return valid;
};