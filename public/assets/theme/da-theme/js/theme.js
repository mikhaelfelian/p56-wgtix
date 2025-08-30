;(function($) {
    "use strict";
    
    function testimonial_slides(){
        if ( $('.testimonial-slides').length ){
            $('.testimonial-slides').each(function(){
                $('.testimonial-slides').owlCarousel({
                    loop: true,
                    margin: 0,
                    nav: false,
                    dots: true,
                    items: 1
                })
            })            
        }
    }
    testimonial_slides();
    
    function study_block_carousel(){
        if ( $('.study-block-carousel').length ){            
            $('.study-block-carousel').owlCarousel({
                loop: true,
                margin: 0,
                nav: false,
                dots: true,
                responsive: {
                    0: {
                        items: 1
                    },
                    600: {
                        items: 2
                    },
                    992: {
                        items: 3
                    }
                }
            })
        }
    }
    study_block_carousel();
    
    function mapBox() {
        if ( $('#mapBox').length ){
            var $lat = $('#mapBox').data('lat');
            var $lon = $('#mapBox').data('lon');
            var $zoom = $('#mapBox').data('zoom');
            var $marker = $('#mapBox').data('marker');
            var map = new GMaps({
                el: '#mapBox',
                lat: $lat,
                lng: $lon,
                scrollwheel: false,
                scaleControl: true,
                streetViewControl: false,
                panControl: true,
                disableDoubleClickZoom: true,
                mapTypeControl: false,
                zoom: $zoom,
				styles: [
					{"featureType":"water","elementType":"geometry","stylers":[{"color":"#e9e9e9"},{"lightness":17}]},          
					{"featureType":"landscape","elementType":"geometry","stylers":[{"color":"#f5f5f5"},{"lightness":20}]},
					{"featureType":"road.highway","elementType":"geometry.fill","stylers":[{"color":"#ffffff"},{"lightness":17}]},
					{"featureType":"road.highway","elementType":"geometry.stroke","stylers":[{"color":"#ffffff"},{"lightness":29},{"weight":0.2}]},
					{"featureType":"road.arterial","elementType":"geometry","stylers":[{"color":"#ffffff"},{"lightness":18}]},
					{"featureType":"road.local","elementType":"geometry","stylers":[{"color":"#ffffff"},{"lightness":16}]},
					{"featureType":"poi","elementType":"geometry","stylers":[{"color":"#f5f5f5"},{"lightness":21}]},
					{"featureType":"poi.park","elementType":"geometry","stylers":[{"color":"#dedede"},{"lightness":21}]},{"elementType":"labels.text.stroke","stylers":[{"visibility":"on"},{"color":"#ffffff"},{"lightness":16}]},{"elementType":"labels.text.fill","stylers":[{"saturation":36},{"color":"#333333"},{"lightness":40}]},{"elementType":"labels.icon","stylers":[{"visibility":"off"}]},
					{"featureType":"transit","elementType":"geometry","stylers":[{"color":"#f2f2f2"},{"lightness":19}]},
					{"featureType":"administrative","elementType":"geometry.fill","stylers":[{"color":"#fefefe"},{"lightness":20}]},
					{"featureType":"administrative","elementType":"geometry.stroke","stylers":[{"color":"#fefefe"},{"lightness":17},{"weight":1.2}]}
				]
            });
        
            map.addMarker({
                lat: $lat,
                lng: $lon,
                icon: $marker
            })              
        }
    }
    mapBox();
    
    function navbarAffix(){
        if ( $('.navbar').length ){
            var affixTop =  $('.navbar').offset().top;
            $('.navbar').affix({
                offset: {
                    top: affixTop
                }
            })
        }
    }
    navbarAffix();
	
	function revsliderOne(){
		if ( $('#slider1').length ){
			$("#slider1").revolution({
				sliderType:"standard",
				sliderLayout:"auto",
				delay:9000,
      			autoHeight:"on",
				navigation: {
		  			touch:{
						touchenabled:"on"
					},
					arrows: {
						style: "hermes",
						enable: true,
						hide_onmobile: false,
						hide_onleave: false,
						tmp: '<div class="tp-arr-allwrapper">	<div class="tp-arr-imgholder"></div>	<div class="tp-arr-titleholder">{{title}}</div>	</div>',
						left: {
							h_align: "left",
							v_align: "center",
							h_offset: 10,
							v_offset: 0
						},
						right: {
							h_align: "right",
							v_align: "center",
							h_offset: 10,
							v_offset: 0
						}
					},
				},			
				responsiveLevels:[4096,1200,992,768,639,480],
			  	gridwidth:[1140,940,720,450,450,290],
			  	gridheight:[587,480,480,450,480,480]
			})
		}
	}
	revsliderOne();
	
	function revsliderTwo(){
		if ( $('#slider2').length ){
			$("#slider2").revolution({
				sliderType:"standard",
				sliderLayout:"auto",
				delay:9000,
      			autoHeight:"on",
				navigation: {
		  			touch:{
						touchenabled:"on"
					},
					arrows: {
						style: "hermes",
						enable: true,
						hide_onmobile: false,
						hide_onleave: false,
						tmp: '<div class="tp-arr-allwrapper">	<div class="tp-arr-imgholder"></div>	<div class="tp-arr-titleholder">{{title}}</div>	</div>',
						left: {
							h_align: "left",
							v_align: "center",
							h_offset: 10,
							v_offset: 0
						},
						right: {
							h_align: "right",
							v_align: "center",
							h_offset: 10,
							v_offset: 0
						}
					}
				},			
				responsiveLevels:[4096,1200,992,768,639,480],
			  	gridwidth:[1140,940,720,450,450,290],
			  	gridheight:[437,350,700,700,700,500]
			})
		}
	}
	revsliderTwo();
	
	function revenueChart(){
		if ( $('.revenueChart').length ){
			
			var ctx = $(".revenueChart").get(0).getContext("2d");
			var myChart = new Chart(ctx, {
				type: 'line',
				data: {
					labels: ["Jun", "Jul", "Aug", "Sep", "Oct", "Nov"],
					datasets: [
						{
							label: "Revenue",
							lineTension: 0.1,
							backgroundColor: "rgba(236,86,70,0.1)",
							borderColor: "#ec5646",
							borderCapStyle: 'butt',
							borderDash: [],
							borderDashOffset: 0.0,
							borderJoinStyle: 'miter',
							pointBorderColor: "#ec5646",
							pointBackgroundColor: "#fff",
							pointBorderWidth: 2,
							pointHoverRadius: 5,
							pointHoverBackgroundColor: "#000",
							pointHoverBorderColor: "#ec5646",
							pointHoverBorderWidth: 2,
							pointRadius: 1,
							pointHitRadius: 10,
							data: [300, 400, 200, 400, 500, 700],
						}
					]
				},
				options: {
					scales: {
						yAxes: [{
							ticks: {
								beginAtZero:true
							}
						}]
					}
				}
			})			
		}
	}
	revenueChart();
	
	function pageCoverImg(){
		if ( $('[data-bgimage]').length ){
			
			var bgimg = $('[data-bgimage]').data("bgimage");
			
			$('[data-bgimage]').css('background-image', 'url(' + bgimg + ')')
			
		}
	}
	pageCoverImg();
	
	function teamMember(){
		if ( $('.team-member').length ){
			
			$('.team-member .hover-trigger').on('click', function(e){
				e.preventDefault();
				
				$(this).toggleClass('open');
				$(this).parent().toggleClass('show-cont')
			})
			
		}
	}
	teamMember();
	
	function caseStudy(){
		if ( $('.case-item').length ){
			
			$('.case-item .hover-trigger').on('click', function(e){
				e.preventDefault();
				
				$(this).parent().parent().toggleClass('full-cont')
			})
			
		}
	}
	caseStudy();
	
	function portoflioItem(){
		if ( $('.portfolio-item').length ){
			
			$('.portfolio-item .details-btn').on('click', function(e){
				e.preventDefault();
				
				$(this).toggleClass('open');
				$('#portfolio-details').slideToggle('slow')
			})
			
		}
	}
	portoflioItem();
    
    function filterableGallery(){
        if ( $('.filterable-gallery').length ){
            $('.filterable-gallery').imagesLoaded( function() {
                
                $(".filterable-gallery").isotope({
                    itemSelector: ".gallery-item",
                    layoutMode: 'masonry',
                    masonry: {
                        columnWidth: '.grid-sizer'
                    }
                });

                // Add isotope click function
                $(".gallery-filter li").on('click',function(){
                    $(".gallery-filter li").removeClass("active");
                    $(this).addClass("active");

                    var selector = $(this).attr("data-filter");
                    $(".filterable-gallery").isotope({
                        filter: selector
                    })
                })
                
            })
        }
    }    
    filterableGallery();
	
	
    
    function counterUpActivator(){
        if ( $('.counter').length ){
            $('.counter').counterUp()
        }
    }
	counterUpActivator();
    
    
    function uiSlider(){
        if ( $('.slider-range').length ) {
            $( ".slider-range" ).slider({
                range: true,
                min: 0,
                max: 400,
                values: [ 40, 150 ],
                slide: function( event, ui ) {
                    $( ".range-amount" ).val( "$" + ui.values[ 0 ] + " - $" + ui.values[ 1 ] )
                }
            });
            $( ".range-amount" ).val( "$" + $( ".slider-range" ).slider( "values", 0 ) + " - $" + $( ".slider-range" ).slider( "values", 1 ) )
        }
    }
    uiSlider()
    
})(jQuery)