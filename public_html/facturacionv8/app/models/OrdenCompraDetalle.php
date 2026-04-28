<?php

class OrdenCompraDetalle extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $id_detalle;

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
    public $cantidad;

    /**
     *
     * @var double
     */
    public $precio;

    /**
     *
     * @var double
     */
    public $precio_sin_igv;

    /**
     *
     * @var double
     */
    public $sub_total;

    /**
     *
     * @var double
     */
    public $importe;

    /**
     *
     * @var string
     */
    public $id_codigoprecio;

    /**
     *
     * @var double
     */
    public $igv;

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
     * @var string
     */
    public $id_tipoafectacionigv;

    /**
     *
     * @var integer
     */
    public $id_producto;

    /**
     *
     * @var string
     */
    public $codigo_producto;

    /**
     *
     * @var string
     */
    public $descripcion;

    /**
     *
     * @var string
     */
    public $tipo_unidad;

    /**
     *
     * @var integer
     */
    public $id_presentacion;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema(APP_DB_SCHEMA);
        $this->setSource("orden_compra_detalle");
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return OrdenCompraDetalle[]|OrdenCompraDetalle|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null): \Phalcon\Mvc\Model\ResultsetInterface
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return OrdenCompraDetalle|\Phalcon\Mvc\Model\ResultInterface|\Phalcon\Mvc\ModelInterface|null
     */
    public static function findFirst($parameters = null): ?\Phalcon\Mvc\ModelInterface
    {
        return parent::findFirst($parameters);
    }

}
