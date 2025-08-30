<!DOCTYPE html>
<html lang="en">

<head>
	<!-- Required meta tags always come first -->
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<title><?= $title ?></title>

	<!--Fonts-->
	<link
		href="https://fonts.googleapis.com/css?family=Roboto:400,100,100italic,300,300italic,400italic,500,500italic,700,700italic,900,900italic"
		rel="stylesheet" type="text/css">

	<!--Bootstrap-->
	<link rel="stylesheet" href="<?= base_url('/public/assets/theme/da-theme/css/bootstrap.min.css') ?>">
	<link rel="stylesheet" href="<?= base_url('/public/assets/theme/da-theme/css/bootstrap-theme.min.css') ?>">
	<!--Font Awesome-->
	<link rel="stylesheet" href="<?= base_url('/public/assets/theme/da-theme/css/font-awesome.min.css') ?>">
	<!--Owl Carousel-->
	<link rel="stylesheet"
		href="<?= base_url('/public/assets/theme/da-theme/vendors/owl.carousel/owl.carousel.css') ?>">
	<!--Magnific Popup-->
	<link rel="stylesheet"
		href="<?= base_url('/public/assets/theme/da-theme/vendors/magnific-popup/magnific-popup.css') ?>">
	<!-- RS5.0 Main Stylesheet -->
	<link rel="stylesheet" type="text/css"
		href="<?= base_url('/public/assets/theme/da-theme/vendors/revolution/css/settings.css') ?>">

	<!-- RS5.0 Layers and Navigation Styles -->
	<link rel="stylesheet" type="text/css"
		href="<?= base_url('/public/assets/theme/da-theme/vendors/revolution/css/layers.css') ?>">
	<link rel="stylesheet" type="text/css"
		href="<?= base_url('/public/assets/theme/da-theme/vendors/revolution/css/navigation.css') ?>">

	<!--Theme Styles-->
	<link rel="stylesheet" href="<?= base_url('/public/assets/theme/da-theme/css/style.css') ?>">
	<link rel="stylesheet" href="<?= base_url('/public/assets/theme/da-theme/css/theme.css') ?>">

	<!--[if lt IE 9]>
		<script src="<?= base_url('/public/assets/theme/da-theme/js/html5shiv.min.js') ?>"></script>
		<script src="<?= base_url('/public/assets/theme/da-theme/js/respond.min.js') ?>"></script>
	<![endif]-->

	<script src="<?= base_url('/public/assets/theme/da-theme/js/jquery-2.1.4.min.js') ?>"></script>
	<script src="<?= base_url('/public/assets/theme/da-theme/js/bootstrap.min.js') ?>"></script>

	<!--Magnific Popup-->
	<script
		src="<?= base_url('/public/assets/theme/da-theme/vendors/magnific-popup/jquery.magnific-popup.min.js') ?>"></script>
	<!--Owl Carousel-->
	<script src="<?= base_url('/public/assets/theme/da-theme/vendors/owl.carousel/owl.carousel.min.js') ?>"></script>
	<!--CounterUp-->
	<script src="<?= base_url('/public/assets/theme/da-theme/vendors/couterup/jquery.counterup.min.js') ?>"></script>
	<!--WayPoints-->
	<script src="<?= base_url('/public/assets/theme/da-theme/vendors/waypoint/waypoints.min.js') ?>"></script>
	<!-- RS5.0 Core JS Files -->
	<script
		src="<?= base_url('/public/assets/theme/da-theme/vendors/revolution/js/jquery.themepunch.tools.min.js?rev=5.0') ?>"></script>
	<script
		src="<?= base_url('/public/assets/theme/da-theme/vendors/revolution/js/jquery.themepunch.revolution.min.js?rev=5.0') ?>"></script>
	<!--RS5.0 Extensions-->
	<script
		src="<?= base_url('/public/assets/theme/da-theme/vendors/revolution/js/extensions/revolution.extension.video.min.js') ?>"></script>
	<script
		src="<?= base_url('/public/assets/theme/da-theme/vendors/revolution/js/extensions/revolution.extension.slideanims.min.js') ?>"></script>
	<script
		src="<?= base_url('/public/assets/theme/da-theme/vendors/revolution/js/extensions/revolution.extension.layeranimation.min.js') ?>"></script>
	<script
		src="<?= base_url('/public/assets/theme/da-theme/vendors/revolution/js/extensions/revolution.extension.navigation.min.js') ?>"></script>
</head>

<body>

	<!--Header-->
	<header class="top-bar row style2">
		<div class="container">
			<div class="welcome-message">
				<?= !empty($Pengaturan->deskripsi) ? esc($Pengaturan->deskripsi) : 'Selamat datang di ' . esc($Pengaturan->judul_app ?? $Pengaturan->judul) ?>
			</div>
			<ul class="nav top-nav">
				<?php if (!empty($Pengaturan->url)): ?>
					<li class="email">
						<a href="mailto:<?= esc($Pengaturan->url) ?>">
							<i class="fa fa-envelope-o"></i><?= esc($Pengaturan->url) ?>
						</a>
					</li>
				<?php endif; ?>
				<?php if (!empty($Pengaturan->alamat)): ?>
					<li>
						<a href="https://maps.google.com/?q=<?= urlencode($Pengaturan->alamat) ?>" target="_blank">
							<i class="fa fa-map-marker"></i><?= esc($Pengaturan->alamat) ?>
						</a>
					</li>
				<?php endif; ?>
				<?php if (!empty($Pengaturan->kota)): ?>
					<li>
						<a href="#">
							<i class="fa fa-building"></i><?= ucwords($Pengaturan->kota) ?>
						</a>
					</li>
				<?php endif; ?>
				<?php if (!empty($Pengaturan->telp)): ?>
					<li class="tel">
						<a href="tel:<?= esc($Pengaturan->telp) ?>">
							<i class="fa fa-phone"></i><?= esc($Pengaturan->telp) ?>
						</a>
					</li>
				<?php endif; ?>
			</ul>
		</div>
	</header>

	<!--Navigation-->
	<nav class="navbar navbar-default navbar-static-top style2">
		<div class="container">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#main_nav"
					aria-expanded="false">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a href="#" class="navbar-brand"><img
						src="<?= base_url('/public/assets/theme/da-theme/images/logo2.png') ?>" alt=""></a>
			</div>
			<div class="collapse navbar-collapse" id="main_nav">
				<a href="#" class="btn btn-outline blue pull-right hidden-xs hidden-sm">Sign In</a>
				<ul class="nav navbar-nav navbar-right">
					<li>
						<a href="#">Event</a>
					</li>
					<li>
						<a href="#">Berita</a>
					</li>
				</ul>
			</div>
		</div>
	</nav>

	<!--Slider Area-->
	<section class="row main-slider-area">
		<div id="slider2" class="rev_slider" data-version="5.0">
			<ul>
				<li data-transition="scaledownfromleft" data-title="Slide 1">
					<img src="<?= base_url('/public/assets/theme/da-theme/images/slide2.jpg') ?>" alt=""
						data-bgposition="center center" data-bgfit="cover" data-bgrepeat="no-repeat" class="rev-slidebg"
						data-no-retina>

					<div class="tp-caption welcome-Title welcome-Title2 color-fff tp-resizeme"
						data-x="['left','left','center']" data-hoffset="0" data-y="center"
						data-voffset="['-50','-50','-50','-50,'-50','-50']"
						data-fontsize="['36','36','32','30','30','24']"
						data-lineheight="['45','45','38','36','36','30']" data-width="none" data-height="none"
						data-whitespace="nowrap" data-transform_idle="o:1;"
						data-transform_in="x:-50px;opacity:0;s:1000;e:Power2.easeOut;"
						data-transform_out="opacity:0;s:1500;e:Power4.easeIn;" data-start="1000" data-splitin="none"
						data-splitout="none" data-responsive_offset="on">We are Digital Agency <br>Helping you Get your
						Sales
					</div>

					<a href="#" class="tp-caption welcome-Link btn btn-primary tp-resizeme"
						data-x="['left','left','center']" data-hoffset="['0','0','0','0']" data-y="center"
						data-voffset="['80','80','80','80','80','50']" data-fontsize="16" data-lineheight="48"
						data-width="none" data-height="none" data-whitespace="nowrap" data-transform_idle="o:1;"
						data-transform_in="x:-50px;opacity:0;s:1000;e:Power2.easeOut;"
						data-transform_out="opacity:0;s:1500;e:Power4.easeIn;" data-start="1500" data-splitin="none"
						data-splitout="none" data-responsive_offset="on">free analysis
					</a>

				</li>

				<!--<li data-transition="scaledownfromleft" data-title="Slide 2">
					  <img src="<?= base_url('/public/assets/theme/da-theme/images/slide4.jpg') ?>"  
						 alt=""
						 data-bgposition="center center" 
						 data-bgfit="cover" 
						 data-bgrepeat="no-repeat" 
						 class="rev-slidebg" data-no-retina>
					
					<div class="tp-caption welcome-Title welcome-Title3 color-fff tp-resizeme" 
						data-x="['left','left','center']" 
						data-hoffset="0" 
						data-y="top" 
						data-voffset="['5','0','0','50','50','20']" 
						data-fontsize="['36','32','32','30','30','24']" 
						data-lineheight="['45','38','38','36','36','30']" 
						data-width="none" 
						data-height="none" 
						data-whitespace="nowrap" 
						data-transform_idle="o:1;" 
						data-transform_in="x:-50px;opacity:0;s:1000;e:Power2.easeOut;" 
						data-transform_out="opacity:0;s:1500;e:Power4.easeIn;" 
						data-start="1000" 
						data-splitin="none" 
						data-splitout="none" 
						data-responsive_offset="on">We are Digital Agency <br>Helping you Get your Sales 
					</div>
					
					<div class="tp-caption welcome-Content welcome-Content2 tp-resizeme" 
						data-x="['left','left','center']" 
						data-hoffset="0" 
						data-y="top" 
						data-voffset="['125','100','120','125','125']" 
						data-fontsize="16" 
						data-lineheight="['30','24','24','24','24','24']" 
						data-width="['458','458','458','400','400','290']" 
						data-height="none" 
						data-whitespace="normal" 
						data-transform_idle="o:1;" 
						data-transform_in="x:-50px;opacity:0;s:1000;e:Power2.easeOut;" 
						data-transform_out="opacity:0;s:1500;e:Power4.easeIn;" 
						data-start="1500" 
						data-splitin="none" 
						data-splitout="none" 
						data-responsive_offset="on">Nullam lacinia dolor eu magna aliquet business placerat. Aliquam semper in the area of SEO Development.
					</div>
					
					<iframe src="https://www.youtube.com/embed/5NhIRwCq428" allowfullscreen class="tp-caption slide-Video tp-resizeme" 
						data-x="['right','right','center']" 
						data-hoffset="0" 
						data-y="center" 
						data-voffset="0" 
						data-width="['652','460','458','400','400','260']" 
						data-height="['437','320','458','400','400','240']" 
						data-whitespace="normal" 
						data-transform_idle="o:1;" 
						data-transform_in="x:50px;opacity:0;s:1000;e:Power2.easeOut;" 
						data-transform_out="opacity:0;s:1500;e:Power4.easeIn;" 
						data-start="1500" 
						data-splitin="none" 
						data-splitout="none" 
						data-responsive_offset="on"
						data-videoattributes="version=3&amp;enablejsapi=1&amp; html5=1&amp;volume=100&hd=1& wmode=opaque&showinfo=0&ref=0;&start=15&end=45; origin=http://yourdomain;">
					</iframe>
					
					<ul class="tp-caption list-unstyled slide-OrderedList check-o-list tp-resizeme" 
						data-x="left" 
						data-hoffset="0" 
						data-y="top" 
						data-voffset="['210','170','120','125','125']" 
						data-fontsize="15" 
						data-lineheight="['42','24','24','24','24','24']" 
						data-width="['458','458','458','400','400','290']" 
						data-height="none" 
						data-whitespace="normal" 
						data-transform_idle="o:1;" 
						data-transform_in="x:-50px;opacity:0;s:1000;e:Power2.easeOut;" 
						data-transform_out="opacity:0;s:1500;e:Power4.easeIn;" 
						data-start="1500" 
						data-splitin="none" 
						data-splitout="none" 
						data-responsive_offset="on">
						<li><i class="fa fa-check-circle-o"></i>Some of bulletted Ponts will appear here</li>
						<li><i class="fa fa-check-circle-o"></i>Fit and Fab offers 25% discount</li>
						<li><i class="fa fa-check-circle-o"></i>Cool Place to do your Hard Workouts</li>
					</ul>
					
					<a href="#" class="tp-caption welcome-Link btn btn-primary tp-resizeme" 
						data-x="['left','left','center']" 
						data-hoffset="['0','0','0','0']" 
						data-y="['top','top','bottom']" 
						data-voffset="['380','270','50','50','50','50']" 
						data-fontsize="16" 
						data-lineheight="48" 
						data-width="none" 
						data-height="none" 
						data-whitespace="nowrap" 
						data-transform_idle="o:1;" 
						data-transform_in="x:-50px;opacity:0;s:1000;e:Power2.easeOut;" 
						data-transform_out="opacity:0;s:1500;e:Power4.easeIn;" 
						data-start="1500" 
						data-splitin="none" 
						data-splitout="none" 
						data-responsive_offset="on">free analysis
					</a>
					
				  </li>-->

				<li data-transition="scaledownfromleft" data-title="Slide 2">
					<img src="<?= base_url('/public/assets/theme/da-theme/images/slide4.jpg') ?>" alt=""
						data-bgposition="center center" data-bgfit="cover" data-bgrepeat="no-repeat" class="rev-slidebg"
						data-no-retina>

					<div class="tp-caption welcome-Title welcome-Title2 color-fff tp-resizeme"
						data-x="['left','left','center']" data-hoffset="0" data-y="center"
						data-voffset="['-50','-50','-50','-50,'-50','-50']"
						data-fontsize="['36','36','32','30','30','24']"
						data-lineheight="['45','45','38','36','36','30']" data-width="none" data-height="none"
						data-whitespace="nowrap" data-transform_idle="o:1;"
						data-transform_in="x:-50px;opacity:0;s:1000;e:Power2.easeOut;"
						data-transform_out="opacity:0;s:1500;e:Power4.easeIn;" data-start="1000" data-splitin="none"
						data-splitout="none" data-responsive_offset="on">We are Digital Agency <br>Helping you Get your
						Sales
					</div>

					<a href="#" class="tp-caption welcome-Link btn btn-primary tp-resizeme"
						data-x="['left','left','center']" data-hoffset="['0','0','0','0']" data-y="center"
						data-voffset="['80','80','80','80','80','50']" data-fontsize="16" data-lineheight="48"
						data-width="none" data-height="none" data-whitespace="nowrap" data-transform_idle="o:1;"
						data-transform_in="x:-50px;opacity:0;s:1000;e:Power2.easeOut;"
						data-transform_out="opacity:0;s:1500;e:Power4.easeIn;" data-start="1500" data-splitin="none"
						data-splitout="none" data-responsive_offset="on">free analysis
					</a>

				</li>

				<li data-transition="scaledownfromleft" data-title="Slide 3">
					<img src="<?= base_url('/public/assets/theme/da-theme/images/slide.jpg') ?>" alt=""
						data-bgposition="center center" data-bgfit="cover" data-bgrepeat="no-repeat" class="rev-slidebg"
						data-no-retina>

					<div class="tp-caption welcome-Title welcome-Title2 color-fff tp-resizeme"
						data-x="['left','left','center']" data-hoffset="0" data-y="center"
						data-voffset="['-50','-50','-50','-50,'-50','-50']"
						data-fontsize="['36','36','32','30','30','24']"
						data-lineheight="['45','45','38','36','36','30']" data-width="none" data-height="none"
						data-whitespace="nowrap" data-transform_idle="o:1;"
						data-transform_in="x:-50px;opacity:0;s:1000;e:Power2.easeOut;"
						data-transform_out="opacity:0;s:1500;e:Power4.easeIn;" data-start="1000" data-splitin="none"
						data-splitout="none" data-responsive_offset="on">We are Digital Agency <br>Helping you Get your
						Sales
					</div>

					<a href="#" class="tp-caption welcome-Link btn btn-primary tp-resizeme"
						data-x="['left','left','center']" data-hoffset="['0','0','0','0']" data-y="center"
						data-voffset="['80','80','80','80','80','50']" data-fontsize="16" data-lineheight="48"
						data-width="none" data-height="none" data-whitespace="nowrap" data-transform_idle="o:1;"
						data-transform_in="x:-50px;opacity:0;s:1000;e:Power2.easeOut;"
						data-transform_out="opacity:0;s:1500;e:Power4.easeIn;" data-start="1500" data-splitin="none"
						data-splitout="none" data-responsive_offset="on">free analysis
					</a>

				</li>
			</ul>
		</div>
	</section>

	<!--Feature Services Blocks-->
	<section class="row featured-service-blocks style2">
		<div class="container">
			<div class="col-sm-4 featured-service-block style3">
				<div class="icon-holder"><img
						src="<?= base_url('/public/assets/theme/da-theme/images/icons/service/14.png') ?>" alt=""></div>
				<h3 class="this-title"><a
						href="<?= base_url('/public/assets/theme/da-theme/single-service.html') ?>">Helping you to
						increase your site traffic</a></h3>
				<p class="this-summary">Nullam lacinia dolor eu magna aliquet business placerat. Aliquam semper in the
					area of SEO Development firm is very hard.</p>
				<a href="<?= base_url('/public/assets/theme/da-theme/single-service2.html') ?>" class="more">read
					more</a>
			</div>
			<div class="col-sm-4 featured-service-block style3">
				<div class="icon-holder"><img
						src="<?= base_url('/public/assets/theme/da-theme/images/icons/service/15.png') ?>" alt=""></div>
				<h3 class="this-title"><a
						href="<?= base_url('/public/assets/theme/da-theme/single-service5.html') ?>">Fruitfull Results
						in provided Timeline guaranteed</a></h3>
				<p class="this-summary">Nullam lacinia dolor eu magna aliquet business placerat. Aliquam semper in the
					area of SEO Development firm is very hard.</p>
				<a href="<?= base_url('/public/assets/theme/da-theme/single-service2.html') ?>" class="more">read
					more</a>
			</div>
			<div class="col-sm-4 featured-service-block style3">
				<div class="icon-holder"><img
						src="<?= base_url('/public/assets/theme/da-theme/images/icons/service/16.png') ?>" alt=""></div>
				<h3 class="this-title"><a href="<?= base_url('/public/assets/theme/da-theme/single-service.html') ?>">On
						Page &amp; Off Page SEO in Competitive Price</a></h3>
				<p class="this-summary">Nullam lacinia dolor eu magna aliquet business placerat. Aliquam semper in the
					area of SEO Development firm is very hard.</p>
				<a href="<?= base_url('/public/assets/theme/da-theme/single-service2.html') ?>" class="more">read
					more</a>
			</div>
		</div>
	</section>

	<!--Product Services-->
	<section class="row">
		<div class="container">
			<div class="row">
				<div class="col-md-12 shop-content">
					<div class="row">
						<div class="col-sm-3 product">
							<div class="img-holder row">
								<img src="<?= base_url('/public/assets/theme/da-theme/images/Shop/2.jpg') ?>" alt="" class="product-img">
								<div class="sale-new-tag">sale</div>
								<div class="hover-box">
									<div class="btn-holder">
										<div class="row m0"><a href="#" class="btn btn-outline blue">Add to Cart</a>
										</div>
									</div>
								</div>
							</div>
							<a href="single-product.html">
								<h4 class="pro-title">Flying Ninja T-Shirt</h4>
							</a>
							<p class="pro-about">Curabitur hendrerit fringilla ed enim act elit accumsan hendrerit leo.
							</p>
							<div class="row m0 proRating">
								<i class="fa fa-star starred"></i>
								<i class="fa fa-star starred"></i>
								<i class="fa fa-star starred"></i>
								<i class="fa fa-star starred"></i>
								<i class="fa fa-star"></i>
							</div>
							<h3 class="price"><del>Rp. 1.200.000</del>Rp. 975.000</h3>
						</div>
						<div class="col-sm-3 product">
							<div class="img-holder row">
								<img src="<?= base_url('/public/assets/theme/da-theme/images/Shop/2.jpg') ?>" alt="" class="product-img">
								<div class="hover-box">
									<div class="btn-holder">
										<div class="row m0"><a href="#" class="btn btn-outline blue">Add to Cart</a>
										</div>
									</div>
								</div>
							</div>
							<a href="single-product.html">
								<h4 class="pro-title">Black Color Shirt</h4>
							</a>
							<p class="pro-about">Curabitur hendrerit fringilla ed enim act elit accumsan hendrerit leo.
							</p>
							<div class="row m0 proRating">
								<i class="fa fa-star starred"></i>
								<i class="fa fa-star starred"></i>
								<i class="fa fa-star starred"></i>
								<i class="fa fa-star starred"></i>
								<i class="fa fa-star"></i>
							</div>
							<h3 class="price"><del>Rp. 1.200.000</del>Rp. 975.000</h3>
						</div>
						<div class="col-sm-3 product">
							<div class="img-holder row">
								<img src="<?= base_url('/public/assets/theme/da-theme/images/Shop/2.jpg') ?>" alt="" class="product-img">
								<div class="hover-box">
									<div class="btn-holder">
										<div class="row m0"><a href="#" class="btn btn-outline blue">Add to Cart</a>
										</div>
									</div>
								</div>
							</div>
							<a href="single-product.html">
								<h4 class="pro-title">Flying Ninja T-Shirt</h4>
							</a>
							<p class="pro-about">Curabitur hendrerit fringilla ed enim act elit accumsan hendrerit leo.
							</p>
							<div class="row m0 proRating">
								<i class="fa fa-star starred"></i>
								<i class="fa fa-star starred"></i>
								<i class="fa fa-star starred"></i>
								<i class="fa fa-star starred"></i>
								<i class="fa fa-star"></i>
							</div>
							<h3 class="price"><del>Rp. 1.200.000</del>Rp. 975.000</h3>
						</div>
						<div class="col-sm-3 product">
							<div class="img-holder row">
								<img src="<?= base_url('/public/assets/theme/da-theme/images/Shop/2.jpg') ?>" alt="" class="product-img">
								<div class="hover-box">
									<div class="btn-holder">
										<div class="row m0"><a href="#" class="btn btn-outline blue">Add to Cart</a>
										</div>
									</div>
								</div>
							</div>
							<a href="single-product.html">
								<h4 class="pro-title">Flying Ninja T-Shirt</h4>
							</a>
							<p class="pro-about">Curabitur hendrerit fringilla ed enim act elit accumsan hendrerit leo.
							</p>
							<div class="row m0 proRating">
								<i class="fa fa-star starred"></i>
								<i class="fa fa-star starred"></i>
								<i class="fa fa-star starred"></i>
								<i class="fa fa-star starred"></i>
								<i class="fa fa-star"></i>
							</div>
							<h3 class="price"><del>Rp. 1.200.000</del>Rp. 975.000</h3>
						</div>
						<div class="col-sm-3 product">
							<div class="img-holder row">
								<img src="<?= base_url('/public/assets/theme/da-theme/images/Shop/2.jpg') ?>" alt="" class="product-img">
								<div class="hover-box">
									<div class="btn-holder">
										<div class="row m0"><a href="#" class="btn btn-outline blue">Add to Cart</a>
										</div>
									</div>
								</div>
							</div>
							<a href="single-product.html">
								<h4 class="pro-title">Black Color Shirt</h4>
							</a>
							<p class="pro-about">Curabitur hendrerit fringilla ed enim act elit accumsan hendrerit leo.
							</p>
							<div class="row m0 proRating">
								<i class="fa fa-star starred"></i>
								<i class="fa fa-star starred"></i>
								<i class="fa fa-star starred"></i>
								<i class="fa fa-star starred"></i>
								<i class="fa fa-star"></i>
							</div>
							<h3 class="price"><del>Rp. 1.200.000</del>Rp. 975.000</h3>
						</div>
						<div class="col-sm-3 product">
							<div class="img-holder row">
								<img src="<?= base_url('/public/assets/theme/da-theme/images/Shop/2.jpg') ?>" alt="" class="product-img">
								<div class="hover-box">
									<div class="btn-holder">
										<div class="row m0"><a href="#" class="btn btn-outline blue">Add to Cart</a>
										</div>
									</div>
								</div>
							</div>
							<a href="single-product.html">
								<h4 class="pro-title">Flying Ninja T-Shirt</h4>
							</a>
							<p class="pro-about">Curabitur hendrerit fringilla ed enim act elit accumsan hendrerit leo.
							</p>
							<div class="row m0 proRating">
								<i class="fa fa-star starred"></i>
								<i class="fa fa-star starred"></i>
								<i class="fa fa-star starred"></i>
								<i class="fa fa-star starred"></i>
								<i class="fa fa-star"></i>
							</div>
							<h3 class="price"><del>Rp. 1.200.000</del>Rp. 975.000</h3>
						</div>
						<div class="col-sm-3 product">
							<div class="img-holder row">
								<img src="<?= base_url('/public/assets/theme/da-theme/images/Shop/2.jpg') ?>" alt="" class="product-img">
								<div class="hover-box">
									<div class="btn-holder">
										<div class="row m0"><a href="#" class="btn btn-outline blue">Add to Cart</a>
										</div>
									</div>
								</div>
							</div>
							<a href="single-product.html">
								<h4 class="pro-title">Flying Ninja T-Shirt</h4>
							</a>
							<p class="pro-about">Curabitur hendrerit fringilla ed enim act elit accumsan hendrerit leo.
							</p>
							<div class="row m0 proRating">
								<i class="fa fa-star starred"></i>
								<i class="fa fa-star starred"></i>
								<i class="fa fa-star starred"></i>
								<i class="fa fa-star starred"></i>
								<i class="fa fa-star"></i>
							</div>
							<h3 class="price"><del>Rp. 1.200.000</del>Rp. 975.000</h3>
						</div>
						<div class="col-sm-3 product">
							<div class="img-holder row">
								<img src="<?= base_url('/public/assets/theme/da-theme/images/Shop/2.jpg') ?>" alt="" class="product-img">
								<div class="hover-box">
									<div class="btn-holder">
										<div class="row m0"><a href="#" class="btn btn-outline blue">Add to Cart</a>
										</div>
									</div>
								</div>
							</div>
							<a href="single-product.html">
								<h4 class="pro-title">Black Color Shirt</h4>
							</a>
							<p class="pro-about">Curabitur hendrerit fringilla ed enim act elit accumsan hendrerit leo.
							</p>
							<div class="row m0 proRating">
								<i class="fa fa-star starred"></i>
								<i class="fa fa-star starred"></i>
								<i class="fa fa-star starred"></i>
								<i class="fa fa-star starred"></i>
								<i class="fa fa-star"></i>
							</div>
							<h3 class="price"><del>Rp. 1.200.000</del>Rp. 975.000</h3>
						</div>
						<div class="col-sm-3 product">
							<div class="img-holder row">
								<img src="<?= base_url('/public/assets/theme/da-theme/images/Shop/2.jpg') ?>" alt="" class="product-img">
								<div class="hover-box">
									<div class="btn-holder">
										<div class="row m0"><a href="#" class="btn btn-outline blue">Add to Cart</a>
										</div>
									</div>
								</div>
							</div>
							<a href="single-product.html">
								<h4 class="pro-title">Flying Ninja T-Shirt</h4>
							</a>
							<p class="pro-about">Curabitur hendrerit fringilla ed enim act elit accumsan hendrerit leo.
							</p>
							<div class="row m0 proRating">
								<i class="fa fa-star starred"></i>
								<i class="fa fa-star starred"></i>
								<i class="fa fa-star starred"></i>
								<i class="fa fa-star starred"></i>
								<i class="fa fa-star"></i>
							</div>
							<h3 class="price"><del>Rp. 1.200.000</del>Rp. 975.000</h3>
						</div>
					</div>
					<ul class="pagination">
						<li class="active"><a href="#">1</a></li>
						<li><a href="#">2</a></li>
						<li><a href="#">3</a></li>
						<li><a href="#">4</a></li>
					</ul>
				</div>
			</div>
		</div>
	</section>

	<!--Testimonial-->
	<section class="row testimonials style2">
		<div class="container">
			<div class="row quote-sign text-center"><img
					src="<?= base_url('/public/assets/theme/da-theme/images/quote-sign.png') ?>" alt=""></div>
			<div class="row">
				<div class="testimonial-slides">
					<div class="item">
						<div class="row client-img"><img
								src="<?= base_url('/public/assets/theme/da-theme/images/testimonial.jpg') ?>" alt="">
						</div>
						<div class="row quotes">“ Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam
							nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim
							ad minim veniam, quis nostrud exerci ullamcorper suscipit lobortis nisl ut aliquip ex ea
							commodo ."</div>
						<h4 class="client-id">Johnathan, <span>Theme Designer</span></h4>
					</div>
					<div class="item">
						<div class="row client-img"><img
								src="<?= base_url('/public/assets/theme/da-theme/images/testimonial.jpg') ?>" alt="">
						</div>
						<div class="row quotes">“ Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam
							nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim
							ad minim veniam, quis nostrud exerci ullamcorper suscipit lobortis nisl ut aliquip ex ea
							commodo ."</div>
						<h4 class="client-id">Johnathan, <span>Theme Designer</span></h4>
					</div>
					<div class="item">
						<div class="row client-img"><img
								src="<?= base_url('/public/assets/theme/da-theme/images/testimonial.jpg') ?>" alt="">
						</div>
						<div class="row quotes">“ Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam
							nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim
							ad minim veniam, quis nostrud exerci ullamcorper suscipit lobortis nisl ut aliquip ex ea
							commodo ."</div>
						<h4 class="client-id">Johnathan, <span>Theme Designer</span></h4>
					</div>
					<div class="item">
						<div class="row client-img"><img
								src="<?= base_url('/public/assets/theme/da-theme/images/testimonial.jpg') ?>" alt="">
						</div>
						<div class="row quotes">“ Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam
							nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim
							ad minim veniam, quis nostrud exerci ullamcorper suscipit lobortis nisl ut aliquip ex ea
							commodo ."</div>
						<h4 class="client-id">Johnathan, <span>Theme Designer</span></h4>
					</div>
				</div>
			</div>
		</div>
	</section>

	<!--Footer-->
	<footer class="row site-footer">
		<div class="container">
			<div class="row">
				<div class="col-md-7 col-md-push-5 half-side">
					<div class="footer-contact row footer-widget right-box">
						<h3 class="footer-title">Kontak Kami</h3>
						<ul class="nav">
							<li class="tel"><a href="#"><i class="fa fa-phone"></i>0 (877) 123-4567</a></li>
							<li class="email"><a href="mailto:info@yoursite.com"><i	class="fa fa-envelope-o"></i>info@yoursite.com</a></li>
							<li><a href="#"><i class="fa fa-facebook"></i></a></li>
							<li><a href="#"><i class="fa fa-twitter"></i></a></li>
							<li><a href="#"><i class="fa fa-linkedin"></i></a></li>
							<li><a href="#"><i class="fa fa-youtube-play"></i></a></li>
						</ul>
					</div>
				</div>
				<div class="col-md-5 col-md-pull-7 half-side">
					<div class="footer-about left-box footer-widget row">
						<h3 class="footer-title">About Us</h3>
						<p>Lorem ipsum dolor sit amet, consectetur some dymmy adipiscing elit. Nam turpis quam, sodales
							in text she ante sagittis, varius efficitur mauris. Nam turpis quam, sodales in text should
							be able. to...</p>
					</div>
					<div class="copyright-row left-box footer-widget row">
						&copy; Copyright <?= date('Y') ?> - <?= esc($Pengaturan->judul_app ?? $Pengaturan->judul ?? 'Your Site') ?>. All Rights Reserved.
					</div>
				</div>
			</div>
		</div>
	</footer>

	<!--Theme Script-->
	<script src="<?= base_url('/public/assets/theme/da-theme/js/theme.js') ?>"></script>
</body>

</html>