<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       unitycode.tech
 * @since      1.0.0
 *
 * @package    Dtc
 * @subpackage Dtc/admin/partials
 */

// get template  
// require_once plugin_dir_path( __FILE__ ) . 'templates/dtc-table-clicks-products.php'
?>
<div class="uk-section uk-section-muted">
    <div class="uk-container">
        <h1 class="uk-margin-large-bottom">Painel Analítico - toocomprando.com</h1>
        <ul uk-tab>
            <li><a href="#">Usuários Ativos</a></li>
            <li><a href="#">Cliques/Hits "Chamar WhatsApp"</a></li>
            <li><a href="#">Estatísticas de Buscas</a></li>
        </ul>

        <ul class="uk-switcher uk-margin">
            <!-- Add usuários ativos -->
            <li><?php require_once plugin_dir_path( __FILE__ ) . 'templates/tpl-statistics.php'; ?></li>
            <!-- Add lista de lojistas -->
            <li><?php require_once plugin_dir_path( __FILE__ ) . 'templates/tpl-table-stores.php'; ?></li>
            <!-- Search meter results -->
            <li><?php require_once plugin_dir_path( __FILE__ ) . 'templates/tpl-search-meter-results.php'; ?></li>
        </ul>
    </div>
</div>