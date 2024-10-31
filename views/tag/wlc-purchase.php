<!-- Begin WLC Call -->
<ins class="wlc-purchase" customer-key="<?php echo $options['key'] ?>" data-name="<?php echo $name ?>" data-email="<?php echo $email ?>" data-mobile="<?php echo $mobile ?>" data-amount="<?php echo $amount ?>" data-coupons="<?php echo $coupons ?>" data-timestamp="<?php echo $datePaid ?>" data-purchase-id="<?php echo $purchaseId ?>" data-hash="<?php echo $hash ?>" <?php echo ($dataSimulate ? 'data-simulate="1"' : '') ?>></ins>
<script>
    (function(l, o, v, e) {
        a = o.getElementsByTagName('head')[0];
        r = o.createElement('script');
        r.async = 1;
        r.src = v + e;
        a.appendChild(r);
    })(window, document, '//<?php echo $domain ?>', '<?php echo $path ?>');
</script>
<!-- End WLC Call -->