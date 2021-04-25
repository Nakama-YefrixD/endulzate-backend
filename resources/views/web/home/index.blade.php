@extends('web.layouts.blank')
@section('titleweb')
    Home
@endsection
@section('linksWeb')
<link rel="stylesheet" type="text/css" href="{{ asset('assetsWeb/styles/bootstrap4/bootstrap.min.css')}}">
<link href="{{ asset('assetsWeb/plugins/font-awesome-4.7.0/css/font-awesome.min.css')}}" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="{{ asset('assetsWeb/plugins/OwlCarousel2-2.2.1/owl.carousel.css')}}">
<link rel="stylesheet" type="text/css" href="{{ asset('assetsWeb/plugins/OwlCarousel2-2.2.1/owl.theme.default.css')}}">
<link rel="stylesheet" type="text/css" href="{{ asset('assetsWeb/plugins/OwlCarousel2-2.2.1/animate.css')}}">
<link href="{{ asset('assetsWeb/plugins/colorbox/colorbox.css')}}" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="{{ asset('assetsWeb/styles/main_styles.css')}}">
<link rel="stylesheet" type="text/css" href="{{ asset('assetsWeb/styles/responsive.css')}}">
@endsection
@section('contentWeb')

<div class="home">
    <!-- Home Slider -->
    <div class="home_slider_container">
        <div class="owl-carousel owl-theme home_slider">
            
            <!-- Home Slider Item -->
            <div class="owl-item">
                <div class="home_slider_background" style="background-image:url({{ asset('assetsWeb/images/slider1.jpg')}})"></div>
                <div class="home_slider_content">
                    <div class="home_slider_content_inner">
                        <div class="home_slider_subtitle">Preciosa</div>
                        <div class="home_slider_title">Labiales</div>
                    </div>	
                </div>
            </div>

            <!-- Home Slider Item -->
            <div class="owl-item">
                <div class="home_slider_background" style="background-image:url({{ asset('assetsWeb/images/slider2.jpg')}})"></div>
                <div class="home_slider_content">
                    <div class="home_slider_content_inner">
                        <div class="home_slider_subtitle">Preciosa</div>
                        <div class="home_slider_title">Maquillaje</div>
                    </div>	
                </div>
            </div>

            <!-- Home Slider Item -->
            <div class="owl-item">
                <div class="home_slider_background" style="background-image:url({{ asset('assetsWeb/images/slider3.jpg')}})"></div>
                <div class="home_slider_content">
                    <div class="home_slider_content_inner">
                        <div class="home_slider_subtitle">Preciosa</div>
                        <div class="home_slider_title">Pelucas</div>
                    </div>	
                </div>
            </div>

        </div>
        
        <!-- Home Slider Nav -->

        <div class="home_slider_next d-flex flex-column align-items-center justify-content-center">
            <img src="{{ asset('assetsWeb/images/arrow_r.png')}}" alt=""></div>

        <!-- Home Slider Dots -->

        <div class="home_slider_dots_container">
            <div class="container">
                <div class="row">
                    <div class="col">
                        <div class="home_slider_dots">
                            <ul id="home_slider_custom_dots" class="home_slider_custom_dots">
                                <li class="home_slider_custom_dot active">01.<div></div></li>
                                <li class="home_slider_custom_dot">02.<div></div></li>
                                <li class="home_slider_custom_dot">03.<div></div></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>		
        </div>
    </div>
</div>

<!-- Promo -->

<div class="promo">
    <div class="container">
        <div class="row">
            <div class="col">
                <div class="section_title_container text-center">
                    <div class="section_subtitle">unicamente lo mejor</div>
                    <div class="section_title">promocion en precios</div>
                </div>
            </div>
        </div>
        <div class="row promo_container">

            <!-- Promo Item -->
            <div class="col-lg-4 promo_col">
                <div class="promo_item">
                    <div class="promo_image">
                        <img src="{{ asset('assetsWeb/images/promo1.jpg')}}" alt="">
                        <div class="promo_content promo_content_1">
                            <div class="promo_title">-30% off</div>
                            <div class="promo_subtitle">en todo esmaltes</div>
                        </div>
                    </div>
                    <div class="promo_link"><a href="#">Ver ya</a></div>
                </div>
            </div>

            <!-- Promo Item -->
            <div class="col-lg-4 promo_col">
                <div class="promo_item">
                    <div class="promo_image">
                        <img src="{{ asset('assetsWeb/images/promo2.jpg')}}" alt="">
                        <div class="promo_content promo_content_2">
                            <div class="promo_title">-30% off</div>
                            <div class="promo_subtitle">labiales & sombras</div>
                        </div>
                    </div>
                    <div class="promo_link"><a href="#">Ver ya</a></div>
                </div>
            </div>

            <!-- Promo Item -->
            <div class="col-lg-4 promo_col">
                <div class="promo_item">
                    <div class="promo_image">
                        <img src="{{ asset('assetsWeb/images/promo3.jpg')}}" alt="">
                        <div class="promo_content promo_content_3">
                            <div class="promo_title">-25% off</div>
                            <div class="promo_subtitle">en sets de maquillaje</div>
                        </div>
                    </div>
                    <div class="promo_link"><a href="#">Ver ya</a></div>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- New Arrivals -->

<div class="arrivals">
    <div class="container">
        <div class="row">
            <div class="col">
                <div class="section_title_container text-center">
                    <div class="section_subtitle">unicamente lo mejor</div>
                    <div class="section_title">recien llegado</div>
                </div>
            </div>
        </div>
        <div class="row products_container">

            <!-- Product -->
            <div class="col-lg-4 product_col">
                <div class="product">
                    <div class="product_image">
                        <img src="{{ asset('assetsWeb/images/product1.jpg')}}" alt="">
                    </div>
                    <div class="rating rating_4">
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                    </div>
                    <div class="product_content clearfix">
                        <div class="product_info">
                            <div class="product_name"><a href="#">Labiales FOA en todo color</a></div>
                            <div class="product_price">S/45.00</div>
                        </div>
                        <!-- <div class="product_options">
                            <div class="product_buy product_option"><img src="{{ asset('assetsWeb/images/shopping-bag-white.svg')}}" alt=""></div>
                            <div class="product_fav product_option">+</div>
                        </div> -->
                    </div>
                </div>
            </div>

            <!-- Product -->
            <div class="col-lg-4 product_col">
                <div class="product">
                    <div class="product_image">
                        <img src="{{ asset('assetsWeb/images/product2.jpg')}}" alt="">
                    </div>
                    <div class="rating rating_4">
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                    </div>
                    <div class="product_content clearfix">
                        <div class="product_info">
                            <div class="product_name"><a href="#">alaciadora babyliss</a></div>
                            <div class="product_price">S/35.00</div>
                        </div>
                        <div class="product_options">
                            <!-- <div class="product_buy product_option"><img src="{{ asset('assetsWeb/images/shopping-bag-white.svg')}}" alt=""></div>
                            <div class="product_fav product_option">+</div> -->
                        </div>
                    </div>
                </div>
            </div>

            <!-- Product -->
            <div class="col-lg-4 product_col">
                <div class="product">
                    <div class="product_image">
                        <img src="{{ asset('assetsWeb/images/product3.jpg')}}" alt="">
                    </div>
                    <div class="rating rating_4">
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                    </div>
                    <div class="product_content clearfix">
                        <div class="product_info">
                            <div class="product_name"><a href="#">Set de maquillaje otros</a></div>
                            <div class="product_price">S/145.00</div>
                        </div>
                        <!-- <div class="product_options">
                            <div class="product_buy product_option"><img src="{{ asset('assetsWeb/images/shopping-bag-white.svg')}}" alt=""></div>
                            <div class="product_fav product_option">+</div>
                        </div> -->
                    </div>
                </div>
            </div>

            <div class="col-lg-4 product_col">
                <div class="product">
                    <div class="product_image">
                        <img src="{{ asset('assetsWeb/images/product3.jpg')}}" alt="">
                    </div>
                    <div class="rating rating_4">
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                    </div>
                    <div class="product_content clearfix">
                        <div class="product_info">
                            <div class="product_name"><a href="#">Set de maquillaje otros</a></div>
                            <div class="product_price">S/145.00</div>
                        </div>
                        <!-- <div class="product_options">
                            <div class="product_buy product_option"><img src="{{ asset('assetsWeb/images/shopping-bag-white.svg')}}" alt=""></div>
                            <div class="product_fav product_option">+</div>
                        </div> -->
                    </div>
                </div>
            </div>


        </div>
    </div>
</div>

<!-- Extra -->

<!-- <div class="extra clearfix">
    <div class="extra_promo extra_promo_1">
        <div class="extra_promo_image" style="background-image:url({{ asset('assetsWeb/images/extra_1.jpg')}})"></div>
        <div class="extra_1_content d-flex flex-column align-items-center justify-content-center text-center">
            <div class="extra_1_price">30%<span>off</span></div>
            <div class="extra_1_title">On all shoes</div>
            <div class="extra_1_text">*No dudes en hablarnos, te estamos esperando.</div>
            <div class="button extra_1_button"><a href="#">check out</a></div>
        </div>
    </div>
    <div class="extra_promo extra_promo_2">
        <div class="extra_promo_image" style="background-image:url({{ asset('assetsWeb/images/extra_2.jpg')}})"></div>
        <div class="extra_2_content d-flex flex-column align-items-center justify-content-center text-center">
            <div class="extra_2_title">
                <div class="extra_2_center">&</div>
                <div class="extra_2_top">Mix</div>
                <div class="extra_2_bottom">Match</div>
            </div>
            <div class="extra_2_text">*No dudes en hablarnos, te estamos esperando.</div>
            <div class="button extra_2_button"><a href="#">check out</a></div>
        </div>
    </div>
</div> -->

<!-- Gallery -->

<div class="gallery">
    <div class="gallery_image" style="background-image:url({{ asset('assetsWeb/images/gallery.jpg')}})"></div>
    <div class="container">
        <div class="row">
            <div class="col">
                <div class="gallery_title text-center">
                    <ul>
                        <li><a href="#">#preciosa</a></li>
                        <li><a href="#">#preciosainstagram</a></li>
                        <li><a href="#">#preciosaComesticos</a></li>
                    </ul>
                </div>
                <div class="gallery_text text-center">*No dudes en hablarnos, te estamos esperando.</div>
                <!-- <div class="button gallery_button"><a href="#">submit</a></div> -->
            </div>
        </div>
    </div>	
    <div class="gallery_slider_container">
        
        <!-- Gallery Slider -->
        <div class="owl-carousel owl-theme gallery_slider">
            
            <!-- Gallery Item -->
            <div class="owl-item gallery_item">
                <a class="colorbox" href="{{ asset('assetsWeb/images/gallery_1.jpg')}}">
                    <img src="{{ asset('assetsWeb/images/gallery_1.jpg')}}" alt="">
                </a>
            </div>

            <!-- Gallery Item -->
            <div class="owl-item gallery_item">
                <a class="colorbox" href="{{ asset('assetsWeb/images/gallery_2.jpg')}}">
                    <img src="{{ asset('assetsWeb/images/gallery_2.jpg')}}" alt="">
                </a>
            </div>

            <!-- Gallery Item -->
            <div class="owl-item gallery_item">
                <a class="colorbox" href="{{ asset('assetsWeb/images/gallery_3.jpg') }}">
                    <img src="{{ asset('assetsWeb/images/gallery_3.jpg') }}" alt="">
                </a>
            </div>

            <!-- Gallery Item -->
            <div class="owl-item gallery_item">
                <a class="colorbox" href="{{ asset('assetsWeb/images/gallery_4.jpg')}}">
                    <img src="{{ asset('assetsWeb/images/gallery_4.jpg')}}" alt="">
                </a>
            </div>

            <!-- Gallery Item -->
            <div class="owl-item gallery_item">
                <a class="colorbox" href="{{ asset('assetsWeb/images/gallery_5.jpg')}}">
                    <img src="{{ asset('assetsWeb/images/gallery_5.jpg')}}" alt="">
                </a>
            </div>

            <!-- Gallery Item -->
            <div class="owl-item gallery_item">
                <a class="colorbox" href="{{ asset('assetsWeb/images/gallery_6.jpg')}}">
                    <img src="{{ asset('assetsWeb/images/gallery_6.jpg')}}" alt="">
                </a>
            </div>

        </div>
    </div>	
</div>

<!-- Testimonials -->

<div class="testimonials">
    <div class="container">
        <div class="row">
            <div class="col">
                <div class="section_title_container text-center">
                    <div class="section_subtitle">solamente lo mejor</div>
                    <div class="section_title">testimonios</div>
                </div>
            </div>
        </div>
        <div class="row test_slider_container">
            <div class="col">

                <!-- Testimonials Slider -->
                <div class="owl-carousel owl-theme test_slider text-center">

                    <!-- Testimonial Item -->
                    <div class="owl-item">
                        <div class="test_text">“Excelente servicio, y excelente personal”</div>
                        <div class="test_content">
                            <div class="test_image"><img src="{{ asset('assetsWeb/images/testimonials.jpg')}}" alt=""></div>
                            <div class="test_name">Rosa Elvira</div>
                            <div class="test_title">cliente</div>
                        </div>
                    </div>

                    <!-- Testimonial Item -->
                    <div class="owl-item">
                        <div class="test_text">“Precios muy accesibles en cualquier producto y sobre todo muy buen ambiente.”</div>
                        <div class="test_content">
                            <div class="test_image"><img src="{{ asset('assetsWeb/images/testimonials.jpg')}}" alt=""></div>
                            <div class="test_name">Raul Jhonson</div>
                            <div class="test_title">cliente</div>
                        </div>
                    </div>

                    <!-- Testimonial Item -->
                    <div class="owl-item">
                        <div class="test_text">“Productos en las mejores marcas, y personal con bastante experiencia RECOMENDADO.”</div>
                        <div class="test_content">
                            <div class="test_image"><img src="{{ asset('assetsWeb/images/testimonials.jpg')}}" alt=""></div>
                            <div class="test_name">Anonimo</div>
                            <div class="test_title">cliente</div>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>
</div>

<!-- Newsletter -->
<!-- 
<div class="newsletter">
    <div class="newsletter_content">
        <div class="newsletter_image" style="background-image:url({{ asset('assetsWeb/images/newsletter.jpg')}})"></div>
        <div class="container">
            <div class="row">
                <div class="col">
                    <div class="section_title_container text-center">
                        <div class="section_subtitle">unicamente lo mejor</div>
                        <div class="section_title">subscribe for a 20% discount</div>
                    </div>
                </div>
            </div>
            <div class="row newsletter_container">
                <div class="col-lg-10 offset-lg-1">
                    <div class="newsletter_form_container">
                        <form action="#">
                            <input type="email" class="newsletter_input" required="required" placeholder="E-mail here">
                            <button type="submit" class="newsletter_button">subscribe</button>
                        </form>
                    </div>
                    <div class="newsletter_text">No dudes en hablarnos, te estamos esperando nec. Fusce vel lorem libero. Integer ex mi, facilisis sed nisi ut, vestib ulum ultrices nulla. Aliquam egestas tempor leo.</div>
                </div>
            </div>
        </div>
    </div>
</div> -->

@endsection

@section('scriptWeb')
<script src="{{ asset('assetsWeb/js/jquery-3.2.1.min.js')}}"></script>
<script src="{{ asset('assetsWeb/styles/bootstrap4/popper.js')}}"></script>
<script src="{{ asset('assetsWeb/styles/bootstrap4/bootstrap.min.js')}}"></script>
<script src="{{ asset('assetsWeb/plugins/OwlCarousel2-2.2.1/owl.carousel.js')}}"></script>
<script src="{{ asset('assetsWeb/plugins/easing/easing.js')}}"></script>
<script src="{{ asset('assetsWeb/plugins/parallax-js-master/parallax.min.js')}}"></script>
<script src="{{ asset('assetsWeb/plugins/colorbox/jquery.colorbox-min.js')}}"></script>
<script src="{{ asset('assetsWeb/js/custom.js')}}"></script>
@endsection