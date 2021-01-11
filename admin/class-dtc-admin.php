<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       unitycode.tech
 * @since      1.0.0
 *
 * @package    Dtc
 * @subpackage Dtc/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Dtc
 * @subpackage Dtc/admin
 * @author     jnz93 <contato@unitycode.tech>
 */
class Dtc_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		// Register plugin page
		add_action( 'admin_menu', array($this, 'create_plugin_menu') );

		// Insert short description on product action
		add_action('save_post', array($this, 'insert_short_description_product'), 10, 3);

		// Register ajax requisitions
		add_action('wp_ajax_tooc_count_click', array($this, 'tooc_count_click'));
		add_action('wp_ajax_nopriv_tooc_count_click', array($this, 'tooc_count_click'));

		// Ajax actions
		add_action('wp_ajax_dtc_products_by_store', array($this, 'dtc_get_products_by_storeid')); // executed when logged in
		add_action('wp_ajax_dtc_store_clicks_by_month', array($this, 'dtc_get_store_clicks_by_month')); // executed when logged in
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Dtc_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Dtc_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/dtc-admin.css', array(), $this->version, 'all' );
		wp_enqueue_style( 'uikit', plugin_dir_url( __FILE__ ) . 'css/uikit.min.css', array(), '3.6.5', 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Dtc_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Dtc_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/dtc-admin.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( 'uikit', plugin_dir_url( __FILE__ ) . 'js/uikit.min.js', array(), '3.6.5', false );
		wp_enqueue_script( 'uikit-icons', plugin_dir_url( __FILE__ ) . 'js/uikit-icons.min.js', array(), '3.6.5', false );

	}

	/**
	 * Create page admin of plugin
	 * 
	 * @since 1.0.0
	 */
	public function create_plugin_menu()
	{
		$page_title = 'Dashboard - TooComprando';
		$menu_title = 'Dashboard TooComprando';
		$menu_slug 	= 'dsb-tc';
		$capability = 10;
		$icon_url 	= 'dashicons-star-half';
		$position 	= 20;

		add_menu_page($page_title, $menu_title, $capability, $menu_slug, array($this, 'construct_plugin_page'), $icon_url, $position);
	}

	/**
	 * Construct page admin function
	 * 
	 * @since 1.0.0
	 */
	public function construct_plugin_page()
	{
		// include templates
		require_once plugin_dir_path( __FILE__ ) . 'partials/dtc-admin-display.php';
	}

	/**
	 * Handle count click and save by month and year
	 * 
	 * @since 1.0.0
	 */
	public function tooc_count_click()
	{
		# Product id
		$product_id 	= $_POST['productId'];

		# Sanitize month and year for the post_meta update
		$datenow 		= getdate();
		$string_date 	= $datenow['mon'] . '_' . $datenow['year'];
		$meta_key 		= 'chamar_whatsapp_clicks_' . $string_date;

		# Get curr value of clicks
		$curr_clicks 	= get_post_meta( $product_id, $meta_key, true );

		if ($curr_clicks == '') :
			$curr_clicks = 0;
		endif;

		$sum_clicks = $curr_clicks + 1;
		update_post_meta( $product_id, $meta_key, $sum_clicks );
	}

	/**
	 * Filtro: Cada novo produto cadastrado receberá um descrição curta padrão
	 * 
	 * @since 1.0.0
	 */
	public function insert_short_description_product($post_id, $post, $update)
	{
		if ( $update ) :
			return;
		endif;

		// Se não for product
		if ( 'product' !== $post->post_type ) :
			return;
		endif;

		if ( !wp_is_post_revision( $post_id ) ) :
			
			if ( strlen(get_the_excerpt( $post_id )) < 45 ) :
				$short_description = 'Preço sujeito a alteração sem aviso prévio!</br></br> Consultar Disponibilidade de Estoque e formas de pagamento clicando no botão "CHAMAR NO WHATSAPP".';
				wp_update_post( array('ID' => $post_id, 'post_excerpt' => $short_description));
			endif;

		endif;
	}

	/**
	 * Get all products of store and return table of clicks/hits on button "chamar Whatsapp"
	 * @param $store_id 
	 * 
	 * @since 1.0.0
	 */
	public function dtc_table_of_hits_by_store($store_id)
	{
		# Sanitize month and year for the post_meta update
		$datenow 		= getdate();
		$string_date 	= $datenow['mon'] . '_' . $datenow['year'];
		$meta_key 		= 'chamar_whatsapp_clicks_' . $string_date;

		// Criar método que recebe o id da loja e retorna a tabela
		// $products = wc_get_products( array(
		// 	'status'    => 'publish',
		// 	'limit'     => -1,
		// 	'author'    => $store_id,
		// 	'meta_key'	=> $meta_key,
		// 	'orderby'	=> 'meta_value_num',
		// 	'order'		=> 'DESC'
		// ) );

		$args = array(
			'post_type'	=> 'product',
			'status'    => 'publish',
			'author'    => $store_id,
			'orderby'	=> 'meta_value_num',
			'meta_key'	=> $meta_key,
			// 'order'		=> 'DESC',
			'limit'     => -1,
		);
		$products = new WP_Query($args);

		if ( $products->have_posts() ) :

			echo '<form>
						<div class="uk-margin">
							<label class="uk-form-label" for="form-stacked-text">Buscar produto</label>
							<div class="uk-form-controls uk-margin-small-top">
								<input class="uk-input uk-form-width-large" onkeypress="filterProducts(jQuery(this))" type="text" placeholder="Digite para pesquisar">
							</div>
						</div>
					</form>';
			echo '<table class="uk-table uk-table-striped"><thead><tr><th>Produto</th><th>Clicks - 01/21</th></tr></thead><tbody>';
			while ($products->have_posts()) :
				$products->the_post();
				$product_id = get_the_ID();
				$product_name = get_the_title( $product_id );
				$product_clicks = get_post_meta($product_id, $meta_key, true);
				$product_clicks = $product_clicks != '' ? $product_clicks : '0';

				echo '<tr data-name="'. $product_name .'"><td>'. $product_name .'</td><td>'. $product_clicks .'</td></tr>';
			endwhile;
			echo '</tbody></table>';
		else :
			echo 'O Lojista ainda não tem produtos cadastrados na plataforma.';
		endif;
	}

	/**
	 * Get all stores, count clicks by month and return the table
	 * 
	 * @since 1.0.0
	 */
	public function dtc_sanitized_stores($date)
	{
		$args = array(
			'role__in'  => 'wcfm_vendor'
		);
		$stores = get_users( $args );

		if( !empty($stores) ) :

			$list_stores_and_clicks = array();
			foreach( $stores as $store ) :
				$store_id   = $store->ID;
				$namearr    = explode('-', $store->user_nicename);
				$store_name = ucfirst(implode(' ', $namearr));
		
				$list_stores_and_clicks[] = array(
					'store_id'		=> $store_id,
					'store_name'    => $store_name,
					'clicks'        => Dtc_Admin::dtc_total_clicks_month($store_id, $date),
				);
			endforeach;

			return Dtc_Admin::array_sort($list_stores_and_clicks, 'clicks', SORT_DESC);
		endif;
	}
	
	/**
	 * Count clicks on btn "chamar whatsapp" mensal por loja
	 * @param $store_id
	 * @param $date = Ex: 01_2021
	 * 
	 * @since 1.0.0
	 */
	public function dtc_total_clicks_month($store_id, $date)
	{
		// Criar método que recebe o id da loja e retorna a tabela
		$products = wc_get_products( array(
			'status'    => 'publish',
			'limit'     => -1,
			'author'    => $store_id
		) );
		
		$total_clicks  = 0;
		if ( !empty($products) ) :
			// # Sanitize month and year for the post_meta update
			// $datenow 		= getdate();
			// $string_date 	= $datenow['mon'] . '_' . $datenow['year'];
			$meta_key 		= 'chamar_whatsapp_clicks_' . $date;

			foreach ( $products as $product ) :
				$product_id = $product->id;
				$product_clicks = get_post_meta($product_id, $meta_key, true);

				$total_clicks += $product_clicks;
			endforeach;

			return $total_clicks;
		endif;
	}

	/**
	 * Ajax call dtc get products by store
	 * 
	 * @since 1.0.0
	 */
	public function dtc_get_products_by_storeid()
	{
		# Store id by ajax call
		$store_name = $_POST['storeName'];
		$store_id = $_POST['storeId'];

		# Sanitize month and year for the post_meta update
		$datenow 		= getdate();
		$string_date 	= $datenow['mon'] . '_' . $datenow['year'];
		$meta_key 		= 'chamar_whatsapp_clicks_' . $string_date;

		# Query products by store_id
		$args = array(
			'post_type'	=> 'product',
			'status'    => 'publish',
			'author'    => $store_id,
			'meta_key'	=> $meta_key,
			'orderby'	=> 'meta_value_num',
			'order'		=> 'DESC',
			'limit'     => -1,
		);
		$products = new WP_Query($args);


		if ( $products->have_posts() ) :
			$table = '<h2 class="uk-modal-title">'. $store_name .'</h2><form><div class="uk-margin"><label class="uk-form-label" for="form-stacked-text">Buscar produto</label><div class="uk-form-controls uk-margin-small-top"><input class="uk-input uk-form-width-large" onkeypress="filterProducts(jQuery(this))" type="text" placeholder="Digite para pesquisar"></div></div></form><table class="uk-table uk-table-striped"><thead><tr><th>Produto</th><th>Clicks - 01/21</th></tr></thead><tbody>';

			while ($products->have_posts()) :
				$products->the_post();
				$product_id = get_the_ID();
				$product_name = get_the_title( $product_id );
				$product_clicks = get_post_meta($product_id, $meta_key, true);
				$product_clicks = $product_clicks != '' ? $product_clicks : '0';

				$table .= '<tr data-name="'. $product_name .'"><td>'. $product_name .'</td><td>'. $product_clicks .'</td></tr>';
			endwhile;
			$table .= '</tbody></table><button class="uk-modal-close uk-button uk-button-default" type="button">Fechar</button>';
		else :
			$table = '<p>Não encontramos hits nos produtos. Ou o lojista não cadastrou nenhum produto ainda.</p><button class="uk-modal-close uk-button uk-button-default" type="button">Fechar</button>';
		endif;

		echo $table;
	}

	/**
	 * Ajax call dtc get stores by month
	 * 
	 * @since 1.0.0
	 */
	public function dtc_get_store_clicks_by_month()
	{
		# Sanitize date
		$date_selected 	= $_POST['date'];
		$datearr 		= explode('-', $date_selected);
		$year 			= $datearr[0];
		$pos 			= strpos($datearr[1], '0');

		if ($pos == 0 ) :
			$month 			= str_replace('0', '', $datearr[1]);
		else :
			$month = $datearr[1];
		endif;

		$date 			= $month . '_' . $year;

		# Stores
		$stores = Dtc_Admin::dtc_sanitized_stores($date);
		$ajax_url 	= admin_url('admin-ajax.php');

		if (!empty($stores)) :
			foreach ($stores as $store) :
				$store_name = $store['store_name'];
				$store_id 	= $store['store_id'];
				$clicks 	= $store['clicks'];
				?>
				<tr data-name="<?php echo $store_name ?>">
					<td class="uk-table-link"><span class="uk-padding-small uk-padding-remove-top uk-padding-remove-right uk-padding-remove-bottom"><?php echo $store_name ?></span></td>
					<td class="uk-text-truncate"><?php echo $clicks ?></td>
					<td class="uk-text-nowrap"><button class="uk-button uk-button-default uk-button-small uk-margin-small uk-margin-small-right" uk-toggle="target: #modal-product" onclick="listProductsByStore('<?php echo addslashes($ajax_url) ?>', '<?php echo $store_name ?>', '<?php echo $store_id ?>')">Detalhes</button></td>
				</tr>
				<?php
			endforeach;
		endif;
	}

	/**
	 * sort array multidimensional
	 * 
	 * @param $array
	 * @param $on
	 * @param $order
	 * 
	 * @since 1.0.0 
	 */
	public function array_sort($array, $on, $order=SORT_ASC)
    {
        $new_array = array();
        $sortable_array = array();

        if (count($array) > 0) {
            foreach ($array as $k => $v) {
                if (is_array($v)) {
                    foreach ($v as $k2 => $v2) {
                        if ($k2 == $on) {
                            $sortable_array[$k] = $v2;
                        }
                    }
                } else {
                    $sortable_array[$k] = $v;
                }
            }

            switch ($order) {
                case SORT_ASC:
                    asort($sortable_array);
                break;
                case SORT_DESC:
                    arsort($sortable_array);
                break;
            }

            foreach ($sortable_array as $k => $v) {
                $new_array[$k] = $array[$k];
            }
        }

        return $new_array;
    }
}
