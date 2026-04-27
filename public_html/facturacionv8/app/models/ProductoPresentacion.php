<?php

class ProductoPresentacion extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $id_presentacion;

    /**
     *
     * @var integer
     */
    public $idproducto;

    /**
     *
     * @var integer
     */
    public $idunidad;

    /**
     *
     * @var string
     */
    public $codigo;

    /**
     *
     * @var string
     */
    public $nombre;

    /**
     *
     * @var integer
     */
    public $idunidad_base;

    /**
     *
     * @var double
     */
    public $precio_con_igv;

    /**
     *
     * @var double
     */
    public $precio_sin_igv;

    /**
     *
     * @var double
     */
    public $cantidad_und_base;

    /**
     *
     * @var string
     */
    public $estado;

    /**
     *
     * @var string
     */
    public $fecha_registro;

    /**
     *
     * @var double
     */
    public $precio_minimo;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("humbertoguadalup_bd_sys_facturacion");
        $this->setSource("producto_presentacion");
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return ProductoPresentacion[]|ProductoPresentacion|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null): \Phalcon\Mvc\Model\ResultsetInterface
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return ProductoPresentacion|\Phalcon\Mvc\Model\ResultInterface|\Phalcon\Mvc\ModelInterface|null
     */
    public static function findFirst($parameters = null): ?\Phalcon\Mvc\ModelInterface
    {
        return parent::findFirst($parameters);
    }

}
