<?php

class Prospecto extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $id_prospecto;

    /**
     *
     * @var integer
     */
    public $id_contribuyente;

    /**
     *
     * @var integer
     */
    public $id_sucursal;

    /**
     *
     * @var integer
     */
    public $iduserpage;

    /**
     *
     * @var string
     */
    public $ruc;

    /**
     *
     * @var string
     */
    public $razon_social;

    /**
     *
     * @var string
     */
    public $nombre_contacto;

    /**
     *
     * @var string
     */
    public $fecha_registro;

    /**
     *
     * @var string
     */
    public $estado;

    /**
     *
     * @var string
     */
    public $etiquetas;

    /**
     *
     * @var string
     */
    public $correos;

    /**
     *
     * @var string
     */
    public $fecha_expira_oferta;

    /**
     *
     * @var string
     */
    public $telefonos;

    /**
     *
     * @var string
     */
    public $nota;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("humbertoguadalup_bd_sys_facturacion");
        $this->setSource("prospecto");
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Prospecto[]|Prospecto|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null): \Phalcon\Mvc\Model\ResultsetInterface
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Prospecto|\Phalcon\Mvc\Model\ResultInterface|\Phalcon\Mvc\ModelInterface|null
     */
    public static function findFirst($parameters = null): ?\Phalcon\Mvc\ModelInterface
    {
        return parent::findFirst($parameters);
    }

}
