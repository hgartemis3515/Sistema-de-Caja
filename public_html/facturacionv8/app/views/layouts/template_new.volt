<!DOCTYPE html>
<html lang="es">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<link rel="canonical" href="https://arpsystem.com.pe/facturacionv8/<?php echo $this->router->getControllerName(); ?>/<?php echo $this->router->getActionName(); ?>" />
		<meta property="og:url" content="https://arpsystem.com.pe/facturacionv8/<?php echo $this->router->getControllerName(); ?>/<?php echo $this->router->getActionName(); ?>" />
		
		<link rel="icon" type="image/png" sizes="16x16" href='<?php echo $data_empresa["logo_img_56"]; ?>'>
		<meta property="og:url" content="https://<?php echo $data_empresa['url_domain']; ?>/facturacionv8/<?php echo $this->router->getControllerName(); ?>/<?php echo $this->router->getActionName(); ?>" />
		<meta property="og:image" content='https://<?php echo $data_empresa["url_domain"]; ?><?php echo $data_empresa["logo_img_56"]; ?>' />
		
		<!-- Page title -->
		<title><?= $pageTitle ?? '' ?></title>
		<!-- /Page title -->
		
		<?php
		if(isset($metatag)) {
			echo $metatag;
		}
		?>

		<!-- Global stylesheets -->
		<link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">
		{{ stylesheet_link("template_new/global_assets/css/icons/icomoon/styles.css") }}
		{{ stylesheet_link("template_new/global_assets/css/icons/fontawesome/styles.min.css") }}
		{{ stylesheet_link("template_new/theme_1/css/bootstrap.min.css") }}
		{{ stylesheet_link("template_new/theme_1/css/core.min.css") }}
		{{ stylesheet_link("template_new/theme_1/css/components.min.css") }}
		{{ stylesheet_link("template_new/theme_1/css/colors.min.css") }}
		{{ stylesheet_link("template_new/global_assets/demo_data/fontawesome/flaticon.css") }}
		<!-- /global stylesheets -->
		
		<!-- Core JS files -->
		{{ javascript_include("template_new/global_assets/js/plugins/loaders/pace.min.js") }}
		{{ javascript_include("template_new/global_assets/js/core/libraries/jquery.min.js") }}
		{{ javascript_include("template_new/global_assets/js/core/libraries/bootstrap.min.js") }}
		{{ javascript_include("template_new/global_assets/js/plugins/loaders/blockui.min.js") }}
		{{ javascript_include("template_new/global_assets/js/plugins/notifications/sweet_alert.min.js") }}
		<!-- /core JS files -->

		<!-- Theme JS files -->
		{{ assets.outputJs() }}
		<!-- /theme JS files -->
	
		{{ assets.outputCss() }}

		<?php
		if(isset($HeadOutputJs)) {
			echo $HeadOutputJs;
		}
		?>

		<!-- Facebook Pixel Code -->
		<script>
		!function(f,b,e,v,n,t,s)
		{if(f.fbq)return;n=f.fbq=function(){n.callMethod?
		n.callMethod.apply(n,arguments):n.queue.push(arguments)};
		if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
		n.queue=[];t=b.createElement(e);t.async=!0;
		t.src=v;s=b.getElementsByTagName(e)[0];
		s.parentNode.insertBefore(t,s)}(window, document,'script',
		'https://connect.facebook.net/en_US/fbevents.js');
		fbq('init', '179248925778029');
		fbq('track', 'PageView');
		</script>
		<noscript><img height="1" width="1" style="display:none"
		src="https://www.facebook.com/tr?id=179248925778029&ev=PageView&noscript=1"
		/></noscript>
		<!-- End Facebook Pixel Code -->

	</head>

	<body>
		<!-- Main navbar -->
		<?php $this->partial('common/mainnavbar'); ?>
		<!-- /main navbar -->


		<!-- Page container -->
		<div class="page-container">

			<!-- Page content -->
			<div class="page-content">

				<!-- Main sidebar -->
				{{ partial('common/mainsidebar') }}
				<!-- /main sidebar -->


				<!-- Main content -->
				{{ content() }}
				<!-- /main content -->

			</div>
			<!-- /page content -->

		</div>
		<!-- /page container -->
	</body>
</html>