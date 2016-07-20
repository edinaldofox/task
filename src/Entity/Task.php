<?php

namespace Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Task
 * @ORM\Table(name="task")
 */
class Task
{

    /**
     * @var bigint $id
     *
     * @ORM\Column(name="id", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var string $nome
     *
     * @ORM\Column(name="nome", type="string", nullable=false)
     */
    protected $nome;

    /**
     * @var datetime $inicio
     *
     * @ORM\Column(name="email", type="datetime", nullable=false)
     */
    protected $inicio;

    /**
     * @var datetime $termino
     *
     * @ORM\Column(name="termino", type="datetime", nullable=true)
     */
    protected $termino;

    /**
     * @var integer $porcentagem
     *
     * @ORM\Column(name="porcentagem", type="integer", nullable=false)
     */
    protected $porcentagem;

    /**
     * @return bigint
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param bigint $id
     * @return Task
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getNome()
    {
        return $this->nome;
    }

    /**
     * @param string $nome
     * @return Task
     */
    public function setNome($nome)
    {
        $this->nome = $nome;
        return $this;
    }

    /**
     * @return datetime
     */
    public function getInicio()
    {
        return $this->inicio;
    }

    /**
     * @param datetime $inicio
     * @return Task
     */
    public function setInicio($inicio)
    {
        $this->inicio = $inicio;
        return $this;
    }

    /**
     * @return datetime
     */
    public function getTermino()
    {
        return $this->termino;
    }

    /**
     * @param datetime $termino
     * @return Task
     */
    public function setTermino($termino)
    {
        $this->termino = $termino;
        return $this;
    }

    /**
     * @return int
     */
    public function getPorcentagem()
    {
        return $this->porcentagem;
    }

    /**
     * @param int $porcentagem
     * @return Task
     */
    public function setPorcentagem($porcentagem)
    {
        $this->porcentagem = $porcentagem;
        return $this;
    }

}