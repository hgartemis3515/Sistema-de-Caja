<?php

class OrdenCompra extends \Phalcon\Mvc\Model
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
     * @var integer
     */
    public $idsucursal;

    /**
     *
     * @var integer
     */
    public $id_proveedor;

    /**
     *
     * @var string
     */
    public $fecha_registro;

    /**
     *
     * @var string
     */
    public $fecha_comprobante;

    /**
     *
     * @var string
     */
    public $estado;

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
    public $total_igv;

    /**
     *
     * @var double
     */
    public $total_isc;

    /**
     *
     * @var double
     */
    public $total_icbper;

    /**
     *
     * @var double
     */
    public $total_otr_imp;

    /**
     *
     * @var double
     */
    public $total;

    /**
     *
     * @var integer
     */
    public $nro_guia_remision;

    /**
     *
     * @var string
     */
    public $cod_guia_remision;

    /**
     *
     * @var string
     */
    public $nro_otr_comprobante;

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
    public $fecha_vto_comprobante;

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
    public $tipo_compra;

    /**
     *
     * @var string
     */
    public $log_condicion_pago;

    /**
     *
     * @var string
     */
    public $log;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("humbertoguadalup_bd_sys_facturacion");
        $this->setSource("orden_compra");
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return OrdenCompra[]|OrdenCompra|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null): \Phalcon\Mvc\Model\ResultsetInterface
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return OrdenCompra|\Phalcon\Mvc\Model\ResultInterface|\Phalcon\Mvc\ModelInterface|null
     */
    public static function findFirst($parameters = null): ?\Phalcon\Mvc\ModelInterface
    {
        return parent::findFirst($parameters);
    }

}
