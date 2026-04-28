<?php

class SireRceItem extends \Phalcon\Mvc\Model
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
    public $anio;

    /**
     *
     * @var string
     */
    public $nro_cp_doc_inicial;

    /**
     *
     * @var string
     */
    public $nro_final_rango;

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
    public $bi_gravado_dg;

    /**
     *
     * @var double
     */
    public $igv_ipm_dg;

    /**
     *
     * @var double
     */
    public $bi_gravado_dgng;

    /**
     *
     * @var double
     */
    public $igv_ipm_dgng;

    /**
     *
     * @var double
     */
    public $bi_gravado_dng;

    /**
     *
     * @var double
     */
    public $igv_ipm_dng;

    /**
     *
     * @var double
     */
    public $valor_adq_ng;

    /**
     *
     * @var double
     */
    public $isc;

    /**
     *
     * @var double
     */
    public $icbper;

    /**
     *
     * @var double
     */
    public $otros_trib_cargos;

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
    public $cod_dam_dsi;

    /**
     *
     * @var string
     */
    public $nro_cp_modificado;

    /**
     *
     * @var string
     */
    public $clasif_bss_sss;

    /**
     *
     * @var string
     */
    public $id_proyecto_operadores;

    /**
     *
     * @var double
     */
    public $porcpart;

    /**
     *
     * @var double
     */
    public $imb;

    /**
     *
     * @var string
     */
    public $car_orig_ind_e_o_i;

    /**
     *
     * @var double
     */
    public $detraccion;

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
     * @var string
     */
    public $clu1;

    /**
     *
     * @var string
     */
    public $clu2;

    /**
     *
     * @var string
     */
    public $clu3;

    /**
     *
     * @var string
     */
    public $clu4;

    /**
     *
     * @var string
     */
    public $clu5;

    /**
     *
     * @var string
     */
    public $clu6;

    /**
     *
     * @var string
     */
    public $clu7;

    /**
     *
     * @var string
     */
    public $clu8;

    /**
     *
     * @var string
     */
    public $clu9;

    /**
     *
     * @var string
     */
    public $clu10;

    /**
     *
     * @var string
     */
    public $clu11;

    /**
     *
     * @var string
     */
    public $clu12;

    /**
     *
     * @var string
     */
    public $clu13;

    /**
     *
     * @var string
     */
    public $clu14;

    /**
     *
     * @var string
     */
    public $clu15;

    /**
     *
     * @var string
     */
    public $clu16;

    /**
     *
     * @var string
     */
    public $clu17;

    /**
     *
     * @var string
     */
    public $clu18;

    /**
     *
     * @var string
     */
    public $clu19;

    /**
     *
     * @var string
     */
    public $clu20;

    /**
     *
     * @var string
     */
    public $clu21;

    /**
     *
     * @var string
     */
    public $clu22;

    /**
     *
     * @var string
     */
    public $clu23;

    /**
     *
     * @var string
     */
    public $clu24;

    /**
     *
     * @var string
     */
    public $clu25;

    /**
     *
     * @var string
     */
    public $clu26;

    /**
     *
     * @var string
     */
    public $clu27;

    /**
     *
     * @var string
     */
    public $clu28;

    /**
     *
     * @var string
     */
    public $clu29;

    /**
     *
     * @var string
     */
    public $clu30;

    /**
     *
     * @var string
     */
    public $clu31;

    /**
     *
     * @var string
     */
    public $clu32;

    /**
     *
     * @var string
     */
    public $clu33;

    /**
     *
     * @var string
     */
    public $clu34;

    /**
     *
     * @var string
     */
    public $clu35;

    /**
     *
     * @var string
     */
    public $clu36;

    /**
     *
     * @var string
     */
    public $clu37;

    /**
     *
     * @var string
     */
    public $clu38;

    /**
     *
     * @var string
     */
    public $clu39;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema(APP_DB_SCHEMA);
        $this->setSource("sire_rce_item");
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return SireRceItem[]|SireRceItem|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null): \Phalcon\Mvc\Model\ResultsetInterface
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return SireRceItem|\Phalcon\Mvc\Model\ResultInterface|\Phalcon\Mvc\ModelInterface|null
     */
    public static function findFirst($parameters = null): ?\Phalcon\Mvc\ModelInterface
    {
        return parent::findFirst($parameters);
    }

}
