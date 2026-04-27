<?php

class SunatCodigoubigeo extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var string
     */
    public $codigo_ubigeo;

    /**
     *
     * @var string
     */
    public $departamento;

    /**
     *
     * @var string
     */
    public $provincia;

    /**
     *
     * @var string
     */
    public $distrito;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("humbertoguadalup_bd_sys_facturacion");
        $this->setSource("sunat_codigoubigeo");
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return SunatCodigoubigeo[]|SunatCodigoubigeo|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null): \Phalcon\Mvc\Model\ResultsetInterface
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return SunatCodigoubigeo|\Phalcon\Mvc\Model\ResultInterface|\Phalcon\Mvc\ModelInterface|null
     */
    public static function findFirst($parameters = null): ?\Phalcon\Mvc\ModelInterface
    {
        return parent::findFirst($parameters);
    }

}
