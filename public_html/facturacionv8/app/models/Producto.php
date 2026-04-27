<?php

class Producto extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $idproducto;

    /**
     *
     * @var integer
     */
    public $id_contribuyente;

    /**
     *
     * @var integer
     */
    public $idsucursal;

    /**
     *
     * @var string
     */
    public $codigo;

    /**
     *
     * @var integer
     */
    public $id_unidad_medida;

    /**
     *
     * @var string
     */
    public $id_cod_detraccion;

    /**
     *
     * @var string
     */
    public $id_tipoafectacionigv;

    /**
     *
     * @var integer
     */
    public $id_categoria;

    /**
     *
     * @var string
     */
    public $nombre;

    /**
     *
     * @var string
     */
    public $id_cod_moneda;

    /**
     *
     * @var double
     */
    public $tipo_cambio_sunat;

    /**
     *
     * @var double
     */
    public $precio_compra;

    /**
     *
     * @var double
     */
    public $valor_sin_igv;

    /**
     *
     * @var double
     */
    public $valor_con_igv;

    /**
     *
     * @var double
     */
    public $precio_venta_minimo;

    /**
     *
     * @var string
     */
    public $nota;

    /**
     *
     * @var string
     */
    public $foto;

    /**
     *
     * @var double
     */
    public $stock;

    /**
     *
     * @var double
     */
    public $stock_minimo;

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
    public $afecto_icbper;

    /**
     *
     * @var string
     */
    public $multi_precio;

    /**
     *
     * @var string
     */
    public $fecha_vencimiento;

    /**
     *
     * @var string
     */
    public $marca;

    /**
     *
     * @var string
     */
    public $codigos_presentaciones;

    /**
     *
     * @var double
     */
    public $porcentaje_pventa;

    /**
     *
     * @var double
     */
    public $porcentaje_pminimo;

    /**
     *
     * @var double
     */
    public $costo_promedio;

    /**
     *
     * @var double
     */
    public $factor_igv;

    /**
     *
     * @var double
     */
    public $peso;
    public $destacado;
}
