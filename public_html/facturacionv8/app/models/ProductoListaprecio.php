<?php

class ProductoListaprecio extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $idprecio;

    /**
     *
     * @var integer
     */
    public $idproducto;

    /**
     *
     * @var string
     */
    public $nombre;

    /**
     *
     * @var double
     */
    public $precio;

    /**
     *
     * @var string
     */
    public $estado;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema(APP_DB_SCHEMA);
        $this->setSource("producto_listaprecio");
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return ProductoListaprecio[]|ProductoListaprecio|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null): \Phalcon\Mvc\Model\ResultsetInterface
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return ProductoListaprecio|\Phalcon\Mvc\Model\ResultInterface|\Phalcon\Mvc\ModelInterface|null
     */
    public static function findFirst($parameters = null): ?\Phalcon\Mvc\ModelInterface
    {
        return parent::findFirst($parameters);
    }

}
