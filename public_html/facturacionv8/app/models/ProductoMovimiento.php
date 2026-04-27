<?php

class ProductoMovimiento extends \Phalcon\Mvc\Model
{

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
     * @var string
     */
    public $tipo;

    /**
     *
     * @var integer
     */
    public $id_sucursal;

    /**
     *
     * @var string
     */
    public $fecha_movimiento;

    /**
     *
     * @var integer
     */
    public $id_usuario;

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
     * @var integer
     */
    public $destino_id_sucursal;

    /**
     *
     * @var integer
     */
    public $destino_id_contribuyente;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("humbertoguadalup_bd_sys_facturacion");
        $this->setSource("producto_movimiento");
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return ProductoMovimiento[]|ProductoMovimiento|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null): \Phalcon\Mvc\Model\ResultsetInterface
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return ProductoMovimiento|\Phalcon\Mvc\Model\ResultInterface|\Phalcon\Mvc\ModelInterface|null
     */
    public static function findFirst($parameters = null): ?\Phalcon\Mvc\ModelInterface
    {
        return parent::findFirst($parameters);
    }

}
