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
		$clicks = get_post_meta('3335', 'chamar_whatsapp_clicks', true );
		$buttons = '<p uk-margin>
						<button class="uk-button uk-button-default uk-button-large" uk-toggle="target: #new-quiz">Novo Teste de Personalidade</button>
					</p>';

		$card_buttons = '<div class="uk-child-width-1-2@s uk-grid-match" uk-grid>
							<div>
								<div class="uk-card uk-card-default uk-card-body">
									<h3 class="uk-card-title">Dashboard TooComprando</h3>
									<p>BOLSA ESPORTE – ICONE(CLICKS): '. $clicks .'</p>
								</div>
							</div>
						</div>';
		
		// Outputs
		echo $card_buttons;

		// include templates
		// require_once plugin_dir_path( __FILE__ ) . 'partials/uc-qpt-new-quiz.php';
		// require_once plugin_dir_path( __FILE__ ) . 'partials/templates/uchb-register-customer.php';
		// require_once plugin_dir_path( __FILE__ ) . 'partials/templates/uchb-register-budget.php';
	}

	/**
	 * Handle count click
	 * 
	 * @since 1.0.0
	 */
	public function tooc_count_click()
	{
		$product_id = $_POST['productId'];
		$meta_key = 'chamar_whatsapp_clicks';
		$curr_clicks = get_post_meta( $product_id, $meta_key, true );
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
}
