<?php

class GuiaTransportistaDetalle extends \Phalcon\Mvc\Model
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
     * @var string
     */
    public $codigo;

    /**
     *
     * @var string
     */
    public $descripcion;

    /**
     *
     * @var integer
     */
    public $idproducto;

    /**
     *
     * @var double
     */
    public $peso;

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
        $this->setSchema("humbertoguadalup_bd_sys_facturacion");
        $this->setSource("guia_transportista_detalle");
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return GuiaTransportistaDetalle[]|GuiaTransportistaDetalle|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null): \Phalcon\Mvc\Model\ResultsetInterface
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return GuiaTransportistaDetalle|\Phalcon\Mvc\Model\ResultInterface|\Phalcon\Mvc\ModelInterface|null
     */
    public static function findFirst($parameters = null): ?\Phalcon\Mvc\ModelInterface
    {
        return parent::findFirst($parameters);
    }

}
