<?php

class DetalleDocnooficial extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $iddetalle;

    /**
     *
     * @var integer
     */
    public $id_contribuyente;

    /**
     *
     * @var string
     */
    public $id_tipodocumento;

    /**
     *
     * @var integer
     */
    public $numero_comprobante;

    /**
     *
     * @var string
     */
    public $modalidad;

    /**
     *
     * @var integer
     */
    public $item;

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
    public $sub_total;

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
    public $icbper;

    /**
     *
     * @var double
     */
    public $importe;

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
     * @var double
     */
    public $precio_sin_igv;

    /**
     *
     * @var double
     */
    public $peso;

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
     *
     * @var double
     */
    public $factor_igv;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema(APP_DB_SCHEMA);
        $this->setSource("detalle_docnooficial");
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return DetalleDocnooficial[]|DetalleDocnooficial|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null): \Phalcon\Mvc\Model\ResultsetInterface
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return DetalleDocnooficial|\Phalcon\Mvc\Model\ResultInterface|\Phalcon\Mvc\ModelInterface|null
     */
    public static function findFirst($parameters = null): ?\Phalcon\Mvc\ModelInterface
    {
        return parent::findFirst($parameters);
    }

}
