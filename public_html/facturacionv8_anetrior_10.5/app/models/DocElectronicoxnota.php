<?php

class DocElectronicoxnota extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $id_doc_electronicoxnota;

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
     * @var string
     */
    public $fecha_registro;

    /**
     *
     * @var integer
     */
    public $nota_id_contribuyente;

    /**
     *
     * @var string
     */
    public $nota_id_tipodoc_electronico;

    /**
     *
     * @var string
     */
    public $nota_serie_comprobante;

    /**
     *
     * @var integer
     */
    public $nota_numero_comprobante;

    /**
     *
     * @var string
     */
    public $nota_tipo_envio_sunat;

    /**
     *
     * @var string
     */
    public $nota_estado_envio_sunat;

    /**
     *
     * @var string
     */
    public $nota_id_cod_tipomotivo_credito;

    /**
     *
     * @var string
     */
    public $nota_id_cod_tipomotivo_debito;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("humbertoguadalup_bd_sys_facturacion");
        $this->setSource("doc_electronicoxnota");
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return DocElectronicoxnota[]|DocElectronicoxnota|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null): \Phalcon\Mvc\Model\ResultsetInterface
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return DocElectronicoxnota|\Phalcon\Mvc\Model\ResultInterface|\Phalcon\Mvc\ModelInterface|null
     */
    public static function findFirst($parameters = null): ?\Phalcon\Mvc\ModelInterface
    {
        return parent::findFirst($parameters);
    }

}
