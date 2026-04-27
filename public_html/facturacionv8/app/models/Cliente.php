<?php

use Phalcon\Filter\Validation;
use Phalcon\Filter\Validation\Validator\Email as EmailValidator;

class Cliente extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $idcliente;

    /**
     *
     * @var integer
     */
    public $id_contribuyente;

    /**
     *
     * @var string
     */
    public $id_tipodocidentidad;

    /**
     *
     * @var string
     */
    public $codigo;

    /**
     *
     * @var string
     */
    public $num_doc;

    /**
     *
     * @var string
     */
    public $razon_social;

    /**
     *
     * @var string
     */
    public $nombre_comercial;

    /**
     *
     * @var string
     */
    public $direccion_fiscal;

    /**
     *
     * @var string
     */
    public $id_cod_ubigeo;

    /**
     *
     * @var string
     */
    public $email;

    /**
     *
     * @var string
     */
    public $celular;

    /**
     *
     * @var string
     */
    public $telefono;

    /**
     *
     * @var string
     */
    public $num_cuenta_detraccion;

    /**
     *
     * @var string
     */
    public $detalle_adicional;

    /**
     *
     * @var string
     */
    public $fecha_registro;

    /**
     *
     * @var string
     */
    public $fecha_nac;

    /**
     *
     * @var string
     */
    public $sexo;

    /**
     *
     * @var string
     */
    public $foto;

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
        $this->setSource("cliente");
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Cliente[]|Cliente|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null): \Phalcon\Mvc\Model\ResultsetInterface
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Cliente|\Phalcon\Mvc\Model\ResultInterface|\Phalcon\Mvc\ModelInterface|null
     */
    public static function findFirst($parameters = null): ?\Phalcon\Mvc\ModelInterface
    {
        return parent::findFirst($parameters);
    }

}
