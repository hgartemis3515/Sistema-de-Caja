<?php

class DocRelacion extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var integer
     */
    public $origen_id_contribuyente;

    /**
     *
     * @var string
     */
    public $origen_id_tipodoc_electronico;

    /**
     *
     * @var string
     */
    public $origen_serie_comprobante;

    /**
     *
     * @var integer
     */
    public $origen_numero_comprobante;

    /**
     *
     * @var string
     */
    public $origen_tipo_envio_sunat;

    /**
     *
     * @var string
     */
    public $fecha_registro;

    /**
     *
     * @var string
     */
    public $descripcion;

    /**
     *
     * @var string
     */
    public $accion;

    /**
     *
     * @var integer
     */
    public $destino_id_contribuyente;

    /**
     *
     * @var string
     */
    public $destino_id_tipodoc_electronico;

    /**
     *
     * @var string
     */
    public $destino_serie_comprobante;

    /**
     *
     * @var integer
     */
    public $destino_numero_comprobante;

    /**
     *
     * @var string
     */
    public $destino_tipo_envio_sunat;

    /**
     *
     * @var integer
     */
    public $id_vendedor;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema(APP_DB_SCHEMA);
        $this->setSource("doc_relacion");
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return DocRelacion[]|DocRelacion|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null): \Phalcon\Mvc\Model\ResultsetInterface
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return DocRelacion|\Phalcon\Mvc\Model\ResultInterface|\Phalcon\Mvc\ModelInterface|null
     */
    public static function findFirst($parameters = null): ?\Phalcon\Mvc\ModelInterface
    {
        return parent::findFirst($parameters);
    }

}
