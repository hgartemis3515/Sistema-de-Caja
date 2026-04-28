<?php

class Cuentabanco extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $id_cuentabanco;

    /**
     *
     * @var integer
     */
    public $id_contribuyente;

    /**
     *
     * @var string
     */
    public $id_codigomoneda;

    /**
     *
     * @var string
     */
    public $tipo_cuenta;

    /**
     *
     * @var string
     */
    public $nombre_banco;

    /**
     *
     * @var string
     */
    public $nombre_titular;

    /**
     *
     * @var string
     */
    public $nro_cuenta;

    /**
     *
     * @var string
     */
    public $cci;

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
    public $id_entidadfinanciera;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema(APP_DB_SCHEMA);
        $this->setSource("cuentabanco");
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Cuentabanco[]|Cuentabanco|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null): \Phalcon\Mvc\Model\ResultsetInterface
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Cuentabanco|\Phalcon\Mvc\Model\ResultInterface|\Phalcon\Mvc\ModelInterface|null
     */
    public static function findFirst($parameters = null): ?\Phalcon\Mvc\ModelInterface
    {
        return parent::findFirst($parameters);
    }

}
