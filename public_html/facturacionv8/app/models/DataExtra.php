<?php

class DataExtra extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $id_dataextra;

    /**
     *
     * @var string
     */
    public $html_sitioweb;

    /**
     *
     * @var string
     */
    public $url_guiausuario;

    /**
     *
     * @var integer
     */
    public $id_contribuyente;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema(APP_DB_SCHEMA);
        $this->setSource("data_extra");
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return DataExtra[]|DataExtra|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null): \Phalcon\Mvc\Model\ResultsetInterface
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return DataExtra|\Phalcon\Mvc\Model\ResultInterface|\Phalcon\Mvc\ModelInterface|null
     */
    public static function findFirst($parameters = null): ?\Phalcon\Mvc\ModelInterface
    {
        return parent::findFirst($parameters);
    }

}
