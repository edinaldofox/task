<?php

namespace Controller;


class ControllerConf
{

    public function mensagem($app, $status, $mensagem)
    {
        $tipoSucesso = 'success';
        $mensagemSucesso = 'Dados Salvos com sucesso.';

        $tipoErro = 'danger';
        $mensagemErro = 'Não foi possivel salvar os dados.';

        if ($status) {
            $app['session']->getFlashBag()->add('mensagem', [
                'tipo' => $tipoSucesso,
                'mensagem' => empty($mensagem)?$mensagemSucesso:$mensagem
            ]);
        } else {
            $app['session']->getFlashBag()->add('mensagem', [
                'tipo' => $tipoErro,
                'mensagem' => empty($mensagem)?$mensagemErro:$mensagem
            ]);
        }
    }
}
