<?php

namespace Controller;

use Silex\Application;
use Silex\Api\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;

class UsuarioController extends ControllerConf implements ControllerProviderInterface
{

    public function connect(Application $app)
    {

        // creates a new controller based on the default route
        $controllers = $app['controllers_factory'];

        $controllers->get('/cadastro', function (Request $request) use ($app) {
            if (null === $user = $app['session']->get('user')) {
                return $app->redirect('/login');
            }

            return $app["twig"]->render('Usuario/cadastro.twig', [
                'tituloPainel' => 'Formulario de Cadastro do Usuário',
                'pagina' => 'Dados Usuário'
            ]);
        });

        $controllers->post('/cadastro', function (Request $request) use ($app) {
            if (null === $user = $app['session']->get('user')) {
                return $app->redirect('/login');
            }
            $status = true;
            $usuario = $request->request->all();

            if($request->get('senha') != $request->get('senha_re')) {
                $status = false;
                $this->mensagem($app, $status, "Senha não confere");
            } else if (!$this->validaEmail($app, $usuario['email'])) {
                $status = false;
                $this->mensagem($app, $status, "Este email já esta cadastrado.");
            } else {
                $usuario['senha'] = md5($usuario['senha']);
                unset($usuario['senha_re']);

                $status = $app['db']->insert('usuario', $usuario);
            }

            if(!$status) {

                return $app["twig"]->render('Usuario/cadastro.twig', [
                    'tituloPainel' => 'Formulario de Cadastro do Usuário',
                    'pagina' => 'Dados Usuário',
                    'usuario' => $usuario
                ]);
            }

            $this->mensagem($app, 1);
            return $app->redirect('/usuario/cadastro');

        });

        $controllers->get('/listar', function (Request $request) use ($app) {
            if (null === $user = $app['session']->get('user')) {
                return $app->redirect('/login');
            }

            $sql = $app['db']->fetchAll('SELECT * FROM usuario');

            return $app["twig"]->render('Usuario/listar.twig', [
                    'usuarios' => $sql
                ]);
        });

        $controllers->get('/visualizar/{usuario}', function (Request $request) use ($app) {
            if (null === $user = $app['session']->get('user')) {
                return $app->redirect('/login');
            }

            $sql = 'SELECT * FROM usuario where id = ?';
            $usuario = $app['db']->fetchAssoc($sql, array((int) $request->get('usuario')));

            return $app["twig"]->render('Usuario/visualizar.twig', [
                'tituloPainel' => 'Formulario',
                'pagina' => 'Dados Usuário',
                'usuario' => $usuario
            ]);
        });

        $controllers->get('/editar', function (Request $request) use ($app) {
            if (null === $user = $app['session']->get('user')) {
                return $app->redirect('/login');
            }

            $usuario = $app['session']->get('user');

            $sql = 'SELECT * FROM usuario where id = ?';
            $usuario = $app['db']->fetchAssoc($sql, array((int) $usuario['id']));

            return $app["twig"]->render('Usuario/editar.twig', [
                'tituloPainel' => 'Formulario',
                'pagina' => 'Dados Usuário',
                'usuario' => $usuario
            ]);
        });

        $controllers->post('/editar', function (Request $request) use ($app) {
            if (null === $user = $app['session']->get('user')) {
                return $app->redirect('/login');
            }

            $usuarioSession = $app['session']->get('user');

            $sql = 'SELECT * FROM usuario where id = ?';
            $usuario = $app['db']->fetchAssoc($sql, array((int) $usuarioSession['id']));

            if (!$this->validaEmail($app, $request->get('email')) && $usuario['email'] != $request->get('email')) {
                $this->mensagem($app, 0, "Este email já esta cadastrado.");
                return $app->redirect('/usuario/editar');
            }

            $sql = "UPDATE usuario SET nome = ?, email = ? WHERE id = ?";

            $status = $app['db']->executeUpdate($sql, [
                $request->get('nome'),
                $request->get('email'),
                (int) $usuarioSession['id']
            ]);

            $this->mensagem($app, $status);

            return $app->redirect('/usuario/editar');
        });

        return $controllers;
    }

    public function validaEmail($app, $email) {
        $sql = 'SELECT * FROM usuario where email = ?';
        $usuario = $app['db']->fetchAssoc($sql, [$email]);
        return empty($usuario)?true:false;
    }
}