<?php

class DocDetallepago extends \Phalcon\Mvc\Model
{
    public $id_detallepago;
    public $id_contribuyente;
    public $id_tipodoc_electronico;
    public $serie_comprobante;
    public $numero_comprobante;
    public $tipo_envio_sunat;
    public $id_vendedor;
    public $id_sucursal;
    public $id_condicionpago;
    public $id_codigomoneda;
    public $tipo_cambio_sunat;
    public $total;
    public $cpago_nrooperacion;
    public $fecha_registro;
    public $fechadeposito;
    public $idbanco;
    public $detalle;
    public $estado;
}