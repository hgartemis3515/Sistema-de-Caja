<?php

class SuscripcionCulqi extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $id_suscripcion_culqi;

    /**
     *
     * @var integer
     */
    public $id_contribuyente;

    /**
     *
     * @var integer
     */
    public $id_planreseller;

    /**
     *
     * @var string
     */
    public $id_suscripcion;

    /**
     *
     * @var string
     */
    public $fecha_registro;

    /**
     *
     * @var string
     */
    public $fecha_modificacion;

    /**
     *
     * @var integer
     */
    public $id_usuario_registro;

    /**
     *
     * @var integer
     */
    public $id_usuario_modifica;

    /**
     *
     * @var double
     */
    public $monto;

    /**
     *
     * @var integer
     */
    public $periodo_prueba;

    /**
     *
     * @var string
     */
    public $fecha_pago;

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
        $this->setSource("suscripcion_culqi");
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return SuscripcionCulqi[]|SuscripcionCulqi|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null): \Phalcon\Mvc\Model\ResultsetInterface
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return SuscripcionCulqi|\Phalcon\Mvc\Model\ResultInterface|\Phalcon\Mvc\ModelInterface|null
     */
    public static function findFirst($parameters = null): ?\Phalcon\Mvc\ModelInterface
    {
        return parent::findFirst($parameters);
    }

}
