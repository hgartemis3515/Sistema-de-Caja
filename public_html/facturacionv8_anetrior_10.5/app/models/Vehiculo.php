<?php

class Vehiculo extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $id_vehiculo;

    /**
     *
     * @var integer
     */
    public $id_etransporte;

    /**
     *
     * @var string
     */
    public $nro_placa;

    /**
     *
     * @var string
     */
    public $marca;

    /**
     *
     * @var string
     */
    public $const_inscripcion;

    /**
     *
     * @var double
     */
    public $capacidad;

    /**
     *
     * @var string
     */
    public $dgh;

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
        $this->setSource("vehiculo");
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Vehiculo[]|Vehiculo|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null): \Phalcon\Mvc\Model\ResultsetInterface
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Vehiculo|\Phalcon\Mvc\Model\ResultInterface|\Phalcon\Mvc\ModelInterface|null
     */
    public static function findFirst($parameters = null): ?\Phalcon\Mvc\ModelInterface
    {
        return parent::findFirst($parameters);
    }

}
