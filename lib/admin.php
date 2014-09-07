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

    function create($f3) {
        echo View::instance()->render('header.html');
    }

    function logout($f3) {
        $f3->clear('SESSION');
        $f3->reroute('/login');
    }

}