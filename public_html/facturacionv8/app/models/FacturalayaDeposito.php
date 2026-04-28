<?php

class FacturalayaDeposito extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $id_deposito;

    /**
     *
     * @var integer
     */
    public $id_user_registro;

    /**
     *
     * @var integer
     */
    public $id_user_validacion;

    /**
     *
     * @var string
     */
    public $fecha_deposito;

    /**
     *
     * @var string
     */
    public $fecha_registro;

    /**
     *
     * @var string
     */
    public $fecha_validacion;

    /**
     *
     * @var integer
     */
    public $id_cuentabanco;

    /**
     *
     * @var string
     */
    public $num_operacion;

    /**
     *
     * @var double
     */
    public $monto;

    /**
     *
     * @var integer
     */
    public $id_servicio;

    /**
     *
     * @var integer
     */
    public $id_contribuyente;

    /**
     *
     * @var string
     */
    public $id_tipodoc_electronico;

    /**
     *
     * @var string
     */
    public $serie_comprobante;

    /**
     *
     * @var integer
     */
    public $numero_comprobante;

    /**
     *
     * @var string
     */
    public $tipo_envio_sunat;

    /**
     *
     * @var string
     */
    public $estado_validacion;

    /**
     *
     * @var string
     */
    public $estado_registro;

    /**
     *
     * @var string
     */
    public $nuevo_cliente;

    /**
     *
     * @var integer
     */
    public $mes_contabilidad;

    /**
     *
     * @var integer
     */
    public $anio_contabilidad;

    /**
     *
     * @var string
     */
    public $ingresa_reparticion;

    /**
     *
     * @var string
     */
    public $detalle;

    /**
     *
     * @var string
     */
    public $nombre_servicio;

    /**
     *
     * @var integer
     */
    public $idcliente;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema(APP_DB_SCHEMA);
        $this->setSource("facturalaya_deposito");
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return FacturalayaDeposito[]|FacturalayaDeposito|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null): \Phalcon\Mvc\Model\ResultsetInterface
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return FacturalayaDeposito|\Phalcon\Mvc\Model\ResultInterface|\Phalcon\Mvc\ModelInterface|null
     */
    public static function findFirst($parameters = null): ?\Phalcon\Mvc\ModelInterface
    {
        return parent::findFirst($parameters);
    }

}
