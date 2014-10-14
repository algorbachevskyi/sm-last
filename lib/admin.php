<?php

class Admin {

    function index($f3) {

        if ($f3->get('SESSION.user') !== 'admin') {
            $f3->reroute('/login');
        } else {

            $db = $f3->get('db');

            // get products:
//            $products=new DB\SQL\Mapper($db,'products');
//            $productsResult = array_map(array($products,'cast'),$products->find(''));
//            $f3->set('products',$productsResult);

            // get categories:
//            $categories=new DB\SQL\Mapper($db,'categories');
//            $categoriesResult = array_map(array($categories,'cast'),$categories->find(''));
//            foreach ($categoriesResult as $category) {
//                $categs[$category['id']] = $category['name'];
//            }
//            $f3->set('categories',$categs);



            // get orders:
            $orders=new DB\SQL\Mapper($db,'orders');
            $ordersResult = array_map(array($orders,'cast'),$orders->find(''));

            $activeOrders = $acceptedOrders = $declinedOrders = 0;

            foreach ($ordersResult as $key=>$order) {

                if ($order['status'] == 1) {
                    $activeOrders += 1;
                } elseif ($order['status'] == 0) {
                    $acceptedOrders +=1;
                } elseif ($order['status'] == -1) {
                    $declinedOrders +=1;
                }

                // get client for order:
                $client = new DB\SQL\Mapper($db,'clients');
                $clientResult = array_map(array($client,'cast'),$client->find(array('id= ?',$order["client_id"])));
                $ordersResult[$key]['client'] = $clientResult[0];

                // get products for order:

                $query = 'SELECT p.*, op.product_amount FROM products AS p JOIN order_product AS op ON p.id=op.product_id WHERE op.order_id="'.$order["id"].'"';
                $orderProductsResult = $db->exec($query);
//                var_dump($orderProductsResult);
                $ordersResult[$key]['products'] = $orderProductsResult;

            }


            $f3->set('orders',$ordersResult);
            $f3->set('activeOrders',$activeOrders);
            $f3->set('acceptedOrders',$acceptedOrders);
            $f3->set('declinedOrders',$declinedOrders);

            echo View::instance()->render('a-header.html');
            echo View::instance()->render('admin.html');
            echo View::instance()->render('a-footer.html');

        }

    }

    function order($f3) {

    }

    function settings($f3) {
        if ($f3->get('SESSION.user') !== 'admin') {
            $f3->reroute('/login');
        } else {

            $db = $f3->get('db');
            if (isset($_POST['update_settings'])) {
                $res = $db->exec('UPDATE settings SET email="'.$_POST['email'].'" WHERE id="1"');
                $f3->set('email',$_POST['email']);

            } else {
                $res = $db->exec('SELECT email FROM settings WHERE id=1');
                $f3->set('email',$res[0]['email']);
            }

            echo View::instance()->render('a-header.html');
            echo View::instance()->render('a-settings.html');
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

            // get products:
            $products=new DB\SQL\Mapper($db,'products');
            $productsResult = array_map(array($products,'cast'),$products->find(''));
            $f3->set('products',$productsResult);

            // get categories:
            $categories=new DB\SQL\Mapper($db,'categories');
            $categoriesResult = array_map(array($categories,'cast'),$categories->find(''));

            foreach ($categoriesResult as $category) {
                $categs[$category['id']] = $category['name'];
            }

            $f3->set('categories',$categs);

            echo View::instance()->render('a-header.html');
            echo View::instance()->render('a-products.html');
            echo View::instance()->render('a-footer.html');

        }
    }

    function add($f3) {
        if ($f3->get('SESSION.user') !== 'admin') {
            $f3->reroute('/login');
        } else {

            $db = $f3->get('db');
            $categories=new DB\SQL\Mapper($db,'categories');
            $categoriesResult = array_map(array($categories,'cast'),$categories->find(''));
            $f3->set('categories',$categoriesResult);

            echo View::instance()->render('a-header.html');
            echo View::instance()->render('add-product.html');
            echo View::instance()->render('a-footer.html');

            if (isset($_POST['add_product'])) {

                $name = $_POST['name'];
                $categoryId = $_POST['category'];
                $count = $_POST['count'];
                $price = $_POST['price'];
                $data = $_POST['data'];
                $about = $_POST['about'];
                $warranty = $_POST['warranty'];
                $recomended = isset($_POST['recomended']) ? $_POST['recomended'] : 0;

                $images = $_POST['product-files'];

                $products=new DB\SQL\Mapper($db,'products');
                $products->name = $name;
                $products->category_id = $categoryId;
                $products->data = $data;
                $products->about = $about;
                $products->warranty = $warranty;
                $products->price = $price;
                $products->count = $count;
                $products->recomended = $recomended;
                $products->save();

                if ($images !== '') {

                    $images = explode(",",$images);

                    foreach ($images as $image) {
                        $pictures=new DB\SQL\Mapper($db,'pictures');
                        $pictures->product_id = $products->id;
                        $pictures->url = $image;
                        $pictures->save();
                    }

                }

                $f3->reroute('/admin/products');
//                $db->exec('INSERT INTO products (category_id,name,about,price,count) VALUES ("',$name,')');

            }
        }
    }

    function edit($f3) {
        if ($f3->get('SESSION.user') !== 'admin') {
            $f3->reroute('/login');
        } else {

            $db = $f3->get('db');

            if (isset($_POST['update_product'])) {
                $name = $_POST['name'];
                $categoryId = $_POST['category'];
                $count = $_POST['count'];
                $price = $_POST['price'];
                $data = $_POST['data'];
                $about = $_POST['about'];
                $warranty = $_POST['warranty'];
                $productId = $_POST['productId'];
                $recomended = isset($_POST['recomended']) ? $_POST['recomended'] : 0;

                $db->exec('UPDATE products SET category_id="'.$categoryId.'", name="'.$name.'", about="'.$about.'", data="'.$data.'", warranty="'.$warranty
                    .'", price="'.$price.'", count="'.$count.'", recomended="'.$recomended.'" WHERE id="'.$productId.'"');

                $images = $_POST['product-files'];

                if ($images !== '') {
                    $images = explode(",",$images);

                    foreach ($images as $image) {
                        $pictures=new DB\SQL\Mapper($db,'pictures');
                        $pictures->product_id = $productId;
                        $pictures->url = $image;
                        $pictures->save();
                    }
                }

                $f3->reroute('/admin/products');
            } else {

                $products=new DB\SQL\Mapper($db,'products');

                // get product:
                $id = $f3->get('PARAMS["id"]');
                $productsResult = array_map(array($products,'cast'),$products->find(array('id= ?',$id)));
                $f3->set('product',$productsResult[0]);
                $f3->set('productId',$id);

                // get categories:
                $categories=new DB\SQL\Mapper($db,'categories');
                $categoriesResult = array_map(array($categories,'cast'),$categories->find(''));
                $f3->set('categories',$categoriesResult);

                $pictures=new DB\SQL\Mapper($db,'pictures');
                $picturesResult = array_map(array($pictures,'cast'),$pictures->find(array('product_id= ?',$id)));
                $f3->set('pictures',$picturesResult);

                echo View::instance()->render('a-header.html');
                echo View::instance()->render('edit-product.html');
                echo View::instance()->render('a-footer.html');
            }
        }
    }

    function delete($f3) {
        if ($f3->get('SESSION.user') !== 'admin') {
            $f3->reroute('/login');
        } else {

            if (isset($_POST['delete_product'])) {
                $id = $f3->get('POST["productId"]');

                $db = $f3->get('db');

                // get pictures per product:
                $productPictures = $db->exec('SELECT * FROM pictures WHERE product_id="'.$id.'"');

                //delete all product pictures from disk:
                $ds = '/';
                $storeFolder = 'products_imgs';
                foreach($productPictures as $picture) {

                    $targetFile = dirname( __DIR__) . $ds. 'ui'. $ds. $storeFolder . $ds. $picture['url'];

                    // delete from disk
                    unlink($targetFile);

                    // delete from db
                    $db->exec(
                        'DELETE FROM pictures WHERE id="'.$picture['id'].'"'
                    );
                }

                // delete product from db:
                $db->exec(
                    'DELETE FROM products WHERE id='.$id
                );

                $f3->reroute('/admin/products');
            } else if (isset($_POST['delete_category'])) {
                $id = $f3->get('POST["categoryId"]');

                $db = $f3->get('db');
                $db->exec(
                    'DELETE FROM categories WHERE id='.$id
                );

                $f3->reroute('/admin/categories');
            } else {
                $f3->reroute('/admin');
            }
        }
    }

    function categories($f3) {
        if ($f3->get('SESSION.user') !== 'admin') {
            $f3->reroute('/login');
        } else {

            $db = $f3->get('db');
            $categories=new DB\SQL\Mapper($db,'categories');

            $categoriesResult = array_map(array($categories,'cast'),$categories->find(''));

            $f3->set('categories',$categoriesResult);

            echo View::instance()->render('a-header.html');
            echo View::instance()->render('a-categories.html');
            echo View::instance()->render('a-footer.html');

        }
    }

    function addCategory($f3) {
        if ($f3->get('SESSION.user') !== 'admin') {
            $f3->reroute('/login');
        } else {

            echo View::instance()->render('a-header.html');
            echo View::instance()->render('add-category.html');
            echo View::instance()->render('a-footer.html');

            if (isset($_POST['add_category'])) {

                $db = $f3->get('db');

                $name = $_POST['name'];
                $about = $_POST['about'];

                $products=new DB\SQL\Mapper($db,'categories');
                $products->name = $name;
                $products->about = $about;
                $products->save();

                $f3->reroute('/admin/categories');

            }
        }
    }

    function editCategory($f3) {
        if ($f3->get('SESSION.user') !== 'admin') {
            $f3->reroute('/login');
        } else {

            $db = $f3->get('db');

            if (isset($_POST['update_category'])) {
                $name = $_POST['name'];
                $about = $_POST['about'];
                $categoryId = $_POST['categoryId'];

                $db->exec('UPDATE categories SET name="'.$name.'", about="'.$about
                    .'" WHERE id="'.$categoryId.'"');

                $f3->reroute('/admin/categories');
            } else {

                $categories=new DB\SQL\Mapper($db,'categories');

                // get product:
                $id = $f3->get('PARAMS["id"]');
                $categoryResult = array_map(array($categories,'cast'),$categories->find(array('id= ?',$id)));
                $f3->set('category',$categoryResult[0]);
                $f3->set('categoryId',$id);

                echo View::instance()->render('a-header.html');
                echo View::instance()->render('edit-category.html');
                echo View::instance()->render('a-footer.html');
            }
        }
    }

    function upload($f3) {
        $ds = '/';  //1
        $storeFolder = 'products_imgs';   //2

        if (!empty($_FILES)) {

            $tempFile = $_FILES['file']['tmp_name'];          //3

            $targetPath = dirname( __DIR__) . $ds. 'ui'. $ds. $storeFolder . $ds;  //4

            $targetFile =  $targetPath. $_FILES['file']['name'];  //5

            move_uploaded_file($tempFile,$targetFile); //6

        }
    }

    function removeFile($f3) {

        if (!empty($_POST)) {
            $ds = '/';
            $storeFolder = 'products_imgs';
            $targetFile = dirname( __DIR__) . $ds. 'ui'. $ds. $storeFolder . $ds. $_POST['deleted_file'];

            // delete file from disk
            unlink($targetFile);

            // delete file from db
                $id = $_POST['deleted_file_id'];

                $db = $f3->get('db');
                $db->exec(
                    'DELETE FROM pictures WHERE id='.$id
                );

                $f3->reroute('/admin/product/'.$_POST['product_id']);

        }

    }

    function logout($f3) {
        $f3->clear('SESSION');
        $f3->reroute('/login');
    }

}