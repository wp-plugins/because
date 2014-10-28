<?php

/**
 * Calls the class on the post edit screen.
 */
function call_votingBox() {
    new votingBox();
}

if ( is_admin() ) {
    add_action( 'load-post.php', 'call_votingBox' );
    add_action( 'load-post-new.php', 'call_votingBox' );
}

/** 
 * The Class.
 */
class votingBox {

	/**
	 * Hook into the appropriate actions when the class is constructed.
	 */
	public function __construct() {
		add_action( 'add_meta_boxes', array( $this, 'add_meta_box' ) );
		add_action( 'save_post', array( $this, 'save' ) );
	}

	/**
	 * Adds the meta box container.
	 */
	public function add_meta_box( $post_type ) {
            $post_types = array('post', 'page');     //limit meta box to certain post types
            if ( in_array( $post_type, $post_types )) {
		add_meta_box(
			'some_meta_box_name'
			,__( 'Social Polling Question', 'myplugin_textdomain' )
			,array( $this, 'render_meta_box_content' )
			,$post_type
			,'advanced'
			,'high'
		);
            }
	}

	/**
	 * Save the meta when the post is saved.
	 *
	 * @param int $post_id The ID of the post being saved.
	 */
	public function save( $post_id ) {
	
		/*
		 * We need to verify this came from the our screen and with proper authorization,
		 * because save_post can be triggered at other times.
		 */

		global $wpdb;

		// Check if our nonce is set.
		if ( ! isset( $_POST['myplugin_inner_custom_box_nonce'] ) )
			return $post_id;

		$nonce = $_POST['myplugin_inner_custom_box_nonce'];

		// Verify that the nonce is valid.
		if ( ! wp_verify_nonce( $nonce, 'myplugin_inner_custom_box' ) )
			return $post_id;

		// If this is an autosave, our form has not been submitted,
                //     so we don't want to do anything.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
			return $post_id;

		// Check the user's permissions.
		if ( 'page' == $_POST['post_type'] ) {

			if ( ! current_user_can( 'edit_page', $post_id ) )
				return $post_id;
	
		} else {

			if ( ! current_user_can( 'edit_post', $post_id ) )
				return $post_id;
		}

		/* OK, its safe for us to save the data now. */
		//Question
		$social_polling_active_field = sanitize_text_field( $_POST['social_polling_active_field'] );
		// Update the meta field.
		update_post_meta( $post_id, 'social_polling_active_field', $social_polling_active_field );	

		// check to see if social polling fields were already populated
		$old_social_polling_answer_one_field = get_post_meta( $post_id , '_social_polling_answer_one_field', true);
		$old_social_polling_answer_two_field = get_post_meta( $post_id , '_social_polling_answer_two_field', true);
		$update_table = $wpdb->prefix . "social_polling";	
		
					
		//Question
		$social_polling_question_field = sanitize_text_field( $_POST['social_polling_question_field'] );
		// Update the meta field.
		update_post_meta( $post_id, 'social_polling_question_field', $social_polling_question_field );		
		
		//Answer1
		$social_polling_answer_one_field = sanitize_text_field( $_POST['social_polling_answer_one_field'] );
		// Update the meta field.
		update_post_meta( $post_id, '_social_polling_answer_one_field', sanitize_title($social_polling_answer_one_field) );
		update_post_meta( $post_id, 'social_polling_answer_one_field', $social_polling_answer_one_field );
				
		$social_polling_answer_one_image_id = sanitize_text_field( $_POST['social_polling_answer_one_image_id'] );
		// Update the meta field.
		update_post_meta( $post_id, 'social_polling_answer_one_image_id', $social_polling_answer_one_image_id );

		if ($old_social_polling_answer_one_field){
			$wpdb->update( $update_table, 
					array(
						'vote_value' => sanitize_title($social_polling_answer_one_field)
					),
					array(
						'poll_id' => $post_id,
						'vote_value' => $old_social_polling_answer_one_field
					)
			);
		}
		
			
		//Answer2
		$social_polling_answer_two_field = sanitize_text_field( $_POST['social_polling_answer_two_field'] );
		// Update the meta field.
		update_post_meta( $post_id, 'social_polling_answer_two_field', $social_polling_answer_two_field );
		update_post_meta( $post_id, '_social_polling_answer_two_field', sanitize_title($social_polling_answer_two_field) );
		
		$social_polling_answer_two_image_id = sanitize_text_field( $_POST['social_polling_answer_two_image_id'] );
		// Update the meta field.
		update_post_meta( $post_id, 'social_polling_answer_two_image_id', $social_polling_answer_two_image_id );

		if ($old_social_polling_answer_two_field){
			$wpdb->update( $update_table, 
					array(
						'vote_value' => sanitize_title($social_polling_answer_two_field)
					),
					array(
						'poll_id' => $post_id,
						'vote_value' => $old_social_polling_answer_two_field
					)
			);
		}
			
		
		$mydata = sanitize_text_field( $_POST['myplugin_new_field'] );
		// Update the meta field.
		update_post_meta( $post_id, '_my_meta_value_key', $mydata );
	
	
	
	
	
	
	
	
	
	
	}


	/**
	 * Render Meta Box content.
	 *
	 * @param WP_Post $post The post object.
	 */
	public function render_meta_box_content( $post ) {
	
		// Add an nonce field so we can check for it later.
		wp_nonce_field( 'myplugin_inner_custom_box', 'myplugin_inner_custom_box_nonce' );

		// Use get_post_meta to retrieve an existing value from the database.
		$post_meta = get_post_meta($post->ID);
		
		?>
		<style>
        .answer_divs {
            width: 49%;
            float: left;
            text-align:center;
        }
        
        
        .answer_divs .active_img {
        box-shadow: 0 0 0 1px #fff,0 0 0 5px #1e8cbe; 
        }
        </style>

  
  <?php if(esc_attr(  $post_meta['social_polling_active_field'][0] ) == 'yes'  &&   
  			(((is_null($post_meta['social_polling_answer_one_field'][0])  || trim($post_meta['social_polling_answer_one_field'][0])) == '') ||
			((is_null($post_meta['social_polling_answer_two_field'][0])  || trim($post_meta['social_polling_answer_two_field'][0])) == ''))
		): ?>
  
  <div id="message" class="error">
  <p><strong>Social Polling</strong> - Error | <strong>Enter Answer #1 AND Answer #2 Fields Must Be Filled Out</strong></p>
  </div>
  
<?php endif; ?>
<p>
  <?php // Display the form, using the current value.?>

  
		<label for="social_polling_question_field">
		<?php _e( 'Polling Question Active', 'myplugin_textdomain' ); ?>:
		</label>
		<select type="select" id="social_polling_active_field" name="social_polling_active_field" value=" <?php echo esc_attr(  $post_meta['social_polling_active_field'][0] ); ?>">
        	 <option value="yes"  <?php echo esc_attr(  $post_meta['social_polling_active_field'][0] ) == 'yes' ? 'selected="selected"' : ''; ?> >Yes</option>    
            <option value="no"  <?php echo esc_attr(  $post_meta['social_polling_active_field'][0] ) == 'no' ? 'selected="selected"' : ''; ?> >No</option>
                 
        </select>
</p>






<?php //Question Field ?>
<p>
  <?php // Display the form, using the current value.?>
		<label for="social_polling_question_field">
		<?php _e( 'Polling Question', 'myplugin_textdomain' ); ?>:
		</label>
		<input placeholder="<?php _e( 'Enter your question', 'myplugin_textdomain' ); ?>" type="text" id="social_polling_question_field" name="social_polling_question_field" value=" <?php echo esc_attr(  $post_meta['social_polling_question_field'][0] ); ?>" size="35" />
</p>

<hr />
<?php //Answer 1 Fields ?>
<div class="answer_1 answer_divs">
  <p>
    <label for="social_polling_answer_one_field">
      <?php _e( 'Enter Answer #1', 'myplugin_textdomain' ); ?>
    </label>
  </p>
    <p><input placeholder="Answer 1 Label" type="text" id="social_polling_answer_one_field" name="social_polling_answer_one_field" value="<?php echo esc_attr(  $post_meta['social_polling_answer_one_field'][0] ); ?> " size="25" /></p>
 
  
  
  
   <p>
    <label for="social_polling_answer_one_image_id"><?php _e( 'Answer #1 Image', 'myplugin_textdomain' )?></label>
    </p> <input type="hidden" name="social_polling_answer_one_image_id" id="social_polling_answer_one_image_id" value="<?php echo $post_meta['social_polling_answer_one_image_id'][0]; ?>"  data-img_target ='social_polling_answer_one_image' />
   <p>  <?php $image_1 = wp_get_attachment_image_src( $post_meta['social_polling_answer_one_image_id'][0], 'thumbnail' );?>
    <img src="<?php echo $image_1[0] ? $image_1[0] : ''; ?>" id="social_polling_answer_one_image_image" class="<?php echo $image_1[0] ? 'active_img' : ''; ?>"   data-img_target ='social_polling_answer_one_image' /></p>
   
    <p><input type="button" id="meta-image-button" class="button meta-image-button_upload" value="<?php _e( 'Choose or Upload an Image', 'myplugin_textdomain' )?>" data-img_target ='social_polling_answer_one_image' /></p>
    <?php  if($image_1[0]): ?>
    <p><a href="javascript:void(0)" class="button remove_polling_img" data-img_target ='social_polling_answer_one_image'  >Remove Current Image</a></p>
	<?php endif; ?>
  
  
  
</div><?php //END answer_1 answer_divs ?>

<?php //Answer 2 Fields ?>
<div class="answer_2 answer_divs">
  	<p>
    <label for="social_polling_answer_two_field">
      <?php _e( 'Enter Answer #2', 'myplugin_textdomain' ); ?>
    </label>
    </p>
    <p><input 
    	placeholder="Answer 2 Label" 
        type="text" 
        id="social_polling_answer_two_field" 
        name="social_polling_answer_two_field" 
        value="<?php echo esc_attr( $post_meta['social_polling_answer_two_field'][0] ); ?> " 
        size="25" />
    </p>
 
  
  
  
  <p>
    <label for="social_polling_answer_two_image_id"><?php _e( 'Answer #2 Image', 'myplugin_textdomain' )?></label>
   </p>  
    <?php $image_2 = wp_get_attachment_image_src( $post_meta['social_polling_answer_two_image_id'][0], 'thumbnail' );?>
    
   <p> <input type="hidden" name="social_polling_answer_two_image_id" id="social_polling_answer_two_image_id" value="<?php echo $post_meta['social_polling_answer_two_image_id'][0]; ?>"  data-img_target ='social_polling_answer_two_image' />
    
    <img src="<?php echo $image_2[0] ? $image_2[0] : ''; ?>" id="social_polling_answer_two_image_image" class="<?php echo $image_2[0] ? 'active_img' : ''; ?>"  data-img_target ='social_polling_answer_two_image' /></p>
   
   <p><input type="button" id="meta-image-button" class="button meta-image-button_upload" value="<?php _e( 'Choose or Upload an Image', 'myplugin_textdomain' )?>" data-img_target ='social_polling_answer_two_image' /></p>
     <?php  if($image_1[0]): ?>
    <p><a href="javascript:void(0)" class="button remove_polling_img"  data-img_target ='social_polling_answer_two_image' >Remove Current Image</a></p>
	<?php endif; ?>
    

  
</div><?php //END answer_2 answer_divs ?>
<div style="clear:left"></div>


<?php	}//render_meta_box_content
}
