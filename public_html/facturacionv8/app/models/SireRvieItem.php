<?php

class SireRvieItem extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $id;

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
     * @var string
     */
    public $periodo_anio;

    /**
     *
     * @var string
     */
    public $periodo_mes;

    /**
     *
     * @var string
     */
    public $num_ticket;

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
    public $periodo;

    /**
     *
     * @var string
     */
    public $car_sunat;

    /**
     *
     * @var string
     */
    public $fecha_emision;

    /**
     *
     * @var string
     */
    public $fecha_vcto_pago;

    /**
     *
     * @var string
     */
    public $tipo_cp_doc;

    /**
     *
     * @var string
     */
    public $serie_cdp;

    /**
     *
     * @var string
     */
    public $nro_cp_doc_inicial;

    /**
     *
     * @var string
     */
    public $nro_cp_doc_final;

    /**
     *
     * @var string
     */
    public $tipo_doc_identidad;

    /**
     *
     * @var string
     */
    public $nro_doc_identidad;

    /**
     *
     * @var string
     */
    public $apellidos_nombres_razon_social;

    /**
     *
     * @var double
     */
    public $valor_facturado_exportacion;

    /**
     *
     * @var double
     */
    public $bi_gravada;

    /**
     *
     * @var double
     */
    public $dscto_bi;

    /**
     *
     * @var double
     */
    public $igv_ipm;

    /**
     *
     * @var double
     */
    public $dscto_igv_ipm;

    /**
     *
     * @var double
     */
    public $mto_exonerado;

    /**
     *
     * @var double
     */
    public $mto_inafecto;

    /**
     *
     * @var double
     */
    public $isc;

    /**
     *
     * @var double
     */
    public $bi_grav_ivap;

    /**
     *
     * @var double
     */
    public $ivap;

    /**
     *
     * @var double
     */
    public $icbper;

    /**
     *
     * @var double
     */
    public $otros_tributos;

    /**
     *
     * @var double
     */
    public $total_cp;

    /**
     *
     * @var string
     */
    public $moneda;

    /**
     *
     * @var double
     */
    public $tipo_cambio;

    /**
     *
     * @var string
     */
    public $fecha_emision_doc_modificado;

    /**
     *
     * @var string
     */
    public $tipo_cp_modificado;

    /**
     *
     * @var string
     */
    public $serie_cp_modificado;

    /**
     *
     * @var string
     */
    public $nro_cp_modificado;

    /**
     *
     * @var string
     */
    public $id_proyecto_operadores_atribucion;

    /**
     *
     * @var string
     */
    public $tipo_de_nota;

    /**
     *
     * @var string
     */
    public $est_comp;

    /**
     *
     * @var double
     */
    public $valor_fob_embarcado;

    /**
     *
     * @var double
     */
    public $valor_op_gratuitas;

    /**
     *
     * @var string
     */
    public $tipo_operacion;

    /**
     *
     * @var string
     */
    public $dam_cp;

    /**
     *
     * @var string
     */
    public $clu;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema(APP_DB_SCHEMA);
        $this->setSource("sire_rvie_item");
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return SireRvieItem[]|SireRvieItem|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null): \Phalcon\Mvc\Model\ResultsetInterface
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return SireRvieItem|\Phalcon\Mvc\Model\ResultInterface|\Phalcon\Mvc\ModelInterface|null
     */
    public static function findFirst($parameters = null): ?\Phalcon\Mvc\ModelInterface
    {
        return parent::findFirst($parameters);
    }

}
