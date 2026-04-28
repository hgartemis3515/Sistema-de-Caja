<?php

class SunatTipodecambio extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var string
     */
    public $fecha;

    /**
     *
     * @var double
     */
    public $compra;

    /**
     *
     * @var double
     */
    public $venta;

    /**
     *
     * @var string
     */
    public $moneda;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema(APP_DB_SCHEMA);
        $this->setSource("sunat_tipodecambio");
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return SunatTipodecambio[]|SunatTipodecambio|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null): \Phalcon\Mvc\Model\ResultsetInterface
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return SunatTipodecambio|\Phalcon\Mvc\Model\ResultInterface|\Phalcon\Mvc\ModelInterface|null
     */
    public static function findFirst($parameters = null): ?\Phalcon\Mvc\ModelInterface
    {
        return parent::findFirst($parameters);
    }

}
