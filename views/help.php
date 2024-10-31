<link rel="stylesheet" href="<?php echo WLC_PLUGIN_URL?>css/style.css" />
<div class="wrap woocommerce">
    <table class="wc_status_table widefat" cellspacing="0" id="status">
        <thead>
            <tr>
                <th colspan="3" data-export-label="Shortcodes informations"><h2>Shortcodes informations</h2></th>
            </tr>
        </thead>
        <tbody style="background-color: lavender;">
            <tr>
                <td data-export-label="Home URL"> <?php echo __( 'Referral widget shortcode&nbsp;:' , 'referral-program-parrainage-clients') ?></td>
                <td class="help"><span class="woocommerce-help-tip"></span></td>
                <td><strong>[wlc_box]</strong></td>
            </tr>
            <tr>
                <td data-export-label="Shortcode du widget de suivi de vos parrainages"><?php echo __( 'Purchase referral widget shortcode&nbsp' , 'referral-program-parrainage-clients')?></td>
                <td class="help"><span class="woocommerce-help-tip"></span></td>
                <td><strong>[wlc_dashboard]</strong></td>
            </tr>
            <tr>
                <td data-export-label="Shortcode du widget de suivi de vos parrainages"><?php echo __( 'Rich snippet widget' , 'referral-program-parrainage-clients')?></td>
                <td class="help"><span class="woocommerce-help-tip"></span></td>
                <td><strong>[wlc_rich_snippet]</strong></td>
            </tr>
            <tr>
                <td data-export-label="Shortcode du widget de suivi de vos parrainages"><?php echo __( 'Badge widget' , 'referral-program-parrainage-clients')?></td>
                <td class="help"><span class="woocommerce-help-tip"></span></td>
                <td><strong>[wlc_badge]</strong></td>
            </tr>
            <tr>
                <td data-export-label="Shortcode du widget de suivi de vos parrainages"><?php echo __( 'Reviews widget' , 'referral-program-parrainage-clients')?></td>
                <td class="help"><span class="woocommerce-help-tip"></span></td>
                <td><strong>[wlc_reviews]</strong></td>
            </tr>
            <tr>
                <td data-export-label="Personnaliser le titre du widget"><?php echo __( 'Customize widget title&nbsp;:' , 'referral-program-parrainage-clients') ?></td>
                <td class="help"><span class="woocommerce-help-tip"></span></td>
                <td>[wlc_box title="<?php echo __('My title', 'referral-program-parrainage-clients')?>"]</td>
            </tr>
            <tr>
                <td><?php echo __('To customize your color choice<br>You have to enter the hexadecimal code of your color without the "#" like on these 3 examples:' , 'referral-program-parrainage-clients')?></td>
                <td class="help"><span class="woocommerce-help-tip"></span></td>
                <td></td>
            </tr>
            <tr>
                <td data-export-label="Personnaliser la couleur du texte du widget"><?php echo __( 'Customize widget text color&nbsp;:' , 'referral-program-parrainage-clients') ?></td>
                <td class="help"><span class="woocommerce-help-tip"></span></td>
                <td>[wlc_box header-color="FF69B4"]</td>
            </tr>
            <tr>
                <td data-export-label="Personnaliser la couleur de fond du widget"><?php echo __( 'Customize widget background color&nbsp;:' , 'referral-program-parrainage-clients') ?></td>
                <td class="help"><span class="woocommerce-help-tip"></span></td>
                <td>[wlc_box background-color="00FF00"]</td>
            </tr>
            <tr>
                <td data-export-label="Personnaliser la couleur de fond du widget"><?php echo __( 'Customize widget header background color&nbsp;:' , 'referral-program-parrainage-clients') ?></td>
                <td class="help"><span class="woocommerce-help-tip"></span></td>
                <td>[wlc_box header-background-color="000000"]</td>
            </tr>
            <tr>
                <td data-export-label="Personnaliser plusieurs paramètres en même temps"><?php echo __( 'Customize several parameters at the same time&nbsp;:' , 'referral-program-parrainage-clients') ?></td>
                <td class="help"><span class="woocommerce-help-tip"></span></td>
                <td>[wlc_box image="plant" title="<?php echo __('Invite your friends', 'referral-program-parrainage-clients')?>" header-color="FF69B4"]</td>
            </tr>
            <tr>
                <td data-export-label="Personnaliser l'image du widget"><?php echo __( 'Customize widget image&nbsp;:' , 'referral-program-parrainage-clients') ?></td>
                <td class="help"><span class="woocommerce-help-tip"></span></td>
                <td>
                    <ul id="iconHelp">
                        <li>[wlc_box image="cash"]  <img src="http://s.app.welovecustomers.fr/widgets/img/referral-box-corner-cash.png"/></li>
                        <li>[wlc_box image="share"]  <img src="http://s.app.welovecustomers.fr/widgets/img/referral-box-corner-share.png"/></li>
                        <li>[wlc_box image="gift"]  <img src="http://s.app.welovecustomers.fr/widgets/img/referral-box-corner-gift.png"/></li>
                        <li>[wlc_box image="heart"]  <img src="http://s.app.welovecustomers.fr/widgets/img/referral-box-corner-heart.png"/></li>
                        <li>[wlc_box image="like"]  <img src="http://s.app.welovecustomers.fr/widgets/img/referral-box-corner-like.png"/></li>
                        <li>[wlc_box image="rocket"]  <img src="http://s.app.welovecustomers.fr/widgets/img/referral-box-corner-rocket.png"/></li>
                        <li>[wlc_box image="reward"]  <img src="http://s.app.welovecustomers.fr/widgets/img/referral-box-corner-reward.png"/></li>
                        <li>[wlc_box image="discount"]  <img src="http://s.app.welovecustomers.fr/widgets/img/referral-box-corner-discount.png"/></li>
                        <li>[wlc_box image="dollar"]  <img src="http://s.app.welovecustomers.fr/widgets/img/referral-box-corner-dollar.png"/></li>
                        <li>[wlc_box image="euro"]  <img src="http://s.app.welovecustomers.fr/widgets/img/referral-box-corner-euro.png"/></li>
                        <li>[wlc_box image="euro2"]  <img src="http://s.app.welovecustomers.fr/widgets/img/referral-box-corner-euro2.png"/></li>
                        <li>[wlc_box image="icecream"]  <img src="http://s.app.welovecustomers.fr/widgets/img/referral-box-corner-icecream.png"/></li>
                        <li>[wlc_box image="plant"]  <img src="http://s.app.welovecustomers.fr/widgets/img/referral-box-corner-plant.png"/></li>
                        <li>[wlc_box image="white-gift"]  <img src="http://s.app.welovecustomers.fr/widgets/img/referral-box-corner-white-gift.png" height="48" width="48"/></li>
                    </ul>
                </td>
            </tr>
        </tbody>
    </table>
</div>