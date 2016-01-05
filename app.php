<?php

use Silex\Provider,
    Symfony\Component\HttpFoundation\Response,
    Symfony\Component\HttpFoundation\Request,
    Symfony\Component\Translation\Loader\YamlFileLoader,
    EmpariWeb\Provider\YamlConfigServiceProvider,
    EmpariWeb\Provider\RestControllerProvider,    
    EmpariWeb\Provider\RpcControllerProvider,    
    EmpariWeb\Service\Authentication as AuthenticationService,
    EmpariWeb\Service\Authorization as AuthorizationService;

require_once __DIR__.'/vendor/autoload.php';

//Application Initialization
$app = new Silex\Application();

//Providers Configuration
$app->register(new Provider\TwigServiceProvider());
$app->register(new Provider\SessionServiceProvider());
$app->register(new Provider\UrlGeneratorServiceProvider());
$app->register(new Provider\HttpCacheServiceProvider());
$app->register(new Provider\SerializerServiceProvider());
$app->register(new Provider\SwiftmailerServiceProvider());
$app->register(new Provider\HttpFragmentServiceProvider());
$app->register(new Provider\ServiceControllerServiceProvider());

//Twig Configuration
$app['twig.path'] = array('views');
//assets path
$app['asset_path'] = 'views/assets';
//Plenissime server configuration
$app['server'] = 'http://159.203.141.150/api';
$app['key'] = 'ed4905c19fa8b7524821ed24b298d5404f5d38ef';
//Time Zone Configuration
date_default_timezone_set('America/Sao_Paulo');

//Debug Configuration
$app['debug'] = true;

//routes
$index = new \EmpariWeb\Controller\IndexController();
$app->mount('/',$index);

//run app
return $app;