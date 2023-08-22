<?php
add_action( 'after_setup_theme', 'hansa_setup' );
if ( ! function_exists( 'hansa_setup' ) ) {
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function hansa_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on custom, use a find and replace
		 * to change 'custom' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'custom', get_template_directory() . '/languages' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		add_theme_support ('align-wide');

		add_theme_support('woocommerce');

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus(
			array(
				'top-menu' => esc_html__( 'Top menu', 'custom' ),
				'main-menu-mobile' => esc_html__( 'Mobile menu', 'custom' ),
			)
		);


		// Set up the WordPress core custom background feature.
		add_theme_support( 'custom-background', apply_filters( 'custom_custom_background_args', array(
			'default-color' => 'ffffff',
			'default-image' => '',
		) ) );

		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		/**
		 * Add support for core custom logo.
		 *
		 * @link https://codex.wordpress.org/Theme_Logo
		 */
		add_theme_support( 'custom-logo', array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		) );
	}
}


/** Including theme styles and scripts */
add_action( 'wp_enqueue_scripts', 'hansa_enqueue_scripts', 15 );
if ( ! function_exists( 'hansa_enqueue_scripts' ) ) {
	function hansa_enqueue_scripts() {

		// Juery-UI styles
		$file_url = '//cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.css';
		wp_enqueue_style( 'theme-jquery-ui', $file_url, array(), null );

		// Main styles
		$file_url = '/assets/css/main.css';
		wp_enqueue_style( 'theme-main', CUSTOM_THEME_URI . $file_url, array(), filemtime(CUSTOM_THEME_PATH . $file_url) );

		// Main styles
		$file_url = '/assets/libs/intl-tel-input/css/intlTelInput.css';
		wp_enqueue_style( 'theme-intl-tel', CUSTOM_THEME_URI . $file_url, array(), filemtime(CUSTOM_THEME_PATH . $file_url) );



		//Media styles
		$file_url = '/assets/css/media.css';
		wp_enqueue_style( 'theme-media', CUSTOM_THEME_URI . $file_url, array(), filemtime(CUSTOM_THEME_PATH . $file_url) );

		// WP styles
		$file_url = '/style.css';
		wp_enqueue_style( 'theme-general', CUSTOM_THEME_URI . $file_url, array(), filemtime(CUSTOM_THEME_PATH . $file_url) );


		// Scripts
		$is_loyale_script = false;
		$is_logged_in = is_user_logged_in();
		if(!$is_logged_in) {
			if(is_page_template('templates/sign-in-tpl.php')) {
				$is_loyale_script = true;
			}
		} else {
			$loyale_id = get_user_meta(get_current_user_id(),'loyale_customer_id',true);
			if(!$loyale_id && is_wc_endpoint_url( 'edit-account' )) {
				$is_loyale_script = true;
			}
		}
		if($is_loyale_script) {
			wp_enqueue_script('loyale-scripts', '//web.loyale.io/loyale-sso-1.1.js' , array(), null, true);
		}

		$file_url = '/assets/libs/intl-tel-input/js/intlTelInput.js';
		wp_enqueue_script('theme-intl-tel', CUSTOM_THEME_URI . $file_url, array(), filemtime(CUSTOM_THEME_PATH . $file_url), true);

		// Clipboard.js
		$file_url = '/assets/js/clipboard.min.js';
		wp_enqueue_script('theme-clipboard', CUSTOM_THEME_URI . $file_url, array('jquery'), filemtime(CUSTOM_THEME_PATH . $file_url), true);

		// Lightslider.js
		$file_url = '/assets/js/lightslider.js';
		wp_enqueue_script('theme-lightslider', CUSTOM_THEME_URI . $file_url, array('jquery'), filemtime(CUSTOM_THEME_PATH . $file_url), true);

		// Owlcarousel.js
		$file_url = '/assets/js/owl.carousel.min.js';
		wp_enqueue_script('theme-owlcar', CUSTOM_THEME_URI . $file_url, array('jquery'), filemtime(CUSTOM_THEME_PATH . $file_url), true);

		// Jquery-ui.js
		$file_url = '//cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js';
		wp_enqueue_script('theme-jquery-ui', $file_url, array('jquery'), null, true);

		// Front scripts
		$file_url = '/assets/js/scripts.js';
		wp_enqueue_script('theme-scripts', CUSTOM_THEME_URI . $file_url, array('jquery'), filemtime(CUSTOM_THEME_PATH . $file_url), true);

		// Jquery.cookies
		$file_url = '/assets/js/jquery.cookie.js';
		wp_enqueue_script('jquery-cookies', CUSTOM_THEME_URI . $file_url, array('jquery'), filemtime(CUSTOM_THEME_PATH . $file_url), true);

		// WP scripts
		$file_url = '/assets/js/wp-scripts.js';
		wp_enqueue_script('theme-wp-scripts', CUSTOM_THEME_URI . $file_url, array('jquery'), filemtime(CUSTOM_THEME_PATH . $file_url), true);

		if(is_checkout()) {
			// Currency.js
			$file_url = '/assets/js/simple-mask-money.js';
			wp_enqueue_script('wp-mask-money', CUSTOM_THEME_URI . $file_url, array(), filemtime(CUSTOM_THEME_PATH . $file_url), true);
			// Checkout.js
			$file_url = '/assets/js/checkout.js';
			wp_enqueue_script('wp-checkout', CUSTOM_THEME_URI . $file_url, array('jquery','wp-mask-money', 'theme-wp-scripts'), filemtime(CUSTOM_THEME_PATH . $file_url), true);
		}

		$localized_vars = array(
			'ajax_url' => admin_url( 'admin-ajax.php' ),
			'currency' => get_woocommerce_currency_symbol()
		);

		if($is_loyale_script) {
			$loyale_vars = array(
				'scheme_id' => get_field('loyale_scheme_id','option'),
				'success_url' => site_url() . '/wp-json/sso-login/v1/token',
				'error_url' => site_url(),
				'env' => 'production',
				'ajax_url' => admin_url( 'admin-ajax.php' )
			);
			$localized_vars = array_merge($localized_vars,$loyale_vars);
		}

		wp_localize_script( 'theme-wp-scripts', 'backend_vars', $localized_vars);
		wp_localize_script( 'wp-checkout', 'backend_vars', $localized_vars);

		if(is_page_template('templates/sign-in-tpl.php')) {
			// Sign in scripts
			$file_url = '/assets/js/register-form.js';
			wp_enqueue_script('theme-wp-register', CUSTOM_THEME_URI . $file_url, array('jquery'), filemtime(CUSTOM_THEME_PATH . $file_url), true);
		}
		wp_localize_script( 'theme-wp-register', 'back_vars', array(
			'ajax_url' => admin_url( 'admin-ajax.php' )
		));
	}
}

if( function_exists('acf_add_options_page') ) {
	acf_add_options_page(array(
		'page_title' 	=> 'Theme General Settings',
		'menu_title'	=> 'Theme Settings',
		'menu_slug' 	=> 'theme-general-settings',
		'capability'	=> 'edit_posts',
		'redirect'		=> false
	));

	acf_add_options_sub_page(array(
		'page_title' 	=> 'Theme Header Settings',
		'menu_title'	=> 'Header',
		'parent_slug'	=> 'theme-general-settings',
	));

	acf_add_options_sub_page(array(
		'page_title' 	=> 'Theme Footer Settings',
		'menu_title'	=> 'Footer',
		'parent_slug'	=> 'theme-general-settings',
	));

	acf_add_options_sub_page(array(
		'page_title' 	=> 'Loyale Settings',
		'menu_title'	=> 'Loyale',
		'parent_slug'	=> 'theme-general-settings',
	));

}

function hansa_register_blocks() {

    // Проверяем, что функция доступна.
    if( function_exists( 'acf_register_block_type' ) ) {

        // Регистрируем блок рекомендаций.
        acf_register_block_type(array(
            'name'              => 'home-slider',
            'title'             => __('Home slider'),
            'description'       => __('A custom testimonial block.'),
            'render_template'   => 'template-parts/blocks/home-slider.php',
            'category'          => 'formatting',
            'mode'				=> 'edit'
        ));
    }
}
add_action( 'acf/init', 'hansa_register_blocks' );

// Add custom user profile fields
add_action( 'show_user_profile', 'hansa_add_custom_user_profile_fields' );
add_action( 'edit_user_profile', 'hansa_add_custom_user_profile_fields' );
function hansa_add_custom_user_profile_fields( $user ) {
?>
	<h2>Additional information</h2>
    <table id="custom_user_field_table" class="form-table">
        <tr id="custom_user_field_row">
            <th>
                <label for="custom_field"><?php _e('Date of birth', 'hansa'); ?></label>
            </th>
            <td>
                <input type="date" name="birthdate" id="birthdate" value="<?php echo esc_attr( get_the_author_meta( 'birthdate', $user->ID ) ); ?>" class="regular-text" />
            </td>
        </tr>
    </table>
	<h2>B2B Customer</h2>
    <table id="custom_user_field_table" class="form-table">
        <tr id="custom_user_field_row">
            <th>
                <label for="is_approved"><?php _e('Is approved?', 'hansa'); ?></label>
            </th>
            <td>
                <input type="checkbox" name="is_approved" id="is_approved" value="yes" <?php echo get_user_meta($user->ID, 'is_approved', true) ? 'checked' : ''; ?> />
            </td>
        </tr>
        <tr id="custom_user_field_row">
            <th>
                <label for="user_pricelist"><?php _e('Pricelist Name', 'hansa'); ?></label>
            </th>
            <td>
                <select type="text" name="user_pricelist" id="user_pricelist">
                	<?php
                		$selected_pl = get_user_meta($user->ID, 'user_pricelist', true);
                		$selected_pl_data = explode('-',$selected_pl);
                		$selected_pl_id = intval(array_shift($selected_pl_data));
                		$pricelists = array_map('trim',explode(PHP_EOL,get_option('wc_settings_tab_import_pricelists')));
                		foreach($pricelists as $pl) {
                			$pl_data = explode('-',$pl);
                			$pl_id = array_shift($pl_data);
                			if(!empty($pl)) {
                				if(is_int($selected_pl_id) && $selected_pl) {
                					echo '<option ' . ($pl_id == $selected_pl_id ? 'selected ' : '') .'value="' . $pl . '">' . $pl . '</option>';
                				} else {
                					echo '<option ' . (2 == $pl_id ? 'selected ' : '') .'value="' . $pl . '">' . $pl . '</option>';
                				}
                			}
                		}
                	?>
                </select>
            </td>
        </tr>
    </table>
<?php
}

// Save custom user profile fields
add_action('personal_options_update', 'hansa_save_custom_user_profile_fields');
add_action( 'edit_user_profile_update', 'hansa_save_custom_user_profile_fields' );
function hansa_save_custom_user_profile_fields( $user_id ) {
    if(isset($_POST['birtdate'])) {
    	$custom_data = $_POST['birtdate'];
    	update_user_meta( $user_id, 'birtdate', $custom_data );
    }
    if(isset($_POST['user_pricelist'])) {
    	update_user_meta( $user_id, 'user_pricelist', $_POST['user_pricelist']);
    }
    if(isset($_POST['is_approved'])) {
    	update_user_meta( $user_id, 'is_approved', $_POST['is_approved']);
    } else {
    	update_user_meta( $user_id, 'is_approved', false );
    }
}

// Add categories setting for displaying taxonomies
add_action('product_cat_edit_form_fields','edit_form_fields',5);
add_action('product_cat_edit_form', 'edit_form');
add_action('product_cat_add_form_fields','add_form_fields');
add_action('product_cat_add_form','edit_form');

function edit_form() {
 // your desired code
}

function add_form_fields ($tag) {
    $taxonomies = get_taxonomies(array('_builtin' => false),'objects');
    $exclude_tax = array('product_type','product_visibility','product_cat','product_tag','product_shipping_class','pa_size');
    $parent = 0;
    if(!taxonomy_exists($tag)) {
    	$parent = $tag->parent;
	    $term_id = $tag->term_id;
	    $related_meta = get_option( "related_tax_$term_id");
    } else {
    	$related_meta = array();
    }
    if($parent == 0) {
?>
  	<div class="form-field term-related-taxes">
        <div valign="top" scope="row">
            <label for="catpic"><?php _e('Disabled taxomonies', ''); ?></label>
        </div>
        <div>
    	<?php foreach($taxonomies as $key=>$value) {
    		$selected = false;
    		if(in_array($value->name, $exclude_tax)) {
    			continue;
    		}
    		if(is_array($related_meta)) {
        		if(in_array($value->name,$related_meta)) {
        			$selected = true;
        		}
    		}
		?>
    		<span style="display: flex; align-items: center;">
    			<input type="checkbox" name="related_tax[]" value="<?php echo $value->name; ?>" <?php echo $selected ? 'checked' : ''; ?>>
    		<label for=""><?php echo $value->label; ?></label><br><br>
    		</span>
    	<?php } ?>
            <span class="description">Select related taxonomies for category (will be shown in filters)</span>
        </div>
    </div>
<?php
    }
}

function edit_form_fields ($tag) {
    $taxonomies = get_taxonomies(array('_builtin' => false),'objects');
    $exclude_tax = array('product_type','product_visibility','product_cat','product_tag','product_shipping_class','pa_size');
    $parent = 0;
    if(!taxonomy_exists($tag)) {
    	$parent = $tag->parent;
	    $term_id = $tag->term_id;
	    $related_meta = get_option( "related_tax_$term_id");
    } else {
    	$related_meta = array();
    }
    if($parent == 0) {
?>
  	<tr class="form-field term-related-taxes">
        <th valign="top" scope="row">
            <label for="catpic"><?php _e('Disabled taxomonies', ''); ?></label>
        </th>
        <td>
    	<?php foreach($taxonomies as $key=>$value) {
    		$selected = false;
    		if(in_array($value->name, $exclude_tax)) {
    			continue;
    		}
    		if(is_array($related_meta)) {
        		if(in_array($value->name,$related_meta)) {
        			$selected = true;
        		}
    		}
		?>
    		<span style="display: flex; align-items: center;">
    			<input type="checkbox" name="related_tax[]" value="<?php echo $value->name; ?>" <?php echo $selected ? 'checked="true"' : ''; ?>>
    		<label for=""><?php echo $value->label; ?></label><br><br>
    		</span>
    	<?php } ?>
            <span class="description">Select related taxonomies for category (will be shown in filters)</span>
        </td>
    </tr>
<?php
    }
}

add_action ( 'edited_product_cat', 'save_extra_fileds');
add_action('created_product_cat','save_extra_fileds');
// save extra category extra fields callback function
function save_extra_fileds( $term_id ) {
    if ( isset( $_POST['related_tax'] ) ) {
        $termid = $term_id;
        $cat_meta = get_option( "related_tax_$termid");
    	if(empty($_POST['related_tax'])) {
    		delete_option(  "related_tax_$termid" );
    	} else {
	        if ($cat_meta !== false ) {
	            update_option(  "related_tax_$termid",$_POST['related_tax']  );
	        } else {
	             add_option(  "related_tax_$termid",$_POST['related_tax'] );
        	}
    	}
    } else {
    }
}

// when a category is removed
add_filter('deleted_term_taxonomy', 'remove_tax_Extras');
function remove_tax_Extras($term_id) {
    $termid = $term_id;
	if($_POST['taxonomy'] == '{$taxonomy}'):
		if(get_option( "related_tax_$termid"))
		    delete_option( "related_tax_$termid");
	endif;
}

function hansa_js_reset_related_tax() {
	$gift_cat = get_term_by('slug', 'gifts', 'product_cat');
	$gift_cat_id = $gift_cat->term_id;
?>
<script>
    let giftCatId = <?php echo $gift_cat_id; ?>;
	(function($){
	  $.deparam = $.deparam || function(uri){
	    if(uri === undefined){
	      uri = window.location.search;
	    }
	    let queryString = {};
	    uri.replace(
	      new RegExp(
	        "([^?=&]+)(=([^&#]*))?", "g"),
	        function($0, $1, $2, $3) { queryString[$1] = $3; }
	      );
	      return queryString;
	    };
		$(document).ajaxSuccess(function(e, request, settings){
		    var object = $.deparam(settings.data);
		    if(object.action === 'acf%2Fvalidate_save_post' && object.screen === 'edit-product_cat' && object.taxonomy === 'product_cat'){
		        $('input[name="related_tax[]"]').prop('checked',false);
		    }
		});

		$('.term-parent-wrap select').on('change',function(){
			let value = $(this).val();
			if(value != -1) {
				$('.term-related-taxes').hide();
		        $('input[name="related_tax[]"]').prop('checked',false);
			} else {
				$('.term-related-taxes').show();
			}
		});

		if($('p.is_custom_message_field').length) {
			if(!$('.is_gift_field input').prop('checked')) {
				$('p.is_custom_message_field').hide();
			}
		}

		$('.is_gift_field input').on('change',function(){
			if(!$(this).prop('checked')) {
				$('p.is_custom_message_field').hide();
			} else {
				$('p.is_custom_message_field').show();
			}
		});
	})(jQuery);
</script>
<?php
}
add_action( 'admin_footer', 'hansa_js_reset_related_tax' ); // For back-end

// Disable CF7 paragraphs
add_filter('wpcf7_autop_or_not', '__return_false');

// Move Yoast to bottom
function yoasttobottom() {
	return 'low';
}
add_filter( 'wpseo_metabox_prio', 'yoasttobottom');

add_action('admin_head', 'hansa_admin_styles');
function hansa_admin_styles() {
  echo '<style>
    #profile-page h2 {
  		margin-top: 4rem;
	}
  </style>';
}

// Redirect ot home on logout
add_action('wp_logout','auto_redirect_after_logout');
function auto_redirect_after_logout(){
  wp_safe_redirect( home_url() );
  exit;
}

add_filter( 'nav_menu_link_attributes', 'hansa_menu_add_class', 10, 3 );
function hansa_menu_add_class( $classes, $item, $args ) {
    if(isset($args->add_link_class)) {
        $classes['class'] = $args->add_link_class;
    }
    return $classes;
}