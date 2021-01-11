<?php

/**
 * Provide a table of products and hits on button "chamar whatsapp"
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

<?php
# Sanitize month and year for the post_meta update
$datenow 	= getdate();
$date 	    = $datenow['mon'] . '_' . $datenow['year'];
$stores = Dtc_Admin::dtc_sanitized_stores($date);
if( !empty($stores) ) :
    ?>
    <form uk-grid>
        <div class="uk-margin uk-width-1-2">
            <label class="uk-form-label" for="search">Buscar loja</label>
            <div class="uk-form-controls uk-margin-small-top">
                <input id="search" class="uk-input uk-form-width-large" onkeypress="filterStores(jQuery(this).val())" type="text" placeholder="Digite para pesquisar">
            </div>
        </div>
        <div class="uk-width-1-4">
            <label class="uk-form-label" for="date-month">Filtrar por Mês</label>
            <div class="uk-form-controls uk-margin-small-top">
                <input type="month" min="2021-01" name="date-month" id="date-month" class="uk-input uk-form-width-large" onchange="listenChange(jQuery(this), '<?php echo admin_url('admin-ajax.php'); ?>')">
            </div>
        </div>
    </form>

    <div class="uk-overflow-auto">
        <table id="table-stores" class="uk-table uk-table-hover uk-table-middle uk-table-divider">
            <thead>
                <tr>
                    <th class="uk-table-expand">Loja</th>
                    <th class="uk-width-small">Hits/Cliques</th>
                    <th class="uk-table-shrink uk-text-nowrap"></th>
                </tr>
            </thead>
            <tbody>
                <?php
                $list_stores_and_clicks = array();
                foreach( $stores as $arr) :
                    $store_id       = $arr['store_id'];
                    $store_name     = $arr['store_name'];
                    $store_clicks   = $arr['clicks'];
                    ?>

                    <tr data-name="<?php echo $store_name ?>">
                        <td class="uk-table-link"><span class="uk-padding-small uk-padding-remove-top uk-padding-remove-right uk-padding-remove-bottom"><?php echo $store_name ?></span></td>
                        <td class="uk-text-truncate"><?php echo $store_clicks ?></td>
                        <td class="uk-text-nowrap"><button class="uk-button uk-button-default uk-button-small uk-margin-small uk-margin-small-right" uk-toggle="target: #modal-product" onclick="listProductsByStore('<?php echo admin_url('admin-ajax.php'); ?>', '<?php echo $store_name ?>', '<?php echo $store_id ?>')">Detalhes</button></td>
                    </tr>

                    <?php
                endforeach;
                ?>
            </tbody>
        </table>
    </div>
    <?php
endif;
?>
<!-- Modal Lista de produtos -->
<div id="modal-product" uk-modal>
    <div class="uk-modal-dialog uk-modal-body">
        
    </div>
</div>