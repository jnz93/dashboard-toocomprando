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
// Lojistas
$stores = get_users( [ 'role__in' => [ 'wcfm_vendor' ] ] );
?>
<div class="uk-child-width-1-1@s uk-margin-large-top" uk-grid>
    <div>
        <div uk-grid>
            <div class="uk-width-auto@m">
                <ul class="uk-tab-left" uk-tab="connect: #component-tab-left; animation: uk-animation-fade">
                    <?php 
                    if( !empty($stores) ) :
                        foreach( $stores as $store ) :
                            $namearr = explode('-', $store->user_nicename);
                            $name = ucfirst(implode(' ', $namearr));

                            echo '<li><a href="#">' . $name . '</a></li>';
                        endforeach;
                    endif;
                    ?>
                </ul>
            </div>
            <div class="uk-width-expand@m">
                <ul id="component-tab-left" class="uk-switcher">
                    <?php
                    # Sanitize month and year for the post_meta update
                    $datenow 		= getdate();
                    $string_date 	= $datenow['mon'] . '_' . $datenow['year'];
                    $meta_key 		= 'chamar_whatsapp_clicks_' . $string_date;
                    
                    if( !empty($stores) ) :
                        foreach( $stores as $store ) :
                            $store_id = $store->id;

                            // Criar método que recebe o id da loja e retorna a tabela
                            $products = wc_get_products( array(
                                'status'    => 'publish',
                                'limit'     => -1,
                                'author'    => $store_id
                            ) );

                            if ( !empty($products) ) :
                                echo '<li><table class="uk-table uk-table-striped"><thead><tr><th>Produto</th><th>Clicks</th></tr></thead><tbody>';
                                foreach ( $products as $product ) :
                                    $product_id = $product->id;
                                    $product_name = $product->name;
                                    $product_clicks = get_post_meta($product_id, $meta_key, true);
                                    $product_clicks = $product_clicks != '' ? $product_clicks : '0';

                                    echo '<tr><td>'. $product_name .'</td><td>'. $product_clicks .'</td></tr>';
                                endforeach;
                                echo '</tbody></table></li>';
                            else :
                                echo '<li>O Lojista ainda não tem produtos cadastrados na plataforma.</li>';
                            endif;
                        endforeach;
                    endif;
                    ?>
                </ul>
            </div>
        </div>
    </div>
</div>
