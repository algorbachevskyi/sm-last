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
            console.log(product);
            var productPrice = product.price*product.amount;
            totalPrice += productPrice;

            prod += '<tr>' +
                '<td><img src="ui/products_imgs/' + product.url + '" class="img-cart" /></td>' +
                '<td>' + product.data + '</p></td>' +
                '<td>' +
                '<form class="form-inline">' +
                '<select class="form-control">';

            for (var i=1; i<=product.count; i++ ) {
                prod += '<option value="' + i + '"';

                if (i == product.amount) prod += 'selected';

                prod += '>' + i + '</option>';
            }

            prod += '</select>' +
                '<a class="btn btn-primary" rel="tooltip" title="Видалити" onclick="deleteFromBasket(\'' + product.id + '\'); updateCart();"><i class="fa fa-trash-o"></i></a>' +
                '</form>' +
                '</td>' +
                '<td>' + product.price + '.00 грн</td>' +
                '<td>' + productPrice + '.00 грн</td>' +
                '</tr>';
        });

        console.log(prod);

        prod += '<tr>' +
            '<td colspan="6">&nbsp;</td>' +
            '</tr>' +
            '<tr>' +
            '<td colspan="4" class="text-right"><strong>Загальна Вартість</strong></td>' +
            '<td>' + totalPrice + '.00 грн</td>' +
            '</tr>';

        $('#cart-table-body').html(prod);

    }
}