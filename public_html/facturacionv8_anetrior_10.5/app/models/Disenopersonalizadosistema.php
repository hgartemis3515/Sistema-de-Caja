<?php

class Disenopersonalizadosistema extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $id_diseno;

    /**
     *
     * @var string
     */
    public $nombre;

    /**
     *
     * @var string
     */
    public $modulo;

    /**
     *
     * @var string
     */
    public $categoria;

    /**
     *
     * @var string
     */
    public $enlace;

    /**
     *
     * @var string
     */
    public $preview;

    /**
     *
     * @var string
     */
    public $estado;

    /**
     *
     * @var string
     */
    public $tipo;

    /**
     *
     * @var integer
     */
    public $id_contribuyente;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("humbertoguadalup_bd_sys_facturacion");
        $this->setSource("disenopersonalizadosistema");
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Disenopersonalizadosistema[]|Disenopersonalizadosistema|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null): \Phalcon\Mvc\Model\ResultsetInterface
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Disenopersonalizadosistema|\Phalcon\Mvc\Model\ResultInterface|\Phalcon\Mvc\ModelInterface|null
     */
    public static function findFirst($parameters = null): ?\Phalcon\Mvc\ModelInterface
    {
        return parent::findFirst($parameters);
    }

}
