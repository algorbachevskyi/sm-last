$(function() {

    updateCart();

});

var updateCart = function() {
    if (getBasketLength() == 0) {
        $('#cart-table-body').html('');
        $('#cart-table').css('display','none');
        $('#empty-cart-text').css('display','block');
        $('#go-to-order').css('display','none');
    } else {
        $('#cart-table').css('display','table');
        basketProducts = getToBasket();

        // form cart table:
        var prod = '',
            totalPrice = 0;

        basketProducts.forEach(function(product){
            var productPrice = product.price*product.amount;
            totalPrice += productPrice;

            prod += '<tr>' +
                '<td><a href="/product/' + product.id + '"><img src="ui/products_imgs/' + product.url + '" class="img-cart" /></a></td>' +
                '<td>' + product.name + '</p></td>' +
                '<td>' +
                '<form class="form-inline">' +
                '<select class="form-control" onchange="updateProductAmount(\'' + product.id + '\',this.value);">';

            for (var i=1; i<=product.count; i++ ) {
                prod += '<option value="' + i + '"';

                if (i == product.amount) prod += 'selected';

                prod += '>' + i + '</option>';
            }

            prod += '</select>' +
                '</form>' +
                '</td>' +
                '<td>' + product.price + '.00 грн</td>' +
                '<td><span id="product-price-' + product.id + '">' + productPrice + '</span>.00 грн' + '<a class="btn btn-primary pull-right" rel="tooltip" title="Видалити" onclick="deleteFromBasket(\'' + product.id + '\'); updateCart();"><i class="fa fa-trash-o"></i></a>' +
                '</td>' +
                '</tr>';
        });

        prod += '<tr>' +
            '<td colspan="6">&nbsp;</td>' +
            '</tr>' +
            '<tr>' +
            '<td colspan="4" class="text-right"><strong>Загальна Вартість</strong></td>' +
            '<td><span id="total-price">' + totalPrice + '</span>.00 грн</td>' +
            '</tr>';

        $('#cart-table-body').html(prod);

    }
}