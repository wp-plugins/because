(function ( $ ) {

	"use strict";



	//----------------------------

	//Voting Box Javascript

	//----------------------------

	$(function () {

		// Place your public-facing JavaScript here

		$(document).on('click', 'button.answer_divs', function(){

		

		var $post_id = $(this).attr('data-post_id'); 

		var $vote_value = $(this).attr('data-vote_value'); 

		

	

		$.ajax({

			type:"POST",

			url:ajaxurl,

			//url: "/wp-admin/admin-ajax.php",

			data: {

				 wwad: 'social_polling_vote', 

				 post_id: $post_id, 

				 vote_value: $vote_value, 

				},

			success:function(data){

				//alert(data);

				show_results($post_id);

				if(data === "already_voted"){
					$('#social_polling_see_results_wrapper').html('You Already Voted');	
				}
				else{
					$('#social_polling_see_results_wrapper').html('');
					$('.sp_comment-list').html(data);
				}

				//console.log(data+ajaxurl);

			}//success

		});//$.ajax({

			

				

	})//$(document).on('click', 'button.answer_divs', function(){

	

	

	

	

	function show_results($post_id){

		//Check if results are arelready on screen.

		var $on_screen = $('#results_box_'+$post_id).length;

		

		//if we already have the results on screeen.

		if( $on_screen > 0){

			return false;			

		}else{}

		

		$.ajax({

			type:"POST",

			dataType: "json",

			url:ajaxurl,

			//url: "/wp-admin/admin-ajax.php",

			data: {

				 wwad: 'show_results', 

				 post_id: $post_id, 

				},

			success:function(data){

				var answer_1 = data.social_poll_answer_1;

				var answer_2 = data.social_poll_answer_2;


				var answer_1_votes = data.social_poll_answer_1.total_votes;

				var answer_2_votes = data.social_poll_answer_2.total_votes;
				var answer_1_percent = (answer_1_votes/(answer_1_votes+answer_2_votes)*100).toFixed(2);
				var answer_2_percent = (answer_2_votes/(answer_1_votes+answer_2_votes)*100).toFixed(2);

				var answer_1_text = data.social_poll_answer_1.answer_text;

				var answer_2_text = data.social_poll_answer_2.answer_text;



				var $results_html = '<div class="one_result_box" id="results_box_'+$post_id+'"><p><span class="results_answer_text">'+answer_1_text+'</span> :<span class="results_answer_value">'+answer_1_percent+'</span></p>'

				+'<p><span class="results_answer_text">'+answer_2_text+'</span> :<span class="results_answer_value">'+answer_2_percent+'</div></p></div>';

				
				var $answer1_html = '<h1>'+answer_1_percent+'%</h1>';
				var $answer2_html = '<h1>'+answer_2_percent+'%</h1>';	



				$('#answer_1_wrapper .poll_results').html($answer1_html);
				$('#answer_2_wrapper .poll_results').html($answer2_html);


				setTimeout(function() {
					var $comment_box_offset = $('#respond').offset().top + 'px';
					$("html, body").animate({ scrollTop: $comment_box_offset  }, 450);
				}, 1000);

				// var $comment_box_offset = $('#respond').offset().top + 'px';
				// $("html, body").animate({ scrollTop: $comment_box_offset  }, 450);
	
			}//success
		
		});//$.ajax({		

	}//show_results

	$(window).bind('mousewheel DOMMouseScroll', function(event){
	    $("html, body").stop();
	});


	

	//----------------------------

	//Clicking Join Discussion box

	//----------------------------

	/*


		$(document).on('click', 'button.answer_divs', function(){

		var $comment_box_offset = $('#respond').offset().top + 'px';

		$("html, body").animate({ scrollTop: $comment_box_offset  }, 450);

		

	})

	
*/
	

	//----------------------------

	//END Voting Box Javascript

	//----------------------------

	

	

	
//change placeholder text, commented out for now but might need it again

	//document.getElementById('comment').placeholder = 'Why did you vote like that?';

	
 $("textarea")
  .focus(function() {
        if (this.value === this.defaultValue) {
            this.value = '';
        }
  })
  .blur(function() {
        if (this.value === '') {
            this.value = this.defaultValue;
        }
});
	

	

	

	//----------------------------

	//Comment Javascript

	//----------------------------

	// Place your public-facing JavaScript here
		
		//revert textarea to blank value if default value
		$(document).on('click','#submit',function(){
			if($('#commentform textarea#comment').val()=="Thanks for your vote! Want to expand on that opinion? We want to know what you think!"){
				$('#commentform textarea#comment').val('');
			}

		})

		$(document).on('click', '.comment_vote_action', function(){

		var $dis = $(this);

		console.log($comment_ajaxurl);

		var $comment_id = $(this).attr('data-comment_id'); 

		var $wwad = $(this).attr('data_action'); 

		var $nonce = $(this).attr('data-nonce'); 

	

		$.ajax({

			type:"POST",

			url:$comment_ajaxurl,

			//url: "/wp-admin/admin-ajax.php",

			data: {

				 nonce:$nonce,

				 wwad: $wwad, 

				 comment_id: $comment_id, 

				},

			success:function(data){

				console.log(data);

				

				switch(data){

				case('upvote'):	

					var $karma = $dis.parent('.comment_controls').find('.comment_karma_count');

					$karma.html(parseInt($karma.html()) + 1);

				break;	

				case('downvote'):	

				var $karma = $dis.parent('.comment_controls').find('.comment_karma_count');

					$karma.html(parseInt($karma.html()) - 1);

				break;

				

				case('flagged'):	

				$dis.css('font-weight','bold');

				break;

				

				

				case('unflagged'):	

				$dis.css('font-weight','normal');

				break;

				

				

				default:	

					

					

				}

				

			}//success

		});//$.ajax({

			

				

	})//$(document).on('click', 'button.answer_divs', function(){

	

	

	

	//Comment Sorting.

	$(document).on('click', '.sp_orderby_param', function(){

		

		jQuery(this).addClass('selected_sort');

		jQuery('a').not(this).removeClass('selected_sort');

		

		var $sortby = jQuery(this).attr('data-sortby');

 		var $nonce = $(this).attr('data-nonce'); 

		var $post_id = $('#sp_comments_order').attr('data-post_id');

		$.ajax({

			type:"POST",

			url:$comment_ajaxurl,

			//url: "/wp-admin/admin-ajax.php",

			data: {

				 nonce:$nonce,

				 sortby:$sortby,

				 wwad: 'comment_sorting', 

				 post_id: $post_id, 

				},

			success:function(data){

				

				

				

				$('.sp_comment-list').html(data);

				

				

			}//success

		});//$.ajax({

		

	

	

	

	})//$(document).on('click', '.sp_orderby_param', function(){

	

	

	jQuery(window).load(function(){

  			 function show_popup(){

     			jQuery("#thecomment_field").remove();

   			};

  		 window.setTimeout( show_popup, 5000 ); // 5 seconds

	})

	

	

	

	//Character limit

	$('#commentform textarea#comment').keyup(function(e) {

			

		$('#commentform textarea#comment').attr('maxlength', 280);	

		var tval = jQuery('#commentform textarea#comment').val(),
			tlength = tval.length,
			set = 280,
			remain = parseInt(set - tlength);

		$('#commentform .comment-characters-remaining span').text(remain);

	    if (remain <= 0 && e.which !== 0 && e.charCode !== 0) {

	        jQuery('#commentform textarea').val((tval).substring(0, tlength - 1))

	    }

	})//jQuery('#commentform textarea#comment').keypress(function(e) {

	
	$(document).on('click', '.social_share', function(){
		// alert('share click');
		$(this).after('<p>ssdkflnsdflknsdklfnsdkfns</p>')
		return false;
	})

	$(document).on('click', '.comment-collapse-button', function(){
		// $(this)
		if($(this).parents('.comment-body').children('.comment-collapse').css('display')==='block'){
			$(this).parents('.comment-body').children('.comment-collapse').css('display','none');
			$(this).parents('.comment-body').siblings().css('display','none');
			$(this).children('a').text('+');
			$(this).siblings('.comment-author').css('opacity','.4');
		}
		else{
			$(this).parents('.comment-body').children('.comment-collapse').css('display','block');
			$(this).parents('.comment-body').siblings().css('display','block');	
			$(this).children('a').text('-');
			$(this).siblings('.comment-author').css('opacity','1');
		}
		
		// if ($(this))
		return false;
	})

	// Comment sharing javascript

	var fadeOut = "";

	$('.share-anchor').mouseover(function(){
		$(this).siblings('.comment-social-share').stop();
		$(this).siblings('.comment-social-share').css('display','inline-block').animate({
			opacity: 1
		}, 300);
	})

	$('.share-anchor').click(function(){
		clearTimeout(fadeOut);

		$(this).siblings('.comment-social-share').stop();
		$(this).siblings('.comment-social-share').css('display','inline-block').animate({
			opacity: 1
		}, 300);
	})

	$('.reply-vote-share-container').mouseover(function(){
		clearTimeout($(this).data('timeout'));		
	});

	$('.reply-vote-share-container').mouseleave(function(){
			if($(this).children('.comment-social-share').css('opacity') === '1'){
				$(this).children('.comment-social-share').stop();
				var tempDiv = $(this);
				fadeOut = setTimeout(function(){
					tempDiv.children('.comment-social-share').animate({
						opacity: 0
					},300, function(){
						$(this).css('display','none');
					});
				},1000)
				$(this).data('timeout', fadeOut);
			}

			
	})

		

	//----------------------------

	//END Comment Javascript.

	//----------------------------

	

	

	});



}(jQuery));
