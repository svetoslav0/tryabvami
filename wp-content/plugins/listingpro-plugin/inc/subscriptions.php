<?php
require_once(ABSPATH . 'wp-admin/includes/screen.php');
//form paypal

if( !empty($_POST['subscr_idd']) && isset($_POST['subscr_idd']) ){
	$subscrip_id = $_POST['subscr_idd'];
	$uid = $_POST['subscriber_idd'];
	global $listingpro_options;
	require_once THEME_PATH . '/include/stripe/stripe-php/init.php';
	$strip_sk = $listingpro_options['stripe_secrit_key'];
	\Stripe\Stripe::setApiKey($strip_sk);
	try {
		$subscription = \Stripe\Subscription::retrieve($subscrip_id);
		$subscription->cancel();
	}
	catch (Exception $e) {

	}
	$userSubscriptions = get_user_meta($uid, 'listingpro_user_sbscr', true);
	if(!empty($userSubscriptions)){
		foreach($userSubscriptions as $key=>$subscription){
			$subscr_id = $subscription['subscr_id'];
			$subscr_listing_id = $subscription['listing_id'];

			/* $my_listing_post = array();
			$my_listing_post['ID'] = $subscr_listing_id;
			$my_listing_post['post_status'] = 'expired';
			wp_update_post( $my_listing_post ); */

			if($subscr_id == $subscrip_id){
				unset($userSubscriptions[$key]);
				$headers[] = 'Content-Type: text/html; charset=UTF-8';
				/* user email */
				$author_obj = get_user_by('id', $uid);
				$user_email = $author_obj->user_email;
				$usubject = $listingpro_options['listingpro_subject_cancel_subscription'];
				$ucontent = $listingpro_options['listingpro_content_cancel_subscription'];
				
				$website_url = site_url();
				$website_name = get_option('blogname');
				$listing_title = get_the_title($subscr_listing_id);
				$listing_url = get_the_permalink($subscr_listing_id);
				$user_name = $author_obj->user_login;
				$usubject = lp_sprintf2("$usubject", array(
									'website_url' => "$website_url",
									'listing_title' => "$listing_title",
									'listing_url' => "$listing_url",
									'user_name' => "$user_name",
									'website_name' => "$website_name"
								));
								
				$ucontent = lp_sprintf2("$ucontent", array(
									'website_url' => "$website_url",
									'listing_title' => "$listing_title",
									'listing_url' => "$listing_url",
									'user_name' => "$user_name",
									'website_name' => "$website_name"
								));
								
				
				lp_mail_headers_append();
				wp_mail( $user_email, $usubject, $ucontent, $headers );
				/* admin email */
				$adminemail = get_option('admin_email');
				$asubject = $listingpro_options['listingpro_subject_cancel_subscription_admin'];
				$acontent = $listingpro_options['listingpro_content_cancel_subscription_admin'];
				wp_mail( $adminemail, $asubject, $acontent, $headers );
				lp_mail_headers_remove();
			}
		}
	}
	/* removing user meta */
	if(!empty($userSubscriptions)){
		update_user_meta($uid, 'listingpro_user_sbscr', $userSubscriptions);
	}
	else{
		delete_user_meta($uid, 'listingpro_user_sbscr');
	}
	/* end removing user meta */

}





/* for stripe */
if( !empty($_POST['subscr_id']) && isset($_POST['subscr_id']) ){
	$subscrip_id = $_POST['subscr_id'];
	$uid = $_POST['subscriber_id'];
	global $listingpro_options;
	require_once THEME_PATH . '/include/stripe/stripe-php/init.php';
	$strip_sk = $listingpro_options['stripe_secrit_key'];
	\Stripe\Stripe::setApiKey($strip_sk);
	$subscription = \Stripe\Subscription::retrieve($subscrip_id);
	$subscription->cancel();
	$userSubscriptions = get_user_meta($uid, 'listingpro_user_sbscr', true);
	if(!empty($userSubscriptions)){
		foreach($userSubscriptions as $key=>$subscription){
			$subscr_id = $subscription['subscr_id'];
			$subscr_listing_id = $subscription['listing_id'];

			if($subscr_id == $subscrip_id){
				
				$table = 'listing_orders';
				$summary = 'expired';
				$data = array('summary'=>$summary);
				$where = array('post_id'=>$subscr_listing_id);
				lp_update_data_in_db($table, $data, $where);
				
				unset($userSubscriptions[$key]);
				$headers[] = 'Content-Type: text/html; charset=UTF-8';
				/* user email */
				$author_obj = get_user_by('id', $uid);
				$user_email = $author_obj->user_email;
				$usubject = $listingpro_options['listingpro_subject_cancel_subscription'];
				$ucontent = $listingpro_options['listingpro_content_cancel_subscription'];
				
				$website_url = site_url();
				$website_name = get_option('blogname');
				$listing_title = get_the_title($subscr_listing_id);
				$listing_url = get_the_permalink($subscr_listing_id);
				$user_name = $author_obj->user_login;
				$usubject = lp_sprintf2("$usubject", array(
									'website_url' => "$website_url",
									'listing_title' => "$listing_title",
									'listing_url' => "$listing_url",
									'user_name' => "$user_name",
									'website_name' => "$website_name"
								));
								
				$ucontent = lp_sprintf2("$ucontent", array(
									'website_url' => "$website_url",
									'listing_title' => "$listing_title",
									'listing_url' => "$listing_url",
									'user_name' => "$user_name",
									'website_name' => "$website_name"
								));
								
				
				lp_mail_headers_append();
				wp_mail( $user_email, $usubject, $ucontent, $headers );
				/* admin email */
				$adminemail = get_option('admin_email');
				$asubject = $listingpro_options['listingpro_subject_cancel_subscription_admin'];
				$acontent = $listingpro_options['listingpro_content_cancel_subscription_admin'];
				wp_mail( $adminemail, $asubject, $acontent, $headers );
				lp_mail_headers_remove();
			}
		}
	}
	/* removing user meta */
	if(!empty($userSubscriptions)){
		update_user_meta($uid, 'listingpro_user_sbscr', $userSubscriptions);
	}
	else{
		delete_user_meta($uid, 'listingpro_user_sbscr');
	}
	/* end removing user meta */

}


/*---------------------------------------------------
				adding invoice page
----------------------------------------------------*/

function listingpro_register_subscription_page() {
	add_menu_page(
		__( 'Subscriptions', 'listingpro-plugin' ),
		'Subscription',
		'manage_options',
		'lp-listings-subscription',
		'listingpro_subscription_page',
		plugins_url( 'listingpro-plugin/images/icon-subscr.png' ),
		30
	);
	wp_enqueue_style("panel_style", WP_PLUGIN_URL."/listingpro-plugin/assets/css/custom-admin-pages.css", false, "1.0", "all");

}
add_action( 'admin_menu', 'listingpro_register_subscription_page' );

if(!function_exists('listingpro_subscription_page')){
	function listingpro_subscription_page(){
		//adding css

		wp_enqueue_style('bootstrapcss', get_template_directory_uri() . '/assets/lib/bootstrap/css/bootstrap.css');
		wp_enqueue_script('bootstrapadmin', get_template_directory_uri() . '/assets/lib/bootstrap/js/bootstrap.min.js', 'jquery', '', true);
		global $listingpro_options;
		$userSubscriptions;
		$userSubscriptionsp = array();
		$subscription_exist = false;
		require_once THEME_PATH . '/include/stripe/stripe-php/init.php';
		$strip_sk = $listingpro_options['stripe_secrit_key'];
		\Stripe\Stripe::setApiKey($strip_sk);
		$currency = listingpro_currency_sign();
		?>
        <div class="wrap listingpro-coupons">
        <h1 class="wp-heading-inline"><?php esc_html_e('Subscriptions', 'listingpro-plugin');  ?></h1>


        <div id="posts-filter" method="get">

            <div class="tablenav top">

                <div class="alignright">
                    <p class="search-box">
                        <input type="search" id="lp_invoiceInput" onkeyup="lpSearchDataInInvoice()" class="button" placeholder="<?php echo esc_html__('Search Invoices', 'listingpro-plugin'); ?>">
                    </p>
                </div>

                <br class="clear">
            </div>


            <div class="listingpro_coupon_table">
                <table class="table wp-list-table widefat fixed striped posts">
                    <thead>
                    <tr>
                        <!-- <th><?php echo esc_html__('No.', 'listingpro-plugin'); ?></th> -->

                        <th id="cb" class="manage-column column-cb check-column"><label class="screen-reader-text" for="cb-select-all-1">Select All</label><input id="cb-select-all-1" type="checkbox"></th>


                        <th scope="col" id="title" class="manage-column column-title column-primary sortable desc">
                            <a><span><?php echo esc_html__('Status', 'listingpro-plugin'); ?></span><span class="sorting-indicator"></span></a>
                        </th>

                        <th class="manage-column column-tags"><?php echo esc_html__('User', 'listingpro-plugin'); ?></th>
                        <th class="manage-column column-tags"><?php echo esc_html__('Listing', 'listingpro-plugin'); ?></th>
                        <th class="manage-column column-tags"><?php echo esc_html__('Subscription', 'listingpro-plugin'); ?></th>
                        <th class="manage-column column-tags"><?php echo esc_html__('Total', 'listingpro-plugin'); ?></th>
                        <th class="manage-column column-tags"><?php echo esc_html__('Next Payment', 'listingpro-plugin'); ?></th>

                        <th class="manage-column column-tags"><?php echo esc_html__('Action', 'listingpro-plugin'); ?></th>
                    </tr>
                    </thead>
                    <tbody>
					<?php
					$users = get_users( array( 'fields' => array( 'ID' ) ) );
					?>
					<?php
					global $wpdb;
					foreach($users as $user_id){
						$user_id = $user_id->ID;
						$user_obj = get_user_by('id', $user_id);
						$user_login = $user_obj->user_login;
						$userSubscriptions = '';
						$userSubscriptions = get_user_meta($user_id, 'listingpro_user_sbscr', true);

						if(!empty($userSubscriptions) && count($userSubscriptions)>0 ) {
							$subscription_exist = true;
							$n=1;
							foreach($userSubscriptions as $subscription){
								try {

									$plan_id = $subscription['plan_id'];
									$subscr_id = $subscription['subscr_id'];
									$listing_id = $subscription['listing_id'];
									$listing_title = get_the_title($listing_id);
									$subscrObj = \Stripe\Subscription::retrieve($subscr_id);
									if(empty($subscrObj)){
										$userSubscriptionsp[] = $subscription;
									}
									$subscrID = $subscrObj->id;
									$taxStatus = '';
									$planStripe = $subscrObj->plan;
									$stripePrice = $planStripe->amount;
									$plan_title = get_the_title($plan_id);
									$plan_price = get_post_meta($plan_id, 'plan_price', true);
									$stripePrice = (float)$stripePrice/100;
									$stripePrice = round($stripePrice, 2);

									$dbprefix = $wpdb->prefix;
									$myPrice = $wpdb->get_row( "SELECT * FROM ".$dbprefix."listing_orders WHERE plan_id = $plan_id" );
									if(!empty($myPrice)){
										$plan_price = $myPrice->price;
									}
									if($stripePrice==$plan_price){
										$taxStatus = esc_html__('exc. tax', 'listingpro-plugin');
									}
									else{
										$plan_price = $stripePrice;
										$taxStatus = esc_html__('inc. tax', 'listingpro-plugin');
									}
									?>


                                    <tr class="<?php echo $listing_id; ?>">
                                        <td><input type="checkbox"></td>
                                        <td><input class="alert alert-success <?php echo $stripePrice; ?>" type="button" name="lp_delte_coupon_submit" value="<?php echo esc_html__('Active', 'listingpro-plugin'); ?>" ></td>
                                        <td><?php echo $user_login; ?></td>
                                        <td><?php echo $listing_title; ?></td>
                                        <td><?php echo $subscrID; ?></td>
                                        <td><?php echo $plan_price.$currency." ($taxStatus)"; ?></td>
                                        <td><?php echo date(get_option('date_format'), $subscrObj->current_period_end); ?></td>
                                        <td>
                                            <form class="wp-core-ui" id="subscription_cancel" name="subscription_cancel" method="post">
                                                <input type="submit" name="subscription_cancel_submit" class="button action" value="<?php echo esc_html__('Unsubscribe', 'listingpro-plugin'); ?>" onclick="return window.confirm('<?php echo esc_html__('Are you sure you want to proceed action?', 'listingpro-plugin'); ?>');">
                                                <input type="hidden" name="subscr_id" value="<?php echo $subscrID; ?>">
                                                <input type="hidden" name="subscriber_id" value="<?php echo $user_id; ?>">
                                            </form>
                                        </td>

                                    </tr>

									<?php
									$n++;
								}catch (Exception $e) {
									$userSubscriptionsp[] = $subscription;
								}
							}
							
							/* for paypal */
						
							if(!empty($userSubscriptionsp)){
								foreach($userSubscriptionsp as $subscription){
									
									$plan_id = $subscription['plan_id'];
									$subscr_id = $subscription['subscr_id'];
									$subscrID = $subscr_id;
									$listing_id = $subscription['listing_id'];
									$listing_title = get_the_title($listing_id);
									
									$plan_price = get_post_meta($plan_id, 'plan_price', true);
									$plan_duration = get_post_meta($plan_id, 'plan_time', true);
									$plan_duration = trim($plan_duration);
									$taxStatus = '';
									$plan_price = get_post_meta($plan_id, 'plan_price', true);
									$pfx_date = get_the_date( '', $listing_id );
									$pfx_date = date(get_option('date_format'),strtotime($pfx_date . "+$plan_duration days"));
									
									if($plan_price==$plan_price){
										$taxStatus = esc_html__('exc. tax', 'listingpro');
									}
									else{
										$plan_price = $plan_price;
										$taxStatus = esc_html__('inc. tax', 'listingpro');
									}
									
									
									?>
									<tr class="<?php echo $listing_id; ?>">
                                        <td><input type="checkbox"></td>
                                        <td><input class="alert alert-success <?php echo $plan_price; ?>" type="button" name="lp_delte_coupon_submit" value="<?php echo esc_html__('Active', 'listingpro-plugin'); ?>" ></td>
                                        <td><?php echo $user_login; ?></td>
                                        <td><?php echo $listing_title; ?></td>
                                        <td><?php echo $subscrID; ?></td>
                                        <td><?php echo $plan_price.$currency." ($taxStatus)"; ?></td>
                                        <td><?php echo $pfx_date; ?></td>
                                        <td>
                                            <form class="wp-core-ui" id="subscription_cancel" name="subscription_cancel" method="post">
                                                <input type="submit" name="subscription_cancel_submit" class="button action" value="<?php echo esc_html__('Unsubscribe', 'listingpro-plugin'); ?>" onclick="return window.confirm('<?php echo esc_html__('Are you sure you want to proceed action?', 'listingpro-plugin'); ?>');">
                                                <input type="hidden" name="subscr_idd" value="<?php echo $subscrID; ?>">
                                                <input type="hidden" name="subscriber_idd" value="<?php echo $user_id; ?>">
                                            </form>
                                        </td>

                                    </tr>
									
									<?php
									$n++;
								}
								
							}
							
							
						}
					}
					?>
                    </tbody>
                    <tfoot>
                    <tr>
                        <!-- <th><?php echo esc_html__('No.', 'listingpro-plugin'); ?></th> -->

                        <th id="cb" class="manage-column column-cb check-column"><label class="screen-reader-text" for="cb-select-all-1">Select All</label><input id="cb-select-all-1" type="checkbox"></th>


                        <th scope="col" id="title" class="manage-column column-title column-primary sortable desc">
                            <a><span><?php echo esc_html__('Status', 'listingpro-plugin'); ?></span><span class="sorting-indicator"></span></a>
                        </th>

                        <th class="manage-column column-tags"><?php echo esc_html__('User', 'listingpro-plugin'); ?></th>
                        <th class="manage-column column-tags"><?php echo esc_html__('Listing', 'listingpro-plugin'); ?></th>
                        <th class="manage-column column-tags"><?php echo esc_html__('Subscription', 'listingpro-plugin'); ?></th>
                        <th class="manage-column column-tags"><?php echo esc_html__('Total', 'listingpro-plugin'); ?></th>
                        <th class="manage-column column-tags"><?php echo esc_html__('Next Payment', 'listingpro-plugin'); ?></th>

                        <th class="manage-column column-tags"><?php echo esc_html__('Action', 'listingpro-plugin'); ?></th>
                    </tr>
                    </tfoot>
                </table>

            </div>

            </form>


			<?php
			if($subscription_exist==false){
				echo '<p>'.esc_html('Sorry! There is no subscription yet', 'listingpro-plugin').'<p>';
			}
			?>
        </div>

        <!--search-->
        <script>
            function lpSearchDataInInvoice() {
                var input, filter, table, tr, td, i;
                input = document.getElementById("lp_invoiceInput");
                filter = input.value.toUpperCase();
                table = document.getElementsByClassName("wp-list-table");
                for (j = 0; j < table.length; j++) {
                    tr = table[j].getElementsByTagName("tr");
                    for (i = 0; i < tr.length; i++) {
                        td = tr[i].getElementsByTagName("td")[4];
                        if (td) {
                            if (td.innerHTML.toUpperCase().indexOf(filter) > -1) {
                                tr[i].style.display = "";
                            } else {
                                tr[i].style.display = "none";
                            }
                        }
                    }
                }
            }



        </script>
		<?php
	}
}
?>