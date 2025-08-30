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
                    <div class="row quotes">" Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam
                        nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim
                        ad minim veniam, quis nostrud exerci ullamcorper suscipit lobortis nisl ut aliquip ex ea
                        commodo ."</div>
                    <h4 class="client-id">Johnathan, <span>Theme Designer</span></h4>
                </div>
                <div class="item">
                    <div class="row client-img"><img
                            src="<?= base_url('/public/assets/theme/da-theme/images/testimonial.jpg') ?>" alt="">
                    </div>
                    <div class="row quotes">" Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam
                        nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim
                        ad minim veniam, quis nostrud exerci ullamcorper suscipit lobortis nisl ut aliquip ex ea
                        commodo ."</div>
                    <h4 class="client-id">Johnathan, <span>Theme Designer</span></h4>
                </div>
                <div class="item">
                    <div class="row client-img"><img
                            src="<?= base_url('/public/assets/theme/da-theme/images/testimonial.jpg') ?>" alt="">
                    </div>
                    <div class="row quotes">" Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam
                        nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim
                        ad minim veniam, quis nostrud exerci ullamcorper suscipit lobortis nisl ut aliquip ex ea
                        commodo ."</div>
                    <h4 class="client-id">Johnathan, <span>Theme Designer</span></h4>
                </div>
                <div class="item">
                    <div class="row client-img"><img
                            src="<?= base_url('/public/assets/theme/da-theme/images/testimonial.jpg') ?>" alt="">
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
