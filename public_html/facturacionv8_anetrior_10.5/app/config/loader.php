<?php
use Phalcon\Autoload\Loader;

$loader = new Loader();

/**
 * We're a registering a set of directories taken from the configuration file
 */
$loader->setDirectories([
    $config->application->controllersDir,
    $config->application->pluginsDir,
    $config->application->libraryDir,
    $config->application->modelsDir,
    $config->application->formsDir
]);

$loader->setNamespaces([
    'Plugins' => $config->application->pluginsDir,
    // Agrega aquí otros namespaces según sea necesario
]);

$loader->setClasses([
    'Services'                      => APP_PATH . 'app/config/services.php',
    'Validaciones' 		            => APP_PATH . '/library/validaciones.php',
    'Themefacturapdf'     			=> APP_PATH . '/library/themespdf/themefacturapdf.php',
    'Themeboletapdf'     			=> APP_PATH . '/library/themespdf/themeboletapdf.php',
    'Themenotacreditopdf'     		=> APP_PATH . '/library/themespdf/themenotacreditopdf.php',
    'Themenotadebitopdf'     		=> APP_PATH . '/library/themespdf/themenotadebitopdf.php',
    'Themenotapedidopdf'     		=> APP_PATH . '/library/themespdf/themenotapedidopdf.php',
    'Themecotizacionpdf'     		=> APP_PATH . '/library/themespdf/themecotizacionpdf.php',
    'Themeguiaremisionpdf'     		=> APP_PATH . '/library/themespdf/themeguiaremisionpdf.php',

    //librerias adicionales
    'NumeroALetras'             => APP_PATH . '/library/NumeroALetras.php',
]);

/**
 * Registrar el autoloader
 */
$loader->register();