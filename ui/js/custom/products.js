$(function() {
    updateCartWidget();
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
            '<li>' +
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
}