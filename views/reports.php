<link rel="stylesheet" href="<?php echo WLC_PLUGIN_URL;?>css/reports.css" />
<div class="wrap">
    <div id="welcome-panel" class="welcome-panel">
        <div class="welcome-panel-content">
            <h2><?php echo __('Reports', 'referral-program-parrainage-clients'); ?></h2>
            <p>
                <?php echo __('Find more information on our website WeLoveCustomers', 'referral-program-parrainage-clients'); ?>
            </p>

            <a href="https://pixel.welovecustomers.fr" target="_blank" class="action action-advanced-reports wlc-reports-link"
               data-index="analytics-service-link" title="Go to Advanced Reporting">
            <span>
                <?php echo __('Go to Advanced Reporting', 'referral-program-parrainage-clients'); ?>
            </span>
            </a>

            <p>
                <strong><?php echo __('Reward offer', 'referral-program-parrainage-clients'); ?></strong> :
                <?php echo $env['masterInfos'];?>
            </p>
            <p>
                <strong><?php echo __('Welcome offer', 'referral-program-parrainage-clients'); ?></strong> :
                <?php echo $env['slaveInfos'];?>
            </p>
        </div>
    </div>
    <div class="wlc-reports">

        <ul>

            <li class="wlc-reports-item" style="border-right-color: #79a22e">
                <div class="title">
                    <?php echo __('Invitation', 'referral-program-parrainage-clients'); ?>
                </div>
                <div class="content">
                    <?php echo $env['invitation'];?>
                </div>
            </li>
            <li class="wlc-reports-item" style="border-right-color: #007bdb">
                <div class="title">
                    <?php echo __('Referrer', 'referral-program-parrainage-clients'); ?>
                </div>
                <div class="content">
                    <?php echo $env['referrer'];?>
                </div>
            </li>

            <li class="wlc-reports-item" style="border-right-color: #f1c40f">
                <div class="title">
                    <?php echo __('Referee', 'referral-program-parrainage-clients'); ?>
                </div>
                <div class="content">
                    <?php echo $env['referee'];?>
                </div>
            </li>

            <li class="wlc-reports-item" style="border-right-color: #9c5d90">
                <div class="title">
                    <?php echo __('NPS Score', 'referral-program-parrainage-clients'); ?> :
                </div>
                <div class="content <?php echo $env['npsClass'];?>">
                    <?php echo $env['nps'];?>
                </div>
            </li>
        </ul>
    </div>
</div>