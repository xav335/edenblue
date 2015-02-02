/*global jQuery:false, ThemeOptions:false, google:false */

/*
 * Archtek - Responsive Modern WordPress Theme
 * By UXbarn
 * Themeforest Profile: http://themeforest.net/user/UXbarn?ref=UXbarn
 * Demo URL: http://themes.uxbarn.com/redirect.php?theme=archtek_wp
 */

jQuery(document).ready(function($) {
	"use strict";
    
    // ---------------------------------------------- //
    // Configuration Options
    // ---------------------------------------------- //
    var useFixedHeader = Boolean(ThemeOptions.use_fixed_header);
    var showScrollToTopButton = Boolean(ThemeOptions.display_scroll_to_top_button);
    var autoFlipSubmenu = Boolean(ThemeOptions.auto_flipping_submenu);
    
    var homeSliderAutoAnimated = Boolean(ThemeOptions.basic_slider_auto_rotation);
    var homeSliderAutoAnimatedDelay = parseInt(ThemeOptions.basic_slider_rotation_duration, 10);
    var homeSliderAnimation = ThemeOptions.basic_slider_transition;
    var homeSliderAnimationSpeed = parseInt(ThemeOptions.basic_slider_transition_speed, 10);
    
    var testimonialSliderAnimationSpeed = 600;
    
    var imageSliderAnimation = 'directscroll';
    var imageSliderAnimationSpeed = 600;
    
    var portfolioImageSliderAutoAnimated = Boolean(ThemeOptions.portfolio_slider_auto_rotation);
    var portfolioImageSliderAutoAnimatedDelay = parseInt(ThemeOptions.portfolio_slider_rotation_duration, 10);
    var portfolioImageSliderAnimation = ThemeOptions.portfolio_slider_transition;
    var portfolioImageSliderAnimationSpeed = parseInt(ThemeOptions.portfolio_slider_transition_speed, 10);

    var enableLightbox = Boolean(ThemeOptions.enable_lightbox_wp_gallery);



    // ---------------------------------------------- //
    // Global Variables (DO NOT CHANGE!)
    // ---------------------------------------------- //
    var isHeaderCollapsed = false;
    var isSearchDisplayed = false;
	var width = parseInt($('.content-width').css('width').replace('px', ''), 10);
    
    // ---------------------------------------------- //
    
    
    
    
    
    // ---------------------------------------------- //
    // Core Scripts
    // ---------------------------------------------- //
    
    // Initialize custom functions
    renderGoogleMaps();
    initMobileMenu();
    
    
    // Initialize Foundation framework
    $(document).foundation();
    
    
    // For extending the outer padding on a row when all inner columns applied "less-padding" class.
    // It is useful when user wants to create a normal text content layout with less padding between inner columns.
    $('.row.outer-padding').each(function() {
    
        var rowWrapper = $('<div class="row no-bg" />');
        var columnWrapper = $('<div class="large-12 columns"></div>');
        
        $(this).append(
            columnWrapper.append(rowWrapper.append($(this).children('.columns')))
        );
        
    });
   
    // Make contained columns smaller padding
    $('.row.col-small-padding').each(function() {
        $(this).find('.uxb-col.columns').addClass('less-padding');
    });
    
    // If there is no header image, increase the top margin of content container
    if($('#content-container').hasClass('no-header-image')) {
		// Only for tablet and desktop
		if((width >= 768 && width <= 959) || width >= 960) {
			$('#content-container').css('margin-top', Math.abs($('#no-header-image-wrapper').height() - $('#header-container').height()));
		}
    }
    
        
    
    // Add CSS class to submit button of comment form
    $('input#submit, input[type="submit"], input[type="button"]').addClass('flat button');
    
    // To remove some empty tags
    $('p:empty').remove(); // This is mostly added by WP automatically
    
    // To unwrap "p" tag out of "x" button of the message box
    if($('.box .close').length > 0) {
        if($('.box .close').parent().prop('tagName').toLowerCase() === 'p') {
            $('.box .close').unwrap();
        }
    }
    
    // To remove margin-bottom out of the last "p" element inside the message box
    $('.box').find('p:last-child').addClass('no-margin');
    
    // ScrollUp plugin
    if(showScrollToTopButton) {
        $.scrollUp({
            animation: 'slide',
            topDistance: '1000',
            topSpeed: 700,
            scrollText: '',
            animationInSpeed: 700,
            animationOutSpeed: 300,
        });
        $('#scrollUp').append('<i class="icon-angle-up"></i>');
    }
    
    
    // Stretch the image within the container using Backstretch plugin
    // Run only for small mobile and tablet size like iPad
    if(width <= 479 || (width >= 768 && width <= 959) || width >= 960) {
        if($('#content-container .image-shortcode.stretch-image').length > 0) {
            $('#content-container .image-shortcode.stretch-image').each(function() {
                var originalImg = $(this);
                
                if(!originalImg.attr('src')) {
					return;
                }
                
                var originalImgAttr = $(this).prop('attributes');
                var containingColumn = originalImg.closest('.columns');
                var imageShortcodeContainer = originalImg.closest('.vc_single_image');
            
                containingColumn.backstretch(originalImg.attr('src'));
            
                var backstretchContainer = containingColumn.find('.backstretch');
                imageShortcodeContainer.append(backstretchContainer);
                
                var stretchedImg = backstretchContainer.children('img');
                $.each(originalImgAttr, function() {
                    stretchedImg.attr(this.name, this.value);
                });
                
                var parentTag = originalImg.parent();
                if(parentTag.prop('tagName').toLowerCase() === 'a') {
                	
                	if(width >= 960 || width <= 479) {
						backstretchContainer.append(parentTag.css({
							'position' : 'absolute',
							'left' : 0,
							'width' : '100%', // this is required to properly display on FF, IE
						}).append(stretchedImg));
					} else {
						backstretchContainer.append(parentTag.css({
							'position' : 'absolute',
							'left' : 0,
						}).append(stretchedImg));
					}
                }
                
                var caption = containingColumn.find('.image-caption');
                caption.appendTo(backstretchContainer);
                
                originalImg.remove();
                
            });
        }
    }
    
    // Stretch the home slider's images and header images (always stretch on all resolutions by default)
    if($('.home-slider-item, #header-image').length > 0) {
        $('.home-slider-item, #header-image').each(function() {
            stretchHomeSliderImage($(this));
        });
    }
	
	$(window).smartresize(function() {
		$('.home-slider-item, #header-image').each(function() {
            stretchHomeSliderImage($(this));
        });
	});
	
	function stretchHomeSliderImage(obj) {
	
		var originalImg = $(obj).find('img');
		if(originalImg.length > 0 && originalImg.attr('src')) {
			var parentTag = $(obj);
			if( ! $('.home-slider-item').hasClass('no-slide')) {
				parentTag.backstretch(originalImg.attr('src'));
				
				// Get attributes from original image
				var originalImgAttr = originalImg.prop('attributes');
				
				// Set all attributes from original image to backstretch image
				var alteredImg = parentTag.find('div.backstretch').find('img');
				$.each(originalImgAttr, function() {
					alteredImg.attr(obj.name, obj.value);
				});
				
				parentTag.find('div.backstretch').find('img').attr('alt', originalImg.attr('alt'));
				originalImg.remove();
			}
		}
		
	}
	

	
    
    
    // Stretch the slider's images on portfolio single page (always stretch on all resolutions by default)
    if($('#portfolio-item-images').length > 0) {
        $('#portfolio-item-images img').each(function() {
            var originalImg = $(this);
            var parentTag = $(this).parents('.single-portfolio-item');
            
            // Anchor tag, 1st-level parent of the image
            var anchorTagAttributes = $(this).parent('a').prop('attributes');
			 
            // Check real containing tag of the image
            if($(this).parent().prop('tagName').toLowerCase() === 'a') {
				
				parentTag.backstretch(originalImg.attr('src'));
                
                // Get attributes from original image
                var originalImgAttr = $(this).prop('attributes');
                  
                // Set all attributes from original image to backstretch image
                var alteredImg = parentTag.find('div.backstretch').find('img');
                $.each(originalImgAttr, function() {
                    alteredImg.attr(this.name, this.value);
                });
                
                // Declare new anchor tag
                var newAnchor = $('<a />');
                
                // Loop throught anchor tag's attributes and add them to the new anchor tag
                $.each(anchorTagAttributes, function() {
                    newAnchor.attr(this.name, this.value);
                });
                
                parentTag.find('div.backstretch').append(newAnchor.append(alteredImg));
                
                // Remove original set
                $(this).parent().remove();
                
            } else if($(this).parent().prop('tagName').toLowerCase() === 'div') {
                
                //parentTag = $(this).parent();
                parentTag.backstretch(originalImg.attr('src'));
                
                // Remove original set
                originalImg.remove();
                
            }
          
        });
    }
    
    
    // Accordion/Toggle
    /*var icons = {
        header: 'ui-accordion-header-icon',
        activeHeader: 'ui-accordion-header-icon-active'
    };*/
    var animateObj = {
        animate: 'easeOutQuint',
        duration: 600,
    };
    
    if ($('.accordion').length > 0) {
        
        $('.accordion').each(function() {
           $(this).accordion({
                autoHeight: false,
                heightStyle: 'content', // jQuery UI 1.10.x
                collapsible: $(this).attr('data-collapsible') === 'true' ? true : false,
                //icons: icons,
                animate: animateObj,
                active: parseInt($(this).attr('data-active-index'), 10)
           });
        });
        
    }
    
    if ($('.toggle').length > 0) {
        
        $('.toggle').accordion({
            autoHeight: false,
            heightStyle: 'content', // jQuery UI 1.10.x
            //icons: icons,
            collapsible: true,
            animate: animateObj,
            active: false,
        });
        
        if($('.toggle').hasClass('active')) {
            $('.toggle.active').accordion({
                heightStyle: 'content',
                autoHeight: false,
                //icons: icons,
                collapsible: true,
                animate: animateObj,
                active: 0,
            });
            
            $('body').scrollTop(0);
        }
        
    }
    
    // "placeholder" attribute fix for all browsers
    $('[placeholder]').focus(function() {
      var input = $(this);
      if (input.val() === input.attr('placeholder')) {
        input.val('');
        input.removeClass('placeholder');
      }
    }).blur(function() {
      var input = $(this);
      if (input.val() === '' || input.val() === input.attr('placeholder')) {
        input.addClass('placeholder');
        input.val(input.attr('placeholder'));
      }
    }).blur();
    $('[placeholder]').parents('form').submit(function() {
      $(this).find('[placeholder]').each(function() {
        var input = $(this);
        if (input.val() === input.attr('placeholder')) {
          input.val('');
        }
      });
    });
    
    // If there is no "p" tag on page intro, remove margin-bottom for h1 or h2 tag
    if($('#intro').length > 0) {
        if($('#intro').find('p').length === 0) {
            $('#intro').find('h1, h2').addClass('no-margin-bottom');
        }
        
        if($('#intro').find('#intro-line').length === 0) {
            $('#intro').addClass('no-margin-bottom');
        }
    }
    
    // Tabs
    if($('html').hasClass('lt-ie9')) {
        $('.auto').addClass('tabs').removeClass('auto').attr('data-section', 'tabs');
    }
    var tabs = $('.vertical-tabs p.title > a, .tabs p.title > a, .auto p.title > a');
    tabs.click(function() {
        
        // Force hiding any content that contains Google Map
        $(this).parents('.section-container').find('.content').each(function() {
            if($(this).find('.google-map').length > 0) {
				
                $(this).css('display', 'none');
            }
            
        });
        
        // Re-render Google Map for tab content and display the content
        var map = $(this).parents('section').find('.content').find('.google-map');
        if(map.length > 0) {
            $(this).parents('section').find('.content').css({'display': 'block', 'width': '100%'});
            renderGoogleMaps();
        }
        
        // Re-render Testimonials for tab content
        /*var testimonials = $(this).parents('section').find('.content').find('.testimonial-list');
        if(testimonials.length > 0) {
			$(this).parents('section').find('.content').css({'display': 'block', 'width': '100%'});
			renderTestimonials(testimonials);
        }*/
        
    });
    
    // Menu
    var subs = $('ul.sf-menu').supersubs({
        minWidth: 15,
        maxWidth: 25,
        extraWidth: 2
    });
    
    subs.superfish({
        animation : {
            opacity: 'show',
            height: 'show'
        },
        delay : 150,
        speed : 'fast'
    });
    
    if(autoFlipSubmenu) {
        // Set "flip" class to the submenu of the last 2 main menus
        var menus = $('#root-menu > li');
        var menusCount = menus.length;
        var i = 1;
        menus.each(function() {
            if((menusCount-i) <= 1) {
                $(this).find('ul').addClass('flip');
            }
            i++;
        });
    }
    
    
    // Header Search Button
    $('#header-search-button').click(function() {
        if(!isSearchDisplayed) {
            // Display search input
            $('#header-search-input-wrapper').css('display', 'block').stop().animate({
                opacity: 1
            }, 300);
            
            $('#header-search-button').addClass('cancel').find('i').removeClass().addClass('icon-reply');
            $('#header-search-input').focus();
            
            isSearchDisplayed = true;
        } else {
            // Hide search input
            $('#header-search-input-wrapper').stop().animate({
                opacity: 0
            }, 300, function() {
                $(this).css('display', 'none');
            });
            
            $('#header-search-button').removeClass('cancel').find('i').removeClass().addClass('icon-search');
            
            isSearchDisplayed = false;
        }
        
    });
    
    // Function for reducing header when scrolling down
    if(useFixedHeader) {
        
        $('#header-container').removeClass('no-fixed');
        
        $(window).bind('scroll', function() {
			var header = $('#header-container');
			var logo = $('#logo-wrapper');
			var search = $('#header-search');
			var topHeaderValue = 0;
             
             // Once scrolled below 150, do the followings
             if ($(window).scrollTop() > 150) {
                 
                 if(width > 959) {
                     
                     if(!isHeaderCollapsed) {
						
						if( ! $('#header-container').hasClass('center')) { // Default style
							
							topHeaderValue = -84;
							if($('body').hasClass('admin-bar')) { // If WP toolbar is enabled, +28px from the top
								topHeaderValue = -56;
							}
							
							header.stop().animate({
								top: topHeaderValue, // Collapse header size
							}, 800, function() {
								search.css('right', -60).stop().animate({
									top: 84 // Move search button to new position
								});
							});
							
							logo.find('p').hide(); // Hide tagline
							
							if(logo.find('img').length > 0) {
								logo.find('img').css('height', 30);
							} else {
								logo.find('h1').css('fontSize', 30);
							}
							
							logo.css({
								position: 'absolute',
								paddingBottom: 0, 
							});
							logo.stop().animate({
								bottom: 0, // Set new position for logo
							});
							
						} else { // Center style (mostly the same as tablet style)
							
							topHeaderValue = -12;
                             if($('body').hasClass('admin-bar')) { // If WP toolbar is enabled, +28px from the top
                                 topHeaderValue = 16;
                             }
                             
                             header.stop().animate({
                                 top: topHeaderValue, // Collapse header size
                             }, 800);
                             
                             search.stop().animate({
                                top: 12 // Move search button to new position
                             }, 800);
                             
                             logo.find('p').hide(); // Hide tagline
                             
                             if(logo.find('img').length > 0) {
                                 logo.find('img').css('height', 30);
                             } else {
                                 logo.find('h1').css('fontSize', 30);
                             }
                             
                             logo.css({
                                //position: 'absolute',
                                paddingBottom: 0, 
                             });
                             logo.stop().animate({
                                 bottom: 0, // Set new position for logo
                             });
                             
                         }
                         
                         $('#header-search-input').addClass('small');
                         isHeaderCollapsed = true;
                     }
                     
                 } else { // <= 959px (Tablet and below)
                     
                     if(width >= 768) {
                         if(!isHeaderCollapsed) {
                             
                             topHeaderValue = -12;
                             if($('body').hasClass('admin-bar')) { // If WP toolbar is enabled, +28px from the top
                                 topHeaderValue = 16;
                             }
                             
                             header.stop().animate({
                                 top: topHeaderValue, // Collapse header size
                             }, 800);
                             
                             search.stop().animate({
                                top: 12 // Move search button to new position
                             }, 800);
                             
                             logo.find('p').hide(); // Hide tagline
                             
                             if(logo.find('img').length > 0) {
                                 logo.find('img').css('height', 30);
                             } else {
                                 logo.find('h1').css('fontSize', 30);
                             }
                             
                             logo.css({
                                //position: 'absolute',
                                paddingBottom: 0, 
                             });
                             logo.stop().animate({
                                 bottom: 0, // Set new position for logo
                             });
                             
                             $('#header-search-input').addClass('small');
                             
                             isHeaderCollapsed = true;
                         }
                     }
                 }
                 
                 $(header).addClass('collapsed-header');
                 
             } else {
                 
                 topHeaderValue = 0;
                 if($('body').hasClass('admin-bar')) { // If WP toolbar is enabled, +28px from the top
                     topHeaderValue = 28;
                 }
                 
                 header.stop().animate({
                     top: topHeaderValue,
                 });
                 
                 if(logo.find('img').length > 0) {
                     logo.find('img').removeAttr('style');
                 } else {
                     logo.find('h1').removeAttr('style');
                 }
                 
                 
                 
                 logo.css({
                    position: 'static',
                    paddingBottom: 17, // Reset position for logo
                 });
                 logo.find('p').show(); // Show tagline
                 search.stop().animate({
                    top: 0
                 }, function() {
                    $(this).animate({
                        'right': 0 // Reset search button to default position
                    });  
                 });
                 
                 $('#header-search-input').removeClass('small');
                 
                 isHeaderCollapsed = false;
                 
                 $(header).removeClass('collapsed-header');
             }
        });
        
    } else {
        $('#header-container').addClass('no-fixed');
    }
    
    // Home Slider
    if(jQuery().carouFredSel) {
        
        if($('#home-slider').length > 0) {
          
            // If there is no slide, just stop auto animation
            if($('#home-slider').find('.home-slider-item').hasClass('no-slide')) {
                homeSliderAutoAnimated = false;
            }
			
			$('#home-slider').imagesLoaded(function() {
				
				$('#home-slider').carouFredSel({
					responsive: true,
					swipe: true,
					width: '100%',
					onCreate: function() {
						$('#home-slider .home-slider-item').stop().animate({
							opacity: 1
						}, 800, function() {
							$('.slider-caption').stop().animate({
								opacity: 1
							}, 500, function() {
								$('#slider-controller').stop().animate({
									opacity: 1
								}, 800);
							});
						});
					},
					scroll: {
						fx: homeSliderAnimation,
						duration: homeSliderAnimationSpeed,
					},
					auto: {
						play: homeSliderAutoAnimated,
						pauseOnHover: 'resume',
						timeoutDuration: homeSliderAutoAnimatedDelay,
					},
					prev: {
						button: '#slider-prev',
					},
					next: {
						button: '#slider-next',
					},
				},
				{
					transition: true,
				});
			});
		}
	}
    
    // Portfolio's Related Items
    if(jQuery().isotope) {
        var container = $('.portfolio-wrapper');
            if(container.length > 0) {
            $(container).imagesLoaded(function() {
                $(container).isotope({
                    itemSelector : '.portfolio-item',
                });
                $(container).parents('.portfolio-root-wrapper').find('.loading-text').css('display', 'none');
                $(container).parents(".portfolio-loaded-wrapper").stop().animate({
					"opacity" : 1,
				}, 800);
            });
        }
    }
        
    // Portfolio Item on Hover
    if(jQuery().hoverdir) {
        $('.portfolio-item').not('.fixed-box').each(function() { 
            $(this).hoverdir({
                hoverDelay : 75,
                selector : '.portfolio-item-hover',
            });
        });
    }
    
    // Portfolio Images on Single Page
    if(jQuery().carouFredSel) {
        if($('#portfolio-item-images').length > 0) {
			
			$('#portfolio-item-images').imagesLoaded(function() {
				
				$('#portfolio-item-images').carouFredSel({
					responsive: true,
					swipe: true,
					onCreate: function() {
						$(this).find('.single-portfolio-item').stop().animate({
							opacity: 1
						}, 800);
						$('#portfolio-item-images-controller').stop().animate({
							opacity: 1
						}, 800);
					},
					scroll: {
						fx: portfolioImageSliderAnimation,
						duration: portfolioImageSliderAnimationSpeed,
					},
					auto: {
						play: portfolioImageSliderAutoAnimated,
						pauseOnHover: 'resume',
						timeoutDuration: portfolioImageSliderAutoAnimatedDelay,
					},
					prev: {
						button: '#portfolio-item-images-prev',
					},
					next: {
						button: '#portfolio-item-images-next',
					},
				},
				{
					transition: true,
				});
			});
		}
	}
	
	// Make portfolio's container bg "none" [ref:001]
	$('.portfolio-root-wrapper').each(function() {
		$(this).closest('.row').css('background', 'none');
	});
    
    
    // Testimonial Slider
    renderTestimonials($('.testimonial-list'));
    
    // General Image Slider
    if(jQuery().carouFredSel) {
        if($('.image-slider').length > 0) {
            var imageSlider = $('.image-slider');
            imageSlider.each(function() {
                
                var autoRotate = $(this).attr('data-auto-rotation'),
                    imageSliderAutoAnimated = true,
                    imageSliderAutoAnimatedDelay = 10000;
                if(autoRotate !== 'disabled') {
                    imageSliderAutoAnimatedDelay = parseInt(autoRotate, 10) * 1000; // Convert to milliseconds
                } else {
                    imageSliderAutoAnimated = false;
                }
                
                $(this).imagesLoaded(function() {
					
					$(this).carouFredSel({
						responsive: true,
						swipe: true,
						onCreate: function() {
							$(this).css('height', 'auto');
							$(this).parents('.image-slider-wrapper').find('.image-slider-item').stop().animate({
								opacity: 1
							}, 800);
							$(this).parents('.image-slider-wrapper').find('.image-slider-controller').stop().animate({
								opacity: 1
							}, 800);
						},
						scroll: {
							fx: imageSliderAnimation,
							duration: imageSliderAnimationSpeed,
						},
						auto: {
							play: imageSliderAutoAnimated,
							pauseOnHover: 'resume',
							timeoutDuration: imageSliderAutoAnimatedDelay,
						},
						prev: {
							button: $(this).parent().find('.image-slider-prev'),
						},
						next: {
							button: $(this).parent().find('.image-slider-next'),
						},
					},
					{
						transition: true,
					});
				}); 
			});
		}
	}
    
    // Add FancyBox feature to WP gallery shortcode
    if(enableLightbox) {
        registerFancyBoxToWPGallery();
    }
    function registerFancyBoxToWPGallery() {
        // WP Gallery shortcode
        var $wpGallery = $('.gallery');
        
        $wpGallery.each(function() {
            var mainId = $(this).attr('id');
            
            var items = $(this).find('.gallery-item').find('a');
            
            items.each(function() {
                
                var href = $(this).attr('href');
                
                if(href.toLowerCase().indexOf('.jpg') >= 0 ||
                    href.toLowerCase().indexOf('.jpeg') >= 0 ||
                    href.toLowerCase().indexOf('.png') >= 0 ||
                    href.toLowerCase().indexOf('.gif') >= 0)    {
                
                    $(this).addClass('image-box');
                    $(this).attr('rel', mainId);
                    
                }
                
            });
            
        });
    }
    
    // Run FancyBox for Image Modal (aka Lightbox)
    if(jQuery().fancybox) {
        $('.image-box').fancybox({
            centerOnScroll : false,
            overlayOpacity : '0.9',
            overlayColor : '#222',
            showNavArrows : true
        });
    }
    
    
    
    function renderTestimonials(testimonialList) {
		
		if(jQuery().carouFredSel) {
			if(testimonialList.length > 0) {
				//var testimonialList = $('.testimonial-list');
				testimonialList.each(function() {
					
					var autoRotate = $(this).attr('data-auto-rotation'),
						testimonialSliderAutoAnimated = true,
						testimonialSliderAutoAnimatedDelay = 10000;
					if(autoRotate !== 'disabled') {
						testimonialSliderAutoAnimatedDelay = parseInt(autoRotate, 10) * 1000; // Convert to milliseconds
					} else {
						testimonialSliderAutoAnimated = false;
					}
					
					$(this).carouFredSel({
						responsive: true,
						swipe: true,
						onCreate: function() {
							$(this).parents('.testimonial-wrapper').find('.testimonial-inner').stop().animate({
								opacity: 1
							}, 800);
							$(this).parents('.testimonial-wrapper').find('.testimonial-bullets').stop().animate({
								opacity: 1
							}, 800);
						},
						pagination: {
							container: $(this).parent().parent().find('.testimonial-bullets'),
							anchorBuilder: function(nr) {
								return '<a href="#'+nr+'"></a>';
							}
						},
						scroll: {
							fx: 'crossfade',
							duration: testimonialSliderAnimationSpeed,
						},
						auto: {
							play: testimonialSliderAutoAnimated,
							pauseOnHover: 'resume',
							timeoutDuration: testimonialSliderAutoAnimatedDelay,
						},
					},
					{
						transition: true,
					});
				});
			}
		}
    }
    
    function renderGoogleMaps() {
        var elements = $('.google-map');
        
        elements.each(function() {
            
            var rawlatlng = $(this).attr('data-latlng').split(',');
            var lat = jQuery.trim(rawlatlng[0]);
            var lng = jQuery.trim(rawlatlng[1]);
            var address = $(this).attr('data-address');
            var displayType = $(this).attr('data-display-type');
            var zoomLevel = parseInt($(this).attr('data-zoom-level'), 10);
            $(this).css('height', $(this).attr('data-height'));
            
            switch(displayType.toUpperCase()) {
                case 'ROADMAP' : displayType = google.maps.MapTypeId.ROADMAP; break;
                case 'SATELLITE' : displayType = google.maps.MapTypeId.SATELLITE; break;
                case 'HYBRID' : displayType = google.maps.MapTypeId.HYBRID; break;
                case 'TERRAIN' : displayType = google.maps.MapTypeId.TERRAIN; break;
                default : displayType = google.maps.MapTypeId.ROADMAP; break;
            }
            
            var geocoder = new google.maps.Geocoder();
            var latlng = new google.maps.LatLng(lat, lng);
            var myOptions = {
                scrollwheel : false,
                zoom : zoomLevel,
                center : latlng,
                mapTypeId : displayType
            };
            
            var map = new google.maps.Map($(this)[0], myOptions);
            
            geocoder.geocode({
                'address' : address,
                'latLng' : latlng,
            }, function(results, status) {
                if (status === google.maps.GeocoderStatus.OK) {
                    var marker;
                    if(jQuery.trim(address).length > 0) {
                        marker = new google.maps.Marker({
                            map : map,
                            position : results[0].geometry.location
                        });
        
                        map.setCenter(results[0].geometry.location);
                    
                    } else {
                        marker = new google.maps.Marker({
                            map : map,
                            position : latlng
                        });
        
                        marker.setPosition(latlng); map.setCenter(latlng);
                        
                    }
    
                } else {
                    window.alert("Geocode was not successful for the following reason: " + status);
                }
            });
            
        });
    }
    
    function initMobileMenu() {
        //var defaultMenuList = $('#root-menu');
        var mobileMenuList = $('<ul />').appendTo($('#mobile-menu .top-bar-section'));
        
        var clonedList = $('#root-menu > li').clone();
        clonedList = getGeneratedSubmenu(clonedList);
        clonedList.appendTo(mobileMenuList);
        
    }
    
    // Recursive function for generating submenus
    function getGeneratedSubmenu(list) {
        list.each(function() {
            //$(this).append('<li class="divider"></li>');
            
            if($(this).find('ul').length > 0) {
                var submenu = $(this).find('ul');
                
                $(this).addClass('has-dropdown');
                submenu.addClass('dropdown'); 
                
                getGeneratedSubmenu(submenu.find('li'));
            }
        });
        
        return list;
    }
    
});