<?php

require_once(ABSPATH . 'wp-admin/includes/screen.php');
/* =========================form action for wire process============================= */

if( !empty($_POST['payment_submit']) && isset($_POST['payment_submit']) ){
    if (!isset($_SESSION)) { session_start(); }
    global $wpdb,$listingpro_options;
    $ads_durations = $listingpro_options['listings_ads_durations'];
    $currentdate = date("d-m-Y");
    $exprityDate = date('Y-m-d', strtotime($currentdate. ' + '.$ads_durations.' days'));
    $exprityDate = date('d-m-Y', strtotime( $exprityDate ));

    $table = 'listing_campaigns';
    $order_id = '';
    $order_id = $_POST['order_id'];
    $mode = $_POST['mode'];
    $duration = $_POST['duration'];
    $budget = $_POST['budget'];

    $postid= $_POST['post_id'];
    $price_packages = listing_get_metabox_by_ID('listings_ads_purchase_packages', $postid);


    $my_post = array( 'post_title'    => $postid, 'post_status'   => 'publish', 'post_type' => 'lp-ads' );
    $adID = wp_insert_post( $my_post );

    $data = array('post_id' => $postid,'status' => 'success');
    $where = array('transaction_id' => $order_id);
    lp_update_data_in_db($table, $data, $where);
    listing_set_metabox('ads_listing', $postid, $adID);
    listing_set_metabox('ad_status', 'Active', $adID);
    listing_set_metabox('ad_date', $currentdate, $adID);
    listing_set_metabox('ad_expiryDate', $exprityDate, $adID);
    listing_set_metabox('campaign_id',$postid, $adID);
    if($mode=="byduration"){}else{
        //cpc
        listing_set_metabox('remaining_balance', $budget, $adID);
    }
    listing_set_metabox('ads_mode', $mode, $adID);
    listing_set_metabox('duration', $duration, $adID);
    listing_set_metabox('budget', $budget, $adID);

    $packagesDetails = '';
    $priceKeyArray;
    if( !empty($price_packages) ){
        foreach( $price_packages as $type ){
            if($type=="lp_random_ads"){
                $packagesDetails .= esc_html__('Random Ads', 'listingpro-plugin');
            }
            if($type=="lp_detail_page_ads"){
                $packagesDetails .= esc_html__('Detail Page Ads', 'listingpro-plugin');
            }
            if($type=="lp_top_in_search_page_ads"){
                $packagesDetails .= esc_html__('Top in Search Page Ads', 'listingpro-plugin');
            }
            $priceKeyArray[] = $type;
            update_post_meta( $postid, $type, 'active' );
        }
    }
    update_post_meta( $postid, 'campaign_status', 'active' );
    update_post_meta( $postid, 'campaign_id', $adID );

    if( !empty($priceKeyArray) ){
        listing_set_metabox('ad_type', $priceKeyArray, $adID);
    }

    $current_user = wp_get_current_user();
    $user_email = $current_user->user_email;
    $admin_email = get_option('admin_email');
    $listing_title = get_the_title($postid);
    $listing_url = get_the_permalink($postid);
    $campaign_packages = $packagesDetails;

    $author_id = get_post_field( 'post_author', $postid );
    $user_email = get_the_author_meta( 'user_email', $author_id );
    $author_name = get_the_author_meta( 'user_login', $author_id );
    $user_name = $author_name;
    $website_url = site_url();
    $website_name = get_option('blogname');
    /* for admin */
    $subject = $listingpro_options['listingpro_subject_campaign_activate'];
    $mail_content = $listingpro_options['listingpro_content_campaign_activate'];


    $formated_mail_content = lp_sprintf2("$mail_content", array(
        'campaign_packages' => "$campaign_packages",
        'listing_title' => "$listing_title",
        'listing_url' => "$listing_url",
        'author_name' => "$author_name",
        'website_url' => "$website_url",
        'website_name' => "$website_name",
        'user_name' => "$user_name",
    ));


    lp_mail_headers_append();
    $headers[] = 'Content-Type: text/html; charset=UTF-8';
    wp_mail( $admin_email, $subject, $formated_mail_content, $headers);

    /* for author */

    $subject = $listingpro_options['listingpro_subject_campaign_activate_author'];
    $mail_content = $listingpro_options['listingpro_content_campaign_activate_author'];

    $formated_mail_content = lp_sprintf2("$mail_content", array(
        'campaign_packages' => "$campaign_packages",
        'listing_title' => "$listing_title",
        'listing_url' => "$listing_url",
        'website_url' => "$website_url",
        'website_name' => "$website_name",
        'user_name' => "$user_name",
    ));



    $headers[] = 'Content-Type: text/html; charset=UTF-8';
    wp_mail( $user_email, $subject, $formated_mail_content, $headers);
    lp_mail_headers_remove();


}

/* --------------------delete invoice data------------------- */
if( isset($_POST['delete_invoice_ads']) && !empty($_POST['delete_invoice_ads']) ){

    $main_id = $_POST['main_id'];
    $listId = $_POST['listId'];
	$delteAll = false;
	if(isset($_POST['deletecomplete'])){
		if(!empty($_POST['deletecomplete'])){
			$delteAll = true;
		}
	}
    $listId = listing_get_metabox_by_ID('ads_listing', $listId);
    if(empty($listId)){
        $listId = $_POST['listId'];
    }
    if( !empty($main_id) ){
        $table = 'listing_campaigns';
        $where = array('main_id'=>$main_id);
        lp_delete_data_in_db($table, $where);
		
		if(!empty($delteAll)){
			delete_post_meta( $listId, 'campaign_status');
			$price_packages = array('lp_random_ads', 'lp_detail_page_ads', 'lp_top_in_search_page_ads');
			if( !empty($price_packages) ){
				foreach( $price_packages as $val ){
					delete_post_meta( $listId, $val);
				}
			}
		}

    }

}

/* =========================inovices for ads========================================= */
add_action('admin_menu', 'lp_register_ads_invoice_page');

function lp_register_ads_invoice_page() {
    add_submenu_page(
        'lp-listings-invoices',
        'Ads Invoices',
        'Ads Invoices',
        'manage_options',
        'ads-invoices-page',
        'ads_invoices_submenu_page_callback' );
}

function ads_invoices_submenu_page_callback() {
    wp_enqueue_style('bootstrapcss', get_template_directory_uri() . '/assets/lib/bootstrap/css/bootstrap.min.css');
    wp_enqueue_script('bootstrapadmin', get_template_directory_uri() . '/assets/lib/bootstrap/js/bootstrap.min.js', 'jquery', '', true);
    ?>

    <?php
    global $wpdb;
    $dbprefix = $wpdb->prefix;
    $table = 'listing_campaigns';
    $table_name =$dbprefix.$table;
    ?>
	
	
	<div class="wrap listingpro-coupons linvoiceswrap">
            <h1 class="wp-heading-inline"><?php esc_html_e('Ads Invoices', 'listingpro-plugin');  ?></h1>


            <div class="clearfix"></div>
           
                <div class="tablenav top">

                    <div class="alignleft actions bulkactions lp_backend_inv_filter_ads">
                        <label for="bulk-action-selector-top" class="screen-reader-text"><?php echo esc_html__('All Types', 'listingpro-plugin'); ?></label>
                        <select class="lp_invoiceInput_ads">
                            <option value="">
                                <?php echo esc_html__('All Methods', 'listingpro-plugin'); ?>
                            </option>

                            <option value="paypal">
                                <?php echo esc_html__('Paypal', 'listingpro-plugin'); ?>
                            </option>

                            <option value="stripe">
                                <?php echo esc_html__('Stripe', 'listingpro-plugin'); ?>
                            </option>

                            <option value="wire">
                                <?php echo esc_html__('Wire', 'listingpro-plugin'); ?>
                            </option>

                        </select>

                    </div>

                    <div class="alignleft actions bulkactions lp_backend_inv_filter_ads">
                        <label for="bulk-action-selector-top" class="screen-reader-text"><?php echo esc_html__('All Status', 'listingpro-plugin'); ?></label>
                        <select  class="lp_invoiceStatusInput_ads" id="bulk-action-selector-top">
                            <option value="">
                                <?php echo esc_html__('All Status', 'listingpro-plugin'); ?>
                            </option>

                            <option value="success">
                                <?php echo esc_html__('Success', 'listingpro-plugin'); ?>
                            </option>

                            <option value="pending">
                                <?php echo esc_html__('Pending', 'listingpro-plugin'); ?>
                            </option>

                            <option value="failed">
                                <?php echo esc_html__('Failed', 'listingpro-plugin'); ?>
                            </option>
                        </select>

                    </div>
                    <div class="alignright">
                        <p class="search-box">
                            <input type="search" id="lp_adsinvoiceInput" onkeyup="lpSearchDataAdsInInvoice()" class="button" placeholder="<?php echo esc_html__('Search Invoices', 'listingpro-plugin'); ?>">
                        </p>
                    </div>

                    <br class="clear">
                </div>


                <div class="listingpro_coupon_table">
                    <div class="lp_admin_invoice_ajax_result"></div>
                    <!--all -->
                    <div id="tab-1" class="lp-backendtabs-content current">
                        <?php
                        include_once 'templates/invoice_temp_ads/all.php';
                        ?>

                    </div>

                    <!--success -->
                    <div id="tab-2" class="lp-backendtabs-content">
                        <?php
                        include_once 'templates/invoice_temp_ads/success.php';
                        ?>
                    </div>


                    <!--pending -->
                    <div id="tab-3" class="lp-backendtabs-content">
                        <?php
                        include_once 'templates/invoice_temp_ads/pending.php';
                        ?>
                    </div>

                    <!--failed -->
                    <div id="tab-4" class="lp-backendtabs-content">
                        <?php
                        include_once 'templates/invoice_temp_ads/failed.php';
                        ?>
                    </div>

                </div>



        </div>
		
		<!--search-->
        <script>
            function lpSearchDataAdsInInvoice() {
                var input, filter, table, tr, td, i;
                input = document.getElementById("lp_adsinvoiceInput");
                filter = input.value.toUpperCase();
                table = document.getElementsByClassName("wp-list-table");
                for (j = 0; j < table.length; j++) {
                    tr = table[j].getElementsByTagName("tr");
                    for (i = 0; i < tr.length; i++) {
                        td = tr[i].getElementsByTagName("td")[0];
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
?>