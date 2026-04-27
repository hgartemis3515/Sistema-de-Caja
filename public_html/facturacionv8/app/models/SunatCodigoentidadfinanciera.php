<?php

class SunatCodigoentidadfinanciera extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var string
     */
    public $id_entidadfinanciera;

    /**
     *
     * @var string
     */
    public $descripcion;

    /**
     *
     * @var string
     */
    public $estado;

    /**
     *
     * @var string
     */
    public $logo;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("humbertoguadalup_bd_sys_facturacion");
        $this->setSource("sunat_codigoentidadfinanciera");
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return SunatCodigoentidadfinanciera[]|SunatCodigoentidadfinanciera|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null): \Phalcon\Mvc\Model\ResultsetInterface
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return SunatCodigoentidadfinanciera|\Phalcon\Mvc\Model\ResultInterface|\Phalcon\Mvc\ModelInterface|null
     */
    public static function findFirst($parameters = null): ?\Phalcon\Mvc\ModelInterface
    {
        return parent::findFirst($parameters);
    }

}
