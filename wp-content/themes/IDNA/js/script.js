jQuery(document).ready(function($){
	// Submit response when changing answer to questions
	$('.response').change(function(e){
		var t = $(this),
			id = t.attr('id'),
			val = t.val(),
			form = t.closest('form'),
			review_form = $('#review-form'),
			review_id = id + '_review',
			english_response = t.data('plaintext'),
			question = t.attr('name'),
			user = form.find('.pid').val(),
			action = form.attr('action'),
			type = t.attr('type'),
			wrap = t.closest('li'),
			index = $('.slides > li').index(wrap);
			d = {
				pid: user,
				action: 'update'
			};
			d[question] = val;

		if(('checkbox' === type) && (t.prop('checked') === true)){
			form.find('input[type="radio"]').each(function(){
				$(this).prop('checked', false);
			});
		}
		if('radio' === type){
            cbox = form.find('input[type="checkbox"]').attr('id');
            $('label[for="'+cbox+'"]').removeClass('hover');
			form.find('input[type="checkbox"]').prop('checked', false);
			rev = $('#' + review_id);
			$('#' + review_id).prop('checked', true);
		}



		$.post(
			action,
			d,
			function(data, textStatus, jqXHR){
                t.closest('.question-page').find('.answer div').html(english_response);
                $('.flexslider-control-nav li:eq(' + index + ') a').addClass('complete');
                questionTotal = $('.flexslider-control-nav li').not('.hidden').not('.pre-assessment-nav').not('post-assessment-nav').length - 1;
                completed = $('.flexslider-control-nav .complete').length;
                remaining = questionTotal - completed;
                
                $('#nav-complete h3').html(completed);
                $('#nav-remain h3').html(remaining);
			}
		);
	});
    //$('#assessment_complete').submit(function(e){
    $('#review-form').submit(function(e){
        e.preventDefault();
        var t = $(this),
            assessment = t.find('.pid').val(),
            action = t.attr('action'),
            user = t.find('.userid').val(),
            ans_count = 0,
            na_count = 0,
            valid = false,
            invalids = 0,
            questions = $('#review-form input[type="radio"]:checked');

        d = {
            pid: assessment,
            action: 'complete'
        };
        questions.each(function(){
        	question = $(this).attr('name'),
        	val = $(this).val();
        	d[question] = val;
            if(0 == val){
                na_count++;
            } else {
                ans_count++;
            }
        });
        jQuery('.review-block').each(function(){
            answered = $(this).find(':checked').length;
            if(answered == 0){
                $(this).css('background-color', '#a00');
                invalids++;
                $(this).prependTo("#review-form");
            }
        });

        if(invalids == 0){
            $('.flexslider').flexslider('next');
            $.post(
                action,
                d,
                function(data, textStatus, jqXHR){
                    $('.flexslider').flexslider("next");

                    t.html('<h2>Thank you for completing your assessment</h2>');
                    $('#ac_answered h2').html(ans_count);
                    $('#ac_notapply h2').html(na_count);

                }
            );
        }
            
            
        
    });

	$('.reset').click(function(e){
		e.preventDefault();

		var t = $(this),
			form = t.closest('form'),
			question = t.attr('name'),
			user = form.find('.pid').val(),
			action = form.attr('action'),
			wrap = t.closest('li'),
			index = $('.slides > li').index(wrap);
			d = {
				pid: user,
				action: 'clear'
			};
			d[question] = 0;

		$.post(
			action,
			d,
			function(data, textStatus, jqXHR){
				t.closest('.question-page').find('.answer div').html('');
				form.find('input[type="checkbox"]').prop('checked', false);
				form.find('input[type="radio"]').prop('checked', false);
				$('.flexslider-control-nav li:eq(' + index + ') a').removeClass('complete');
			}
		);
	});

	$('.icon-circle').each(function(){
		var t = $(this),
			parent = t.parent(),
			//size = t.data('size'),
			color = t.data('color'),
			index = parent.index(),
			wrapper = parent.closest('.discovery_main'),
			wrapperWidth = wrapper.width(),
			count = wrapper.children().length - 1,
			parentWidth = wrapperWidth / count;
		//t.css('font-size', size);
		t.css('color', color);
		parent.data('color', color).css('width', parentWidth);
		//wrapper.next().find('.report_nav').find('li:eq('+index+') h1').css('color', color);
	});

    var checkboxes = $('input[type=checkbox]').not('.demo-filter');
    checkboxes.each(function(){
        var label = $('label[for='+ $(this).attr('id') + ']');
        label.click(function(){
            $(this).toggleClass('hover');
            //if( $(this).is('.response-na')){
              ///  $(this).addClass('checkedHover');
            //}
        });
        // label.click(function(){
        //     $(this).removeClass('hover checkedHover');
        // });
        $(this).add(label).wrap('<div class="custom-checkbox"></div>');
    });

	$('.report_nav').cycle({
		fx: 'scrollHorz',
		next: '.callout_next',
		prev: '.callout_prev',
		timeout: 0,
		before: function(currSlideElement, nextSlideElement, options, forwardFlag){
            i = $(nextSlideElement).index(),
            t = $(this),
            discmain = t.closest('.callouts_scroller').prev('.discovery_main'),
            items = discmain.children('a'),
            target = items.eq(i),
            sibs = target.siblings(),
            color = target.data('color');
            discmain.css('border-color', color);
            if( 0 === i ){
                $('.discovery_main p').each(function(){
                    col = $(this).data('color');
                    $(this).removeClass('desat').css('color', col);
                });
            } else {
                target.removeClass('desat').find('p').css('color', color);
                sibs.addClass('desat').find('p').css('color', '#999');
            }
            //console.log(this);
			
		} /*,
		pagerAnchorBuilder: function(idx, slide) { 
	        return '.discovery_main a:eq(' + idx + ')'; 
	    }*/
	});
/*	$('.disc_scores').each(function(){
		var t = $(this),
			color = t.find('p').css('color');
		t.data('color', color);
	});*/
	$('.disc_scores').click(function(){
		var t = $(this),
			color = t.data('color'),
			i = t.index(),
			wrap = t.parent(),
			sibs = t.siblings();
		t.removeClass('desat').find('p').css('color', color);
		sibs.addClass('desat').find('p').css('color', '#999');
		wrap.queue(function(){
			$(this).css('border-color', color);
			$(this).dequeue();
		});
		//console.log(wrap.next('.callouts_scroller').find('report_nav'));
		wrap.next('.callouts_scroller').find('.report_nav').cycle(i);
	});
	





	// Set up page slider
	if($('#progress-bar').length > 0){
		$('#progress-bar').prependTo('#container');
	}
	$('#assessment-slider').flexslider({
        animation: 'slide',
        animationLoop: false,
        slideshow: false,
        smoothHeight: false,
        controlsContainer: '.nav-wrapper',
        manualControls: '.flexslider-control-nav li a',
        after: function(slider){
            cur = slider.currentSlide,
            count = slider.count,
            bar = $('#progress-bar');
            if(cur > 1 && cur < (count - 4)){
                bar.fadeIn('slow');
                total = slider.count - 7;
                current = slider.currentSlide - 1;
                $('#progress-count').html(current + '/' + total);
                $('#progress-display').progressbar({value: (current/total)*100});
            } else {
                bar.fadeOut('slow');
                current = $('.slides', '#report-slider').children('li:eq('+cur+')');
                $('.icon-circle', current).each(function(){
                    t = $(this),
                    size = t.data('size');
                    t.css('font-size', size);
                });
            }
        },
        before: function(slider){
            $('.control-toggle.open').click();
        }
    });

    function fadeAddl(el){
        el.fadeOut('slow');
    }
    $('#report-slider').flexslider({
        animation: 'slide',
        animationLoop: false,
        slideshow: false,
        smoothHeight: false,
        controlsContainer: '.nav-wrapper',
        manualControls: '#nav-cats>li',
        after: function(slider){
            
            $('.icon-circle').css('font-size', '0px');
            current = $('#report-slider').find('.slides').children('li:eq('+slider.currentSlide+')');
            current.find('.icon-circle').each(function(){
                size = $(this).data('size');
                $(this).css('font-size', size);
            });

            // if(slider.currentSlide > 2 && slider.currentSlide < 7){
                
            //     $('.addl-media').fadeIn('slow', function(){
            //         setTimeout(fadeAddl($(this)), 5000);
            //     });

            // }

            if('report-slider' == slider.attr('id') && slider.currentSlide == 2){
                $('#innovation_footer').fadeIn();
            }
            if('report-slider' == slider.attr('id') && slider.currentSlide == 6){
                $('#discovery_footer').fadeIn();
            }

         },
        before: function(slider){
            $('.report_nav').cycle(0);
            $('.discovery_main').css('border-color', '#000');
            $('#innovation_footer').fadeOut();
            $('#discovery_footer').fadeOut();
            
        },
    });
    
    $('.header-w-cycle').click(function(){
            $('.discovery_main').css('border-color', '#000');
    });

    $('#nav-cats ul li').click(function(){
        t = $(this),
        idx = t.index(),
        cat = t.closest('li'),
        catidx = cat.index();
        $('.report_nav').cycle(idx);
    });

    $('.header-w-cycle').click(function(){
        $('.report_nav').cycle(0);
    });

    $('.heading-prev').click(function() {
        $('.flexslider').flexslider("previous"); //Go to previous slide
    });

    $('.heading-next').click(function() {
        $('.flexslider').flexslider("next"); //Go to next slide
    });
	$('#assessment-slider a.flex-prev').click(function() {
	$('.flexslider').flexslider("previous"); //Go to previous slide
	});

	$('#assessment-slider a.flex-next').click(function() {
	$('.flexslider').flexslider("next"); //Go to next slide
	});

	$('#wrapper a.top-prev').click(function(e) {
		e.preventDefault();
		$('#assessment-slider a.flex-prev').click();
	//$('.flexslider').flexslider("previous"); //Go to previous slide
	});

	$('#wrapper a.top-next').click(function(e) {
		e.preventDefault();
		$('#assessment-slider a.flex-next').click();
	//$('.flexslider').flexslider("next"); //Go to next slide
	});

    $('#skip-courage').click(function(e){
        e.preventDefault();
        $('#report-slider').flexslider(3);
    });
    $('#skip-discovery').click(function(e){
        e.preventDefault();
        $('#report-slider').flexslider(4);
    });
    $('#skip-delivery').click(function(e){
        e.preventDefault();
        $('#report-slider').flexslider(5);
    });
    $('.characteristic_nav').click(function(e){
        e.preventDefault();
        d = $(this).data('move');
        $('#report-slider').flexslider(d);
    });

	$('.control-toggle').live('click', function(e){
		e.preventDefault();
		t = $(this),
		nav = t.parent('.nav-wrapper');
		if(t.hasClass('open')){
			nav.animate({
				'left': '-484px'
			}, 500);
			t.removeClass('open');
		} else {
			nav.animate({
				'left': '0px'
			}, 500);
			t.addClass('open');
		}
	});

    $('#nav-cats li').click(function(){
        $('.control-toggle').click();
    });

    $('a.panel_box').each(function(){
        href = $(this).attr('href');
        $(this).colorbox({
            inline:true,
            href: href,
            width: 960,
            height: '70%'

        });
    });
    $('a.char_box').colorbox({
            iframe:true,
            width: 960,
            height: '70%'
        });

    $('.footer_control').live('click', function(e){
        e.preventDefault();
        t = $(this),
        nav = t.parent('.footer_wrap');
        if(t.hasClass('open')){
            nav.animate({
                'bottom': '-620px'
            }, 330);
            t.removeClass('open');
        } else {
            nav.animate({
                'bottom': '0px'
            }, 330);
            t.addClass('open');
        }
    });
    $('.footer_heading_wrap').live('click', function(){
        $(this).prev('.footer_control').click();
    });

    $('#dem_toggle').click(function(e){
        e.preventDefault();
        t = $(this);
        if(t.hasClass('open')){
            t.next().children('.demographic_wrap').fadeOut('slow');
            t.removeClass('open');
        } else {
            t.next().children('.demographic_wrap').fadeIn('slow');
            t.addClass('open');
        }
    });



	function resizeIt(){
		var headerHeight = $('#header').outerHeight(),
			footerHeight = $('#footer').outerHeight(),
			viewHeight = $(window).height(),
			wrapperHeight = viewHeight - headerHeight - footerHeight - 60 - 28;
			$('#wrapper').height(wrapperHeight);
	}
//	$('html').css('margin-bottom', '-20px');
//	$(window).resize(resizeIt);
//	resizeIt();

// JS Charts

if($('#chart_a_1').length > 0){
    chart = new Highcharts.Chart({
    	colors: ['#000', '#999'],
        chart: {
            renderTo: 'chart_a_1',
            type: 'bar',
            spacingBottom: 60,
            height: 190,
            width: 268,
            animation: {
                duration: 1000
            },
            marginRight: 30
        },
        title: {
            text: null
        },
        xAxis: {
            labels: {
                enabled: false
            },
            tickLength: 0,
            tickHeight: 0
        },
        yAxis: {
            min: 1,
            max: 7,
            title: {
                text: null
            },
            labels: {
                overflow: 'justify'
            },
            tickInterval: 1
        },
        tooltip: {
            enabled: false
        },
        plotOptions: {
            bar: {
                dataLabels: {
                    enabled: true,
                    style: {
                        fontWeight: 'bold',
                        fontSize: '24px'
                    }
                },
                borderWidth: 0,
                shadow: false,
                pointWidth: 14,
                pointPadding: 0.2,
                pointStart: 1
            }
        },
        legend: {
            layout: 'vertical',
            align: 'left',
            verticalAlign: 'bottom',
            x: 0,
            y: 45,
            floating: true,
            borderWidth: 0,
            backgroundColor: '#FFFFFF',
            shadow: false
        },
        credits: {
            enabled: false
        },
        series: [{
            name: 'Your Average Score',
            data: JSON.parse("[" + assessment.a + "]"),
            index: 2
        }, {
            name: 'Successful Innovators',
            data: JSON.parse("[" + assessment.a_avg + "]"),
            index: 1
        }]
    });

    chart2 = new Highcharts.Chart({
    	colors: ['#3b2c7d', '#999'],
        chart: {
            renderTo: 'chart_a_2',
            type: 'bar',
            spacingBottom: 60,
            height: 190,
            width: 268,
            animation: {
                duration: 1000
            },
            marginRight: 30
        },
        title: {
            text: null
        },
        xAxis: {
            labels: {
                enabled: false
            },
            tickLength: 0,
            tickHeight: 0
        },
        yAxis: {
            min: 1,
            max: 7,
            title: {
                text: null
            },
            labels: {
                overflow: 'justify'
            },
            tickInterval: 1
        },
        tooltip: {
            enabled: false
        },
        plotOptions: {
            bar: {
                dataLabels: {
                    enabled: true,
                    style: {
                        fontWeight: 'bold',
                        fontSize: '24px'
                    }
                },
                borderWidth: 0,
                shadow: false,
                pointWidth: 14,
                pointPadding: 0.2,
                pointStart: 1
            }
        },
        legend: {
            layout: 'vertical',
            align: 'left',
            verticalAlign: 'bottom',
            x: 0,
            y: 45,
            floating: true,
            borderWidth: 0,
            backgroundColor: '#FFFFFF',
            shadow: false
        },
        credits: {
            enabled: false
        },
        series: [{
            name: 'Your Average Score',
            data: JSON.parse("[" + assessment.a1 + "]"),
            index: 2
        }, {
            name: 'Successful Innovators',
            data: JSON.parse("[" + assessment.a1_avg + "]"),
            index: 1
        }]
    });

    chart3 = new Highcharts.Chart({
    	colors: ['#645a8f', '#999'],
        chart: {
            renderTo: 'chart_a_3',
            type: 'bar',
            spacingBottom: 60,
            height: 190,
            width: 268,
            animation: {
                duration: 1000
            },
            marginRight: 30
        },
        title: {
            text: null
        },
        xAxis: {
            labels: {
                enabled: false
            },
            tickLength: 0,
            tickHeight: 0
        },
        yAxis: {
            min: 1,
            max: 7,
            title: {
                text: null
            },
            labels: {
                overflow: 'justify'
            },
            tickInterval: 1
        },
        tooltip: {
            enabled: false
        },
        plotOptions: {
            bar: {
                dataLabels: {
                    enabled: true,
                    style: {
                        fontWeight: 'bold',
                        fontSize: '24px'
                    }
                },
                borderWidth: 0,
                shadow: false,
                pointWidth: 14,
                pointPadding: 0.2,
                pointStart: 1
            }
        },
        legend: {
            layout: 'vertical',
            align: 'left',
            verticalAlign: 'bottom',
            x: 0,
            y: 45,
            floating: true,
            borderWidth: 0,
            backgroundColor: '#FFFFFF',
            shadow: false
        },
        credits: {
            enabled: false
        },
        series: [{
            name: 'Your Average Score',
            data: JSON.parse("[" + assessment.a2 + "]"),
            index: 2
        }, {
            name: 'Successful Innovators',
            data: JSON.parse("[" + assessment.a2_avg + "]"),
            index: 1
        }]
    });

    chart4 = new Highcharts.Chart({
    	colors: ['#a29cbc', '#999'],
        chart: {
            renderTo: 'chart_a_4',
            type: 'bar',
            spacingBottom: 60,
            height: 190,
            width: 268,
            animation: {
                duration: 1000
            },
            marginRight: 30
        },
        title: {
            text: null
        },
        xAxis: {
            labels: {
                enabled: false
            },
            tickLength: 0,
            tickHeight: 0
        },
        yAxis: {
            min: 1,
            max: 7,
            title: {
                text: null
            },
            labels: {
                overflow: 'justify'
            },
            tickInterval: 1
        },
        tooltip: {
            enabled: false
        },
        plotOptions: {
            bar: {
                dataLabels: {
                    enabled: true,
                    style: {
                        fontWeight: 'bold',
                        fontSize: '24px'
                    }
                },
                borderWidth: 0,
                shadow: false,
                pointWidth: 14,
                pointPadding: 0.2,
                pointStart: 1
            }
        },
        legend: {
            layout: 'vertical',
            align: 'left',
            verticalAlign: 'bottom',
            x: 0,
            y: 45,
            floating: true,
            borderWidth: 0,
            backgroundColor: '#FFFFFF',
            shadow: false
        },
        credits: {
            enabled: false
        },
        series: [{
            name: 'Your Average Score',
            data: JSON.parse("[" + assessment.a3 + "]"),
            index: 2
        }, {
            name: 'Successful Innovators',
            data: JSON.parse("[" + assessment.a3_avg + "]"),
            index: 1
        }]
    });

    chart5 = new Highcharts.Chart({
    	colors: ['#000', '#999'],
        chart: {
            renderTo: 'chart_b_1',
            type: 'bar',
            spacingBottom: 60,
            height: 190,
            width: 268,
            animation: {
                duration: 1000
            },
            marginRight: 30
        },
        title: {
            text: null
        },
        xAxis: {
            labels: {
                enabled: false
            },
            tickLength: 0,
            tickHeight: 0
        },
        yAxis: {
            min: 1,
            max: 7,
            title: {
                text: null
            },
            labels: {
                overflow: 'justify'
            },
            tickInterval: 1
        },
        tooltip: {
            enabled: false
        },
        plotOptions: {
            bar: {
                dataLabels: {
                    enabled: true,
                    style: {
                        fontWeight: 'bold',
                        fontSize: '24px'
                    }
                },
                borderWidth: 0,
                shadow: false,
                pointWidth: 14,
                pointPadding: 0.2,
                pointStart: 1
            }
        },
        legend: {
            layout: 'vertical',
            align: 'left',
            verticalAlign: 'bottom',
            x: 0,
            y: 45,
            floating: true,
            borderWidth: 0,
            backgroundColor: '#FFFFFF',
            shadow: false
        },
        credits: {
            enabled: false
        },
        series: [{
            name: 'Your Average Score',
            data: JSON.parse("[" + assessment.b + "]"),
            index: 2
        }, {
            name: 'Successful Innovators',
            data: JSON.parse("[" + assessment.b_avg + "]"),
            index: 1
        }]
    });


    chart6 = new Highcharts.Chart({
    	colors: ['#fbb041', '#999'],
        chart: {
            renderTo: 'chart_b_2',
            type: 'bar',
            spacingBottom: 60,
            height: 190,
            width: 268,
            animation: {
                duration: 1000
            },
            marginRight: 30
        },
        title: {
            text: null
        },
        xAxis: {
            labels: {
                enabled: false
            },
            tickLength: 0,
            tickHeight: 0
        },
        yAxis: {
            min: 1,
            max: 7,
            title: {
                text: null
            },
            labels: {
                overflow: 'justify'
            },
            tickInterval: 1
        },
        tooltip: {
            enabled: false
        },
        plotOptions: {
            bar: {
                dataLabels: {
                    enabled: true,
                    style: {
                        fontWeight: 'bold',
                        fontSize: '24px'
                    }
                },
                borderWidth: 0,
                shadow: false,
                pointWidth: 14,
                pointPadding: 0.2,
                pointStart: 1
            }
        },
        legend: {
            layout: 'vertical',
            align: 'left',
            verticalAlign: 'bottom',
            x: 0,
            y: 45,
            floating: true,
            borderWidth: 0,
            backgroundColor: '#FFFFFF',
            shadow: false
        },
        credits: {
            enabled: false
        },
        series: [{
            name: 'Your Average Score',
            data: JSON.parse("[" + assessment.b1 + "]"),
            index: 2
        }, {
            name: 'Successful Innovators',
            data: JSON.parse("[" + assessment.b1_avg + "]"),
            index: 1
        }]
    });


    chart7 = new Highcharts.Chart({
    	colors: ['#99daf5', '#999'],
        chart: {
            renderTo: 'chart_b_3',
            type: 'bar',
            spacingBottom: 60,
            height: 190,
            width: 268,
            animation: {
                duration: 1000
            },
            marginRight: 30
        },
        title: {
            text: null
        },
        xAxis: {
            labels: {
                enabled: false
            },
            tickLength: 0,
            tickHeight: 0
        },
        yAxis: {
            min: 1,
            max: 7,
            title: {
                text: null
            },
            labels: {
                overflow: 'justify'
            },
            tickInterval: 1
        },
        tooltip: {
            enabled: false
        },
        plotOptions: {
            bar: {
                dataLabels: {
                    enabled: true,
                    style: {
                        fontWeight: 'bold',
                        fontSize: '24px'
                    }
                },
                borderWidth: 0,
                shadow: false,
                pointWidth: 14,
                pointPadding: 0.2,
                pointStart: 1
            }
        },
        legend: {
            layout: 'vertical',
            align: 'left',
            verticalAlign: 'bottom',
            x: 0,
            y: 45,
            floating: true,
            borderWidth: 0,
            backgroundColor: '#FFFFFF',
            shadow: false
        },
        credits: {
            enabled: false
        },
        series: [{
            name: 'Your Average Score',
            data: JSON.parse("[" + assessment.b2 + "]"),
            index: 2
        }, {
            name: 'Successful Innovators',
            data: JSON.parse("[" + assessment.b2_avg + "]"),
            index: 1
        }]
    });


    chart8 = new Highcharts.Chart({
    	colors: ['#ec297b', '#999'],
        chart: {
            renderTo: 'chart_b_4',
            type: 'bar',
            spacingBottom: 60,
            height: 190,
            width: 268,
            animation: {
                duration: 1000
            },
            marginRight: 30
        },
        title: {
            text: null
        },
        xAxis: {
            labels: {
                enabled: false
            },
            tickLength: 0,
            tickHeight: 0
        },
        yAxis: {
            min: 1,
            max: 7,
            title: {
                text: null
            },
            labels: {
                overflow: 'justify'
            },
            tickInterval: 1
        },
        tooltip: {
            enabled: false
        },
        plotOptions: {
            bar: {
                dataLabels: {
                    enabled: true,
                    style: {
                        fontWeight: 'bold',
                        fontSize: '24px'
                    }
                },
                borderWidth: 0,
                shadow: false,
                pointWidth: 14,
                pointPadding: 0.2,
                pointStart: 1
            }
        },
        legend: {
            layout: 'vertical',
            align: 'left',
            verticalAlign: 'bottom',
            x: 0,
            y: 45,
            floating: true,
            borderWidth: 0,
            backgroundColor: '#FFFFFF',
            shadow: false
        },
        credits: {
            enabled: false
        },
        series: [{
            name: 'Your Average Score',
            data: JSON.parse("[" + assessment.b3 + "]"),
            index: 2
        }, {
            name: 'Successful Innovators',
            data: JSON.parse("[" + assessment.b3_avg + "]"),
            index: 1
        }]
    });


    chart9 = new Highcharts.Chart({
    	colors: ['#d5de24', '#999'],
        chart: {
            renderTo: 'chart_b_5',
            type: 'bar',
            spacingBottom: 60,
            height: 190,
            width: 268,
            animation: {
                duration: 1000
            },
            marginRight: 30
        },
        title: {
            text: null
        },
        xAxis: {
            labels: {
                enabled: false
            },
            tickLength: 0,
            tickHeight: 0
        },
        yAxis: {
            min: 1,
            max: 7,
            title: {
                text: null
            },
            labels: {
                overflow: 'justify'
            },
            tickInterval: 1
        },
        tooltip: {
            enabled: false
        },
        plotOptions: {
            bar: {
                dataLabels: {
                    enabled: true,
                    style: {
                        fontWeight: 'bold',
                        fontSize: '24px'
                    }
                },
                borderWidth: 0,
                shadow: false,
                pointWidth: 14,
                pointPadding: 0.2,
                pointStart: 1
            }
        },
        legend: {
            layout: 'vertical',
            align: 'left',
            verticalAlign: 'bottom',
            x: 0,
            y: 45,
            floating: true,
            borderWidth: 0,
            backgroundColor: '#FFFFFF',
            shadow: false
        },
        credits: {
            enabled: false
        },
        series: [{
            name: 'Your Average Score',
            data: JSON.parse("[" + assessment.b4 + "]"),
            index: 2
        }, {
            name: 'Successful Innovators',
            data: JSON.parse("[" + assessment.b4_avg + "]"),
            index: 1
        }]
    });


    chart10 = new Highcharts.Chart({
    	colors: ['#90278e', '#999'],
        chart: {
            renderTo: 'chart_b_6',
            type: 'bar',
            spacingBottom: 60,
            height: 190,
            width: 268,
            animation: {
                duration: 1000
            },
            marginRight: 30
        },
        title: {
            text: null
        },
        xAxis: {
            labels: {
                enabled: false
            },
            tickLength: 0,
            tickHeight: 0
        },
        yAxis: {
            min: 1,
            max: 7,
            title: {
                text: null
            },
            labels: {
                overflow: 'justify'
            },
            tickInterval: 1
        },
        tooltip: {
            enabled: false
        },
        plotOptions: {
            bar: {
                dataLabels: {
                    enabled: true,
                    style: {
                        fontWeight: 'bold',
                        fontSize: '24px'
                    }
                },
                borderWidth: 0,
                shadow: false,
                pointWidth: 14,
                pointPadding: 0.2,
                pointStart: 1
            }
        },
        legend: {
            layout: 'vertical',
            align: 'left',
            verticalAlign: 'bottom',
            x: 0,
            y: 45,
            floating: true,
            borderWidth: 0,
            backgroundColor: '#FFFFFF',
            shadow: false
        },
        credits: {
            enabled: false
        },
        series: [{
            name: 'Your Average Score',
            data: JSON.parse("[" + assessment.b5 + "]"),
            index: 2
        }, {
            name: 'Successful Innovators',
            data: JSON.parse("[" + assessment.b5_avg + "]"),
            index: 1
        }]
    });


    chart11 = new Highcharts.Chart({
    	colors: ['#000', '#999'],
        chart: {
            renderTo: 'chart_c_1',
            type: 'bar',
            spacingBottom: 60,
            height: 190,
            width: 268,
            animation: {
                duration: 1000
            },
            marginRight: 30
        },
        title: {
            text: null
        },
        xAxis: {
            labels: {
                enabled: false
            },
            tickLength: 0,
            tickHeight: 0
        },
        yAxis: {
            min: 1,
            max: 7,
            title: {
                text: null
            },
            labels: {
                overflow: 'justify'
            },
            tickInterval: 1
        },
        tooltip: {
            enabled: false
        },
        plotOptions: {
            bar: {
                dataLabels: {
                    enabled: true,
                    style: {
                        fontWeight: 'bold',
                        fontSize: '24px'
                    }
                },
                borderWidth: 0,
                shadow: false,
                pointWidth: 14,
                pointPadding: 0.2,
                pointStart: 1
            }
        },
        legend: {
            layout: 'vertical',
            align: 'left',
            verticalAlign: 'bottom',
            x: 0,
            y: 45,
            floating: true,
            borderWidth: 0,
            backgroundColor: '#FFFFFF',
            shadow: false
        },
        credits: {
            enabled: false
        },
        series: [{
            name: 'Your Average Score',
            data: JSON.parse("[" + assessment.c + "]"),
            index: 2
        }, {
            name: 'Successful Executors',
            data: JSON.parse("[" + assessment.c_avg + "]"),
            index: 1
        }]
    });


    chart12 = new Highcharts.Chart({
    	colors: ['#97963E', '#999'],
        chart: {
            renderTo: 'chart_c_2',
            type: 'bar',
            spacingBottom: 60,
            height: 190,
            width: 268,
            animation: {
                duration: 1000
            },
            marginRight: 30
        },
        title: {
            text: null
        },
        xAxis: {
            labels: {
                enabled: false
            },
            tickLength: 0,
            tickHeight: 0
        },
        yAxis: {
            min: 1,
            max: 7,
            title: {
                text: null
            },
            labels: {
                overflow: 'justify'
            },
            tickInterval: 1
        },
        tooltip: {
            enabled: false
        },
        plotOptions: {
            bar: {
                dataLabels: {
                    enabled: true,
                    style: {
                        fontWeight: 'bold',
                        fontSize: '24px'
                    }
                },
                borderWidth: 0,
                shadow: false,
                pointWidth: 14,
                pointPadding: 0.2,
                pointStart: 1
            }
        },
        legend: {
            layout: 'vertical',
            align: 'left',
            verticalAlign: 'bottom',
            x: 0,
            y: 45,
            floating: true,
            borderWidth: 0,
            backgroundColor: '#FFFFFF',
            shadow: false
        },
        credits: {
            enabled: false
        },
        series: [{
            name: 'Your Average Score',
            data: JSON.parse("[" + assessment.c1 + "]"),
            index: 2
        }, {
            name: 'Successful Executors',
            data: JSON.parse("[" + assessment.c1_avg + "]"),
            index: 1
        }]
    });
    chart13 = new Highcharts.Chart({
        colors: ['#8E2376', '#999'],
        chart: {
            renderTo: 'chart_c_3',
            type: 'bar',
            spacingBottom: 60,
            height: 190,
            width: 268,
            animation: {
                duration: 1000
            },
            marginRight: 30
        },
        title: {
            text: null
        },
        xAxis: {
            labels: {
                enabled: false
            },
            tickLength: 0,
            tickHeight: 0
        },
        yAxis: {
            min: 1,
            max: 7,
            title: {
                text: null
            },
            labels: {
                overflow: 'justify'
            },
            tickInterval: 1
        },
        tooltip: {
            enabled: false
        },
        plotOptions: {
            bar: {
                dataLabels: {
                    enabled: true,
                    style: {
                        fontWeight: 'bold',
                        fontSize: '24px'
                    }
                },
                borderWidth: 0,
                shadow: false,
                pointWidth: 14,
                pointPadding: 0.2,
                pointStart: 1
            }
        },
        legend: {
            layout: 'vertical',
            align: 'left',
            verticalAlign: 'bottom',
            x: 0,
            y: 45,
            floating: true,
            borderWidth: 0,
            backgroundColor: '#FFFFFF',
            shadow: false
        },
        credits: {
            enabled: false
        },
        series: [{
            name: 'Your Average Score',
            data: JSON.parse("[" + assessment.c2 + "]"),
            index: 2
        }, {
            name: 'Successful Executors',
            data: JSON.parse("[" + assessment.c2_avg + "]"),
            index: 1
        }]
    });
    chart14 = new Highcharts.Chart({
        colors: ['#D42712', '#999'],
        chart: {
            renderTo: 'chart_c_4',
            type: 'bar',
            spacingBottom: 60,
            height: 190,
            width: 268,
            animation: {
                duration: 1000
            },
            marginRight: 30
        },
        title: {
            text: null
        },
        xAxis: {
            labels: {
                enabled: false
            },
            tickLength: 0,
            tickHeight: 0
        },
        yAxis: {
            min: 1,
            max: 7,
            title: {
                text: null
            },
            labels: {
                overflow: 'justify'
            },
            tickInterval: 1
        },
        tooltip: {
            enabled: false
        },
        plotOptions: {
            bar: {
                dataLabels: {
                    enabled: true,
                    style: {
                        fontWeight: 'bold',
                        fontSize: '24px'
                    }
                },
                borderWidth: 0,
                shadow: false,
                pointWidth: 14,
                pointPadding: 0.2,
                pointStart: 1
            }
        },
        legend: {
            layout: 'vertical',
            align: 'left',
            verticalAlign: 'bottom',
            x: 0,
            y: 45,
            floating: true,
            borderWidth: 0,
            backgroundColor: '#FFFFFF',
            shadow: false
        },
        credits: {
            enabled: false
        },
        series: [{
            name: 'Your Average Score',
            data: JSON.parse("[" + assessment.c3 + "]"),
            index: 2
        }, {
            name: 'Successful Executors',
            data: JSON.parse("[" + assessment.c3_avg + "]"),
            index: 1
        }]
    });
    chart15 = new Highcharts.Chart({
        colors: ['#801820', '#999'],
        chart: {
            renderTo: 'chart_c_5',
            type: 'bar',
            spacingBottom: 60,
            height: 190,
            width: 268,
            animation: {
                duration: 1000
            },
            marginRight: 30
        },
        title: {
            text: null
        },
        xAxis: {
            labels: {
                enabled: false
            },
            tickLength: 0,
            tickHeight: 0
        },
        yAxis: {
            min: 1,
            max: 7,
            title: {
                text: null
            },
            labels: {
                overflow: 'justify'
            },
            tickInterval: 1
        },
        tooltip: {
            enabled: false
        },
        plotOptions: {
            bar: {
                dataLabels: {
                    enabled: true,
                    style: {
                        fontWeight: 'bold',
                        fontSize: '24px'
                    }
                },
                borderWidth: 0,
                shadow: false,
                pointWidth: 14,
                pointPadding: 0.2,
                pointStart: 1
            }
        },
        legend: {
            layout: 'vertical',
            align: 'left',
            verticalAlign: 'bottom',
            x: 0,
            y: 45,
            floating: true,
            borderWidth: 0,
            backgroundColor: '#FFFFFF',
            shadow: false
        },
        credits: {
            enabled: false
        },
        series: [{
            name: 'Your Average Score',
            data: JSON.parse("[" + assessment.c4 + "]"),
            index: 2
        }, {
            name: 'Successful Executors',
            data: JSON.parse("[" + assessment.c4_avg + "]"),
            index: 1
        }]
    });
}

$('.demo-filter-form input').change(function(e){
    t = $(this),
    val = t.val(),
    form = t.closest('form'),
    action = form.attr('action'),
    group = t.closest('.cat-demo-group'),
    catid = group.data('section'),
    chartname = group.siblings('.chart').data('chart'),
    data = form.serialize();
    $('#screen-cover').fadeIn();
    $.get(action,
        data,
        function(d, textStatus, jqXHR){
            newval = d[0][catid];
            window[chartname].series[0].setData(JSON.parse("["+newval+"]"), true);
            //    window[chartname].series[0].data[0].update(JSON.parse("["+demoval+"]"), true);
           /* window[chartname].series[0].name = label;
            window[chartname].series[0].legendItem = window[chartname].series[0].legendItem.destroy();
            window[chartname].isDirtyLegend = true;*/
            window[chartname].redraw(false);
            $('#screen-cover').fadeOut();
        },
        'json'
    );

/*    $('input[type="radio"]', group).not($(this)).prop('checked', false);
    if(!t.hasClass('industry-filter')){
        $('select', group).val('');
        label = t.next('label').html();
    } else {
        label = t.find(':selected').html();
    }*/
    //chartname = 'chart';
   /* demoval = demographics[val];
    demoval = demoval[catid];*/

    /*window[chartname].series[0].setData(JSON.parse("["+demoval+"]"), true);
    //    window[chartname].series[0].data[0].update(JSON.parse("["+demoval+"]"), true);
    window[chartname].series[0].name = label;
    window[chartname].series[0].legendItem = window[chartname].series[0].legendItem.destroy();
    window[chartname].isDirtyLegend = true;
    window[chartname].redraw(false);*/
});

$('.graph-filter-toggle').click(function(e){
    e.preventDefault();
    if($(this).hasClass('open')){
        $(this).removeClass('open');
        $(this).next('.cat-demo-group').fadeOut();
    } else {
        $(this).addClass('open');
        $(this).next('.cat-demo-group').fadeIn();
    }
});
$('.demographic_wrap .close').click(function(e){
    e.preventDefault();
    t = $(this);
    t.closest('.demographic_wrap').prev('.graph-filter-toggle').click();
});

$('.sep').click(function(e){
    e.stopPropagation();
});

});