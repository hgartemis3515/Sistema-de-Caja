<?php

class ComunicacionBaja extends \Phalcon\Mvc\Model
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
     * @var string
     */
    public $detalle;

    /**
     *
     * @var string
     */
    public $motivo;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("humbertoguadalup_bd_sys_facturacion");
        $this->setSource("comunicacion_baja");
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return ComunicacionBaja[]|ComunicacionBaja|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null): \Phalcon\Mvc\Model\ResultsetInterface
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return ComunicacionBaja|\Phalcon\Mvc\Model\ResultInterface|\Phalcon\Mvc\ModelInterface|null
     */
    public static function findFirst($parameters = null): ?\Phalcon\Mvc\ModelInterface
    {
        return parent::findFirst($parameters);
    }

}
