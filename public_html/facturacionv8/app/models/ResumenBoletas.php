<?php

class ResumenBoletas extends \Phalcon\Mvc\Model
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
    public $codigo;

    /**
     *
     * @var string
     */
    public $serie;

    /**
     *
     * @var integer
     */
    public $secuencia;

    /**
     *
     * @var string
     */
    public $tipo_envio_sunat;

    /**
     *
     * @var string
     */
    public $fecha_documentos;

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
    public $numero_ticket;

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
    public $accion_sunat;

    /**
     *
     * @var string
     */
    public $detalle;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema(APP_DB_SCHEMA);
        $this->setSource("resumen_boletas");
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return ResumenBoletas[]|ResumenBoletas|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null): \Phalcon\Mvc\Model\ResultsetInterface
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return ResumenBoletas|\Phalcon\Mvc\Model\ResultInterface|\Phalcon\Mvc\ModelInterface|null
     */
    public static function findFirst($parameters = null): ?\Phalcon\Mvc\ModelInterface
    {
        return parent::findFirst($parameters);
    }

}
