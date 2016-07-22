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

            $sql = 'SELECT * FROM task as t join usuario as u on u.id = t.usuario ORDER BY t.id DESC LIMIT 10';
            $tasks = $app['db']->fetchAll($sql);

            return $app["twig"]->render('Index/index.twig', [
                    'pagina' => 'Bem Vindo!',
                    'tituloPainel' => 'Últimas tasks criadas',
                    'tasks' => $tasks
                ]
            );
        });

        $controllers->get('/minhas-tasks', function (Application $app) {
            if (null === $user = $app['session']->get('user')) {
                return $app->redirect('/login');
            }

            $usuario = $app['session']->get('user');

            $sql = 'SELECT * FROM task where usuario = ? LIMIT 20';
            $tasks = $app['db']->fetchAll($sql, [$usuario['id']]);

            return $app["twig"]->render('Index/minhasTasks.twig', [
                    'tasks' => $tasks
                ]
            );
        });

        return $controllers;
    }

}