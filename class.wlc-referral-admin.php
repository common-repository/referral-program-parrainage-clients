<?php

class Wlc_Referral_Admin {

	private static $initiated = false;

	public static function check_version(){

		if ( version_compare( $GLOBALS['wp_version'], WLC_MINIMUM_WP_VERSION, '<' ) ) {

			$message = '<strong>'.sprintf( __('This plugin requires WordPress %s or higher.' , 'referral-program-parrainage-clients'), WLC_MINIMUM_WP_VERSION ).'</strong> '.sprintf(__('Please <a href="%1$s">upgrade WordPress</a> to a current version.', 'referral-program-parrainage-clients'), 'https://codex.wordpress.org/Upgrading_WordPress');
			self::bail_on_activation($message);
		}
	}

	public static function bail_on_activation($message) {
		?>
		<!doctype html>
		<html>
			<head>
				<meta charset="<?php bloginfo( 'charset' ); ?>">
                <style>
                    * {
                        text-align: center;
                        margin: 0;
                        padding: 0;
                        font-family: "Lucida Grande",Verdana,Arial,sans-serif;
                    }
                    p {
                        margin-top: 1em;
                        font-size: 18px;
                    }           
                </style>
			</head>
			<body>
				<p><?php echo esc_html( $message ); ?></p>
			</body>
		</html>
		<?php
		exit;
	}

	public static function init() {
        wp_enqueue_script('jquery');
		self::check_version();

		if ( ! self::$initiated ) {
			self::init_hooks();
		}
		if ( !empty( $_POST ) && isset($_POST[WLC_REFERRAL::OPTIONS]['key'])) {
			$res = update_option(WLC_REFERRAL::OPTIONS, json_encode($_POST[WLC_REFERRAL::OPTIONS]));
		}
	}

	public static function uninstall() {

		delete_option(WLC_REFERRAL::OPTIONS);

	}


	public static function init_hooks() {

		self::$initiated = true;

		add_action('admin_menu', array('Wlc_Referral_Admin', 'admin_menu'));
        
        add_action( 'admin_enqueue_scripts', array('Wlc_Referral_Admin', 'enqueue_color_picker' ));
        
 	}
    
    public static function enqueue_color_picker( $hook_suffix ) {
        // first check that $hook_suffix is appropriate for your admin page
        wp_enqueue_style( 'wp-color-picker' );
        wp_enqueue_script( 'my-colorpicker-handle', plugins_url('js/colorpicker.js', __FILE__ ), array( 'wp-color-picker' ), false, true );
    }

	public static function admin_menu() {
        add_menu_page( 'Parrainage', 'Parrainage', 'manage_options', 'referral', array('Wlc_Referral_Admin', 'referral_init' ));
        
        add_submenu_page( 'referral', 'Parrainage', 'Paramétrage' , 'manage_options', 'referral', array('Wlc_Referral_Admin', 'referral_init' ));

        add_submenu_page( 'referral', __('Reports'),  __('Reports'), 'manage_options', 'referral_reports', array('Wlc_Referral_Admin', 'reports_init' ));
        
        add_submenu_page( 'referral','Aide', 'Aide' , 'manage_options','help', array('Wlc_Referral_Admin', 'help'));
	}
    
    private static function wlcCall($endPoint, $callData){
        $callData = http_build_query($callData);

        $context_options = array (
                'http' => array (
                    'method' => 'POST',
                    'header'=> "Content-type: application/x-www-form-urlencoded\r\n"
                        . "Content-Length: " . strlen($callData) . "\r\n",
                    'content' => $callData
                    )
                );

        $context = stream_context_create($context_options);
        $endPoint = 'https://pixel.welovecustomers.fr/api/' . $endPoint;


        $fp = @fopen($endPoint, 'r', false, $context);
        $response = false;
        if ($fp){
            while (($buffer = fgets($fp, 4096)) !== false) {
                $response .= $buffer;
            }
            if (!feof($fp)) {
            }
            fclose($fp);

        }
        return $response;
    }


    public static function nps(){
        $scoreNPS = false;
        $options =  WLC_REFERRAL::get_widget_options();
        $callData = array(
                            'customerKey' => self::getDefaultValue($options, 'key'),
                            'apiGlue' => self::getDefaultValue($options, 'glue'),
                );
        $response = self::wlcCall('getNPS/', $callData); // getting NPS score for this customer
        if ($response){
            $scoreNPS = json_decode($response);
            $scoreNPS = $scoreNPS->score;
        } else{
            $scoreNPS = '';
        }
        return $scoreNPS;
    }

    public static function getStats(){
        $options =  WLC_REFERRAL::get_widget_options();
        $callData = array(
            'customerKey' => self::getDefaultValue($options, 'key'),
            'apiGlue' => self::getDefaultValue($options, 'glue'),
        );
        $response = self::wlcCall('getStats/', $callData); // getting NPS score for this customer
        if ($response){
            $stats = json_decode($response);
            return $stats->stats;
        } else{
            return null;
        }

        return null;
    }


    public static function getDefaultValue($array, $key, $defaultValue = null) {
        if(isset($array[$key])) {
            return $array[$key];
        }
        return $defaultValue;
    }
    
    public static function referralInfos(){
        $offerInfos = false;
        $referralOfferInfos = false;
        $options =  WLC_REFERRAL::get_widget_options();
        $callData = array(
                        'customerKey' => self::getDefaultValue($options, 'key'),
                );
        $response = self::wlcCall('referralInfos/', $callData);
        if ($response){
            $offerInfos = json_decode($response);
            if ($offerInfos->masterOffer){
                $referralOfferInfos['nameOffer1'] = $offerInfos->nameOffer1;
                $referralOfferInfos['masterOfferId'] = $offerInfos->masterOfferId;
                if ($offerInfos->masterOffer->offerValueType == 'percent'){
                    $referralOfferInfos['masterOfferValueType'] = "%";
                }else if ($offerInfos->masterOffer->offerValueType == 'amount'){
                    $referralOfferInfos['masterOfferValueType'] = "€";
                }else if ($offerInfos->masterOffer->offerValueType == 'shipping'){
                    $referralOfferInfos['masterOfferValueType'] = "";
                }
                $referralOfferInfos['masterOfferValue'] = $offerInfos->masterOffer->offerValue;
            }
            if ($offerInfos->slaveOffer){
                $referralOfferInfos['nameOffer2'] = $offerInfos->nameOffer2;
                $referralOfferInfos['slaveOfferId'] = $offerInfos->slaveOfferId;
                if ($offerInfos->slaveOffer->offerValueType == 'percent'){
                    $referralOfferInfos['slaveOfferValueType'] = "%";
                }else if ($offerInfos->slaveOffer->offerValueType == 'amount'){
                    $referralOfferInfos['slaveOfferValueType'] = "€";
                }else if ($offerInfos->slaveOffer->offerValueType == 'shipping'){
                    $referralOfferInfos['slaveOfferValueType'] = "";
                }
                $referralOfferInfos['slaveOfferValue'] = $offerInfos->slaveOffer->offerValue;
            }
        } 
        return $referralOfferInfos;
    }
    
	public static function referral_init(){
		$content = array();
        $scoreNPS = self::nps();
        $referralInfos = self::referralInfos();
        $options = WLC_REFERRAL::get_widget_options();
        $user = wp_get_current_user();
        $initMode = (!self::getDefaultValue($options, 'key'))?true:false;
        $wlcUrl = 'http://www.welovecustomers.fr/developpez-le-parrainage-en-e-commerce/' . 
                    '?utm_campaign=' . (($initMode)?'install-plugin':'manage') .
                    '&utm_source=wordpress&utm_medium=' . WLC_VERSION .
                    '&utm_term='. get_site_url().
                    '&utm_content='. urlencode($user->user_firstname." ".$user->user_lastname)
                    ;
        $env = array(
                    'stats'       => $wlcUrl,
                    'initMode'      => $initMode
                );
        require 'views/display.php';
    }
    
    public static function help(){
        require 'views/help.php';
    }

    public static function reports_init() {
        $stats = self::GetStats();
        $referralInfos = self::referralInfos();

        if(!$referralInfos || !$stats)
            return;

        $env = array(
            'stats'       => $stats,
            'masterInfos' => $referralInfos['masterOfferValue']. " ".$referralInfos['masterOfferValueType'],
            'slaveInfos' => $referralInfos['slaveOfferValue']. " ".$referralInfos['slaveOfferValueType'],
            'nps' => $stats->nps->score,
            'invitation' => $stats->contact,
            'referrer' => $stats->father,
            'referee' => $stats->slave,
            'npsClass' => self::getNPSClass($stats->nps->score)

        );

        require 'views/reports.php';
    }

    /**
     * @return string
     */
    public static function getNPSClass($score = null) {

        if($score) {
            if($score > 20) {
                return "reports-success";
            }

            if($score > -20) {
                return "reports-warning";
            }

            return "reports-danger";
        }

        return "reports-info";
    }
}

