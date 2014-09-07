<?php

class Admin {

    function index($f3) {

        if ($f3->get('SESSION.user') !== 'admin') {
            $f3->reroute('/login');
        } else {
            echo View::instance()->render('a-header.html');
            echo View::instance()->render('admin.html');
            echo View::instance()->render('a-footer.html');

        }

    }

    function login($f3) {
        echo View::instance()->render('login.html');

        $db = $f3->get('db');

        if (isset($_POST['login'])) {

            $name = $_POST['email'];
            $pass = $_POST['pass'];
            $usersTable = new DB\SQL\Mapper($db, 'users');
            $auth = new Auth($usersTable, array('id'=>'name','pw'=>'password'));

            if($loginResult = $auth->login($name,$pass)) {
                $f3->set('SESSION.user','admin');
                $f3->reroute('/admin');
            } else {
                true;
            }
        } elseif ($f3->get('SESSION.user') === 'admin') {
            $f3->reroute('/admin');
        }
    }

    function products($f3) {
        if ($f3->get('SESSION.user') !== 'admin') {
            $f3->reroute('/login');
        } else {

            $db = $f3->get('db');
            $products=new DB\SQL\Mapper($db,'products');

            $productsResult = array_map(array($products,'cast'),$products->find(''));

            $f3->set('products',$productsResult);

            echo View::instance()->render('a-header.html');
            echo View::instance()->render('a-products.html');
            echo View::instance()->render('a-footer.html');




        }
    }

    function add($f3) {
        if ($f3->get('SESSION.user') !== 'admin') {
            $f3->reroute('/login');
        } else {

            echo View::instance()->render('a-header.html');
            echo View::instance()->render('add-product.html');
            echo View::instance()->render('a-footer.html');

            if (isset($_POST['add_product'])) {

                $db = $f3->get('db');

                $name = $_POST['name'];
                $categoryId = $_POST['category'];
                $count = $_POST['count'];
                $price = $_POST['price'];
                $about = $_POST['about'];

                $products=new DB\SQL\Mapper($db,'products');
                $products->name = $name;
                $products->category_id = $categoryId;
                $products->about = $about;
                $products->price = $price;
                $products->count = $count;
                $products->save();
//                $db->exec('INSERT INTO products (category_id,name,about,price,count) VALUES ("',$name,')');

            }
        }
    }

    function edit($f3) {
        if ($f3->get('SESSION.user') !== 'admin') {
            $f3->reroute('/login');
        } else {
            echo View::instance()->render('a-header.html');
            echo View::instance()->render('edit-product.html');
            echo View::instance()->render('a-footer.html');

        }
    }

    function categories($f3) {
        if ($f3->get('SESSION.user') !== 'admin') {
            $f3->reroute('/login');
        } else {
            echo View::instance()->render('a-header.html');
            echo View::instance()->render('a-categories.html');
            echo View::instance()->render('a-footer.html');

        }
    }

    function logout($f3) {
        $f3->clear('SESSION');
        $f3->reroute('/login');
    }

}