<?php

class Movimientocaja extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $id_movimiento;

    /**
     *
     * @var integer
     */
    public $id_contribuyente;

    /**
     *
     * @var string
     */
    public $tipo_envio_sunat;

    /**
     *
     * @var integer
     */
    public $id_sucursal;

    /**
     *
     * @var integer
     */
    public $id_vendedor;

    /**
     *
     * @var string
     */
    public $fecha_registro;

    /**
     *
     * @var string
     */
    public $fecha_movimiento;

    /**
     *
     * @var string
     */
    public $descripcion;

    /**
     *
     * @var string
     */
    public $moneda;

    /**
     *
     * @var double
     */
    public $monto;

    /**
     *
     * @var string
     */
    public $tipo_movimiento;

    /**
     *
     * @var string
     */
    public $detalle;

    /**
     *
     * @var string
     */
    public $ruc_comprobante;

    /**
     *
     * @var string
     */
    public $tipo_comprobante;

    /**
     *
     * @var string
     */
    public $serie_num_comprobante;

    /**
     *
     * @var string
     */
    public $estado;

    /**
     *
     * @var integer
     */
    public $id_condicionpago;

    /**
     *
     * @var string
     */
    public $cpago_nrooperacion;

    /**
     *
     * @var string
     */
    public $fechadeposito;

    /**
     *
     * @var integer
     */
    public $idbanco;

    /**
     *
     * @var integer
     */
    public $id_proveedor;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("humbertoguadalup_bd_sys_facturacion");
        $this->setSource("movimientocaja");
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Movimientocaja[]|Movimientocaja|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null): \Phalcon\Mvc\Model\ResultsetInterface
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Movimientocaja|\Phalcon\Mvc\Model\ResultInterface|\Phalcon\Mvc\ModelInterface|null
     */
    public static function findFirst($parameters = null): ?\Phalcon\Mvc\ModelInterface
    {
        return parent::findFirst($parameters);
    }

}
