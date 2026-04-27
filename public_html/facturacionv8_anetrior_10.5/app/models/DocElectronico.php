<?php

class DocElectronico extends \Phalcon\Mvc\Model
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
     * @var string
     */
    public $id_tipo_operacion;

    /**
     *
     * @var string
     */
    public $tipo_venta;

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
    public $total_isc;

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
     * @var string
     */
    public $total_letras;

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
     * @var string
     */
    public $id_motivotraslado;

    /**
     *
     * @var string
     */
    public $motivo_traslado;

    /**
     *
     * @var double
     */
    public $peso;

    /**
     *
     * @var integer
     */
    public $numero_paquetes;

    /**
     *
     * @var string
     */
    public $id_codigopuerto;

    /**
     *
     * @var string
     */
    public $numero_contenedor;

    /**
     *
     * @var string
     */
    public $id_modalidadtraslado;

    /**
     *
     * @var string
     */
    public $modalidad_traslado;

    /**
     *
     * @var string
     */
    public $fecha_traslado;

    /**
     *
     * @var integer
     */
    public $id_vehiculo;

    /**
     *
     * @var string
     */
    public $transporte_nro_placa;

    /**
     *
     * @var integer
     */
    public $idconductor;

    /**
     *
     * @var string
     */
    public $id_tipodoc_conductor;

    /**
     *
     * @var string
     */
    public $num_doc_conductor;

    /**
     *
     * @var string
     */
    public $nombre_completo_conductor;

    /**
     *
     * @var string
     */
    public $licencia_conductor;

    /**
     *
     * @var integer
     */
    public $id_etransporte;

    /**
     *
     * @var string
     */
    public $id_tipo_documento_transporte;

    /**
     *
     * @var string
     */
    public $nro_documento_transporte;

    /**
     *
     * @var string
     */
    public $razon_social_transporte;

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
    public $array_docs_referencia;

    /**
     *
     * @var string
     */
    public $id_tipo_comprobante_modifica;

    /**
     *
     * @var string
     */
    public $serie_documento_modifica;

    /**
     *
     * @var integer
     */
    public $nro_documento_modifica;

    /**
     *
     * @var string
     */
    public $id_cod_tipomotivo_credito;

    /**
     *
     * @var string
     */
    public $descripcion_motivo_credito;

    /**
     *
     * @var string
     */
    public $id_cod_tipomotivo_debito;

    /**
     *
     * @var string
     */
    public $descripcion_motivo_debito;

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
    public $percepcion_idtipo;

    /**
     *
     * @var double
     */
    public $percepcion_montobase;

    /**
     *
     * @var double
     */
    public $percepcion_porcentaje;

    /**
     *
     * @var double
     */
    public $percepcion_monto;

    /**
     *
     * @var string
     */
    public $detraccion_id_mediopago;

    /**
     *
     * @var string
     */
    public $detraccion_cuenta;

    /**
     *
     * @var string
     */
    public $detraccion_iddetraccion;

    /**
     *
     * @var double
     */
    public $detraccion_porcentaje;

    /**
     *
     * @var double
     */
    public $detraccion_monto;

    /**
     *
     * @var string
     */
    public $detraccion_texto;

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
    public $name_xml;

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
    public $rb_id_contribuyente;

    /**
     *
     * @var string
     */
    public $rb_codigo;

    /**
     *
     * @var string
     */
    public $rb_serie;

    /**
     *
     * @var integer
     */
    public $rb_secuencia;

    /**
     *
     * @var string
     */
    public $rb_tipo_envio_sunat;

    /**
     *
     * @var string
     */
    public $ignorar_documento;

    /**
     *
     * @var string
     */
    public $credito_sunat;

    /**
     *
     * @var string
     */
    public $retencion_aplica;

    /**
     *
     * @var double
     */
    public $retencion_factor;

    /**
     *
     * @var double
     */
    public $retencion_monto;

    /**
     *
     * @var double
     */
    public $retencion_base;

    /**
     *
     * @var string
     */
    public $ruta_qr;

    /**
     *
     * @var string
     */
    public $indicador_envio_sunat;

    /**
     *
     * @var string
     */
    public $tipo_doc_transporte_mercancias;

    /**
     *
     * @var string
     */
    public $indicador_traslado_total_dam_ds;
}