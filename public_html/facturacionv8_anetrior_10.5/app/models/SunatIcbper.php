<?php

class SunatIcbper extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $anio;

    /**
     *
     * @var double
     */
    public $monto;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("humbertoguadalup_bd_sys_facturacion");
        $this->setSource("sunat_icbper");
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return SunatIcbper[]|SunatIcbper|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null): \Phalcon\Mvc\Model\ResultsetInterface
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return SunatIcbper|\Phalcon\Mvc\Model\ResultInterface|\Phalcon\Mvc\ModelInterface|null
     */
    public static function findFirst($parameters = null): ?\Phalcon\Mvc\ModelInterface
    {
        return parent::findFirst($parameters);
    }

}
