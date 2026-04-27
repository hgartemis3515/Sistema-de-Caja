<?php

use Phalcon\Filter\Validation;
use Phalcon\Filter\Validation\Validator\Email as EmailValidator;

class Contribuyente extends \Phalcon\Mvc\Model
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
    public $nombre_comercial;

    /**
     *
     * @var string
     */
    public $email;

    /**
     *
     * @var string
     */
    public $telefono;

    /**
     *
     * @var string
     */
    public $codigo_ubigeo;

    /**
     *
     * @var string
     */
    public $urbanizacion;

    /**
     *
     * @var string
     */
    public $direccion_fiscal;

    /**
     *
     * @var string
     */
    public $usuario_sol;

    /**
     *
     * @var string
     */
    public $clave_sol;

    /**
     *
     * @var string
     */
    public $ruta_certificado;

    /**
     *
     * @var string
     */
    public $pass_certificado;

    /**
     *
     * @var string
     */
    public $tipo_certificado;

    /**
     *
     * @var string
     */
    public $ruta_xml_prueba;

    /**
     *
     * @var string
     */
    public $ruta_xml_produccion;

    /**
     *
     * @var string
     */
    public $tipo_envio_sunat;

    /**
     *
     * @var integer
     */
    public $id_patrocinador;

    /**
     *
     * @var string
     */
    public $fecha_registro;

    /**
     *
     * @var string
     */
    public $img_logo;

    /**
     *
     * @var string
     */
    public $modalidad_envio_sunat;

    /**
     *
     * @var string
     */
    public $dominio;

    /**
     *
     * @var string
     */
    public $https;

    /**
     *
     * @var string
     */
    public $logo_461;

    /**
     *
     * @var string
     */
    public $logo_56;

    /**
     *
     * @var string
     */
    public $logo_291;

    /**
     *
     * @var string
     */
    public $logo_350;

    /**
     *
     * @var string
     */
    public $fecha_expiracion;

    /**
     *
     * @var string
     */
    public $fecha_expira_cert;

    /**
     *
     * @var integer
     */
    public $correlativo_rd_boletas;

    /**
     *
     * @var integer
     */
    public $correlativo_comunicacion_bajas;

    /**
     *
     * @var integer
     */
    public $sunat_idregimen;

    /**
     *
     * @var string
     */
    public $restriccion_stock;

    /**
     *
     * @var string
     */
    public $multi_almacen;

    /**
     *
     * @var integer
     */
    public $num_decimales;

    /**
     *
     * @var string
     */
    public $prodduplicados_detalle;

    /**
     *
     * @var string
     */
    public $precio_venta_minimo;

    /**
     *
     * @var string
     */
    public $modulo_marketing;

    /**
     *
     * @var string
     */
    public $envio_automatico_docs;

    /**
     *
     * @var string
     */
    public $mostrar_codprod_pdfs;

    /**
     *
     * @var string
     */
    public $cotizacion_con_igv;

    /**
     *
     * @var string
     */
    public $mostrar_uprecio_clieprod;

    /**
     *
     * @var string
     */
    public $tiene_detracciones;

    /**
     *
     * @var string
     */
    public $tiene_percepciones;

    /**
     *
     * @var string
     */
    public $estado;

    /**
     *
     * @var string
     */
    public $tipo_busqueda_doc;

    /**
     *
     * @var string
     */
    public $customer_id_culqi;

    /**
     *
     * @var string
     */
    public $token;

    /**
     *
     * @var string
     */
    public $ver_fecha_vencimiento;

    /**
     *
     * @var string
     */
    public $ver_marca;

    /**
     *
     * @var string
     */
    public $captcha_key_public;

    /**
     *
     * @var string
     */
    public $captcha_key_private;

    /**
     *
     * @var string
     */
    public $url_soporte;

    /**
     *
     * @var string
     */
    public $url_politica_privacidad;

    /**
     *
     * @var string
     */
    public $url_terminos_condiciones;

    /**
     *
     * @var string
     */
    public $url_empresa;

    /**
     *
     * @var string
     */
    public $informar_condpago_sunat;

    /**
     *
     * @var string
     */
    public $mostrar_aviso;

    /**
     *
     * @var integer
     */
    public $tipo_empresa;

    /**
     *
     * @var double
     */
    public $renta_3ra_porcentaje;

    /**
     *
     * @var double
     */
    public $renta_3ra_coeficiente;

    /**
     *
     * @var string
     */
    public $mype;

    /**
     *
     * @var string
     */
    public $tipo_empresa_sunat;

    /**
     *
     * @var string
     */
    public $nombre_ose;

    /**
     *
     * @var string
     */
    public $u_prueba_ose;

    /**
     *
     * @var string
     */
    public $c_prueba_ose;

    /**
     *
     * @var string
     */
    public $url_prueba_ose;

    /**
     *
     * @var string
     */
    public $u_produccion_ose;

    /**
     *
     * @var string
     */
    public $c_produccion_ose;

    /**
     *
     * @var string
     */
    public $url_produccion_ose;

    /**
     *
     * @var double
     */
    public $regimen_retencion;

    /**
     *
     * @var string
     */
    public $url_facebook;

    /**
     *
     * @var string
     */
    public $url_youtube;

    /**
     *
     * @var string
     */
    public $url_tiktok;

    /**
     *
     * @var string
     */
    public $url_instagram;

    /**
     *
     * @var string
     */
    public $url_twitter;

    /**
     *
     * @var string
     */
    public $sunat_u_sol_principal;

    /**
     *
     * @var string
     */
    public $sunat_p_sol_principal;

    /**
     *
     * @var string
     */
    public $sunat_client_id;

    /**
     *
     * @var string
     */
    public $sunat_client_secret;

    /**
     *
     * @var string
     */
    public $user_sol_busq_cpe;

    /**
     *
     * @var string
     */
    public $pass_sol_busq_cpe;
}