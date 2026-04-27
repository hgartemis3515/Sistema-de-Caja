<?php

use Phalcon\Filter\Validation;
use Phalcon\Filter\Validation\Validator\Email as EmailValidator;

class Sucursal extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $idsucursal;

    /**
     *
     * @var integer
     */
    public $id_contribuyente;

    /**
     *
     * @var string
     */
    public $codigo;

    /**
     *
     * @var string
     */
    public $nombre;

    /**
     *
     * @var double
     */
    public $factor_igv;

    /**
     *
     * @var string
     */
    public $direccion;

    /**
     *
     * @var string
     */
    public $id_ubigeo;

    /**
     *
     * @var string
     */
    public $urbanizacion;

    /**
     *
     * @var string
     */
    public $telefono;

    /**
     *
     * @var string
     */
    public $email;

    /**
     *
     * @var string
     */
    public $sitio_web;

    /**
     *
     * @var string
     */
    public $informacion_adicional;

    /**
     *
     * @var string
     */
    public $fecha_registro;

    /**
     *
     * @var string
     */
    public $estado;

    /**
     *
     * @var string
     */
    public $leyenda_comprobantes;

    /**
     *
     * @var string
     */
    public $factura_serie;

    /**
     *
     * @var integer
     */
    public $factura_numero;

    /**
     *
     * @var string
     */
    public $factura_formato;

    /**
     *
     * @var string
     */
    public $boleta_serie;

    /**
     *
     * @var integer
     */
    public $boleta_numero;

    /**
     *
     * @var string
     */
    public $boleta_formato;

    /**
     *
     * @var string
     */
    public $notacredito_factura_serie;

    /**
     *
     * @var integer
     */
    public $notacredito_factura_numero;

    /**
     *
     * @var string
     */
    public $notacredito_factura_formato;

    /**
     *
     * @var string
     */
    public $notadebito_factura_serie;

    /**
     *
     * @var integer
     */
    public $notadebito_factura_numero;

    /**
     *
     * @var string
     */
    public $notadebito_factura_formato;

    /**
     *
     * @var string
     */
    public $notacredito_boleta_serie;

    /**
     *
     * @var integer
     */
    public $notacredito_boleta_numero;

    /**
     *
     * @var string
     */
    public $notacredito_boleta_formato;

    /**
     *
     * @var string
     */
    public $notadebito_boleta_serie;

    /**
     *
     * @var integer
     */
    public $notadebito_boleta_numero;

    /**
     *
     * @var string
     */
    public $notadebito_boleta_formato;

    /**
     *
     * @var string
     */
    public $guia_remision_serie;

    /**
     *
     * @var integer
     */
    public $guia_remision_numero;

    /**
     *
     * @var string
     */
    public $guia_remision_formato;

    /**
     *
     * @var string
     */
    public $guia_transportista_serie;

    /**
     *
     * @var integer
     */
    public $guia_transportista_numero;

    /**
     *
     * @var string
     */
    public $guia_transportista_formato;

    /**
     *
     * @var string
     */
    public $orden_compra_serie;

    /**
     *
     * @var integer
     */
    public $orden_compra_numero;

    /**
     *
     * @var string
     */
    public $orden_compra_formato;

    /**
     *
     * @var string
     */
    public $txt_pdf_a4_1;

    /**
     *
     * @var string
     */
    public $txt_pdf_a4_2;

    /**
     *
     * @var string
     */
    public $txt_pdf_a4_3;

    /**
     *
     * @var string
     */
    public $txt_pdf_ticket_1;

    /**
     *
     * @var string
     */
    public $txt_pdf_ticket_2;

    /**
     *
     * @var string
     */
    public $txt_pdf_ticket_3;

    /**
     *
     * @var integer
     */
    public $plantilla_pdf_a4;

    /**
     *
     * @var integer
     */
    public $plantilla_pdf_ticket;

    /**
     *
     * @var integer
     */
    public $id_plantillapdf_factura_a4;

    /**
     *
     * @var integer
     */
    public $id_plantillapdf_factura_ticket;

    /**
     *
     * @var integer
     */
    public $id_plantillapdf_boleta_a4;

    /**
     *
     * @var integer
     */
    public $id_plantillapdf_boleta_ticket;

    /**
     *
     * @var integer
     */
    public $id_plantillapdf_notacredito_a4;

    /**
     *
     * @var integer
     */
    public $id_plantillapdf_notacredito_ticket;

    /**
     *
     * @var integer
     */
    public $id_plantillapdf_notadebito_a4;

    /**
     *
     * @var integer
     */
    public $id_plantillapdf_notadebito_ticket;

    /**
     *
     * @var integer
     */
    public $id_plantillapdf_guiaremision_a4;

    /**
     *
     * @var integer
     */
    public $id_plantillapdf_guiaremision_ticket;

    /**
     *
     * @var integer
     */
    public $id_plantillapdf_notaventa_a4;

    /**
     *
     * @var integer
     */
    public $id_plantillapdf_notaventa_ticket;

    /**
     *
     * @var integer
     */
    public $id_plantillapdf_cotizacion_a4;

    /**
     *
     * @var integer
     */
    public $id_plantillapdf_cotizacion_ticket;

    /**
     *
     * @var integer
     */
    public $id_plantillapdf_guiatransportista_a4;

    /**
     *
     * @var integer
     */
    public $id_plantillapdf_guiatransportista_ticket;

    /**
     *
     * @var integer
     */
    public $id_plantillapdf_ordencompra_a4;

    /**
     *
     * @var integer
     */
    public $id_plantillapdf_ordencompra_ticket;

    /**
     *
     * @var string
     */
    public $boleta_mostrar_items_igv;

    /**
     *
     * @var string
     */
    public $factura_mostrar_items_igv;

    /**
     *
     * @var string
     */
    public $notacredito_mostrar_items_igv;

    /**
     *
     * @var string
     */
    public $notadebito_mostrar_items_igv;

    /**
     *
     * @var string
     */
    public $guiaremision_mostrar_items_igv;

    /**
     *
     * @var string
     */
    public $cotizacion_mostrar_items_igv;

    /**
     *
     * @var string
     */
    public $notaventa_mostrar_items_igv;

    /**
     *
     * @var string
     */
    public $ordencompra_mostrar_items_igv;

    /**
     *
     * @var string
     */
    public $img_qr_yape;

    /**
     *
     * @var string
     */
    public $titular_yape;

    /**
     *
     * @var string
     */
    public $celular_yape;

    /**
     *
     * @var string
     */
    public $img_qr_plin;

    /**
     *
     * @var string
     */
    public $titular_plin;

    /**
     *
     * @var string
     */
    public $celular_plin;

    /**
     *
     * @var string
     */
    public $boleta_mostrar_yape;

    /**
     *
     * @var string
     */
    public $factura_mostrar_yape;

    /**
     *
     * @var string
     */
    public $notaventa_mostrar_yape;

    /**
     *
     * @var string
     */
    public $boleta_mostrar_plin;

    /**
     *
     * @var string
     */
    public $factura_mostrar_plin;

    /**
     *
     * @var string
     */
    public $notaventa_mostrar_plin;

    /**
     *
     * @var string
     */
    public $cotizacion_mostrar_yape;

    /**
     *
     * @var string
     */
    public $cotizacion_mostrar_plin;

    /**
     *
     * @var string
     */
    public $glosa_amazonia;
}
