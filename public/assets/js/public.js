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

//$('#social_polling_see_results_wrapper').html($results_html);



//var $answer1_html = answer_1_text+' '+answer_1_votes;

//var $answer2_html = answer_2_text+' '+answer_2_votes;


var $answer1_html = '<h1>'+answer_1_percent+'%</h1>Votes';

var $answer2_html = '<h1>'+answer_2_percent+'%</h1>Votes';	



$('#answer_1_wrapper .poll_results').html($answer1_html);

$('#answer_2_wrapper .poll_results').html($answer2_html);


		setTimeout(function() {
			var $comment_box_offset = $('#respond').offset().top + 'px';
  $("html, body").animate({ scrollTop: $comment_box_offset  }, 450);
}, 2000);

//$('#social_polling_see_results_wrapper').html('You Already Voted');
				

				

				

				

				

				

				

			}//success

		

		});//$.ajax({

		

	}//show_results

	

	

	

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

	
 $("#textarea")
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

				

				//alert(data);

				//show_results($post_id);

				

				

				//console.log(data+ajaxurl);

				

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

				

				//alert(data);

				//show_results($post_id);

				

				

				//console.log(data+ajaxurl);

				

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

	jQuery('#commentform textarea#comment').keypress(function(e) {

			

		jQuery('#commentform textarea#comment').attr('maxlength', 280);	

			

  		  var tval = jQuery('#commentform textarea#comment').val(),

       		  tlength = tval.length,

              set = 280,

              remain = parseInt(set - tlength);

    		  jQuery('#charcters_left').html(remain);

    if (remain <= 0 && e.which !== 0 && e.charCode !== 0) {

        jQuery('#commentform textarea').val((tval).substring(0, tlength - 1))

    }

	})//jQuery('#commentform textarea#comment').keypress(function(e) {

	

	

	

	

	

	

	

	

	

		

	//----------------------------

	//END Comment Javascript.

	//----------------------------

	

	

	});



}(jQuery));
