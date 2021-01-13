<?php
/**
 * Provide a statistics results of search by search meter plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       unitycode.tech
 * @since      1.0.0
 *
 * @package    Dtc
 * @subpackage Dtc/admin/partials/templates
 */
?>
<div class="uk-flex">
    <div class="uk-card uk-card-default uk-card-body uk-width-1-4@m uk-margin-small-right">
        <h3 class="uk-card-title">Ontem e Hoje</h3>
        <?php tguy_sm_summary_table(1); ?>
    </div>
    <div class="uk-card uk-card-default uk-card-body uk-width-1-4@m uk-margin-small-right">
        <h3 class="uk-card-title">Últimos 7 dias</h3>
        <?php tguy_sm_summary_table(7); ?>
    </div>
    <div class="uk-card uk-card-default uk-card-body uk-width-1-4@m uk-margin-small-right">
        <h3 class="uk-card-title">Últimos 30 dias</h3>
        <?php tguy_sm_summary_table(30); ?>
    </div>
</div>