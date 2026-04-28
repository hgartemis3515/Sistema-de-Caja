<?php

class Opciones extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $id_opcion;

    /**
     *
     * @var integer
     */
    public $id_contribuyente;

    /**
     *
     * @var string
     */
    public $color_fondo_tipo;

    /**
     *
     * @var string
     */
    public $color_fondo_1_rgb;

    /**
     *
     * @var string
     */
    public $color_fondo_2_rgb;

    /**
     *
     * @var integer
     */
    public $id_plantilla_login;

    /**
     *
     * @var string
     */
    public $img_background_login;

    /**
     *
     * @var integer
     */
    public $id_plantilla_registro;

    /**
     *
     * @var string
     */
    public $img_background_register;

    /**
     *
     * @var string
     */
    public $msj_expira_suscripcion;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema(APP_DB_SCHEMA);
        $this->setSource("opciones");
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Opciones[]|Opciones|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null): \Phalcon\Mvc\Model\ResultsetInterface
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Opciones|\Phalcon\Mvc\Model\ResultInterface|\Phalcon\Mvc\ModelInterface|null
     */
    public static function findFirst($parameters = null): ?\Phalcon\Mvc\ModelInterface
    {
        return parent::findFirst($parameters);
    }

}
