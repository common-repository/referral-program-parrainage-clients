<?php

class Wlc_Referral
{
    private static $initiated = false;
    const OPTIONS = 'wlc_referral_widget_options';
    public static function init()
    {
        if (!self::$initiated) {
            self::init_hooks();
            add_action('init', array('Wlc_Referral', 'referral_language_load'));
        }
    }

    // Languages localization.
    public static function referral_language_load()
    {
        load_plugin_textdomain(WLC_TRANSLATOR, FALSE, basename(dirname(__FILE__)) . '/languages');
    }

    /**
     * Initializes WordPress hooks
     */
    public static function init_hooks()
    {
        self::$initiated = true;


        // add_action( 'wp_footer', array('Wlc_Referral', 'insert_widget') );

        add_action('woocommerce_thankyou', array('Wlc_Referral', 'checkTransaction'));

        add_filter('woocommerce_get_shop_coupon_data', array('Wlc_Referral', 'check_and_create_coupon'), 20, 2);

        add_action('woocommerce_order_status_processing', array('Wlc_Referral', 'checkTransactionOrderStatusProcessing'));

        add_action('woocommerce_order_status_completed', array('Wlc_Referral', 'checkTransactionOrderStatusCompleted'));

        add_action('woocommerce_order_status_cancelled', array('Wlc_Referral', 'checkTransactionOrderStatusKO'));
        add_action('woocommerce_order_status_refunded', array('Wlc_Referral', 'checkTransactionOrderStatusKO'));
        add_shortcode('wlc_reviews', array('Wlc_Referral', 'reviews_shortcode'));
        add_shortcode('wlc_badge', array('Wlc_Referral', 'badge_shortcode'));
        add_shortcode('wlc_loyalty', array('Wlc_Referral', 'loyalty_shortcode'));
        add_shortcode('wlc_box', array('Wlc_Referral', 'widget_shortcode'));
        add_shortcode('wlc_rich_snippet', array('Wlc_Referral', 'rich_snippet_shortcode'));


        add_shortcode('wlc_bigboard', array('Wlc_Referral', 'widget_bigboard'));

        add_shortcode('wlc_dashboard', array('Wlc_Referral', 'dashboard_shortcode'));

        add_shortcode('wlc_giftshop', array('Wlc_Referral', 'giftshop_shortcode'));

        add_action('admin_head', array('Wlc_Referral', 'custom_mce_button'));

        add_action('admin_print_footer_scripts', array('Wlc_Referral', 'add_quicktags'));

        add_action('wp_loaded', array('Wlc_Referral', 'register_routes'));



        add_filter('woocommerce_account_menu_items', array('Wlc_Referral', 'wlc_one_more_link'));

        add_filter('woocommerce_get_endpoint_url', array('Wlc_Referral', 'wlc_referral_hook_endpoint'), 10, 4);
    }

    /**
     * This function is where we add a new menu in the woocommerce navigation
     */

    public static function wlc_one_more_link($menu_links)
    {
        $options = self::get_widget_options();

        if ($options['showPageInWooCommerceAccountMenu']) {

            // we will hook "wlc_referral" later
            $new = array('wlc_referral' => __('Referral', 'referral-program-parrainage-clients'));

            // or in case you need 2 links
            // $new = array( 'link1' => 'Link 1', 'link2' => 'Link 2' );
            // array_slice() is good when you want to add an element between the other ones
            $menu_links = array_slice($menu_links, 0, -1, true)
                + $new
                + array_slice($menu_links, -1, NULL, true);
        }
        return $menu_links;
    }

    public static function wlc_referral_hook_endpoint($url, $endpoint, $value, $permalink)
    {

        $options = self::get_widget_options();
        if ($endpoint === 'wlc_referral' && $options['showPageInWooCommerceAccountMenu']) {

            // ok, here is the place for your custom URL, it could be external
            // $url = get_site_url() . '/my-account/referral/';
            $url = $options['showPageInWooCommerceAccountMenu'];
        }
        return $url;
    }

    /**
     * This function is where we register our routes for our example endpoint.
     */
    public static function register_routes()
    {
        $url = strtok($_SERVER["REQUEST_URI"], '?');
        switch ($url){
            case '/wlc/config/check':
                self::checkConfig();
            break;
            case '/wlc/offer/add':
                self::offerAdd();
            break;
            case '/wlc/purchase/details':
            case '/wlc/order/details':
                self::orderDetails();
            break;
        }
    }

    public static function offerAdd()
    {

        header('Content-Type: application/json');
        $res = array();

        $options = self::get_widget_options();
        $couponData = new stdClass();

        if (isset($_POST['customerKey']) && isset($_POST['apiGlue'])
                && $_POST['customerKey'] == $options['key'] && $_POST['apiGlue'] == $options['glue']) {
            if (isset($_POST['code'])){
                $code = $_POST['code'];

                $couponData->offerType = $_POST['type'];
                if ($couponData->offerType == 'F' || $couponData->offerType == 'contact'){
                    if ($couponData->offerType == 'contact'){
                        // on force une offre filleul
                        $couponData->offerType = 'F';
                        $couponData->quantity = 1000;
                    }
                    $couponData->fOffer = new stdClass();
                    $couponData->fOffer->offerValue = $_POST['value'];
                    $couponData->fOffer->offerValueType =  $_POST['valueType'];
                    $couponData->fOffer->minimumAmountToBuy = $_POST['minimumAmount'];
                }

                if ($couponData->offerType == 'P'){
                    $couponData->pOffer = new stdClass();
                    $couponData->pOffer->offerValue = $_POST['value'];
                    $couponData->pOffer->offerValueType =  $_POST['valueType'];
                    $couponData->pOffer->minimumAmountToBuy = $_POST['minimumAmount'];
                }  

                $couponData->name = $_POST['name'];
                $couponData->delay = $_POST['delay'];

/*

          $postData['customerKey']  = Db::getInstance()->getDataForUserId("api_key", $offer->userId);
          $postData['apiGlue']      = Db::getInstance()->getDataForUserId("api_glue", $offer->userId);
          $postData['code']         = $params['code'];
          $postData['valueType']    = $offer->offerValueType;
          $postData['value']        = $offer->offerValue;
          $postData['type']         = $params['type'];
          $postData['minimumAmount'] = (int)$offer->minimumAmountToBuy;
          $postData['name']         = $priceRuleTitle;
          $postData['delay']         = $params['delay'];
*/



            $res['code'] = $code;
            $res['couponData'] = $couponData;
            $res['post'] = $_POST;

                $data = self::create_coupon($couponData, $code);

                $res['data'] = $data;
                $res['message'] = 'success';

            } else {
                $res['message'] = 'no code';
            }
        } else {
            $res['message'] = 'unauthorized';
        }
        echo json_encode($res);
        die();
    }

    public static function checkConfig()
    {
        header('Content-Type: application/json');

        $status = "ko";

        $options =  WLC_REFERRAL::get_widget_options();
        $callData = array(
            'customerKey' => self::getDefaultValue($options, 'key'),
            'apiGlue' => self::getDefaultValue($options, 'glue'),
        );

        $jsonResponse = self::wlcCall('checkInstall/', $callData);

        $isValidConfig = false;
        if ($jsonResponse) {
            $response = json_decode($jsonResponse);
            $isValidConfig = $response->res;
        }

        if ($isValidConfig) {
            $status = "ok";
        }

        global $woocommerce;
        echo json_encode(array(
            'code' => 200,
            "status" => $status,
            'framework_type' => 'WooCommerce',
            'framework_version' => $woocommerce->version,
            'php_version' => phpversion(),
            'module_version' => WLC_VERSION
        ));

        die();
    }


    public static function orderDetails(){
        header('Content-Type: application/json');

        $orderDetails = new stdClass();
        $orderDetails->success = false;
        $orderDetails->message = false;
        $options = self::get_widget_options();

        if (isset($_GET['apikey']) && isset($_GET['apiglue'])
                && $_GET['apikey'] == $options['key'] && $_GET['apiglue'] == $options['glue']) {

            if (isset($_GET['orderid'])){
                $orderId = (int) $_GET['orderid'];

                $order = wc_get_order($orderId);
                if ($order){

                    $orderDetails->success = true;
                    $orderDetails->order = $orderId;
                    $orderDetails->status = $order->get_status();
                    $orderDetails->products = [];
                    foreach ($order->get_items() as $orderedItem){
                        $product = wc_get_product($orderedItem->get_product_id());

                        $orderDetailsItems = new stdClass();
                        $orderDetailsItems->name = $orderedItem->get_name();
                        $orderDetailsItems->sku = $product->get_sku();
                        $orderDetailsItems->qty = $orderedItem->get_quantity();
                        $orderDetailsItems->pricePerUnit = floatval($product->get_price_excluding_tax());
                        $orderDetailsItems->pricePerUnitWithTax = floatval($product->get_price());

                        $orderDetails->products[] = $orderDetailsItems;
                    }


                /*
                    {"order":"20073","status":"complete","products":[{"name":"Bouton d\u00e9port\u00e9 Ledlenser MT14","sku":"501025","qty":"1.0000","pricePerUnit":"20.7500","pricePerUnitWithTax":22.41},{"name":"Pince magn\u00e9tique Led Lenser pour lampes torches MT10 et MT14","sku":"501033","qty":"1.0000","pricePerUnit":"33.2500","pricePerUnitWithTax":35.91}]}
                    */

                } else {
                    $orderDetails->message = 'Bad identifier';
                }
            } else {
                $orderDetails->message = 'No identifier';
            }
        } else {
            $orderDetails->message = 'Bad credentials';
        }

        echo json_encode((array)$orderDetails);

        die();
    }

    public static function getDefaultValue($array, $key, $defaultValue = null)
    {
        if (isset($array[$key])) {
            return $array[$key];
        }
        return $defaultValue;
    }

    private static function wlcCall($endPoint, $callData)
    {
        $callData = http_build_query($callData);

        $context_options = array(
            'http' => array(
                'method' => 'POST',
                'header' => "Content-type: application/x-www-form-urlencoded\r\n"
                    . "Content-Length: " . strlen($callData) . "\r\n",
                'content' => $callData
            )
        );


        $context = stream_context_create($context_options);
        $endPoint = 'https://pixel.welovecustomers.fr/api/' . $endPoint;


        $fp = @fopen($endPoint, 'r', false, $context);
        $response = false;
        if ($fp) {
            while (($buffer = fgets($fp, 4096)) !== false) {
                $response .= $buffer;
            }
            if (!feof($fp)) {
            }
            fclose($fp);
        }
        return $response;
    }


    public static function add_quicktags()
    {
        if (wp_script_is('quicktags')) { ?>
            <script type="text/javascript">
                QTags.addButton('box_shortcode', 'Widget de parrainage', '[wlc_box]', '', '', '', 1);
                QTags.addButton('dashboard_shortcode', 'Widget de suivi de vos parrainages', '[wlc_dashboard]', '', '', '', 2);
                QTags.addButton('giftshop_shortcode', 'Lien vers la boutique cadeaux', '[wlc_giftshop]', '', '', '', 3);
                QTags.addButton('badge_shortcode', 'Widget de badge avis', '[wlc_badge]', '', '', '', 3);
                QTags.addButton('loyalty_shortcode', 'Widget de fid', '[wlc_loyalty]', '', '', '', 3);
                QTags.addButton('reviews_shortcode', 'Widget d\'avis', '[wlc_reviews]', '', '', '', 3);
                QTags.addButton('rich_snippet_shortcode', 'Widget de données enrichies', '[wlc_rich_snippet]', '', '', '', 3);
            </script><?php
                    }
                }

                public static function custom_mce_button()
                {
                    // Check if user have permission
                    if (!current_user_can('edit_posts') && !current_user_can('edit_pages')) {
                        return;
                    }
                    // Check if WYSIWYG is enabled
                    if ('true' == get_user_option('rich_editing')) {
                        add_filter('mce_external_plugins', array('Wlc_Referral', 'dashboard_button'));
                        add_filter('mce_buttons', array('Wlc_Referral', 'register_dashboard_button'));
                        add_filter('mce_external_plugins', array('Wlc_Referral', 'box_button'));
                        add_filter('mce_buttons', array('Wlc_Referral', 'register_box_button'));
                        /*
                        add_filter('mce_external_plugins', array('Wlc_Referral', 'giftshop_button'));
                        add_filter('mce_buttons', array('Wlc_Referral', 'register_giftshop_button'));
                        */
                    }
                }

                public static function dashboard_button($plugin_array)
                {
                    $plugin_array['dashboard_button'] = WLC_PLUGIN_URL . 'js/dashboard_shortcode.js';
                    return $plugin_array;
                }

                public static function register_dashboard_button($buttons)
                {
                    array_push($buttons, 'dashboard_button');
                    return $buttons;
                }

                public static function box_button($plugin_array)
                {
                    $plugin_array['box_button'] = WLC_PLUGIN_URL . 'js/box_shortcode.js';
                    return $plugin_array;
                }

                public static function register_box_button($buttons)
                {
                    array_push($buttons, 'box_button');
                    return $buttons;
                }
                public static function giftshop_button($plugin_array)
                {
                    $plugin_array['giftshop_button'] = WLC_PLUGIN_URL . 'js/giftshop_shortcode.js';
                    return $plugin_array;
                }

                public static function register_giftshop_button($buttons)
                {
                    array_push($buttons, 'giftshop_button');
                    return $buttons;
                }

                public static function badge_shortcode($param)
                {
                    $options = self::get_widget_options();
                    if (isset($options['key'])) {
                        $param = shortcode_atts(
                            array(
                                'star-color' => 'pink',
                            ),
                            $param
                        );
                        $param['key'] = $options['key'];
                        ob_start();
                        include 'views/tag/wlc-badge.php';
                        $data = ob_get_contents();
                        ob_end_clean();
                        return $data;
                    }
                }

                public static function widget_shortcode($param)
                {
                    $options = self::get_widget_options();
                    if (isset($options['key'])) {
                        $param = shortcode_atts(
                            array(
                                'image' => $options["image"],
                                'background-color' => Wlc_Referral::getSharpOut($options["background-color"]),
                                'header-background-color' => Wlc_Referral::getSharpOut($options["header-background-color"]),
                                'title' => $options['title'],
                                'header-color' => Wlc_Referral::getSharpOut($options["header-color"]),
                                'data-fields' => '',
                                'data-widget-config' => ''
                            ),
                            $param
                        );
                        if ($param['image'] == 'cash' || $param['image'] == 'share' || $param['image'] == 'gift' || $param['image'] == 'heart' || $param['image'] == 'like' || $param['image'] == 'rocket' || $param['image'] == 'reward' || $param['image'] == 'discount' || $param['image'] == 'dollar' || $param['image'] == 'euro' || $param['image'] == 'euro2' || $param['image'] == 'icecream' || $param['image'] == 'plant' || $param['image'] == 'white-gift') {
                            $param['image'] = "referral-box-corner-" . $param['image'] . ".png";
                        } else {
                            $param['image'] = $options['image'];
                        }
                        $param['key'] = $options['key'];

                        ob_start();
                        include 'views/tag/wlc-box.php';

                        $data = ob_get_contents();
                        ob_end_clean();
                        return $data;
                    }
                }

                public static function widget_bigboard($param)
                {
                    $options = self::get_widget_options();
                    if (isset($options['key'])) {
                        $param = shortcode_atts(
                            array(
                                'data-widget-config' => ''
                            ),
                            $param
                        );
                        $param['key'] = $options['key'];
                        $param['glue'] = $options['glue'];

                        ob_start();
                        include 'views/tag/wlc-bigboard.php';

                        $data = ob_get_contents();
                        ob_end_clean();
                        return $data;
                    }
                }

                public static function get_reviews($endPoint, $callData)
                {
                    $result = false;
                    $jsonResponse = self::wlcCall($endPoint, $callData);
                    $response = json_decode($jsonResponse);
                    if ($response) {
                        $result = array(
                            'avgScore' => round($response->score / 10),
                            'reviewsTotal' => $response->nb,
                            'reviews' => $response->reviews,
                            'reviewsUrl' => $response->reviewsUrl
                        );
                    }
                    return $result;
                }

                public static function get_account_info($endPoint, $callData)
                {
                    $result = false;
                    $jsonResponse = self::wlcCall($endPoint, $callData);
                    $response = json_decode($jsonResponse);
                    if ($response) {
                        $result = array(
                            'shopName' => $response->shops[0]->name,
                            'shopImage' => $response->shops[0]->image,
                            'shopAddress' => $response->shops[0]->fullAddress,
                        );
                    }
                    return $result;
                }

                public static function rich_snippet_shortcode($param)
                {
                    $data = false;
                    $options = self::get_widget_options();

                    if (isset($options['key']) && isset($options['glue'])) {
                        if (is_user_logged_in()) {
                            $current_user = wp_get_current_user();
                            if ($current_user) {

                                $callData = array(
                                    'customerKey'   => self::getDefaultValue($options, 'key'),
                                    'reviews'       => 'random'
                                );

                                $reviews = self::get_reviews('getSatisScore/', $callData);

                                $callData = array(
                                    'customerKey'   => self::getDefaultValue($options, 'key'),
                                    'apiGlue' => self::getDefaultValue($options, 'glue')
                                );

                                $accountInfo = self::get_account_info('getShops/', $callData);

                                if ($reviews and $accountInfo) {
                                    $data = $accountInfo;
                                    $data['avgScore'] = $reviews['avgScore'];
                                    $data['reviewsTotal'] = $reviews['reviewsTotal'];
                                    ob_start();
                                    include 'views/rich-snippets.php';

                                    $data = ob_get_contents();
                                    ob_end_clean();
                                }
                            }
                        }
                    }
                    return $data;
                }

                public static function reviews_shortcode($param)
                {
                    $data = false;
                    $options = self::get_widget_options();

                    if (isset($options['key']) && isset($options['glue'])) {
                        if (is_user_logged_in()) {
                            $current_user = wp_get_current_user();
                            if ($current_user) {

                                $callData = array(
                                    'customerKey'   => self::getDefaultValue($options, 'key'),
                                    'reviews'       => 'random'
                                );

                                $reviews = self::get_reviews('getSatisScore/', $callData);

                                $callData = array(
                                    'customerKey'   => self::getDefaultValue($options, 'key'),
                                    'apiGlue' => self::getDefaultValue($options, 'glue')
                                );

                                $accountInfo = self::get_account_info('getShops/', $callData);
                                if ($reviews and $accountInfo) {
                                    $data = array_merge($reviews, $accountInfo);
                                    $param = shortcode_atts(
                                        array(
                                            'star-color' => 'pink',
                                        ),
                                        $param
                                    );

                                    ob_start();
                                    include 'views/rich-snippets.php';
                                    include 'views/tag/wlc-reviews.php';

                                    $data = ob_get_contents();
                                    ob_end_clean();
                                }
                            }
                        }
                    }
                    return $data;
                }

                public static function loyalty_shortcode($param)
                {
                    $options = self::get_widget_options();
                    if (isset($options['key'])) {
                        $param = shortcode_atts(
                            array(),
                            $param
                        );

                        $current_user = wp_get_current_user();
                        if ($current_user) {
                            $param['email'] = $current_user->user_email;
                        }
                        $param['key'] = $options['key'];

                        ob_start();
                        include 'views/tag/wlc-loyalty.php';

                        $data = ob_get_contents();
                        ob_end_clean();
                        return $data;
                    }
                }



                public static function dashboard_shortcode($param)
                {
                    $options = self::get_widget_options();
                    if (isset($options['key'])) {
                        $param = shortcode_atts(
                            array(
                                'image' => $options["image"],
                                'background-color' => Wlc_Referral::getSharpOut($options["background-color"]),
                                'header-background-color' => Wlc_Referral::getSharpOut($options["header-background-color"]),
                                'title' => 'Suivi des parrainages',
                                'header-color' => Wlc_Referral::getSharpOut($options["header-color"]),
                                'data-fields' => '',
                                'data-widget-config' => ''
                            ),
                            $param
                        );
                        if ($param['image'] == 'cash' || $param['image'] == 'share' || $param['image'] == 'gift' || $param['image'] == 'heart' || $param['image'] == 'like' || $param['image'] == 'rocket' || $param['image'] == 'reward' || $param['image'] == 'discount' || $param['image'] == 'dollar' || $param['image'] == 'euro' || $param['image'] == 'euro2' || $param['image'] == 'icecream' || $param['image'] == 'plant' || $param['image'] == 'white-gift') {
                            $param['image'] = "referral-box-corner-" . $param['image'] . ".png";
                        } else {
                            $param['image'] = $options['image'];
                        }
                        $param['key'] = $options['key'];

                        ob_start();
                        include 'views/tag/wlc-dashboard.php';

                        $data = ob_get_contents();
                        ob_end_clean();
                        return $data;
                    }
                }

                public static function giftshop_shortcode($param)
                {

                    $data = false;
                    $options = self::get_widget_options();
                    $current_user_name = $current_user_email = '';
                    $url = '#';
                    if (isset($options['key']) && isset($options['glue'])) {
                        if (is_user_logged_in()) {
                            $current_user = wp_get_current_user();
                            if ($current_user) {
                                $current_user_name = $current_user->user_firstname . ' ' . $current_user->user_lastname;
                                $current_user_email = $current_user->user_email;


                                $callData = array(
                                    'customerKey'   => self::getDefaultValue($options, 'key'),
                                    'apiGlue'       => self::getDefaultValue($options, 'glue'),
                                    'email'         => $current_user_email
                                );

                                $jsonResponse = self::wlcCall('getGiftShopUrlForContact/', $callData);

                                if ($jsonResponse) {
                                    $response = json_decode($jsonResponse);
                                    $url = $response->url;

                                    // getting shortcode / default parameters
                                    $giftShopParam = shortcode_atts(
                                        array(
                                            'class'     => '',
                                            'id'        => '',
                                            'text'      => 'Vitrine cadeaux'
                                        ),
                                        $param
                                    );

                                    $giftShopLink = array(
                                        'link'      => $url,
                                        'class'     => $giftShopParam['class'],
                                        'id'        => $giftShopParam['id'],
                                        'text'      => $giftShopParam['text'],
                                    );
                                    ob_start();
                                    include 'views/tag/wlc-giftshop-link.php';

                                    $data = ob_get_contents();
                                    ob_end_clean();
                                }
                            }
                        }
                    }
                    return $data;
                }


                public static function check_and_create_coupon($data, $code)
                {
                    global $wpdb;
                    $options = self::get_widget_options();
                    // on checke uniquement si le plugin est activé et si il y a bien un code 
                    // et qu'il ne contient pas que des chiffres
                    $number_validation_regex = "/^\\d+$/"; 
                    if (isset($options['key']) && $code && !preg_match($number_validation_regex, $code)) {
                        // Check if the coupon has already been created in the database
                        $sql = $wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE post_title = %s AND post_type = 'shop_coupon' AND post_status = 'publish' ORDER BY post_date DESC LIMIT 1;", $code);
                        $coupon_id = $wpdb->get_var($sql);
                        if (empty($coupon_id)) {
                            // checking if the coupon is a referral coupon
                            // call rest API check avec $code
                            $callData = array('inputCode' => strtoupper($code), 'customerKey' => $options['key']);
                            $callData = http_build_query($callData);

                            $context_options = array(
                                'http' => array(
                                    'method' => 'POST',
                                    'header' => "Content-type: application/x-www-form-urlencoded\r\n"
                                        . "Content-Length: " . strlen($callData) . "\r\n",
                                    'content' => $callData
                                )
                            );

                            $context = stream_context_create($context_options);
                            $endPoint = 'https://pixel.welovecustomers.fr/api/checkOfferCode/';

                            $fp = @fopen($endPoint, 'r', false, $context);
                            $wlcCoupon = false;
                            while (($buffer = fgets($fp, 4096)) !== false) {
                                $wlcCoupon .= $buffer;
                            }
                            if (!feof($fp)) {
                            }
                            fclose($fp);
                            if ($wlcCoupon) {

                                $couponData = json_decode($wlcCoupon);

                                // if coupon is valid
                                // we create a coupon in wp
                                if ($couponData && $couponData->res) {
                                    $data = self::create_coupon($couponData, $code);
                                }
                            }
                        }
                    }
                    return $data;
                }



                public static function create_coupon($couponData, $code){
                    // checking for creating slave or master referral code
                    $referralOffer = false;
                    if ($couponData->offerType == 'F' || $couponData->offerType == 'contact'){
                        if ($couponData->offerType == 'contact'){
                            // on force une offre filleul pour les offres de type code contact 
                            $referralOffer = $couponData->fOffer;
                            $couponData->offerType = 'F';
                            $couponData->quantity = 1000;
                        } else {
                            $referralOffer = $couponData->fOffer;
                        }
                    } else {
                        $referralOffer = $couponData->pOffer;
                    }



                    // Create a coupon with the properties you need
                    $data = array();

                    $data['minimum_amount']  = (($referralOffer->minimumAmountToBuy) ?
                            ($referralOffer->minimumAmountToBuy)
                            : (($referralOffer->offerValueType == 'amount') ?
                                $referralOffer->offerValue : ''));

                    switch($referralOffer->offerValueType){
                        case 'percent':
                            $data['discount_type'] = 'percent';
                            $data['coupon_amount'] = $referralOffer->offerValue; // value
                        break;
                        case 'amount':
                            $data['discount_type'] = 'fixed_cart';
                            $data['coupon_amount'] = $referralOffer->offerValue; // value
                        break;
                        case 'product':
                            $data['discount_type'] = 'free_gift';
                            $data['coupon_amount'] = 0;
                            $data['_wc_free_gift_coupon_data'] = 
                                        array (
                                            $referralOffer->offerValue => 
                                            array (
                                                'product_id' => $referralOffer->offerValue,
                                                'variation_id' => 0,
                                                'quantity' => 1
                                            )
                                        );
                        break;
                    }

                    $data['usage_limit']      = 1;
                    $data['usage_limit_per_user'] = 1;
                    if ($couponData->quantity && (int)$couponData->quantity > 1){
                        $data['usage_limit']      = (int)$couponData->quantity;
                    }
                    

                    // Save the coupon in the database
                    $coupon = array(
                        'post_title' => $code,
                        'post_content' => '',
                        'post_status' => 'publish',
                        'post_author' => 1,
                        'post_type' => 'shop_coupon'
                    );



                    // cumul autorisé sur les codes parrains uniquement
                    if ($couponData->offerType == 'F') {
                        $data['individual_use'] = 'yes';
                    }

                    if ($couponData->offerType == 'P') {
                        $cumulativeDiscounts = self::getDefaultValue($options, 'cumulativeDiscounts');
                        if ($cumulativeDiscounts != 'no'){
                            // cumul autorisé sur les codes parrains uniquement
                            $data['individual_use'] = 'yes';
                        }
                    }
                    
                    // fix for smartcoupons activation => if not set, installation of "smart coupons" plugin activation will block coupon usage
                    $data['_wt_enable_product_category_restriction'] = 'no';
                    
                    $new_coupon_id = wp_insert_post($coupon);


                    // Write the $data values into postmeta table
                    foreach ($data as $key => $value) {
                        update_post_meta($new_coupon_id, $key, $value);
                    }


                    $data['id'] = $new_coupon_id;


                    return $data;
                }

                public static function getSharpOut($hexaColor)
                {
                    if (preg_match('/^#[a-f0-9]{3,6}$/i', $hexaColor)) {
                        $hexaColor = substr($hexaColor, 1);
                    } else {
                        $hexaColor = false;
                    }
                    return $hexaColor;
                }

                public static function insert_widget()
                {
                    $options = self::get_widget_options();
                    if (isset($options['enabled']) && (bool)$options['enabled'] == true) {
                        if (!isset($options['key'])) {
                            echo '<!--WLC Referral API KEY not found-->';
                        } else {
                            $current_user_name = $current_user_email = '';
                            if (is_user_logged_in()) {
                                $current_user = wp_get_current_user();
                                if ($current_user) {
                                    $current_user_name = $current_user->user_firstname . ' ' . $current_user->user_lastname;
                                    $current_user_email = $current_user->user_email;
                                }
                            }
                            // loading on site js ressources
                            $domain = WLC_HOST;
                            $path =  '/js/referral/referral-corner-box.js';
                            include 'views/tag/wlc-corner-box.php';
                        }
                    }
                }

                public static function get_widget_options()
                {
                    $options = get_option(self::OPTIONS);
                    return (array)json_decode($options);
                }


                public static function checkTransactionOrderStatusProcessing($order_id)
                {
                    self::checkTransactionOrderStatusOK($order_id, "processing");
                }
                public static function checkTransactionOrderStatusCompleted($order_id)
                {
                    self::checkTransactionOrderStatusOK($order_id, "completed");
                }


                public static function getRightTotalForOrder($order, $mode)
                {
                    $totalToSync = 0;
                    switch ($mode) {
                        case 'withTax':
                            $totalToSync = $order->get_total();
                            break;
                        case 'withoutShipping':
                            $totalToSync = $order->get_total() - $order->get_shipping_total();
                            break;
                        case 'withoutTax':
                            $totalToSync = $order->get_total() - $order->get_total_tax();
                            break;
                        case 'withoutTaxAndShipping':
                        default:
                            $totalToSync = $order->get_total() - $order->get_shipping_total() - $order->get_total_tax();
                            break;
                    }
                    return $totalToSync;
                }

                public static function checkTransactionOrderStatusOK($order_id, $status)
                {
                    global $woocommerce;
                    $options =  WLC_REFERRAL::get_widget_options();
                    $syncTrigger = self::getDefaultValue($options, 'syncTrigger');
                    $dataSimulate = 0;

                    if (!$order_id) return; // Lets grab the order

                    $order = wc_get_order($order_id);

                    if (!($status == "completed"
                        || ($status == "processing" && ($syncTrigger == "processing" || $syncTrigger == "paid"))
                        || ($status == "paid" && $order->is_paid()))) {
                        $dataSimulate = 1;
                    }


                    $callData = array(
                        'customerKey' => self::getDefaultValue($options, 'key'),
                        'apiGlue' => self::getDefaultValue($options, 'glue'),
                    );


                    // get lang = from billing address country
                    $callData['data-lang'] = $order->get_billing_country();

                    $callData['data-name'] = $order->get_billing_first_name() . ' ' . $order->get_billing_last_name();
                    $callData['data-email'] = $order->get_billing_email();
                    $callData['data-mobile'] = $order->get_billing_phone();

                    $callData['data-simulate'] = $dataSimulate;

                    // achat sans taxes, sans fdp

                    $callData['data-amount'] = self::getRightTotalForOrder($order, self::getDefaultValue($options, 'syncAmount'));


                    $callData['data-coupons'] = implode(',', $order->get_coupon_codes());
                    $callData['data-timestamp'] = ($order->get_date_paid()) ? $order->get_date_paid()->date('U') : time();
                    $callData['data-purchase-id'] = $order->get_order_number();
                    $hash = $callData['apiGlue'] . $callData['data-name'] . $callData['data-email'] . $callData['data-mobile'] . $callData['data-amount'] . $callData['data-coupons'] . $callData['data-timestamp'] . $callData['data-purchase-id'];
                    $callData['data-hash'] = md5($hash);

                    $jsonResponse = self::wlcCall('addBuyer/', $callData);

                    if ($jsonResponse) {
                        $response = json_decode($jsonResponse);
                    }
                }

                public static function checkTransactionOrderStatusKO($order_id)
                {
                    global $woocommerce;
                    $options =  WLC_REFERRAL::get_widget_options();

                    if (!$order_id) return; // Lets grab the order

                    $order = wc_get_order($order_id);

                    $callData = array(
                        'customerKey' => self::getDefaultValue($options, 'key'),
                        'apiGlue' => self::getDefaultValue($options, 'glue'),
                    );

                    $callData['data-purchase-id'] = $order->get_order_number();
                    $hash = $callData['apiGlue'] . $callData['data-purchase-id'];
                    $callData['data-hash'] = md5($hash);

                    $jsonResponse = self::wlcCall('deletePurchase/', $callData);

                    if ($jsonResponse) {
                        $response = json_decode($jsonResponse);
                    }
                }
                public static function checkTransaction($order_id)
                {
                    global $woocommerce;

                    if (!$order_id) return; // Lets grab the order

                    $order = wc_get_order($order_id);

                    $options = self::get_widget_options();

                    $syncTrigger = self::getDefaultValue($options, 'syncTrigger');

                    if ($syncTrigger == 'paid' && $order->is_paid()) {

                        $name = $order->get_billing_first_name() . ' ' . $order->get_billing_last_name();
                        $email = $order->get_billing_email();
                        $mobile = $order->get_billing_phone();
                        $dataSimulate = 0;

                        // achat sans taxes, sans fdp
                        $amount = self::getRightTotalForOrder($order, self::getDefaultValue($options, 'syncAmount'));

                        $datePaid = ($order->get_date_paid()) ? $order->get_date_paid()->date('U') : time();
                        $coupons = implode(',', $order->get_coupon_codes());

                        // This is the order total
                        $purchaseId = $order->get_order_number();
                        $glue = $options['glue'];
                        // This is the order total
                        $hash = md5($glue . $name . $email . $mobile . $amount . $coupons . $datePaid . $purchaseId);



                        // loading on site js ressources
                        $domain = WLC_HOST;
                        $path =  '/js/referral/referral-purchase.js';

                        include 'views/tag/wlc-purchase.php';
                    }
                }
            }
