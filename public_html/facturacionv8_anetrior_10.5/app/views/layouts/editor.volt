<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="canonical" href="https://arpsystem.com.pe/facturacionv8/<?php echo $this->router->getControllerName(); ?>/<?php echo $this->router->getActionName(); ?>" />
    <?php
    if($data_empresa['id_contribuyente'] == 1) {
    ?>
        <link rel="apple-touch-icon" sizes="57x57" href="/facturacionv8/public/img/favicons/apple-icon-57x57.png">
        <link rel="apple-touch-icon" sizes="60x60" href="/facturacionv8/public/img/favicons/apple-icon-60x60.png">
        <link rel="apple-touch-icon" sizes="72x72" href="/facturacionv8/public/img/favicons/apple-icon-72x72.png">
        <link rel="apple-touch-icon" sizes="76x76" href="/facturacionv8/public/img/favicons/apple-icon-76x76.png">
        <link rel="apple-touch-icon" sizes="114x114" href="/facturacionv8/public/img/favicons/apple-icon-114x114.png">
        <link rel="apple-touch-icon" sizes="120x120" href="/facturacionv8/public/img/favicons/apple-icon-120x120.png">
        <link rel="apple-touch-icon" sizes="144x144" href="/facturacionv8/public/img/favicons/apple-icon-144x144.png">
        <link rel="apple-touch-icon" sizes="152x152" href="/facturacionv8/public/img/favicons/apple-icon-152x152.png">
        <link rel="apple-touch-icon" sizes="180x180" href="/facturacionv8/public/img/favicons/apple-icon-180x180.png">
        <link rel="icon" type="image/png" sizes="192x192"  href="/facturacionv8/public/img/favicons/android-icon-192x192.png">
        <link rel="icon" type="image/png" sizes="32x32" href="/facturacionv8/public/img/favicons/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="96x96" href="/facturacionv8/public/img/favicons/favicon-96x96.png">
        <link rel="icon" type="image/png" sizes="16x16" href="/facturacionv8/public/img/favicons/favicon-16x16.png">
        <link rel="manifest" href="/facturacionv8/public/img/favicons/manifest.json">
        <meta name="msapplication-TileColor" content="#ffffff">
        <meta name="msapplication-TileImage" content="/facturacionv8/public/img/favicons/ms-icon-144x144.png">
        <meta name="theme-color" content="#ffffff">
    <?php
    } else {
    ?>
        <link rel="icon" type="image/png" sizes="16x16" href='<?php echo $data_empresa["logo_img_56"]; ?>'>
        <meta property="og:url" content="https://<?php echo $data_empresa['url_domain']; ?>/facturacionv8/<?php echo $this->router->getControllerName(); ?>/<?php echo $this->router->getActionName(); ?>" />
        <meta property="og:image" content='https://<?php echo $data_empresa["url_domain"]; ?><?php echo $data_empresa["logo_img_56"]; ?>' />
    <?php
    }
    ?>
    <base href="/facturacionv8/editorjs/">
    <meta name="dominio" content="<?php echo $data_empresa['url_domain']; ?>">
    <!-- Page title -->
    <title><?= $pageTitle ?? '' ?></title>
    <!-- /Page title -->
    
    <?php
    if(isset($metatag)) {
        echo $metatag;
    }
	?>
	
	{{ assets.outputCss() }}
	
</head>
<body>
	{{ content() }}

	<!-- Theme JS files -->
    {{ assets.outputJs() }}
	<!-- /theme JS files -->
	
    <?php
    if(isset($HeadOutputJs)) {
        echo $HeadOutputJs;
    }
    ?>
</body>
</html>