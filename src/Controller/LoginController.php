<?php

namespace Controller;

use Silex\Application;
use Silex\Api\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;

class LoginController extends ControllerConf implements ControllerProviderInterface
{

    public function connect(Application $app)
    {

        // creates a new controller based on the default route
        $controllers = $app['controllers_factory'];

        $controllers->get('/', function (Application $app) {

            return $app["twig"]->render('Login/login.twig');
        });

        $controllers->get('/logout/', function (Request $request) use ($app) {

            $app['session']->set('user', null);

            return $app->redirect('/login');
        });

        $controllers->post('/autenticar', function (Request $request) use ($app) {
            $sql = 'SELECT * FROM usuario where email = ? and senha = ?';

            $usuario = $app['db']->fetchAssoc($sql, [$request->get('email'), md5($request->get('password'))]);

            if (!empty($usuario)) {
                unset($usuario['senha']);
                $app['session']->set('user', $usuario);
                return $app->redirect('/');
            }

            $this->mensagem($app, 0, 'Login ou senha invalido.');
            return $app->redirect('/login');
        });

        return $controllers;
    }

}