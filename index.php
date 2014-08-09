<?php

$f3=require('lib/base.php');

$f3->set('DEBUG',1);
if ((float)PCRE_VERSION<7.9)
	trigger_error('PCRE version is out of date');

$f3->config('config.ini');


// routes

// Routing
//$f3->route('POST /@controller/@action [ajax]','@controller->@action');
//$f3->route('POST /@controller/@action','@controller->@action');

$f3->route('GET /','main->index');
$f3->route('GET /products','main->products');
$f3->route('GET /about','main->about');
$f3->route('GET /contacts','main->contacts');
$f3->route('GET /delivery','main->delivery');

//$f3->route('POST /flats','main->flats');
//$f3->route('POST /admin','admin->index');


$f3->set('ONERROR',
    function($f3) {
        echo 'Errorrrr';
        var_dump($f3->get('ERROR.text'));
    }
);

$f3->run();
