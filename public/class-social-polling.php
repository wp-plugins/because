<?php
/**
 * Plugin Name.
 *
 * @package   Social_Polling
 * @author    Your Name <email@example.com>
 * @license   GPL-2.0+
 * @link      http://example.com
 * @copyright 2014 Your Name or Company Name
 */

/**
 * Plugin class. This class should ideally be used to work with the
 * public-facing side of the WordPress site.
 *
 * If you're interested in introducing administrative or dashboard
 * functionality, then refer to `class-social-polling-admin.php`
 *
 * @TODO: Rename this class to a proper name for your plugin.
 *
 * @package Social_Polling
 * @author  Your Name <email@example.com>
 */
class Social_Polling {

	/**
	 * Plugin version, used for cache-busting of style and script file references.
	 *
	 * @since   1.0.0
	 *
	 * @var     string
	 */
	const VERSION = '1.0.0';

	/**
	 * @TODO - Rename "social-polling" to the name your your plugin
	 *
	 * Unique identifier for your plugin.
	 *
	 *
	 * The variable name is used as the text domain when internationalizing strings
	 * of text. Its value should match the Text Domain file header in the main
	 * plugin file.
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	protected $plugin_slug = 'social-polling';

	/**
	 * Instance of this class.
	 *
	 * @since    1.0.0
	 *
	 * @var      object
	 */
	protected static $instance = null;

	/**
	 * Initialize the plugin by setting localization and loading public scripts
	 * and styles.
	 *
	 * @since     1.0.0
	 */
	private function __construct() {
		
		
		global $wpdb;
		$db_table = $wpdb->prefix . "social_polling";
		$this->db_table = $db_table;

		// Load plugin text domain
		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );

		// Activate plugin when new blog is added
		add_action( 'wpmu_new_blog', array( $this, 'activate_new_site' ) );

		// Load public-facing style sheet and JavaScript.
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

		/* Define custom functionality.
		 * Refer To http://codex.wordpress.org/Plugin_API#Hooks.2C_Actions_and_Filters
		 */
		add_action( '@TODO', array( $this, 'action_method_name' ) );
		add_filter( '@TODO', array( $this, 'filter_method_name' ) );
		
		
		//Right Before Comment form.
		//do_action( 'comment_form_before' );
		add_action( 'output_social_poll_action', array( $this, 'output_social_poll' ));
		
		// add_filter('wp_insert_comment', array( $this, 'message_reply_email'),  2, 2 );
		// do_action( 'wp_insert_comment', $id, $comment );
		 
		 
		 
		 
		 
		//Add Ajax Actions
			
		//Need to define a javascript variable since our JS is in its own 
		//Seperate JS file.	
		add_action('wp_head', array( $this,'social_polling_pluginname_ajaxurl'));

		if ( is_admin() ) {
    		add_action('wp_ajax_social_PollingAjax',  array( $this,'social_PollingAjax'));
			add_action('wp_ajax_nopriv_social_PollingAjax',  array( $this,'social_PollingAjax'));
   			 // Add other back-end action hooks here
		} else {
   		 // Add non-Ajax front-end action hooks here
			add_action('wp_ajax_social_PollingAjax',  array( $this,'social_PollingAjax'));
			add_action('wp_ajax_nopriv_social_PollingAjax',  array( $this,'social_PollingAjax'));
		
		}//End Handle Ajax
		
		
		
		
		
		

	}
	
	
	
	
	/**
	 * Social Polling Email Response To Comment 
	 *
	 * @since    1.0.0
	 *
	 * 
	 */

	/*
	function message_reply_email( $id, $comment){
		
		
		
		//Comment Approved?
		$comment_approved = $comment->comment_approved;
		if(!$comment->comment_approved  || $comment->comment_approved == 'spam'):
			//Comment not approved, or its spam
			//We can end this function right here
			return;
		endif;
		
		//Get the Comment Author's email.
		//Make sure it's not blank, and it's not NULL
		$author_email = isset($comment->comment_author_email) && $comment->comment_author_email != '' && !is_null($comment->comment_author_email) ? $comment->comment_author_email : false;
		//Get the Comment Author's Name.
		$commenter_name =  $comment->comment_author;
			
		//Is this comment a reply to another comment?
		$comment_parent_id = isset($comment->comment_parent) && !is_null($comment->comment_parent) ? $comment->comment_parent : false;
		
		//If we have a parent comment, then the current comment is a reply.
		//So lets get some details about the parent comment so we can send a notification.
		if($comment_parent_id):
			$parent_comment_array = get_comment( $comment_parent_id, 'ARRAY_A' );
			$parent_comment_email = $parent_comment_array['comment_author_email'];
			$comment_post_ID = $parent_comment_array['comment_post_ID'];
			
			ob_start();?>
			<p>
				<?php  echo $commenter_name ?> Replied to your comment on <strong><a href="<?php echo get_permalink($comment_post_ID); ?>"><?php echo  get_the_title($comment_post_ID); ?></a></strong>
            </p>
            <p>
            <strong>Here is what they said:</strong><br />
            <?php echo  $comment->comment_content; ?>           
            </p>
            <p><a href="<?php echo get_permalink($comment_post_ID); ?>#comment-<?php echo $id; ?>">View on Site</a></p>
			<?php
            $comment_email_content = ob_get_contents();
			ob_end_clean();
			
			//echo $parent_comment_email;
			//echo $commenter_name.' Replied to Your Comment';
			//echo $comment_email_content;
			
			$emailit = wp_mail( $parent_comment_email, $commenter_name.' Replied to Your Comment', $comment_email_content ); 
			
			
			//echo" <br /><hr /><pre style='background-color:black; color:white;'>".htmlspecialchars(print_r($emailit,true))."</pre>";
		endif;
			
		
		
		//This line below should cause an error, which will allow us to see any info outputed above on screen.
		//wp_mail( 'asfsad@yopmail.com', 'The subject', $id.$comment ); 
		//echo 'HELLO';
	}//
	
	*/
	
	
	/**
	 * Social Polling Ajax URL
	 *
	 * @since    1.0.0
	 *
	 * 
	 */
	 
	function social_polling_pluginname_ajaxurl() {
?>
		  <script type="text/javascript">
          var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>?action=social_PollingAjax';
          </script>
    <?php }
	
	
	
	
		
	/**
	 * Social Get Voter.
	 *
	 * @since    1.0.0
	 * 
	 * Actually Do the Vote
	 *
	 * @return   Status of vote.
	 */
	 
	 private function get_voter($get_user_by = ''){
		 
		 switch($get_user_by):
		
			  case('ip_address'):
			   //Try to Grab IP Address
	  
				if (!empty($_SERVER['HTTP_CLIENT_IP']))
				//check ip from share internet
				{
				  $ip=$_SERVER['HTTP_CLIENT_IP'];
				}
				elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
				//to check ip is pass from proxy
				{
				  $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
				}
				else
				{
				  $ip=$_SERVER['REMOTE_ADDR'];
				}
				return $ip;
			  break;//case('ip_address'):
			  
			  default:
			  //Return current user ID.
			  return get_current_user_id();			  
				
			  break;
		endswitch;
		  
	 }//get_voter
	
	
	
	/**
	 * Social Polling Vote
	 *
	 * @since    1.0.0
	 * 
	 * Actually Do the Vote
	 *
	 * @return   Status of vote.
	 */
	 
	 private function social_polling_vote($args = array()){
		 global $wpdb;
				
		 //$args['post_id'] = $post_id;
		 //$args['vote_value']
		 $defaults = array();
		 $do_vote = false;
		 $args = wp_parse_args( $args, $defaults );
		 extract( $args );
	
		 //Lets args for our DB insert.
		 $data = array();
		 $format = array();
		 $data['voter_ip'] =  md5($this->get_voter('ip_address'));
		 //String
		 $format[] = '%s';
		 	 
		 $data['voter_id'] = $this->get_voter();
		//Whole Number/integer
		 $format[] = '%d';
		 	 
		 $data['poll_id'] = $post_id;
	     //String
		 $format[] = '%s';
		 
		 $data['vote_value']= $vote_value;
		 //String
		 $format[] = '%s';
		
		 $table = $this->db_table;
		
		
		 $already_voted = $wpdb->get_row("SELECT * FROM $table WHERE poll_id = ".$data['poll_id']." AND (voter_ip = '".$data['voter_ip']."'  ||    (voter_id = '".$data['voter_id']."'  &&   voter_id != '0' ))" );	
		// $already_voted = $wpdb->get_row("SELECT * FROM $table WHERE poll_id = ".$data['poll_id']." AND (voter_ip = '".$data['voter_ip']."'  ||    voter_id = '".$data['voter_id']."')" );	
		 if($already_voted):
		 //You already voted 
		 	$response['vote_status'] = 'already_voted';
		 	return $response;
		 else:
			//Do the vote	
			 $do_vote = $wpdb->insert( $table, $data, $format );
		 endif;//if($already_voted):
		
	    if($do_vote):
			$response['vote_status'] = 'voted';
	    	//modify comments to display 
			$this->add_id_to_comments($data);
			$response['new_comments'] = $this->update_comments($data);
		else:
		 return false;
		endif;
	 }//social_polling_vote
	 
	 private function add_id_to_comments($data){
	 	global $wpdb;

	 	$table = $wpdb->prefix . "postmeta";
		$query = "SELECT meta_key FROM $table WHERE post_id='" . $data['poll_id'] . "' AND meta_value='" . $data['vote_value']. "'";
		$ans_choice = $wpdb->get_row($query);

		if ($ans_choice->meta_key == "social_polling_answer_one_field" || $ans_choice->meta_key == "_social_polling_answer_one_field"){
			$choice = "one";
		}
		else if ($ans_choice->meta_key == "social_polling_answer_two_field" || $ans_choice->meta_key == "_social_polling_answer_two_field"){
			$choice = "two";
		}

	 	$table = $wpdb->prefix . "comments";
	 	$comments = $wpdb->get_results("SELECT comment_ID from $table where comment_post_ID='" . $data['poll_id'] . "' AND (comment_author_IP = '".$data['voter_ip']."'  ||    (user_id = '".$data['voter_id']."'  &&   user_id != '0' ))" );	

	 	foreach($comments as $row){
	 		add_comment_meta($row->comment_ID,'vote_choice',$choice);
	 	}
                
        return;
	 }

	 private function update_comments($data){
	 			
				
				$post_id = $data['poll_id'];
				
				$comment_args['post_id'] = $post_id;

				$comments = get_comments( $comment_args );

				ob_start();

				wp_list_comments(array(

							'callback' => 'mytheme_comment',

							'walker' => new zipGun_walker_comment()

							//'per_page' => 10, //Allow comment pagination

							//'reverse_top_level' => $sortby == 'oldest' ? false : true //Show the latest comments at the top of the list

				), $comments);

				$updated_comments = ob_get_contents();

				ob_end_clean;

	 }
	 
	  public function get_poll_results($post_id){
		 global $wpdb;
			  $table = $this->db_table;
			  
			  //Get all poll results from DB
			  $poll_votes = $wpdb->get_results("SELECT * FROM $table WHERE poll_id = '".$post_id."'", 'ARRAY_A');	
			  
			  
			  $answers = array();
			  $answer_1 = get_post_meta($post_id,'_social_polling_answer_one_field',true ); 
			  $answer_2 = get_post_meta($post_id,'_social_polling_answer_two_field',true ); 
				
				//Get answer 1 from post meta
				if(!empty($answer_1)  &&   $answer_1 != NULL):
					$answers['social_poll_answer_1']['answer_text'] = get_post_meta($post_id,'social_polling_answer_one_field',true ); 
					$answers['social_poll_answer_1']['answer_slug'] = $answer_1;
					$answers['social_poll_answer_1']['total_votes'] = 0;
				else:	
					$answer_1 = false;
				endif;
				
				//Get answer 2 from post meta
				if(!empty($answer_2)  &&   $answer_2 != NULL):
					$answers['social_poll_answer_2']['answer_text'] = get_post_meta($post_id,'social_polling_answer_two_field',true ); 
				    $answers['social_poll_answer_2']['answer_slug'] = $answer_2;
					$answers['social_poll_answer_2']['total_votes'] = 0;
				else:	
					$answer_2 = false;
				endif;
		 
		 
		 		//Create a result array
		 		foreach( $poll_votes as $key => $value):
				
					//Only add to array if vote value matches our current value
					switch($value['vote_value']):
						case($answer_1):
						
							$answers['social_poll_answer_1']['total_votes'] = $answers['social_poll_answer_1']['total_votes'] + 1;
							$answers['social_poll_answer_1']['voters']['by_ip'][$value['voter_ip']] = $value['voter_ip'];
							$answers['social_poll_answer_1']['voters']['by_id'][$value['voter_id']] = $value['voter_id'];
						
						break;
						
						case($answer_2):
						
							$answers['social_poll_answer_2']['total_votes'] = $answers['social_poll_answer_2']['total_votes'] + 1;
							$answers['social_poll_answer_2']['voters']['by_ip'][$value['voter_ip']] = $value['voter_ip'];
							$answers['social_poll_answer_2']['voters']['by_id'][$value['voter_id']] = $value['voter_id'];
						
						
						break;
						
						default:
						
									
					endswitch;//switch($value['vote_value']):
				
				endforeach;
		 	  //	foreach( $poll_votes as $key => $value):	
		 
		 
		 return  $answers;
		  
		  
		  
		  
	  }//get_poll_results
	 
	 
	 
	 
	 
	 
	 
	 
	
	/**
	 * Social Polling Ajax actions
	 *
	 * @since    1.0.0
	 *
	 * @return    Plugin slug variable.
	 */
	 
	 
	 public function social_PollingAjax(){
		
		
		
		//What We Are Doing
		$wwad = isset($_POST['wwad']) ? $_POST['wwad'] : false;
		if(!$wwad):
			echo 'error';
			exit();
		endif;
		
		switch($wwad):
		  //Getting some results
		    case('show_results'):
				$post_id = is_numeric(sanitize_text_field($_POST['post_id'])) ?  sanitize_text_field($_POST['post_id'])  :  false;
				
				if(!$post_id):
					echo 'error';
				exit();
				endif;
			
				//Get all results from this poll.
				$results = $this->get_poll_results($post_id);
				echo json_encode($results);
				die();
				exit();
					
			
			break;//show_results	
			
			
			
			
			
		  //Doing a vote			
		  case('social_polling_vote'):
		 	//Post we are voting on.
		 	$vote = false;
			$post_id = is_numeric(sanitize_text_field($_POST['post_id'])) ?  sanitize_text_field($_POST['post_id'])  :  false;
			$vote_value = sanitize_text_field($_POST['vote_value']);
		 	
			//If we have an issue with the post ID.
			if(!$post_id):
				echo 'error';
			exit();
			endif;
			
			
			//Lets take our post_id and use this to get our two possible answers.
			$answers = array();
			$answer_1 = get_post_meta($post_id,'_social_polling_answer_one_field',true ); 
			$answer_2 = get_post_meta($post_id,'_social_polling_answer_two_field',true ); 
			
			//Get answer 1 from post meta
			if(!empty($answer_1)  &&   $answer_1 != NULL):
				$answers[] = $answer_1;
			endif;
			
			//Get answer 2 from post meta
			if(!empty($answer_2)  &&   $answer_2 != NULL):
				$answers[] = $answer_2;
			endif;
			
			//If our vote value is in out answer array, then do the vote.
			if(in_array($vote_value, $answers)):
			
				$args = array();
				$args['post_id'] = $post_id;
				$args['vote_value'] = $vote_value;			
				
				$vote = $this->social_polling_vote($args);
			
			
			endif;
			
			switch($vote[vote_status]): 	
		 	case('voted'):
				// echo 'You were voting on Post '.get_post_meta($post_id,'social_polling_question_field',true );
		 		// 	echo 'You voted '.$vote_value;
		 		echo $vote['new_comments'];
		  	break;
			
			case('already_voted'):
				echo "already_voted";
		  	break;			
			
			default:
			echo 'Error';
			endswitch;
		  
		  
		  die(); 
		  break;
		
		endswitch;//switch($wwad):
		
		
			
	
		die(); 
		exit(); 
		 
	 
	 
	 
	 
	 }
	 /**
	 * Output Social Polling Question and Vote Box.
	 *
	 * @since    1.0.0
	 *
	 * @return    Plugin slug variable.
	 */
	 
	 
	 public function output_social_poll(){
		global $post; 
			
		 	ob_start();
			//First lets check if social polling is active on this post.
			$active = get_post_meta($post->ID, 'social_polling_active_field', true ) == 'yes' ? true : false;
			
			if($active):
			//Get our post meta
			$post_meta = get_post_meta($post->ID);
			
			//Get our answer images, if we have them.
			$image_1 = wp_get_attachment_image_src( $post_meta['social_polling_answer_one_image_id'][0], 'medium' );
			$image_2 = wp_get_attachment_image_src( $post_meta['social_polling_answer_two_image_id'][0], 'medium' );
			
			$answer_1 = trim($post_meta['_social_polling_answer_one_field'][0]);
			$answer_2 = trim($post_meta['_social_polling_answer_two_field'][0]);
			
			if(is_null($answer_1) || $answer_1 == '' ||  is_null($answer_2) || $answer_2 == ''):
				
			 if ( current_user_can( 'manage_options' ) ):
				echo ' <div id="social_polling_wrapper"><p class="error"><strong>Social Polling Error</strong> You must fill out answer 1 and answer 2 in the <a href="'.get_edit_post_link( $post->ID).'">admin</a> for this post in order for the social poll to appear.</p></div>';
			endif;
			
			return false;
			endif;
			
		
			
			?>
            <div id="social_polling_wrapper">
            <h1 class="sp_branding"><img src="<?php echo plugins_url( 'assets/images/official logo.png', __FILE__ ); ?>" /></h1>
            <label class="social_polling_question"> <?php echo $post_meta['social_polling_question_field'][0]; ?></label>
            <div class="social_polling_answers">
            <button id="answer_1_wrapper" class="answer_divs"  data-post_id = "<?php echo $post->ID ?>"  data-vote_value = "<?php echo $post_meta['_social_polling_answer_one_field'][0]; ?>">
            
             <p style=" <?php echo $image_1[0] ? 'background-image:url('.$image_1[0].')' : ''; ?>" class="answer_image">
   			  <?php /*?> <img src="" id="social_polling_answer_one_image_image" class="<?php echo $image_1[0] ? 'active_img' : ''; ?>"   data-img_target ='social_polling_answer_one_image' /><?php */?>
               <span class="poll_results"></span>
             </p>
            
            <label class="answer_label"> <?php echo esc_attr(  $post_meta['social_polling_answer_one_field'][0] ); ?> </label>
            
            
            </button><?php //#answer_1_wrapper ?>
            <button id="answer_2_wrapper" class="answer_divs" data-post_id = "<?php echo $post->ID ?>"  data-vote_value = "<?php echo $post_meta['_social_polling_answer_two_field'][0]; ?>">
             <p style=" <?php echo $image_2[0] ? 'background-image:url('.$image_2[0].')' : ''; ?>" class="answer_image">
   			  <?php /*?> <img  id="social_polling_answer_two_image_image" class="<?php echo $image_1[0] ? 'active_img' : ''; ?>"   data-img_target ='social_polling_answer_two_image' /><?php */?>
               <span class="poll_results"></span>
             </p>
            
           <label class="answer_label"> <?php echo esc_attr(  $post_meta['social_polling_answer_two_field'][0] ); ?> </label>
            
            </button><?php //#answer_2_wrapper ?>
            <div style="clear:left"></div>
            
            
            
            
            
            </div><?php //social_polling_answers ?>
         
            
            	<div id="social_polling_see_results_wrapper"><?php echo _e( 'Vote to see results', 'myplugin_textdomain' ); ?></div>
            
           <div id="social_polling_already_voted_wrapper">Note: You have already voted on this poll</div> 
            
            
            
            
            
            
            
            </div>
			<?php endif;//$active.
			//echo" <br /><hr /><pre style='background-color:black; color:white;'>".htmlspecialchars(print_r($post_meta,true))."</pre>";
			$socialpoll = ob_get_contents();
			ob_end_clean();
		 
	 		echo $socialpoll;
	 
	 }	

	/**
	 * Return the plugin slug.
	 *
	 * @since    1.0.0
	 *
	 * @return    Plugin slug variable.
	 */
	public function get_plugin_slug() {
		return $this->plugin_slug;
	}

	/**
	 * Return an instance of this class.
	 *
	 * @since     1.0.0
	 *
	 * @return    object    A single instance of this class.
	 */
	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Fired when the plugin is activated.
	 *
	 * @since    1.0.0
	 *
	 * @param    boolean    $network_wide    True if WPMU superadmin uses
	 *                                       "Network Activate" action, false if
	 *                                       WPMU is disabled or plugin is
	 *                                       activated on an individual blog.
	 */
	public static function activate( $network_wide ) {
		
		if ( function_exists( 'is_multisite' ) && is_multisite() ) {

			if ( $network_wide  ) {

				// Get all blog ids
				$blog_ids = self::get_blog_ids();

				foreach ( $blog_ids as $blog_id ) {

					switch_to_blog( $blog_id );
					self::single_activate();
				}

				restore_current_blog();

			} else {
				self::single_activate();
			}

		} else {
			self::single_activate();
		}

	
	 //Create Social Polling Database Table
	  global $wpdb;
   	  global $sp_db_version;

   	  $table_name = $wpdb->prefix . "social_polling";
      
	  $sql = "CREATE TABLE $table_name (
	  id mediumint(9) NOT NULL AUTO_INCREMENT,
	  vote_time TIMESTAMP NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
	  poll_id tinytext NOT NULL,
	  vote_value text NOT NULL,
	  voter_id mediumint(9) NOT NULL,
	  voter_ip VARCHAR(55) DEFAULT '' NOT NULL,
	  UNIQUE KEY id (id)
		);";

   	  require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
  	  dbDelta( $sql );
 
      add_option( "sp_db_version", $sp_db_version );

	
	}//activate

	/**
	 * Fired when the plugin is deactivated.
	 *
	 * @since    1.0.0
	 *
	 * @param    boolean    $network_wide    True if WPMU superadmin uses
	 *                                       "Network Deactivate" action, false if
	 *                                       WPMU is disabled or plugin is
	 *                                       deactivated on an individual blog.
	 */
	public static function deactivate( $network_wide ) {

		if ( function_exists( 'is_multisite' ) && is_multisite() ) {

			if ( $network_wide ) {

				// Get all blog ids
				$blog_ids = self::get_blog_ids();

				foreach ( $blog_ids as $blog_id ) {

					switch_to_blog( $blog_id );
					self::single_deactivate();

				}

				restore_current_blog();

			} else {
				self::single_deactivate();
			}

		} else {
			self::single_deactivate();
		}

	}

	/**
	 * Fired when a new site is activated with a WPMU environment.
	 *
	 * @since    1.0.0
	 *
	 * @param    int    $blog_id    ID of the new blog.
	 */
	public function activate_new_site( $blog_id ) {

		if ( 1 !== did_action( 'wpmu_new_blog' ) ) {
			return;
		}

		switch_to_blog( $blog_id );
		self::single_activate();
		restore_current_blog();

	}

	/**
	 * Get all blog ids of blogs in the current network that are:
	 * - not archived
	 * - not spam
	 * - not deleted
	 *
	 * @since    1.0.0
	 *
	 * @return   array|false    The blog ids, false if no matches.
	 */
	private static function get_blog_ids() {

		global $wpdb;

		// get an array of blog ids
		$sql = "SELECT blog_id FROM $wpdb->blogs
			WHERE archived = '0' AND spam = '0'
			AND deleted = '0'";

		return $wpdb->get_col( $sql );

	}

	/**
	 * Fired for each blog when the plugin is activated.
	 *
	 * @since    1.0.0
	 */
	private static function single_activate() {
		// @TODO: Define activation functionality here
	}

	/**
	 * Fired for each blog when the plugin is deactivated.
	 *
	 * @since    1.0.0
	 */
	private static function single_deactivate() {
		// @TODO: Define deactivation functionality here
	}

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		$domain = $this->plugin_slug;
		$locale = apply_filters( 'plugin_locale', get_locale(), $domain );

		load_textdomain( $domain, trailingslashit( WP_LANG_DIR ) . $domain . '/' . $domain . '-' . $locale . '.mo' );
		load_plugin_textdomain( $domain, FALSE, basename( plugin_dir_path( dirname( __FILE__ ) ) ) . '/languages/' );

	}

	/**
	 * Register and enqueue public-facing style sheet.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		wp_enqueue_style( $this->plugin_slug . '-plugin-styles', plugins_url( 'assets/css/public.css', __FILE__ ), array(), self::VERSION );
	}

	/**
	 * Register and enqueues public-facing JavaScript files.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		wp_enqueue_script( $this->plugin_slug . '-plugin-script', plugins_url( 'assets/js/public.js', __FILE__ ), array( 'jquery' ), self::VERSION );
	}

	/**
	 * NOTE:  Actions are points in the execution of a page or process
	 *        lifecycle that WordPress fires.
	 *
	 *        Actions:    http://codex.wordpress.org/Plugin_API#Actions
	 *        Reference:  http://codex.wordpress.org/Plugin_API/Action_Reference
	 *
	 * @since    1.0.0
	 */
	public function action_method_name() {
		// @TODO: Define your action hook callback here
	}

	/**
	 * NOTE:  Filters are points of execution in which WordPress modifies data
	 *        before saving it or sending it to the browser.
	 *
	 *        Filters: http://codex.wordpress.org/Plugin_API#Filters
	 *        Reference:  http://codex.wordpress.org/Plugin_API/Filter_Reference
	 *
	 * @since    1.0.0
	 */
	public function filter_method_name() {
		// @TODO: Define your filter hook callback here
	}

}
