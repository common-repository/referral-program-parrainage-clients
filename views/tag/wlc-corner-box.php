<!-- Begin WLC Call -->
<ins class="wlc-corner-box" data-key="<?php echo $options['key'] ?>" data-position="<?php echo $options['position'] ?>" data-title="<?php echo $options['title'] ?>" data-image="<?php echo $options['image'] ?>" data-displayOnMobile="<?php echo $options['displayOnMobile'] ?>" data-label-substitution="<?php echo $options['substitution'] ?>" data-color="<?php echo self::getSharpOut($options['color']) ?>" data-background-color="<?php echo self::getSharpOut($options['background-color']) ?>" data-header-color="<?php echo self::getSharpOut($options['header-color']) ?>" data-header-background-color="<?php echo self::getSharpOut($options['header-background-color']) ?>" data-title-size="<?php echo $options['title-size'] ?>" data-state="<?php echo $options['state'] ?>" data-description="<?php echo $options['description'] ?>" data-name="<?php echo $current_user_name ?>" data-email="<?php echo $current_user_email ?>"></ins>
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