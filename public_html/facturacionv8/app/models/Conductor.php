<?php

class Conductor extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $idconductor;

    /**
     *
     * @var integer
     */
    public $id_etransporte;

    /**
     *
     * @var string
     */
    public $id_tipodocidentidad;

    /**
     *
     * @var string
     */
    public $num_doc;

    /**
     *
     * @var string
     */
    public $nombre_completo;

    /**
     *
     * @var string
     */
    public $licencia_conducir;

    /**
     *
     * @var string
     */
    public $tipo_licencia;

    /**
     *
     * @var string
     */
    public $telefono;

    /**
     *
     * @var string
     */
    public $estado;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("humbertoguadalup_bd_sys_facturacion");
        $this->setSource("conductor");
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Conductor[]|Conductor|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null): \Phalcon\Mvc\Model\ResultsetInterface
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Conductor|\Phalcon\Mvc\Model\ResultInterface|\Phalcon\Mvc\ModelInterface|null
     */
    public static function findFirst($parameters = null): ?\Phalcon\Mvc\ModelInterface
    {
        return parent::findFirst($parameters);
    }

}
