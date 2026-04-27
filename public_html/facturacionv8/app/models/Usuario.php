<?php

use Phalcon\Filter\Validation;
use Phalcon\Filter\Validation\Validator\Email as EmailValidator;

class Usuario extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $idusuario;

    /**
     *
     * @var integer
     */
    public $id_contribuyente;

    /**
     *
     * @var string
     */
    public $codigo;

    /**
     *
     * @var integer
     */
    public $id_rol;

    /**
     *
     * @var string
     */
    public $nombre;

    /**
     *
     * @var string
     */
    public $apellido;

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
    public $email;

    /**
     *
     * @var string
     */
    public $password;

    /**
     *
     * @var string
     */
    public $url_image;

    /**
     *
     * @var string
     */
    public $fecha_registro;

    /**
     *
     * @var string
     */
    public $estado;

    /**
     *
     * @var integer
     */
    public $idsucursal;

    /**
     *
     * @var string
     */
    public $ver_ventas_totales;

    /**
     *
     * @var string
     */
    public $modificacion_almacen;

    /**
     *
     * @var string
     */
    public $modificacion_multi_almacen;

    /**
     *
     * @var string
     */
    public $ventas_multisucursal;

    /**
     *
     * @var string
     */
    public $acceso_mod_compras;

    /**
     *
     * @var string
     */
    public $validar_precio_minimo;

    /**
     *
     * @var string
     */
    public $permisos;
}