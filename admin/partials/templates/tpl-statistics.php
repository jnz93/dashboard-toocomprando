<?php

/**
 * Provide a data of user online on site
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       unitycode.tech
 * @since      1.0.0
 *
 * @package    Dtc
 * @subpackage Dtc/admin/partials/templates
 */

$days_of_month  = date('j');
?>
<div class="uk-flex">
    <div class="uk-card uk-card-default uk-card-body uk-width-1-3 uk-margin-right">
        <h4 class="uk-card-title">Usuários online</h4>
        <h1 class="uk-text-bolder"><?php echo wp_statistics_useronline() ?></h1>
    </div>
    <div class="uk-card uk-card-default uk-card-body uk-width-1-3">
        <h4 class="uk-card-title">Visitantes no mês</h4>
        <h1 class="uk-text-bolder"><?php echo wp_statistics_visitor('-' . $days_of_month); ?></h1>
    </div>
</div>