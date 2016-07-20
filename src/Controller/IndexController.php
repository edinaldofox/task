<?php

namespace Controller;

use Silex\Application;
use Silex\Api\ControllerProviderInterface;

class IndexController implements ControllerProviderInterface
{

    public function connect(Application $app)
    {

        // creates a new controller based on the default route
        $controllers = $app['controllers_factory'];

        $controllers->get('/', function (Application $app) {
            if (null === $user = $app['session']->get('user')) {
                return $app->redirect('/login');
            }

            return $app["twig"]->render('Index/index.twig', [
                    'pagina' => 'Bem Vindo!',
                    'tituloPainel' => 'Tasks',
                ]
            );
        });

        return $controllers;
    }

}