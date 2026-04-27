<?php

class EpUserpageversiones extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $idversion;

    /**
     *
     * @var integer
     */
    public $iduserpage;

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
    public $fecharegistro;

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
        $this->setSchema("humbertoguadalup_bd_sys_facturacion");
        $this->setSource("ep_userpageversiones");
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return EpUserpageversiones[]|EpUserpageversiones|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null): \Phalcon\Mvc\Model\ResultsetInterface
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return EpUserpageversiones|\Phalcon\Mvc\Model\ResultInterface|\Phalcon\Mvc\ModelInterface|null
     */
    public static function findFirst($parameters = null): ?\Phalcon\Mvc\ModelInterface
    {
        return parent::findFirst($parameters);
    }

}
