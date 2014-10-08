<?php

class Main {
    function index($f3) {

        $db = $f3->get('db');

        // get products:
        $productsResult = $db->exec('SELECT * FROM products WHERE recomended="1"');

        // get categories:
        $categoriesResult = $db->exec('SELECT * FROM categories');
        foreach ($categoriesResult as $category) { $categs[$category['id']] = $category['name']; }
        $f3->set('categories',$categs);

        // get pictures per product:
        foreach ($productsResult as $index=>$product) {
            $productPictures = $db->exec('SELECT * FROM pictures WHERE product_id="'.$product['id'].'"');
            $productsResult[$index]['images'] = $productPictures;
        }

        $f3->set('products',$productsResult);
        $f3->set('activeMenu','main');

        echo View::instance()->render('header.html');
        echo View::instance()->render('slider.html');
        echo View::instance()->render('products-small-list.html');
        echo View::instance()->render('footer.html');

    }

    function products($f3) {

        $db = $f3->get('db');
        $category = isset($_GET['cat']) ? $_GET['cat'] : 0;

        // get categories id:
        $categoriesResult = $db->exec('SELECT * FROM categories');
        $issetCategoryId = false;
        foreach ($categoriesResult as $cat) {
            $categs[$cat['id']] = $cat['name'];
            if ($cat['id'] == $category) {
                $issetCategoryId = true;
            }
        }

        $where = '';
        if ($issetCategoryId) {
            $where = ' WHERE category_id="'.$category.'"';
        } else {
            $category = 0;
        }

        // get products for category:
        $productsResult = $db->exec('SELECT * FROM products'.$where);

        // get pictures per product:
        foreach ($productsResult as $index=>$product) {
            $productPictures = $db->exec('SELECT * FROM pictures WHERE product_id="'.$product['id'].'"');
            $productsResult[$index]['images'] = $productPictures;
        }


        $f3->set('categories',$categs);
        $f3->set('products',$productsResult);
        $f3->set('activeCategory',$category);
        $f3->set('activeMenu','products');

        echo View::instance()->render('header.html');
        echo View::instance()->render('products-big-list.html');
        echo View::instance()->render('footer.html');

    }

    function about($f3) {

        $db = $f3->get('db');

        // get categories:
        $categoriesResult = $db->exec('SELECT * FROM categories');
        foreach ($categoriesResult as $category) { $categs[$category['id']] = $category['name']; }
        $f3->set('categories',$categs);
        $f3->set('activeMenu','about');

        echo View::instance()->render('header.html');
        echo View::instance()->render('about.html');
        echo View::instance()->render('footer.html');


    }

    function contacts($f3) {

        $db = $f3->get('db');

        // get categories:
        $categoriesResult = $db->exec('SELECT * FROM categories');
        foreach ($categoriesResult as $category) { $categs[$category['id']] = $category['name']; }
        $f3->set('categories',$categs);
        $f3->set('activeMenu','contacts');

        echo View::instance()->render('header.html');
        echo View::instance()->render('contacts.html');
        echo View::instance()->render('footer.html');

    }

    function delivery($f3) {

        $db = $f3->get('db');

        // get categories:
        $categoriesResult = $db->exec('SELECT * FROM categories');
        foreach ($categoriesResult as $category) { $categs[$category['id']] = $category['name']; }
        $f3->set('categories',$categs);
        $f3->set('activeMenu','delivery');

        echo View::instance()->render('header.html');
        echo View::instance()->render('delivery.html');
        echo View::instance()->render('footer.html');

    }
}