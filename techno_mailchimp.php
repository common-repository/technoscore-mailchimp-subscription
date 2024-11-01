<?php
/*
 * Plugin Name: TechnoScore MailChimp Subscription   
 * Plugin URI: http://nddw.com/demo3/sws-res-slider/newsletter-integation-mailchimp/
 * Description: Newsletter integration with mailchimp
 * Version: 1.0.0
 * Author: Technoscore
 * Author URI: http://www.technoscore.com/
 * Text Domain: techno_
*/
// create custom plugin settings menu
add_action('admin_menu', 'techno_create_menu');

function techno_create_menu() {

	//create new top-level menu
	add_menu_page('Newsletter Subscription', 'Newsletter Subscription', 'administrator', __FILE__, 'techno_settings_page');

	//call register settings function
	add_action( 'admin_init', 'techno_register_settings' );
}


function techno_register_settings() {
	//register our settings
	register_setting( 'techno-settings-group', 'techno_form_title' );
	register_setting( 'techno-settings-group', 'techno_mailchimp_api_key' );
	register_setting( 'techno-settings-group', 'techno_mailchimp_list_id' );
	register_setting( 'techno-settings-group', 'techno_submit_button' );
	register_setting( 'techno-settings-group', 'techno_name_placeholder' );
	register_setting( 'techno-settings-group', 'techno_email_placeholder' );
	
}

function techno_settings_page() {
?>
<div class="wrap">
<h1>Mailchimp Integration</h1>
<form method="post" action="options.php">
    <?php settings_fields( 'techno-settings-group' ); ?>
    <?php do_settings_sections( 'techno-settings-group' ); ?>
    <table class="form-table">
        <tr valign="top">
        <th scope="row">Form Title</th>
        <td><input type="text" name="techno_form_title" value="<?php echo esc_attr( get_option('techno_form_title') ); ?>" /></td>
        </tr>        
        <tr valign="top">
        <th scope="row">Mailchimp API Key</th>
        <td><input type="text" name="techno_mailchimp_api_key" value="<?php echo esc_attr( get_option('techno_mailchimp_api_key') ); ?>" /></td>
        </tr>
        <tr valign="top">
        <th scope="row">Mailchimp List Id</th>
        <td><input type="text" name="techno_mailchimp_list_id" value="<?php echo esc_attr( get_option('techno_mailchimp_list_id') ); ?>" /></td>
        </tr>  
        <tr valign="top">
        <th scope="row">Submit Button Text</th>
        <td><input type="text" name="techno_submit_button" value="<?php echo esc_attr( get_option('techno_submit_button') ); ?>" /></td>
        </tr>
		 <tr valign="top">
        <th scope="row">Submit Button Class</th>
        <td><input type="text" name="techno_submit_button_style" value="<?php echo esc_attr( get_option('techno_submit_button_style') ); ?>" /></td>
        </tr>		
		<tr valign="top">
        <th scope="row">Name Field Placeholder</th>
        <td><input type="text" name="techno_name_placeholder" value="<?php echo esc_attr( get_option('techno_name_placeholder') ); ?>" /></td>
        </tr>
		<tr valign="top">
        <th scope="row">Email Field Placeholder</th>
        <td><input type="text" name="techno_email_placeholder" value="<?php echo esc_attr( get_option('techno_email_placeholder') ); ?>" /></td>
        </tr>
		
		<tr valign="top">
        <th scope="row">Mailchimp Integration Shortcode</th>
        <td>[techno_mailchimp_init]</td>
        </tr>
		
    </table>
    <?php submit_button(); ?>
</form>
</div>
<?php } 

function techno_mailchimp($atts) {
?>
<div class="techno_newsletter_main">
		<div class="techno_newsletter_title">
		<?php echo (get_option('techno_form_title') ?  esc_attr(get_option('techno_form_title'))  : 'Form Title'); ?>
		</div>
		
			<div id = "techno_err" class="techno_err "></div>
		
			<form method="post"  name ="techno_newsletter" class="techno_newsletter"  id="techno_newsletter">
				<p>
			<label><?php _e( 'Name:' ); ?></label> 
				<input class="" id="techno_name" name="techno_name" type="text" value="" placeholder="<?php echo (get_option('techno_name_placeholder') ?  esc_attr(get_option('techno_name_placeholder'))  : 'Enter Name'); ?>"/>
			</p>
				
				<p>
			<label><?php _e( 'Email:' ); ?></label> 
				<input class="" id="techno_email" name="techno_email" type="email" value="" placeholder="<?php 	echo (get_option('techno_email_placeholder') ?  esc_attr(get_option('techno_email_placeholder'))  : 'Enter Email'); ?>"/>
			</p>
		
				<p>
				<input class="techno_save" id="submit" name="techno_submit" type="button" value="<?php echo (get_option('techno_submit_button') ?  esc_attr(get_option('techno_submit_button'))  : 'Save'); ?>" />
			</p>
				
				</form>
				</div>		
<script>		
	jQuery('.techno_save').click(function(){
			 var techno_name = jQuery('#techno_name').val();
			 var techno_email =  jQuery('#techno_email').val();
			var se_ajax_url = '<?php echo admin_url('admin-ajax.php'); ?>';
			  var final_link2 = se_ajax_url + "?action=techno_mailchimp";
			  jQuery.ajax({
					url: final_link2, // our PHP handler file
					type: 'POST',
					data: {techno_name:techno_name,techno_email:techno_email},
					success: function (response,textStatus, jqXHR) { 
						 if(response ){
						  var data = JSON.parse(response); 
						  jQuery('.techno_err').html(data.message); 
						  jQuery('.techno_err').addClass(data.class); 
							  jQuery('#techno_name').val('');
							  jQuery('#techno_email').val('');				
							  setTimeout(function(){
										 if(jQuery('#techno_err').hasClass('error') ){
											jQuery('#techno_err').removeClass('error');
											jQuery('#techno_err').empty('');
										}
										if(jQuery('#techno_err').hasClass('sucess')){
										jQuery('#techno_err').removeClass('sucess');
										jQuery('#techno_err').empty('');
										}
								},5000);
							 
					 }else{
						 return false;
					  }
					}
			  });
});

				</script>
			<?php
 }
add_shortcode( 'techno_mailchimp_init', 'techno_mailchimp' );


add_action('wp_ajax_techno_mailchimp','techno_mailchimp_process_form');
add_action('wp_ajax_nopriv_techno_mailchimp','techno_mailchimp_process_form');
function techno_mailchimp_process_form(){
if(isset($_POST['techno_name']) && !empty($_POST['techno_name']) && isset($_POST['techno_email']) && !empty($_POST['techno_email'])  ){
			require_once plugin_dir_path(__FILE__).'inc/MCAPI.class.php';
			$techno_mailchimp_api_key =  esc_attr( get_option('techno_mailchimp_api_key') ); 
			$techno_mailchimp_list_id =  esc_attr( get_option('techno_mailchimp_list_id') ); 
			
			/**MAILCHIMP SECTION STARTS**/
			$techno_mailchimp_api = new MCAPI($techno_mailchimp_api_key); 
			
			$techno_name  =  sanitize_text_field($_POST["techno_name"]);
			$techno_email  =  sanitize_text_field($_POST["techno_email"]);
			$merge_vars = array(
									'NAME'=>$techno_name,								
									); 
					$techno_mailchimp_res = $techno_mailchimp_api->listSubscribe($techno_mailchimp_list_id,$techno_email,$merge_vars,'techno_email',false);
					
					if($techno_mailchimp_res === true) { 
							// It worked! 
							//return 'Success! Check your email to confirm sign up.'; 
							 $techno_mailchimp_msg = 'Success! Check your email to confirm sign up.'; 
							   $techno_mailchimp_response['error'] = false;
							   $techno_mailchimp_response['class'] = 'sucess';
					}else{ 
							// An error ocurred, return error message 
							 $techno_mailchimp_msg =   'Error: ' . $techno_mailchimp_api->errorMessage; 
							   $techno_mailchimp_response['error'] = true;
							   $techno_mailchimp_response['class'] = 'error';
					} 
					/**MAILCHIMP SECTION ENDS**/

		}else{
		            $techno_mailchimp_msg = 'Please fill all fields';
				   $techno_mailchimp_response['class'] = 'error';
		
		}		
      
        $techno_mailchimp_response['message'] = $techno_mailchimp_msg;

	echo json_encode($techno_mailchimp_response);
    wp_die();
}