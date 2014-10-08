<?php

class Main {
    function index($f3) {

        $db = $f3->get('db');

        // get products:
        $productsResult = $db->exec('SELECT * FROM products WHERE recomended="1" LIMIT 4');

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

    function product($f3) {

        $db = $f3->get('db');
        $id = $f3->get('PARAMS["id"]');

        if (is_numeric($id)) {
            $productsResult = $db->exec('SELECT * FROM products WHERE id="'.$id.'"');

            if (empty($productsResult)) {
                $f3->reroute('/products');
            } else {

                $categoryResult = $db->exec('SELECT name FROM categories WHERE id="'.$productsResult[0]['category_id'].'"');
                $productsResult[0]['category_name'] = $categoryResult[0]['name'];

                // get categories:
                $categoriesResult = $db->exec('SELECT * FROM categories');
                foreach ($categoriesResult as $category) { $categs[$category['id']] = $category['name']; }
                $f3->set('categories',$categs);

                // get pictures per product:
                $productPictures = $db->exec('SELECT * FROM pictures WHERE product_id="'.$id.'"');
                $productsResult[0]['images'] = $productPictures;

                // get similar products:
                $similarProducts = $db->exec('SELECT * FROM products WHERE category_id="'.$category['id'].'" LIMIT 4');

                $f3->set('product',$productsResult[0]);
                $f3->set('products',$similarProducts);
                $f3->set('activeCategory',$productsResult[0]['category_id']);
                $f3->set('activeMenu','products');
            };
        } else {
            $f3->reroute('/products');
        };

        echo View::instance()->render('header.html');
        echo View::instance()->render('product-detail.html');
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