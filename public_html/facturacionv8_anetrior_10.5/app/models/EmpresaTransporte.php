<?php

class EmpresaTransporte extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $id_etransporte;

    /**
     *
     * @var integer
     */
    public $id_contribuyente;

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
    public $razon_social;

    /**
     *
     * @var string
     */
    public $codigo_ubigeo;

    /**
     *
     * @var string
     */
    public $direccion;

    /**
     *
     * @var string
     */
    public $telefono;

    /**
     *
     * @var string
     */
    public $nota;

    /**
     *
     * @var string
     */
    public $estado;

    /**
     *
     * @var string
     */
    public $tipo_traslado;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("humbertoguadalup_bd_sys_facturacion");
        $this->setSource("empresa_transporte");
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return EmpresaTransporte[]|EmpresaTransporte|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null): \Phalcon\Mvc\Model\ResultsetInterface
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return EmpresaTransporte|\Phalcon\Mvc\Model\ResultInterface|\Phalcon\Mvc\ModelInterface|null
     */
    public static function findFirst($parameters = null): ?\Phalcon\Mvc\ModelInterface
    {
        return parent::findFirst($parameters);
    }

}
