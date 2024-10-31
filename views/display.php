<link rel="stylesheet" href="<?php echo WLC_PLUGIN_URL; ?>css/style.css" />
<div class="wrap">
    <div id="welcome-panel" class="welcome-panel">
        <div class="welcome-panel-content">
            <h2><?= __('Plugin Settings', 'referral-program-parrainage-clients') ?></h2>
            <div class="welcome-panel-column-container">
                <div class="welcome-panel-column">
                    <h3><?= __('Start', 'referral-program-parrainage-clients') ?>&nbsp;!</h3>
                    <?php if ($env['initMode']) { ?>
                        <p><?php echo sprintf(__('To get an API Key, please register using this link:', 'referral-program-parrainage-clients')) ?>
                        </p>
                        <p>
                            <a class="button button-primary button-hero" href="<?php echo $env['wlcUrl'] ?>"><?php echo __('Get it!', 'referral-program-parrainage-clients'); ?></a>
                        </p>
                    <?php } else { ?>
                        <p><?php echo sprintf(__('To update and manage your referral programm, click here:', 'referral-program-parrainage-clients')) ?>
                        </p>
                        <p>
                            <a class="button button-primary button-hero" href="<?php echo $env['wlcUrl'] ?>"><?php echo __('Manage it!', 'referral-program-parrainage-clients'); ?></a>
                        </p>
                    <?php } ?>
                </div>
                <div class="welcome-panel-column">
                    <h3><?php echo sprintf(__('NPS score:', 'referral-program-parrainage-clients')) ?></h3>
                    <div id="scoreNPS">
                        <?php
                        if (self::getDefaultValue($options, 'key') && $scoreNPS !== false) {
                            echo sprintf(__('<span id="scoreNPSValue">%d</span>', 'referral-program-parrainage-clients'), $scoreNPS);
                        } else {
                            echo '-';
                        }
                        ?>
                    </div>
                </div>
                <div class="welcome-panel-column welcome-panel-lastcolumn">
                    <h3><?php echo sprintf(__('Plugin version:', 'referral-program-parrainage-clients')) ?></h3>
                    <div id='pluginVersionText'><?php echo sprintf(__('You are using plugin version %s.', 'referral-program-parrainage-clients'), WLC_VERSION); ?></div>
                </div>
            </div>
            <div id="wlc_infos">
                <div id="referralInfos">
                    <?php if (self::getDefaultValue($options, 'key')) { ?>
                        <h3><?php echo __('Referral program:', 'referral-program-parrainage-clients') ?></h3>
                        <div><?php echo sprintf(__('Reward offer = %d%s', 'referral-program-parrainage-clients'), $referralInfos['masterOfferValue'], $referralInfos['masterOfferValueType']); ?></div>
                        <div><?php echo sprintf(__('Welcome offer = %d%s', 'referral-program-parrainage-clients'), $referralInfos['slaveOfferValue'], $referralInfos['slaveOfferValueType']); ?></div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

    <form class="form-table" method="post" action="">
        <?php settings_fields('wlc_referral_group') ?>
        <table id="form">
            <tr>
                <td>
                    <label for="wlc_referral[key]">
                        <?php echo __('API Key', 'referral-program-parrainage-clients') ?>
                    </label>
                </td>
                <td>
                    <input type="text" name="<?php echo WLC_REFERRAL::OPTIONS ?>[key]" value="<?php echo self::getDefaultValue($options, 'key') ?>">
                </td>
            </tr>
            <tr>
                <td>
                    <label for="wlc_referral[glue]">
                        <?php echo __('API Glue', 'referral-program-parrainage-clients') ?>
                    </label>
                </td>
                <td>
                    <input type="text" name="<?php echo WLC_REFERRAL::OPTIONS ?>[glue]" value="<?php echo self::getDefaultValue($options, 'glue') ?>">
                </td>
            </tr>
            <tr>
                <td>
                    <label for="wlc_referral[title]">
                        <?php echo __('Title', 'referral-program-parrainage-clients') ?>
                    </label>
                </td>
                <td>
                    <input type="text" name="<?php echo WLC_REFERRAL::OPTIONS ?>[title]" value="<?php echo self::getDefaultValue($options, 'title') ?>">
                </td>
            </tr>
            <tr>
                <td>
                    <label for="wlc_referral[substitution]">
                        <?php echo __('Substitution', 'referral-program-parrainage-clients') ?>
                    </label>
                </td>
                <td>
                    <input type="text" name="<?php echo WLC_REFERRAL::OPTIONS ?>[substitution]" value="<?php echo self::getDefaultValue($options, 'substitution') ?>">
                </td>
            </tr>
            <tr>
                <td>
                    <label for="wlc_referral[description]">
                        <?php echo __('Description', 'referral-program-parrainage-clients') ?>
                    </label>
                </td>
                <td>
                    <input type="text" name="<?php echo WLC_REFERRAL::OPTIONS ?>[description]" value="<?php echo self::getDefaultValue($options, 'description') ?>">
                </td>
            </tr>
            <tr>
                <td>
                    <label for="wlc_referral[image]">
                        <?php echo __('Image', 'referral-program-parrainage-clients') ?>
                    </label>
                </td>
                <td>
                    <select name="<?php echo WLC_REFERRAL::OPTIONS ?>[image]" id="wlcIcon">
                        <option value="referral-box-corner-cash.png"><?php echo __('Icon_Cash', 'referral-program-parrainage-clients') ?></option>
                        <option value="referral-box-corner-share.png"><?php echo __('Icon_Share', 'referral-program-parrainage-clients') ?></option>
                        <option value="referral-box-corner-gift.png"><?php echo __('Icon_Gift', 'referral-program-parrainage-clients') ?></option>
                        <option value="referral-box-corner-heart.png"><?php echo __('Icon_Heart', 'referral-program-parrainage-clients') ?></option>
                        <option value="referral-box-corner-like.png"><?php echo __('Icon_Like', 'referral-program-parrainage-clients') ?></option>
                        <option value="referral-box-corner-rocket.png"><?php echo __('Icon_Rocket', 'referral-program-parrainage-clients') ?></option>
                        <option value="referral-box-corner-reward.png"><?php echo __('Icon_Reward', 'referral-program-parrainage-clients') ?></option>
                        <option value="referral-box-corner-discount.png"><?php echo __('Icon_Discount', 'referral-program-parrainage-clients') ?></option>
                        <option value="referral-box-corner-dollar.png"><?php echo __('Icon_Dollar', 'referral-program-parrainage-clients') ?></option>
                        <option value="referral-box-corner-euro.png"><?php echo __('Icon_Euro', 'referral-program-parrainage-clients') ?></option>
                        <option value="referral-box-corner-euro2.png"><?php echo __('Icon_Euro2', 'referral-program-parrainage-clients') ?></option>
                        <option value="referral-box-corner-icecream.png"><?php echo __('Icon_Icecream', 'referral-program-parrainage-clients') ?></option>
                        <option value="referral-box-corner-plant.png"><?php echo __('Icon_Plant', 'referral-program-parrainage-clients') ?></option>
                        <option value="referral-box-corner-white-gift.png"><?php echo __('Icon_Whitegift', 'referral-program-parrainage-clients') ?></option>
                    </select>
                </td>
            </tr>
            <tr>
                <td>
                    <label for="wlc_referral[color]">
                        <?php echo __('Text color', 'referral-program-parrainage-clients') ?>
                    </label>
                </td>
                <td>
                    <input type="text" name="<?php echo WLC_REFERRAL::OPTIONS ?>[color]" value="<?php echo $options['color'] ?>" class="my-color-field" />
                </td>
            </tr>
            <tr>
                <td>
                    <label for="wlc_referral[background-color]">
                        <?php echo __('Background color', 'referral-program-parrainage-clients') ?>
                    </label>
                </td>
                <td>
                    <input type="text" name="<?php echo WLC_REFERRAL::OPTIONS ?>[background-color]" value="<?php echo $options['background-color'] ?>" class="my-color-field">
                </td>
            </tr>
            <tr>
                <td>
                    <label for="wlc_referral[header-color]">
                        <?php echo __('Header color', 'referral-program-parrainage-clients') ?>
                    </label>
                </td>
                <td>
                    <input type="text" name="<?php echo WLC_REFERRAL::OPTIONS ?>[header-color]" value="<?php echo $options['header-color'] ?>" class="my-color-field">
                </td>
            </tr>
            <tr>
                <td>
                    <label for="wlc_referral[header-background-color]">
                        <?php echo __('Header background color', 'referral-program-parrainage-clients') ?>
                    </label>
                </td>
                <td>
                    <input type="text" name="<?php echo WLC_REFERRAL::OPTIONS ?>[header-background-color]" value="<?php echo $options['header-background-color'] ?>" class="my-color-field">
                </td>
            </tr>
            <tr>
                <td>
                    <label for="wlc_referral[title-size]">
                        <?php echo __('Font size', 'referral-program-parrainage-clients') ?>
                    </label>
                </td>
                <td>
                    <input type="text" name="<?php echo WLC_REFERRAL::OPTIONS ?>[title-size]" value="<?php echo self::getDefaultValue($options, 'title-size') ?>">
                </td>
            </tr>
            <tr>
                <td>
                    <label for="wlc_referral[syncTrigger]">
                        <?php echo __('SyncTrigger', 'referral-program-parrainage-clients') ?>
                    </label>
                </td>
                <td>
                    <select name="<?php echo WLC_REFERRAL::OPTIONS ?>[syncTrigger]" id="wlcSyncTrigger">
                        <option value="paid"><?php echo __('trigger_Paid', 'referral-program-parrainage-clients') ?></option>
                        <option value="processing"><?php echo __('trigger_Processing', 'referral-program-parrainage-clients') ?></option>
                        <option value="completed"><?php echo __('trigger_Completed', 'referral-program-parrainage-clients') ?></option>
                    </select>
                </td>
            </tr>
            <tr>
                <td>
                    <label for="wlc_referral[syncAmount]">
                        <?php echo __('SyncAmount', 'referral-program-parrainage-clients') ?>
                    </label>
                </td>
                <td>
                    <select name="<?php echo WLC_REFERRAL::OPTIONS ?>[syncAmount]" id="wlcSyncAmount">
                        <option value="withoutTaxAndShipping"><?php echo __('amountWithoutTaxAndShipping', 'referral-program-parrainage-clients') ?></option>
                        <option value="withTax"><?php echo __('amountWithTax', 'referral-program-parrainage-clients') ?></option>
                        <option value="withoutTax"><?php echo __('amountWithoutTax', 'referral-program-parrainage-clients') ?></option>
                        <option value="withoutShipping"><?php echo __('amountWithoutShipping', 'referral-program-parrainage-clients') ?></option>
                    </select>
                </td>
            </tr>
            <tr>
                <td>
                    <label for="wlc_referral[cumulativeDiscounts]">
                        <?php echo __('CumulativeDiscounts', 'referral-program-parrainage-clients') ?>
                    </label>
                </td>
                <td>
                    <select name="<?php echo WLC_REFERRAL::OPTIONS ?>[cumulativeDiscounts]" id="wlcCumulativeDiscounts">
                        <option value="yes"><?php echo __('Yes', 'referral-program-parrainage-clients') ?></option>
                        <option value="no"><?php echo __('No', 'referral-program-parrainage-clients') ?></option>
                    </select>
                </td>
            </tr>
            <tr>
                <td>
                    <label for="wlc_referral[showPageInWooCommerceAccountMenu]">
                        <?php echo __('showPageInWooCommerceAccountMenu', 'referral-program-parrainage-clients') ?>
                    </label>
                </td>
                <td>
                    <input type="text" name="<?php echo WLC_REFERRAL::OPTIONS ?>[showPageInWooCommerceAccountMenu]" value="<?php echo self::getDefaultValue($options, 'showPageInWooCommerceAccountMenu') ?>">
                </td>
            </tr>
            <?php do_settings_fields('wlc_referral_group', 'default') ?>
        </table>
        <?php do_settings_sections('wlc_referral_group') ?>
        <?php submit_button() ?>
    </form>
</div>
<script>
    jQuery(document).ready(function($) {
        $('#wlcEnabled').val('<?php echo $options['enabled'] ?>');
        $('#wlcPosition').val('<?php echo $options['position'] ?>');
        $('#wlcIcon').val('<?php echo $options['image'] ?>');
        $('#wlcSyncTrigger').val('<?php echo $options['syncTrigger'] ?>');
        $('#wlcSyncAmount').val('<?php echo $options['syncAmount'] ?>');
        $('#wlcCumulativeDiscounts').val('<?php echo $options['cumulativeDiscounts'] ?>');

        $('#wlcIcon').change(function() {
            if ($('#imgIconPreviewContainer')) {
                $('#imgIconPreviewContainer').remove();
            }
            $(this).parent().append('<div id="imgIconPreviewContainer"><img src="http://s.app.welovecustomers.fr/widgets/img/' + $(this).val() + '" /></div>');
        });


        $('<?php echo WLC_REFERRAL::OPTIONS ?>[image]').change();

        var score = parseInt($("#scoreNPSValue").text());
        var color = '#666';
        if (score < -20) {
            color = '#DC3912';
        } else if (score >= -20 && score < 20) {
            color = '#FF9900';
        } else if (score >= 20) {
            color = '#109618';
        }
        $("#scoreNPSValue").css('color', color);


    });
</script>