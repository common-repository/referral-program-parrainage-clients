<?php
    $shortCodeOptions = '';
    $current_user = wp_get_current_user();
    if ($current_user && $current_user->ID != 0){
        $email = $current_user->user_email;
        $glue = $param['glue'];
        $shortCodeOptions = ' data-contact-email="'.$email.'" data-ehash="'.md5($email . $glue).'" ';
    }
?><ins class="wlc-bigboard" data-key="<?php echo $param['key']; ?>" data-widget-config="<?php echo $param['data-widget-config']; ?>" <?php echo $shortCodeOptions ?>></ins> 
<script>
(function(l,o,v,e){
a=o.getElementsByTagName('head')[0];
r=o.createElement('script');
r.async=1;
r.src=v+e;
a.appendChild(r);
})(window,document,'//dj8z0bra0q3sp.cloudfront.net','/js/referral/bigboard.js');
</script>
<!-- End WLC Call -->