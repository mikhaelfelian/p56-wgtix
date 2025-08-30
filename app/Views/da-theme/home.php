<?php
/**
 * Created by: Mikhael Felian Waskito - mikhaelfelian@gmail.com
 * Date: 2025-08-29
 * Github : github.com/mikhaelfelian
 * description : Home page template for Digital Agency theme
 * This file represents the home page template for the Digital Agency theme.
 */

echo $this->extend('da-theme/layout/main');
echo $this->section('content');
?>

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
        </ul>
    </div>
</section>

<!--Feature Services Blocks-->
<section class="row featured-service-blocks style2">
    <div class="container">
        <div class="col-sm-4 featured-service-block style3">
            <div class="icon-holder">
                <img src="<?= base_url('/public/assets/theme/da-theme/images/icons/service/14.png') ?>" alt="">
            </div>
            <h3 class="this-title">
                <a href="#">Helping you to increase your site traffic</a>
            </h3>
            <p class="this-summary">Nullam lacinia dolor eu magna aliquet business placerat. Aliquam semper in the
                area of SEO Development firm is very hard.</p>
            <a href="#" class="more">read more</a>
        </div>
        <div class="col-sm-4 featured-service-block style3">
            <div class="icon-holder">
                <img src="<?= base_url('/public/assets/theme/da-theme/images/icons/service/15.png') ?>" alt="">
            </div>
            <h3 class="this-title">
                <a href="#">Fruitfull Results in provided Timeline guaranteed</a>
            </h3>
            <p class="this-summary">Nullam lacinia dolor eu magna aliquet business placerat. Aliquam semper in the
                area of SEO Development firm is very hard.</p>
            <a href="#" class="more">read more</a>
        </div>
        <div class="col-sm-4 featured-service-block style3">
            <div class="icon-holder">
                <img src="<?= base_url('/public/assets/theme/da-theme/images/icons/service/16.png') ?>" alt="">
            </div>
            <h3 class="this-title">
                <a href="#">On Page &amp; Off Page SEO in Competitive Price</a>
            </h3>
            <p class="this-summary">Nullam lacinia dolor eu magna aliquet business placerat. Aliquam semper in the
                area of SEO Development firm is very hard.</p>
            <a href="#" class="more">read more</a>
        </div>
    </div>
</section>

<!--Testimonial-->
<section class="row testimonials style2">
    <div class="container">
        <div class="row quote-sign text-center">
            <img src="<?= base_url('/public/assets/theme/da-theme/images/quote-sign.png') ?>" alt="">
        </div>
        <div class="row">
            <div class="testimonial-slides">
                <div class="item">
                    <div class="row client-img">
                        <img src="<?= base_url('/public/assets/theme/da-theme/images/testimonial.jpg') ?>" alt="">
                    </div>
                    <div class="row quotes">" Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam
                        nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim
                        ad minim veniam, quis nostrud exerci ullamcorper suscipit lobortis nisl ut aliquip ex ea
                        commodo ."</div>
                    <h4 class="client-id">Johnathan, <span>Theme Designer</span></h4>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
echo $this->endSection();
?>
