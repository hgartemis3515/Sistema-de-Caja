<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="nombre controlador" content="<?php echo $this->router->getControllerName(); ?>">
	<meta name="accion controlador" content="<?php echo $this->router->getActionName(); ?>" >
	<link rel="canonical" href="https://arpsystem.com.pe/facturacionv8/<?php echo $this->router->getControllerName(); ?>/<?php echo $this->router->getActionName(); ?>" />
	<!-- Page title -->
    <title><?= $pageTitle ?? '' ?></title>
    <!-- /Page title -->

	<link rel="apple-touch-icon" sizes="180x180" href="/facturacionv8/public/img/favicons/apple-touch-icon-180x180.png" />
    <link rel="icon" type="image/png" href="/facturacionv8/public/img/favicons/logo_16x16.png" sizes="16x16" />
    <link rel="icon" type="image/png" href="/facturacionv8/public/img/favicons/logo_32x32.png" sizes="32x32" />
    <link rel="icon" type="image/png" href="/facturacionv8/public/img/favicons/logo_48x48.png" sizes="48x48" />
    <link rel="icon" type="image/png" href="/facturacionv8/public/img/favicons/logo_128x128.png" sizes="128x128" />

	<!-- Global stylesheets -->
	<link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">
	{{ stylesheet_link("templates_lm/global_assets/css/icons/icomoon/styles.css") }}
	{{ stylesheet_link("templates_lm/layout_5/assets/css/bootstrap.min.css") }}
	{{ stylesheet_link("templates_lm/layout_5/assets/css/bootstrap_limitless.min.css") }}
	{{ stylesheet_link("templates_lm/layout_5/assets/css/layout.min.css") }}
	{{ stylesheet_link("templates_lm/layout_5/assets/css/components.min.css") }}
	{{ stylesheet_link("templates_lm/layout_5/assets/css/colors.min.css") }}
	<!-- /global stylesheets -->

	<!-- Core JS files -->
	{{ javascript_include("templates_lm/global_assets/js/main/jquery.min.js") }}
	{{ javascript_include("templates_lm/global_assets/js/main/bootstrap.bundle.min.js") }}
	{{ javascript_include("templates_lm/global_assets/js/plugins/loaders/blockui.min.js") }}
	{{ javascript_include("templates_lm/global_assets/js/plugins/ui/slinky.min.js") }}
	{{ javascript_include("templates_lm/global_assets/js/plugins/ui/fab.min.js") }}
	{{ javascript_include("templates_lm/global_assets/js/plugins/ui/ripple.min.js") }}
	<!-- /core JS files -->

	<!-- Theme JS files -->
	{{ assets.outputJs() }}
	<!-- /theme JS files -->

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

	<!-- Page header -->
	<div class="page-header page-header-dark bg-indigo">

		<!-- Main navbar -->
		<div class="navbar navbar-expand-md navbar-dark bg-indigo border-transparent shadow-0">
			<div class="navbar-brand wmin-0 mr-5">
				<a href="index.html" class="d-inline-block">
					<img src="../../../../global_assets/images/logo_light.png" alt="">
				</a>
			</div>

			<div class="d-md-none">
				<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-mobile">
					<i class="icon-tree5"></i>
				</button>
			</div>

			<div class="collapse navbar-collapse" id="navbar-mobile">
				<span class="navbar-text">
					<span class="badge bg-success-400">Premium</span>
				</span>

				<ul class="navbar-nav ml-md-3">
					<li class="nav-item dropdown">
						<a href="#" class="navbar-nav-link dropdown-toggle caret-0" data-toggle="dropdown">
							<i class="icon-people"></i>
							<span class="d-md-none ml-2">Users</span>
							<span class="badge badge-mark border-orange-300 ml-auto ml-md-0"></span>
						</a>
						
						<div class="dropdown-menu dropdown-content wmin-md-300">
							<div class="dropdown-content-header">
								<span class="font-weight-semibold">Users online</span>
								<a href="#" class="text-default"><i class="icon-search4 font-size-base"></i></a>
							</div>

							<div class="dropdown-content-body dropdown-scrollable">
								<ul class="media-list">
									<li class="media">
										<div class="mr-3">
											<img src="../../../../global_assets/images/placeholders/placeholder.jpg" width="36" height="36" class="rounded-circle" alt="">
										</div>
										<div class="media-body">
											<a href="#" class="media-title font-weight-semibold">Jordana Ansley</a>
											<span class="d-block text-muted font-size-sm">Lead web developer</span>
										</div>
										<div class="ml-3 align-self-center"><span class="badge badge-mark border-success"></span></div>
									</li>

									<li class="media">
										<div class="mr-3">
											<img src="../../../../global_assets/images/placeholders/placeholder.jpg" width="36" height="36" class="rounded-circle" alt="">
										</div>
										<div class="media-body">
											<a href="#" class="media-title font-weight-semibold">Will Brason</a>
											<span class="d-block text-muted font-size-sm">Marketing manager</span>
										</div>
										<div class="ml-3 align-self-center"><span class="badge badge-mark border-danger"></span></div>
									</li>
								</ul>
							</div>

							<div class="dropdown-content-footer bg-light">
								<a href="#" class="text-grey mr-auto">All users</a>
								<a href="#" class="text-grey"><i class="icon-gear"></i></a>
							</div>
						</div>
					</li>
				</ul>

				<ul class="navbar-nav ml-md-auto">
					<li class="nav-item dropdown">
						<a href="#" class="navbar-nav-link dropdown-toggle caret-0" data-toggle="dropdown">
							<i class="icon-pulse2"></i>
							<span class="d-md-none ml-2">Activity</span>
							<span class="badge badge-mark border-orange-300 ml-auto ml-md-0"></span>
						</a>
						
						<div class="dropdown-menu dropdown-menu-right dropdown-content wmin-md-350">
							<div class="dropdown-content-header">
								<span class="font-weight-semibold">Latest activity</span>
								<a href="#" class="text-default"><i class="icon-search4 font-size-base"></i></a>
							</div>

							<div class="dropdown-content-body dropdown-scrollable">
								<ul class="media-list">
									<li class="media">
										<div class="mr-3">
											<a href="#" class="btn bg-success-400 rounded-round btn-icon"><i class="icon-mention"></i></a>
										</div>

										<div class="media-body">
											<a href="#">Taylor Swift</a> mentioned you in a post "Angular JS. Tips and tricks"
											<div class="font-size-sm text-muted mt-1">4 minutes ago</div>
										</div>
									</li>

									<li class="media">
										<div class="mr-3">
											<a href="#" class="btn bg-pink-400 rounded-round btn-icon"><i class="icon-paperplane"></i></a>
										</div>
										
										<div class="media-body">
											Special offers have been sent to subscribed users by <a href="#">Donna Gordon</a>
											<div class="font-size-sm text-muted mt-1">36 minutes ago</div>
										</div>
									</li>
								</ul>
							</div>

							<div class="dropdown-content-footer bg-light">
								<a href="#" class="text-grey mr-auto">All activity</a>
								<div>
									<a href="#" class="text-grey" data-popup="tooltip" title="Clear list"><i class="icon-checkmark3"></i></a>
									<a href="#" class="text-grey ml-2" data-popup="tooltip" title="Settings"><i class="icon-gear"></i></a>
								</div>
							</div>
						</div>
					</li>

					<li class="nav-item dropdown dropdown-user">
						<a href="#" class="navbar-nav-link dropdown-toggle" data-toggle="dropdown">
							<img src="../../../../global_assets/images/placeholders/placeholder.jpg" class="rounded-circle" alt="">
							<span>Victoria</span>
						</a>

						<div class="dropdown-menu dropdown-menu-right">
							<a href="#" class="dropdown-item"><i class="icon-user-plus"></i> My profile</a>
							<a href="#" class="dropdown-item"><i class="icon-coins"></i> My balance</a>
							<a href="#" class="dropdown-item"><i class="icon-comment-discussion"></i> Messages <span class="badge badge-pill bg-blue ml-auto">58</span></a>
							<div class="dropdown-divider"></div>
							<a href="#" class="dropdown-item"><i class="icon-cog5"></i> Account settings</a>
							<a href="#" class="dropdown-item"><i class="icon-switch2"></i> Logout</a>
						</div>
					</li>
				</ul>
			</div>
		</div>
		<!-- /main navbar -->


		<!-- Page header content -->
		<div class="page-header-content header-elements-md-inline">
			<div class="page-title d-flex">
				<h4>Dashboard <small class="font-size-base opacity-50">Good morning, Eugene</small></h4>
				<a href="#" class="header-elements-toggle text-white d-md-none"><i class="icon-more"></i></a>
			</div>

			<div class="header-elements d-none bg-transparent py-0 border-0 mb-3 mb-md-0">
				<form action="#">
					<div class="form-group form-group-feedback form-group-feedback-right">
						<input type="search" class="form-control text-white border-bottom-1 wmin-md-200" placeholder="Search">
						<div class="form-control-feedback text-white">
							<i class="icon-search4 font-size-sm"></i>
						</div>
					</div>
				</form>
			</div>
		</div>
		<!-- /page header content -->


		<!-- Secondary navbar -->
		<div class="navbar navbar-expand-md navbar-dark bg-indigo border-0 shadow-0">
			<div class="d-md-none w-100">
				<button type="button" class="navbar-toggler d-flex align-items-center w-100" data-toggle="collapse" data-target="#navbar-navigation">
					<i class="icon-menu-open mr-2"></i>
					Main navigation
				</button>
			</div>

			<div class="navbar-collapse collapse" id="navbar-navigation">
				<ul class="navbar-nav navbar-nav-highlight">
					<li class="nav-item">
						<a href="index.html" class="navbar-nav-link active">
							<i class="icon-home4 mr-2"></i>
							Dashboard
						</a>
					</li>
				</ul>

				<ul class="navbar-nav navbar-nav-highlight ml-md-auto">
					<li class="nav-item dropdown">
						<a href="#" class="navbar-nav-link dropdown-toggle" data-toggle="dropdown">
							<i class="icon-make-group mr-2"></i>
							Connect
						</a>

						<div class="dropdown-menu dropdown-menu-right dropdown-content wmin-md-350">
							<div class="dropdown-content-body p-2">
								<div class="row no-gutters">
									<div class="col-12 col-sm-4">
										<a href="#" class="d-block text-default text-center ripple-dark rounded p-3">
											<i class="icon-github4 icon-2x"></i>
											<div class="font-size-sm font-weight-semibold text-uppercase mt-2">Github</div>
										</a>

										<a href="#" class="d-block text-default text-center ripple-dark rounded p-3">
											<i class="icon-dropbox text-blue-400 icon-2x"></i>
											<div class="font-size-sm font-weight-semibold text-uppercase mt-2">Dropbox</div>
										</a>
									</div>
									
									<div class="col-12 col-sm-4">
										<a href="#" class="d-block text-default text-center ripple-dark rounded p-3">
											<i class="icon-dribbble3 text-pink-400 icon-2x"></i>
											<div class="font-size-sm font-weight-semibold text-uppercase mt-2">Dribbble</div>
										</a>

										<a href="#" class="d-block text-default text-center ripple-dark rounded p-3">
											<i class="icon-google-drive text-success-400 icon-2x"></i>
											<div class="font-size-sm font-weight-semibold text-uppercase mt-2">Drive</div>
										</a>
									</div>

									<div class="col-12 col-sm-4">
										<a href="#" class="d-block text-default text-center ripple-dark rounded p-3">
											<i class="icon-twitter text-info-400 icon-2x"></i>
											<div class="font-size-sm font-weight-semibold text-uppercase mt-2">Twitter</div>
										</a>

										<a href="#" class="d-block text-default text-center ripple-dark rounded p-3">
											<i class="icon-youtube text-danger icon-2x"></i>
											<div class="font-size-sm font-weight-semibold text-uppercase mt-2">Youtube</div>
										</a>
									</div>
								</div>
							</div>
						</div>
					</li>

					<li class="nav-item dropdown">
						<a href="#" class="navbar-nav-link dropdown-toggle" data-toggle="dropdown">
							<i class="icon-cog3"></i>
							<span class="d-md-none ml-2">Settings</span>
						</a>

						<div class="dropdown-menu dropdown-menu-right">
							<a href="#" class="dropdown-item"><i class="icon-user-lock"></i> Account security</a>
							<a href="#" class="dropdown-item"><i class="icon-statistics"></i> Analytics</a>
							<a href="#" class="dropdown-item"><i class="icon-accessibility"></i> Accessibility</a>
							<div class="dropdown-divider"></div>
							<a href="#" class="dropdown-item"><i class="icon-gear"></i> All settings</a>
						</div>
					</li>
				</ul>
			</div>
		</div>
		<!-- /secondary navbar -->


		<!-- Floating menu -->
		<ul class="fab-menu fab-menu-absolute fab-menu-top-right" data-fab-toggle="click">
			<li>
				<a class="fab-menu-btn btn bg-pink-300 btn-float rounded-round btn-icon">
					<i class="fab-icon-open icon-plus3"></i>
					<i class="fab-icon-close icon-cross2"></i>
				</a>

				<ul class="fab-menu-inner">
					<li>
						<div data-fab-label="Compose email">
							<a href="#" class="btn btn-light rounded-round btn-icon btn-float">
								<i class="icon-pencil"></i>
							</a>
						</div>
					</li>
					<li>
						<div data-fab-label="Conversations">
							<a href="#" class="btn btn-light rounded-round btn-icon btn-float">
								<i class="icon-bubbles7"></i>
							</a>
							<span class="badge badge-pill bg-primary-400">5</span>
						</div>
					</li>
					<li>
						<div data-fab-label="Chat with Jack">
							<a href="#" class="btn bg-pink-400 rounded-round btn-icon btn-float">
								<img src="../../../../global_assets/images/placeholders/placeholder.jpg" class="img-fluid rounded-circle" alt="">
							</a>
							<span class="badge badge-mark border-pink-400"></span>
						</div>
					</li>
				</ul>
			</li>
		</ul>
		<!-- /floating menu -->

	</div>
	<!-- /page header -->
		

	<!-- Page content -->
	<div class="page-content">

		<!-- Main content -->
		<div class="content-wrapper">

			<!-- Content area -->
			{{ content() }}
			<!-- /content area -->

		</div>
		<!-- /main content -->

	</div>
	<!-- /page content -->


	<!-- Footer -->
	<div class="navbar navbar-expand-lg navbar-light">
		<div class="text-center d-lg-none w-100">
			<button type="button" class="navbar-toggler dropdown-toggle" data-toggle="collapse" data-target="#navbar-footer">
				<i class="icon-unfold mr-2"></i>
				Footer
			</button>
		</div>

		<div class="navbar-collapse collapse" id="navbar-footer">
			<span class="navbar-text">
				&copy; 2015 - 2018. <a href="#">Limitless Web App Kit</a> by <a href="http://themeforest.net/user/Kopyov" target="_blank">Eugene Kopyov</a>
			</span>

			<ul class="navbar-nav ml-lg-auto">
				<li class="nav-item"><a href="https://kopyov.ticksy.com/" class="navbar-nav-link" target="_blank"><i class="icon-lifebuoy mr-2"></i> Support</a></li>
				<li class="nav-item"><a href="http://demo.interface.club/limitless/docs/" class="navbar-nav-link" target="_blank"><i class="icon-file-text2 mr-2"></i> Docs</a></li>
				<li class="nav-item"><a href="https://themeforest.net/item/limitless-responsive-web-application-kit/13080328?ref=kopyov" class="navbar-nav-link font-weight-semibold"><span class="text-pink-400"><i class="icon-cart2 mr-2"></i> Purchase</span></a></li>
			</ul>
		</div>
	</div>
	<!-- /footer -->
		
</body>
</html>