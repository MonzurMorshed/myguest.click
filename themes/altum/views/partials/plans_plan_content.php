<?php defined('ALTUMCODE') || die() ?>

<?php if(settings()->stores->additional_domains_is_enabled): ?>
    <?php $additional_domains = (new \Altum\Models\Domain())->get_available_additional_domains(); ?>
<?php endif ?>

<div class="">
    <div class="d-flex justify-content-between align-items-center my-3">
        <div>
            <?= sprintf(l('global.plan_settings.stores_limit'), '<strong>' . ($data->plan_settings->stores_limit == -1 ? l('global.unlimited') : nr($data->plan_settings->stores_limit)) . '</strong>') ?>
        </div>

        <i class="fas fa-fw fa-sm <?= $data->plan_settings->stores_limit ? 'fa-check text-success' : 'fa-times text-muted' ?>"></i>
    </div>

    <div class="d-flex justify-content-between align-items-center my-3">
        <div>
            <?= sprintf(l('global.plan_settings.menus_limit'), '<strong>' . ($data->plan_settings->menus_limit == -1 ? l('global.unlimited') : nr($data->plan_settings->menus_limit)) . '</strong>') ?>
        </div>

        <i class="fas fa-fw fa-sm <?= $data->plan_settings->menus_limit ? 'fa-check text-success' : 'fa-times text-muted' ?>"></i>
    </div>

    

    <?php if(settings()->stores->domains_is_enabled): ?>
        <div class="d-flex justify-content-between align-items-center my-3">
            <div>
                <?= sprintf(l('global.plan_settings.domains_limit'), '<strong>' . ($data->plan_settings->domains_limit == -1 ? l('global.unlimited') : nr($data->plan_settings->domains_limit)) . '</strong>') ?>
            </div>

            <i class="fas fa-fw fa-sm <?= $data->plan_settings->domains_limit ? 'fa-check text-success' : 'fa-times text-muted' ?>"></i>
        </div>
    <?php endif ?>

    

    <?php if(\Altum\Plugin::is_active('teams')): ?>
        <div class="d-flex justify-content-between align-items-center my-3">
            <div>
                <?= sprintf(l('global.plan_settings.teams_limit'), '<strong>' . ($data->plan_settings->teams_limit == -1 ? l('global.unlimited') : nr($data->plan_settings->teams_limit)) . '</strong>') ?>
            </div>

            <i class="fas fa-fw fa-sm <?= $data->plan_settings->teams_limit ? 'fa-check text-success' : 'fa-times text-muted' ?>"></i>
        </div>

        <div class="d-flex justify-content-between align-items-center my-3">
            <div>
                <?= sprintf(l('global.plan_settings.team_members_limit'), '<strong>' . ($data->plan_settings->team_members_limit == -1 ? l('global.unlimited') : nr($data->plan_settings->team_members_limit)) . '</strong>') ?>
            </div>

            <i class="fas fa-fw fa-sm <?= $data->plan_settings->team_members_limit ? 'fa-check text-success' : 'fa-times text-muted' ?>"></i>
        </div>
    <?php endif ?>

    <?php if(\Altum\Plugin::is_active('affiliate') && settings()->affiliate->is_enabled): ?>
        <div class="d-flex justify-content-between align-items-center my-3">
            <div>
                <?= sprintf(l('global.plan_settings.affiliate_commission_percentage'), '<strong>' . nr($data->plan_settings->affiliate_commission_percentage) . '%</strong>') ?>
            </div>

            <i class="fas fa-fw fa-sm <?= $data->plan_settings->affiliate_commission_percentage ? 'fa-check text-success' : 'fa-times text-muted' ?>"></i>
        </div>
    <?php endif ?>

    <div class="d-flex justify-content-between align-items-center my-3">
        <div>
            <?= sprintf(l('global.plan_settings.statistics_retention'), '<strong>' . ($data->plan_settings->statistics_retention == -1 ? l('global.unlimited') : nr($data->plan_settings->statistics_retention)) . '</strong>') ?>
        </div>

        <i class="fas fa-fw fa-sm <?= $data->plan_settings->statistics_retention ? 'fa-check text-success' : 'fa-times text-muted' ?>"></i>
    </div>

    <div class="d-flex justify-content-between align-items-center my-3 <?= $data->plan_settings->analytics_is_enabled ? null : 'text-muted' ?>">
        <div>
            <?= l('global.plan_settings.analytics_is_enabled') ?>
        </div>

        <i class="fas fa-fw fa-sm <?= $data->plan_settings->analytics_is_enabled ? 'fa-check text-success' : 'fa-times' ?>"></i>
    </div>

    <div class="d-flex justify-content-between align-items-center my-3 <?= $data->plan_settings->qr_is_enabled ? null : 'text-muted' ?>">
        <div>
            <?= l('global.plan_settings.qr_is_enabled') ?>
        </div>

        <i class="fas fa-fw fa-sm <?= $data->plan_settings->qr_is_enabled ? 'fa-check text-success' : 'fa-times' ?>"></i>
    </div>

    <div class="d-flex justify-content-between align-items-center my-3 <?= $data->plan_settings->password_protection_is_enabled ? null : 'text-muted' ?>">
        <div>
            <?= l('global.plan_settings.password_protection_is_enabled') ?>
        </div>

        <i class="fas fa-fw fa-sm <?= $data->plan_settings->password_protection_is_enabled ? 'fa-check text-success' : 'fa-times' ?>"></i>
    </div>

    <div class="d-flex justify-content-between align-items-center my-3 <?= $data->plan_settings->removable_branding_is_enabled ? null : 'text-muted' ?>">
        <div>
            <?= l('global.plan_settings.removable_branding_is_enabled') ?>
        </div>

        <i class="fas fa-fw fa-sm <?= $data->plan_settings->removable_branding_is_enabled ? 'fa-check text-success' : 'fa-times' ?>"></i>
    </div>

    <div class="d-flex justify-content-between align-items-center my-3 <?= $data->plan_settings->custom_url_is_enabled ? null : 'text-muted' ?>">
        <div>
            <?= l('global.plan_settings.custom_url_is_enabled') ?>
        </div>

        <i class="fas fa-fw fa-sm <?= $data->plan_settings->custom_url_is_enabled ? 'fa-check text-success' : 'fa-times' ?>"></i>
    </div>

    <div class="d-flex justify-content-between align-items-center my-3 <?= $data->plan_settings->search_engine_block_is_enabled ? null : 'text-muted' ?>">
        <div>
            <?= l('global.plan_settings.search_engine_block_is_enabled') ?>
        </div>

        <i class="fas fa-fw fa-sm <?= $data->plan_settings->search_engine_block_is_enabled ? 'fa-check text-success' : 'fa-times' ?>"></i>
    </div>

    <div class="d-flex justify-content-between align-items-center my-3 <?= $data->plan_settings->custom_css_is_enabled ? null : 'text-muted' ?>">
        <div>
            <?= l('global.plan_settings.custom_css_is_enabled') ?>
        </div>

        <i class="fas fa-fw fa-sm <?= $data->plan_settings->custom_css_is_enabled ? 'fa-check text-success' : 'fa-times' ?>"></i>
    </div>

    <div class="d-flex justify-content-between align-items-center my-3 <?= $data->plan_settings->custom_js_is_enabled ? null : 'text-muted' ?>">
        <div>
            <?= l('global.plan_settings.custom_js_is_enabled') ?>
        </div>

        <i class="fas fa-fw fa-sm <?= $data->plan_settings->custom_js_is_enabled ? 'fa-check text-success' : 'fa-times' ?>"></i>
    </div>

    <?php if(settings()->stores->email_reports_is_enabled): ?>
        <div class="d-flex justify-content-between align-items-center my-3 <?= $data->plan_settings->email_reports_is_enabled ? null : 'text-muted' ?>">
            <div>
                <?= l('global.plan_settings.email_reports_is_enabled') ?>
            </div>

            <i class="fas fa-fw fa-sm <?= $data->plan_settings->email_reports_is_enabled ? 'fa-check text-success' : 'fa-times' ?>"></i>
        </div>
    <?php endif ?>

    <?php if(settings()->main->api_is_enabled): ?>
    <div class="d-flex justify-content-between align-items-center my-3 <?= $data->plan_settings->api_is_enabled ? null : 'text-muted' ?>">
        <div>
            <?= l('global.plan_settings.api_is_enabled') ?>
        </div>

        <i class="fas fa-fw fa-sm <?= $data->plan_settings->api_is_enabled ? 'fa-check text-success' : 'fa-times' ?>"></i>
    </div>
    <?php endif ?>

    <div class="d-flex justify-content-between align-items-center my-3 <?= $data->plan_settings->no_ads ? null : 'text-muted' ?>">
        <div>
            <?= l('global.plan_settings.no_ads') ?>
        </div>

        <i class="fas fa-fw fa-sm <?= $data->plan_settings->no_ads ? 'fa-check text-success' : 'fa-times' ?>"></i>
    </div>
</div>
