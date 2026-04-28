<?php

class DocNoOficial extends \Phalcon\Mvc\Model
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
    public $id_tipodocumento;

    /**
     *
     * @var integer
     */
    public $numero_comprobante;

    /**
     *
     * @var string
     */
    public $modalidad;

    /**
     *
     * @var double
     */
    public $total_gravadas;

    /**
     *
     * @var double
     */
    public $total_inafecta;

    /**
     *
     * @var double
     */
    public $total_exoneradas;

    /**
     *
     * @var double
     */
    public $total_gratuitas;

    /**
     *
     * @var double
     */
    public $total_exportacion;

    /**
     *
     * @var double
     */
    public $total_icbper;

    /**
     *
     * @var double
     */
    public $total_descuento;

    /**
     *
     * @var double
     */
    public $porcentaje_descuento_total;

    /**
     *
     * @var double
     */
    public $sub_total;

    /**
     *
     * @var double
     */
    public $porcentaje_igv;

    /**
     *
     * @var double
     */
    public $impuesto_icbper;

    /**
     *
     * @var double
     */
    public $total_igv;

    /**
     *
     * @var double
     */
    public $total;

    /**
     *
     * @var string
     */
    public $total_letras;

    /**
     *
     * @var string
     */
    public $fecha_comprobante;

    /**
     *
     * @var string
     */
    public $fecha_vto_comprobante;

    /**
     *
     * @var string
     */
    public $id_codigomoneda;

    /**
     *
     * @var double
     */
    public $tipo_cambio_sunat;

    /**
     *
     * @var integer
     */
    public $idcliente;

    /**
     *
     * @var integer
     */
    public $id_vendedor;

    /**
     *
     * @var integer
     */
    public $id_sucursal;

    /**
     *
     * @var string
     */
    public $fecha_registro;

    /**
     *
     * @var string
     */
    public $nota;

    /**
     *
     * @var integer
     */
    public $id_condicionpago;

    /**
     *
     * @var double
     */
    public $monto_adeudado;

    /**
     *
     * @var double
     */
    public $monto_adeudado_inicial;

    /**
     *
     * @var string
     */
    public $log_condicion_pago;

    /**
     *
     * @var string
     */
    public $fecha_pagopendiente;

    /**
     *
     * @var string
     */
    public $cpago_nrooperacion;

    /**
     *
     * @var string
     */
    public $cpago_fechadeposito;

    /**
     *
     * @var integer
     */
    public $cpago_idbanco;

    /**
     *
     * @var string
     */
    public $estado_documento;

    /**
     *
     * @var string
     */
    public $transporte_nro_placa;

    /**
     *
     * @var string
     */
    public $nro_otr_comprobante;

    /**
     *
     * @var string
     */
    public $tipo;

    /**
     *
     * @var integer
     */
    public $idsucursal_origen;

    /**
     *
     * @var integer
     */
    public $idsucursal_destino;

    /**
     *
     * @var string
     */
    public $id_ubigeo_destino;

    /**
     *
     * @var string
     */
    public $dir_destino;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema(APP_DB_SCHEMA);
        $this->setSource("doc_no_oficial");
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return DocNoOficial[]|DocNoOficial|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null): \Phalcon\Mvc\Model\ResultsetInterface
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return DocNoOficial|\Phalcon\Mvc\Model\ResultInterface|\Phalcon\Mvc\ModelInterface|null
     */
    public static function findFirst($parameters = null): ?\Phalcon\Mvc\ModelInterface
    {
        return parent::findFirst($parameters);
    }

}
