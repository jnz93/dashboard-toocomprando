<?php

/**
 * Provide the admin page of plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       unitycode.tech
 * @since      1.0.0
 *
 * @package    Dtc
 * @subpackage Dtc/admin/partials/templates/
 */
?>
<div class="uk-child-width-1-1@s uk-margin-large-top" uk-grid>
    <div>
        <div uk-grid>
            <div class="uk-width-auto@m">
                <ul class="uk-tab-left" uk-tab="connect: #component-tab-left; animation: uk-animation-fade">
                    <li><a href="#">Clicks/Hits "Chamar no Whatsapp"</a></li>
                    <li><a href="#">Usuários ativos no site</a></li>
                </ul>
            </div>
            <div class="uk-width-expand@m">
                <ul id="component-tab-left" class="uk-switcher">
                    <li><?php require_once plugin_dir_path( __FILE__ ) . 'templates/dtc-table-clicks-products.php' ?></li>
                    <li>Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</li>
                </ul>
            </div>
        </div>
    </div>
</div>