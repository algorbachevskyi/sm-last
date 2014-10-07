<?php

class Main {
    function index($f3) {

        echo View::instance()->render('header.html');
        echo View::instance()->render('slider.html');
        echo View::instance()->render('products-small-list.html');
        echo View::instance()->render('footer.html');

    }

    function products($f3) {

        echo View::instance()->render('header.html');
        echo View::instance()->render('footer.html');

    }

    function about($f3) {

        echo View::instance()->render('header.html');
        echo View::instance()->render('about.html');
        echo View::instance()->render('footer.html');

    }

    function contacts($f3) {

        echo View::instance()->render('header.html');
        echo View::instance()->render('contacts.html');
        echo View::instance()->render('footer.html');

    }

    function delivery($f3) {

        echo View::instance()->render('header.html');
        echo View::instance()->render('delivery.html');
        echo View::instance()->render('footer.html');

    }
}