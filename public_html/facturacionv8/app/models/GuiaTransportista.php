<?php

class GuiaTransportista extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $id_contribuyente;

    /**
     *
     * @var integer
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
    public $nota;

    /**
     *
     * @var string
     */
    public $fecha_traslado;

    /**
     *
     * @var integer
     */
    public $id_unidad_medida;

    /**
     *
     * @var string
     */
    public $unidad_medida;

    /**
     *
     * @var double
     */
    public $peso_total;

    /**
     *
     * @var string
     */
    public $transporte_nro_placa;

    /**
     *
     * @var string
     */
    public $num_registro_mtc;

    /**
     *
     * @var string
     */
    public $tuc_vehiculo_principal;

    /**
     *
     * @var string
     */
    public $conductor_tipo_documento;

    /**
     *
     * @var string
     */
    public $conductor_nro_documento;

    /**
     *
     * @var string
     */
    public $conductor_nombre_completo;

    /**
     *
     * @var string
     */
    public $conductor_nro_licencia;

    /**
     *
     * @var string
     */
    public $dest_tipo_documento;

    /**
     *
     * @var string
     */
    public $dest_numero_documento;

    /**
     *
     * @var string
     */
    public $dest_nombre_completo;

    /**
     *
     * @var string
     */
    public $id_ubigeo_partida;

    /**
     *
     * @var string
     */
    public $dir_partida;

    /**
     *
     * @var string
     */
    public $codigo_local_anexo;

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
     *
     * @var string
     */
    public $fecha_envio_sunat;

    /**
     *
     * @var string
     */
    public $estado_envio_sunat;

    /**
     *
     * @var string
     */
    public $hash_cpe;

    /**
     *
     * @var string
     */
    public $hash_cdr;

    /**
     *
     * @var string
     */
    public $cod_sunat;

    /**
     *
     * @var string
     */
    public $msje_sunat;

    /**
     *
     * @var string
     */
    public $ruta_xml;

    /**
     *
     * @var string
     */
    public $name_xml_zip;

    /**
     *
     * @var string
     */
    public $name_cdr;

    /**
     *
     * @var string
     */
    public $name_cdr_zip;

    /**
     *
     * @var integer
     */
    public $intentos_envio_sunat;

    /**
     *
     * @var string
     */
    public $estado_documento;

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
     * @var integer
     */
    public $numero_paquetes;

    /**
     *
     * @var string
     */
    public $num_ticket;

    /**
     *
     * @var string
     */
    public $fec_recepcion_ticket;

    /**
     *
     * @var string
     */
    public $cod_respuesta;

    /**
     *
     * @var string
     */
    public $num_error;

    /**
     *
     * @var string
     */
    public $desc_error;

    /**
     *
     * @var string
     */
    public $desc_success;

    /**
     *
     * @var string
     */
    public $ruta_qr;

    /**
     *
     * @var string
     */
    public $observaciones;

    /**
     *
     * @var string
     */
    public $codigo_local_partida;

    /**
     *
     * @var string
     */
    public $codigo_local_llegada;

    /**
     *
     * @var string
     */
    public $remitente_tipo_documento;

    /**
     *
     * @var string
     */
    public $remitente_numero_documento;

    /**
     *
     * @var string
     */
    public $remitente_nombre_completo;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema(APP_DB_SCHEMA);
        $this->setSource("guia_transportista");
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return GuiaTransportista[]|GuiaTransportista|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null): \Phalcon\Mvc\Model\ResultsetInterface
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return GuiaTransportista|\Phalcon\Mvc\Model\ResultInterface|\Phalcon\Mvc\ModelInterface|null
     */
    public static function findFirst($parameters = null): ?\Phalcon\Mvc\ModelInterface
    {
        return parent::findFirst($parameters);
    }

}
