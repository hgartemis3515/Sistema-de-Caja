<?php

class Suscripcion extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $id_suscripcion;

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
    public $fecha_registro;

    /**
     *
     * @var string
     */
    public $fecha_inicio;

    /**
     *
     * @var string
     */
    public $fecha_fin;

    /**
     *
     * @var integer
     */
    public $limite_mes_doc;

    /**
     *
     * @var double
     */
    public $total;

    /**
     *
     * @var string
     */
    public $condicion_pago;

    /**
     *
     * @var string
     */
    public $tipo_condicionpago;

    /**
     *
     * @var string
     */
    public $num_transaccion;

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
    public $pago_verificado;

    /**
     *
     * @var string
     */
    public $nota_pago_verificado;

    /**
     *
     * @var integer
     */
    public $id_suscripcion_culqi;

    /**
     *
     * @var string
     */
    public $id_charge_culqi;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("humbertoguadalup_bd_sys_facturacion");
        $this->setSource("suscripcion");
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Suscripcion[]|Suscripcion|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null): \Phalcon\Mvc\Model\ResultsetInterface
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Suscripcion|\Phalcon\Mvc\Model\ResultInterface|\Phalcon\Mvc\ModelInterface|null
     */
    public static function findFirst($parameters = null): ?\Phalcon\Mvc\ModelInterface
    {
        return parent::findFirst($parameters);
    }

}
