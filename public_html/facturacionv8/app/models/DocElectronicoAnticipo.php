<?php

class DocElectronicoAnticipo extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $id_anticipo;

    /**
     *
     * @var integer
     */
    public $cpe_id_contribuyente;

    /**
     *
     * @var string
     */
    public $cpe_id_tipodoc_electronico;

    /**
     *
     * @var string
     */
    public $cpe_serie_comprobante;

    /**
     *
     * @var integer
     */
    public $cpe_numero_comprobante;

    /**
     *
     * @var string
     */
    public $cpe_tipo_envio_sunat;

    /**
     *
     * @var integer
     */
    public $anticipo_id_contribuyente;

    /**
     *
     * @var string
     */
    public $anticipo_id_tipodoc_electronico;

    /**
     *
     * @var string
     */
    public $anticipo_serie_comprobante;

    /**
     *
     * @var integer
     */
    public $anticipo_numero_comprobante;

    /**
     *
     * @var string
     */
    public $anticipo_tipo_envio_sunat;

    /**
     *
     * @var double
     */
    public $monto_anticipo;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema(APP_DB_SCHEMA);
        $this->setSource("doc_electronico_anticipo");
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return DocElectronicoAnticipo[]|DocElectronicoAnticipo|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null): \Phalcon\Mvc\Model\ResultsetInterface
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return DocElectronicoAnticipo|\Phalcon\Mvc\Model\ResultInterface|\Phalcon\Mvc\ModelInterface|null
     */
    public static function findFirst($parameters = null): ?\Phalcon\Mvc\ModelInterface
    {
        return parent::findFirst($parameters);
    }

}
