<?php

class ProductoMovimientoDet extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $id_movimiento_det;

    /**
     *
     * @var integer
     */
    public $id_contribuyente;

    /**
     *
     * @var string
     */
    public $id_tipo_movimiento;

    /**
     *
     * @var string
     */
    public $serie;

    /**
     *
     * @var integer
     */
    public $correlativo;

    /**
     *
     * @var string
     */
    public $tipo_envio_sunat;

    /**
     *
     * @var integer
     */
    public $item;

    /**
     *
     * @var double
     */
    public $cantidad;

    /**
     *
     * @var double
     */
    public $costo_unitario;

    /**
     *
     * @var string
     */
    public $id_codigomoneda;

    /**
     *
     * @var integer
     */
    public $o_id_u_medida;

    /**
     *
     * @var string
     */
    public $o_u_medida;

    /**
     *
     * @var double
     */
    public $o_precio;

    /**
     *
     * @var string
     */
    public $o_id_afectigv;

    /**
     *
     * @var string
     */
    public $o_tipo_unidad;

    /**
     *
     * @var integer
     */
    public $o_id_presentacion;

    /**
     *
     * @var string
     */
    public $o_cod_prod;

    /**
     *
     * @var integer
     */
    public $o_id_prod;

    /**
     *
     * @var string
     */
    public $o_nom_prod;

    /**
     *
     * @var integer
     */
    public $d_id_u_medida;

    /**
     *
     * @var string
     */
    public $d_u_medida;

    /**
     *
     * @var double
     */
    public $d_precio;

    /**
     *
     * @var string
     */
    public $d_id_afectigv;

    /**
     *
     * @var string
     */
    public $d_tipo_unidad;

    /**
     *
     * @var integer
     */
    public $d_id_presentacion;

    /**
     *
     * @var string
     */
    public $d_cod_prod;

    /**
     *
     * @var integer
     */
    public $d_id_prod;

    /**
     *
     * @var string
     */
    public $d_nom_prod;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("humbertoguadalup_bd_sys_facturacion");
        $this->setSource("producto_movimiento_det");
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return ProductoMovimientoDet[]|ProductoMovimientoDet|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null): \Phalcon\Mvc\Model\ResultsetInterface
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return ProductoMovimientoDet|\Phalcon\Mvc\Model\ResultInterface|\Phalcon\Mvc\ModelInterface|null
     */
    public static function findFirst($parameters = null): ?\Phalcon\Mvc\ModelInterface
    {
        return parent::findFirst($parameters);
    }

}
