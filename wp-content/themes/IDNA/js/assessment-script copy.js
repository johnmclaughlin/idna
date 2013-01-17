jQuery(document).ready(function($){
    var review_form = $('#review-form'),
        progcount = $('#progress-count'),
        progdisplay = $('#progress-display'),
        bar = $('#progress-bar'),
        checkboxes = $('input[type=checkbox]');
	// Submit response when changing answer to questions
	$('.response').change(function(e){
		var t = $(this),
			id = t.attr('id'),
			val = t.val(),
			form = t.closest('form'),
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
			$('input[type="radio"]', form).each(function(){
				$(this).prop('checked', false);
			});
		}
		if('radio' === type){
            cbox = $('input[type="checkbox"]', form),
            cboxid = cbox.attr('id'),
            rev = $('#' + review_id);

            $('label[for="'+cboxid+'"]').removeClass('hover');
			cbox.prop('checked', false);
			rev.prop('checked', true);
		}
        document.getElementById('assessment-slides').scroll(0, 0);

		$.post(
			action,
			d,
			function(data, textStatus, jqXHR){
                t.closest('.question-page').find('.answer div').html(english_response);
                $('.flexslider-control-nav li:eq(' + index + ') a').addClass('complete');
                questionTotal = $('.flexslider-control-nav li').not('.hidden').not('.pre-assessment-nav').not('post-assessment-nav').length - 1;
                completed = $('.flexslider-control-nav .complete').length;
                remaining = questionTotal - completed;
                
                $('h3', '#nav-complete').html(completed);
                $('h3', '#nav-remain').html(remaining);

                //$('.flex-direction-nav li a.flex-next').click();
			}
		);
	});

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
            if(0 === val){
                na_count++;
            } else {
                ans_count++;
            }
        });
        jQuery('.review-block').each(function(){
            answered = $(this).find(':checked').length;
            if(answered === 0){
                $(this).addClass('error-block');
                invalids++;
                $(this).prependTo("#review-wrap");
            } else {
                $(this).removeClass('error-block');
            }
        });

        if(invalids === 0){
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


    checkboxes.each(function(){
        var label = $('label[for='+ $(this).attr('id') + ']');
        label.click(function(){
            $(this).toggleClass('hover');
        });
        $(this).add(label).wrap('<div class="custom-checkbox"></div>');
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
            count = slider.count;
            if(cur >= 58){
                $('.flex-next, .flex-prev, .control-toggle').addClass('flex-disabled hidden');
            } else {
                $('.flex-next, .control-toggle').removeClass('flex-disabled hidden');
                if(cur > 0){
                    $('.flex-prev').removeClass('flex-disabled hidden');
                }
            }
            if(cur > 1 && cur < (count - 5)){
                bar.fadeIn('slow');
                total = slider.count - 7;
                current = slider.currentSlide - 1;
                progcount.html(current + '/' + total);
                progdisplay.progressbar({value: (current/total)*100});
                $('#wrapper').addClass('has-shadow');
            } else {
                bar.fadeOut('slow');
                $('#wrapper').removeClass('has-shadow');
            }
            if(cur === 58){
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
                        if(0 === val){
                            na_count++;
                        } else {
                            ans_count++;
                        }
                    });
                    jQuery('.review-block').each(function(){
                        answered = $(this).find(':checked').length;
                        if(answered === 0){
                            $(this).addClass('error-block');
                            invalids++;
                            $(this).prependTo("#review-wrap");
                        } else {
                            $(this).removeClass('error-block');
                        }
                    });
            }
        },
        before: function(slider){
            $('.control-toggle.open').click();

        }
    });

    $('.error-block input').live('change', function(e){
        t = $(this),
        wrap = t.closest('.review-block'),
        last_error = $('.error-block', '#review-wrap').last();
        wrap.insertAfter(last_error).removeClass('error-block');

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


	$('.control-toggle').live('click', function(e){
		e.preventDefault();
		t = $(this),
		nav = t.parent('.nav-wrapper');
		if(t.hasClass('open')){
			nav.animate({
				'left': '-492px'
			}, 500);
			t.removeClass('open');
		} else {
			nav.animate({
				'left': '0px'
			}, 500);
			t.addClass('open');
		}
	});


});