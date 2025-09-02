<?php
/**
 * Created by: Mikhael Felian Waskito - mikhaelfelian@gmail.com
 * Date: 2025-08-29
 * Github : github.com/mikhaelfelian
 * description : Footer section template for Digital Agency theme
 * This file represents the footer section template for the Digital Agency theme.
 */
?>

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
						<h3 class="footer-title">Tentang Kami</h3>
						<p>Platform resmi manajemen tiket untuk konser, wisata, dan berbagai event spesial. Nikmati pengalaman pemesanan tiket yang mudah, aman, dan terpercaya hanya di WGPTIX.com.</p>
					</div>
					<div class="copyright-row left-box footer-widget row">
						&copy; Copyright <?= date('Y') ?> - <?= esc($Pengaturan->judul_app ?? $Pengaturan->judul ?? 'Your Site') ?>. All Rights Reserved.
					</div>
				</div>
			</div>
		</div>
	</footer>
