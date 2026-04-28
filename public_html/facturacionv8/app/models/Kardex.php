<?php

class Kardex extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $id_kardex;

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
     * @var integer
     */
    public $idusuario;

    /**
     *
     * @var integer
     */
    public $idproducto;

    /**
     *
     * @var string
     */
    public $tipo_envio_sunat;

    /**
     *
     * @var string
     */
    public $tipo_kardex;

    /**
     *
     * @var string
     */
    public $estado_kardex;

    /**
     *
     * @var double
     */
    public $cantidad_entrada;

    /**
     *
     * @var double
     */
    public $cantidad_salida;

    /**
     *
     * @var double
     */
    public $stock;

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
    public $costo_unitario;

    /**
     *
     * @var double
     */
    public $costo_unitario_promedio;

    /**
     *
     * @var double
     */
    public $costo_total;

    /**
     *
     * @var double
     */
    public $stock_valorizado;

    /**
     *
     * @var string
     */
    public $detalle;

    /**
     *
     * @var string
     */
    public $fecha_registro;

    /**
     *
     * @var integer
     */
    public $docref_id_contribuyente;

    /**
     *
     * @var string
     */
    public $docref_id_tipodoc_electronico;

    /**
     *
     * @var string
     */
    public $docref_serie_comprobante;

    /**
     *
     * @var integer
     */
    public $docref_numero_comprobante;

    /**
     *
     * @var string
     */
    public $docref_tipo_envio_sunat;

    /**
     *
     * @var integer
     */
    public $id_compra;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema(APP_DB_SCHEMA);
        $this->setSource("kardex");
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Kardex[]|Kardex|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null): \Phalcon\Mvc\Model\ResultsetInterface
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Kardex|\Phalcon\Mvc\Model\ResultInterface|\Phalcon\Mvc\ModelInterface|null
     */
    public static function findFirst($parameters = null): ?\Phalcon\Mvc\ModelInterface
    {
        return parent::findFirst($parameters);
    }

}
