<?php

class EpUserpage extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $iduserpage;

    /**
     *
     * @var integer
     */
    public $id_contribuyente;

    /**
     *
     * @var integer
     */
    public $idtemplate;

    /**
     *
     * @var string
     */
    public $htmlcode;

    /**
     *
     * @var string
     */
    public $mainheadercode;

    /**
     *
     * @var string
     */
    public $fecharegistro;

    /**
     *
     * @var string
     */
    public $imagepreview;

    /**
     *
     * @var string
     */
    public $estado;

    /**
     *
     * @var string
     */
    public $is_home;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("humbertoguadalup_bd_sys_facturacion");
        $this->setSource("ep_userpage");
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return EpUserpage[]|EpUserpage|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null): \Phalcon\Mvc\Model\ResultsetInterface
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return EpUserpage|\Phalcon\Mvc\Model\ResultInterface|\Phalcon\Mvc\ModelInterface|null
     */
    public static function findFirst($parameters = null): ?\Phalcon\Mvc\ModelInterface
    {
        return parent::findFirst($parameters);
    }

}
