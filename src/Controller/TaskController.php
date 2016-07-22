<?php

namespace Controller;

use Silex\Application;
use Silex\Api\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;

class TaskController extends ControllerConf implements ControllerProviderInterface
{

    public function connect(Application $app)
    {

        // creates a new controller based on the default route
        $controllers = $app['controllers_factory'];

        $controllers->get('/cadastro', function (Request $request) use ($app) {
            if (null === $user = $app['session']->get('user')) {
                return $app->redirect('/login');
            }

            $usuarios = $app['db']->fetchAll('SELECT * FROM usuario');

            return $app["twig"]->render('Task/cadastro.twig', [
                'tituloPainel' => 'Formulario de Cadastro da Task',
                'pagina' => 'Dados da Task',
                'usuarios' => $usuarios
            ]);
        });

        $controllers->post('/cadastro', function (Request $request) use ($app) {
            if (null === $user = $app['session']->get('user')) {
                return $app->redirect('/login');
            }
            $status = true;
            $task = $request->request->all();

            $status = $app['db']->insert('task', $task);

            if(!$status) {

                return $app["twig"]->render('Task/cadastro.twig', [
                    'tituloPainel' => 'Formulario de Cadastro da Task',
                    'pagina' => 'Dados da Task',
                    'task' => $task
                ]);
            }

            $this->mensagem($app, 1);
            return $app->redirect('/task/cadastro');

        });

        $controllers->get('/listar', function (Request $request) use ($app) {
            if (null === $user = $app['session']->get('user')) {
                return $app->redirect('/login');
            }

            $usuario = $app['session']->get('user');
            $sql = 'SELECT
                      t.id as task_id,
                      t.nome as task_nome,
                      t.andamento as task_andamento,
                      u.nome as usuario_nome
                      FROM task as t join usuario as u on u.id = t.usuario WHERE u.id = ?';
            $tasks = $app['db']->fetchAll($sql, [$usuario['id']]);

            return $app["twig"]->render('Task/listar.twig', [
                'tituloPainel' => 'Listagem de tasks',
                'tasks' => $tasks
            ]);
        });

        $controllers->get('/visualizar/{task}', function (Request $request) use ($app) {
            if (null === $user = $app['session']->get('user')) {
                return $app->redirect('/login');
            }

            $sql = 'SELECT * FROM task where id = ?';
            $task = $app['db']->fetchAssoc($sql, array((int) $request->get('task')));
            $usuarios = $app['db']->fetchAll('SELECT * FROM usuario');

            return $app["twig"]->render('Task/visualizar.twig', [
                'tituloPainel' => 'Formulario',
                'pagina' => 'Dados da Task',
                'task' => $task,
                'usuarios' => $usuarios
            ]);
        });

        $controllers->get('/editar/{task}', function (Request $request) use ($app) {
            if (null === $user = $app['session']->get('user')) {
                return $app->redirect('/login');
            }

            $sql = 'SELECT * FROM task where id = ?';
            $task = $app['db']->fetchAssoc($sql, array((int) $request->get('task')));
            $usuarios = $app['db']->fetchAll('SELECT * FROM usuario');

            return $app["twig"]->render('Task/editar.twig', [
                'tituloPainel' => 'Formulario',
                'pagina' => 'Dados da Task',
                'task' => $task,
                'usuarios' => $usuarios
            ]);
        });

        $controllers->post('/editar', function (Request $request) use ($app) {
            if (null === $user = $app['session']->get('user')) {
                return $app->redirect('/login');
            }

            $sql = "UPDATE task SET nome = ?, descricao = ?, andamento = ?, usuario = ? WHERE id = ?";
            $taskRequest = $request->request->all();
            $taskRequest['id'] = (int) $taskRequest['id'];

            $status = $app['db']->executeUpdate($sql, [
                $request->get('nome'),
                $request->get('descricao'),
                $request->get('andamento'),
                $request->get('usuario'),
                (int) $request->get('id')
            ]);

            $this->mensagem($app, $status);

            return $app->redirect('/task/editar/'.$request->get('id'));
        });

        return $controllers;
    }

}