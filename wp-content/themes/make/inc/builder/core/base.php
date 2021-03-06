<?php
/**
 * @package Make
 */

if ( ! function_exists( 'TTFMAKE_Builder_Base' ) ) :
/**
 * Defines the functionality for the HTML Builder.
 *
 * @since 1.0.0.
 */
class TTFMAKE_Builder_Base {
	/**
	 * The one instance of TTFMAKE_Builder_Base.
	 *
	 * @since 1.0.0.
	 *
	 * @var   TTFMAKE_Builder_Base
	 */
	private static $instance;

	/**
	 * Instantiate or return the one TTFMAKE_Builder_Base instance.
	 *
	 * @since  1.0.0.
	 *
	 * @return TTFMAKE_Builder_Base
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Initiate actions.
	 *
	 * @since  1.0.0.
	 *
	 * @return TTFMAKE_Builder_Base
	 */
	public function __construct() {
		// Include the API
		require_once get_template_directory() . '/inc/builder/core/api.php';

		// Include the configuration helpers
		require_once get_template_directory() . '/inc/builder/core/configuration-helpers.php';

		// Add the core sections
		require_once get_template_directory() . '/inc/builder/sections/section-definitions.php';

		// Include the save routines
		require_once get_template_directory() . '/inc/builder/core/save.php';

		// Include the front-end helpers
		require_once get_template_directory() . '/inc/builder/sections/section-front-end-helpers.php';

		// Set up actions
		add_action( 'admin_init', array( $this, 'register_post_type_support_for_builder' ) );
		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ), 1 ); // Bias toward top of stack
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ), 11 );
		add_action( 'admin_print_styles-post.php', array( $this, 'admin_print_styles' ) );
		add_action( 'admin_print_styles-post-new.php', array( $this, 'admin_print_styles' ) );
		add_filter( 'admin_body_class', array( $this, 'admin_body_class' ) );
		add_action( 'admin_footer', array( $this, 'print_templates' ) );
		add_action( 'post_submitbox_misc_actions', array( $this, 'builder_toggle' ) );
	}

	/**
	 * Add support for post types to use the Make builder.
	 *
	 * @since  1.3.0.
	 *
	 * @return void
	 */
	public function register_post_type_support_for_builder() {
		add_post_type_support( 'page', 'make-builder' );
	}

	/**
	 * Add the meta box.
	 *
	 * @since  1.0.0.
	 *
	 * @return void
	 */
	public function add_meta_boxes() {
		foreach ( ttfmake_get_post_types_supporting_builder() as $name ) {
			add_meta_box(
				'ttfmake-builder',
				esc_html__( 'Page Builder', 'make' ),
				array( $this, 'display_builder' ),
				$name,
				'normal',
				'high'
			);
		}
	}

	/**
	 * Display the checkbox to turn the builder on or off.
	 *
	 * @since  1.2.0.
	 *
	 * @return void
	 */
	public function builder_toggle() {
		// Do not show the toggle for pages as the builder is controlled by page templates
		if ( 'page' === get_post_type() ) {
			return;
		}

		// Only show the builder toggle for CPTs that support the builder
		if ( ! ttfmake_post_type_supports_builder( get_post_type() ) ) {
			return;
		}

		$using_builder = get_post_meta( get_the_ID(), '_ttfmake-use-builder', true );
	?>
		<div class="misc-pub-section">
			<input type="checkbox" value="1" name="use-builder" id="use-builder"<?php checked( $using_builder, 1 ); ?> />
			&nbsp;<label for="use-builder"><?php esc_html_e( 'Use Page Builder', 'make' ); ?></label>
		</div>
	<?php
	}

	/**
	 * Display the meta box.
	 *
	 * @since  1.0.0.
	 *
	 * @param  WP_Post    $post_local    The current post object.
	 * @return void
	 */
	public function display_builder( $post_local ) {
		wp_nonce_field( 'save', 'ttfmake-builder-nonce' );

		// Get the current sections
		global $ttfmake_sections;
		$ttfmake_sections = get_post_meta( $post_local->ID, '_ttfmake-sections', true );
		$ttfmake_sections = ( is_array( $ttfmake_sections ) ) ? $ttfmake_sections : array();

		// Load the boilerplate templates
		get_template_part( 'inc/builder/core/templates/menu' );
		get_template_part( 'inc/builder/core/templates/stage', 'header' );

		$section_data        = ttfmake_get_section_data( $post_local->ID );
		$registered_sections = ttfmake_get_sections();

		// Print the current sections
		foreach ( $section_data as $section ) {
			/**
			 * In Make 1.4.0, the blank section was deprecated. Any existing blank sections are converted to 1 column,
			 * text sections.
			 */
			if ( isset( $section['section-type'] ) && 'blank' === $section['section-type'] && isset( $registered_sections['text'] ) ) {
				// Convert the data for the section
				$content = ( ! empty( $section['content'] ) ) ? $section['content'] : '';
				$title   = ( ! empty( $section['title'] ) ) ? $section['title'] : '';
				$label   = ( ! empty( $section['label'] ) ) ? $section['label'] : '';
				$state   = ( ! empty( $section['state'] ) ) ? $section['state'] : 'open';
				$id      = ( ! empty( $section['id'] ) ) ? $section['id'] : time();

				// Set the data
				$section = array(
					'id'             => $id,
					'state'          => $state,
					'section-type'   => 'text',
					'title'          => $title,
					'label'          => $label,
					'columns-number' => 1,
					'columns-order'  => array(
						0 => 1,
						1 => 2,
						2 => 3,
						3 => 4,
					),
					'columns'        => array(
						1 => array(
							'id'	   => '',
							'title'    => '',
							'image-id' => 0,
							'content'  => $content,
							''
						),
						2 => array(
							'id'	   => '',
							'title'    => '',
							'image-id' => 0,
							'content'  => '',
							''
						),
						3 => array(
							'id'	   => '',
							'title'    => '',
							'image-id' => 0,
							'content'  => '',
							''
						),
						4 => array(
							'id'	   => '',
							'title'    => '',
							'image-id' => 0,
							'content'  => '',
							''
						),
					)
				);
			}
		}

		get_template_part( 'inc/builder/core/templates/stage', 'footer' );

		// Add the sort input
		$section_order = get_post_meta( $post_local->ID, '_ttfmake-section-ids', true );

		// Handle legacy section order, if present
		if ( ! empty( $section_order ) ) {
			$ordered_sections = array();

			foreach ( $section_order as $section_id ) {
				array_push( $ordered_sections, $section_data[$section_id] );
			}

			$section_data = $ordered_sections;
		}

		// Expose section defaults to JS
		wp_localize_script( 'ttfmake-builder', 'ttfMakeSectionDefaults', ttfmake_get_section_definitions()->get_section_defaults() );
		// Expose saved sections data to JS
		wp_localize_script( 'ttfmake-builder', 'ttfMakeSectionData', ttfmake_get_section_json_data( $section_data ) );

		// Fetch templates
		$templates = array();
		foreach ( ttfmake_get_sections() as $section ) {
			$template = $this->load_section( $section, array(), true );

			if ( !is_array( $template ) ) {
				$templates[$section['id']] = $template;
			} else {
				$templates = array_merge( $templates, $template );
			}
		}

		// Expose section template strings to JS
		wp_localize_script( 'ttfmake-builder', 'ttfMakeSectionTemplates', $templates );
	}

	/**
	 * Enqueue the JS and CSS for the admin.
	 *
	 * @since  1.0.0.
	 *
	 * @param  string    $hook_suffix    The suffix for the screen.
	 * @return void
	 */
	public function admin_enqueue_scripts( $hook_suffix ) {
		// Only load resources if they are needed on the current page
		if ( ! in_array( $hook_suffix, array( 'post.php', 'post-new.php' ) ) || ! ttfmake_post_type_supports_builder( get_post_type() ) ) {
			return;
		}

		// Enqueue the CSS
		wp_enqueue_style(
			'ttfmake-builder',
			Make()->scripts()->get_css_directory_uri() . '/builder/core/builder.css',
			array(),
			TTFMAKE_VERSION
		);

		wp_enqueue_style( 'wp-color-picker' );

		// Dependencies regardless of min/full scripts
		$dependencies = array(
			'wplink',
			'utils',
			'media-views',
			'wp-color-picker',
			'jquery-effects-core',
			'jquery-ui-sortable',
			'backbone',
		);

		wp_register_script(
			'ttfmake-builder/js/models/section.js',
			Make()->scripts()->get_js_directory_uri() . '/builder/core/models/section.js',
			array(),
			TTFMAKE_VERSION,
			true
		);

		wp_register_script(
			'ttfmake-builder/js/collections/sections.js',
			Make()->scripts()->get_js_directory_uri() . '/builder/core/collections/sections.js',
			array(),
			TTFMAKE_VERSION,
			true
		);

		wp_register_script(
			'ttfmake-builder/js/views/menu.js',
			Make()->scripts()->get_js_directory_uri() . '/builder/core/views/menu.js',
			array(),
			TTFMAKE_VERSION,
			true
		);

		wp_register_script(
			'ttfmake-builder/js/views/section.js',
			Make()->scripts()->get_js_directory_uri() . '/builder/core/views/section.js',
			array(),
			TTFMAKE_VERSION,
			true
		);

		wp_register_script(
			'ttfmake-builder/js/views/overlay.js',
			Make()->scripts()->get_js_directory_uri() . '/builder/core/views/overlay.js',
			array(),
			TTFMAKE_VERSION,
			true
		);

		/**
		 * Filter the dependencies for the Make builder JS.
		 *
		 * @since 1.2.3.
		 *
		 * @param array    $dependencies    The list of dependencies.
		 */
		$dependencies = apply_filters(
			'make_builder_js_dependencies',
			array_merge(
				$dependencies,
				array(
					'ttfmake-builder/js/models/section.js',
					'ttfmake-builder/js/collections/sections.js',
					'ttfmake-builder/js/views/menu.js',
					'ttfmake-builder/js/views/section.js',
					'ttfmake-builder/js/views/overlay.js'
				)
			)
		);

		wp_enqueue_script(
			'ttfmake-builder',
			Make()->scripts()->get_js_directory_uri() . '/builder/core/app.js',
			$dependencies,
			TTFMAKE_VERSION,
			true
		);

		// Fetch color palette
		$color_settings = array( 'color-primary', 'color-secondary', 'color-text', 'color-detail', 'color-primary-link', 'color-button-background', 'background_color', 'main-background-color' );

		$color_values = array_map( array( Make()->thememod(), 'get_value' ), $color_settings );
		$color_values = array_values( $color_values );
		$color_values = array_unique( $color_values );
		$color_values = array_filter( $color_values, 'strlen' );

		// Add data needed for the JS
		$data = array(
			'pageID'        => get_the_ID(),
			'postRefresh'   => true,
			'confirmString' => esc_html__( 'Are you sure you want to trash this section permanently?', 'make' ),
			'palettes'      => $color_values
		);

		wp_localize_script(
			'ttfmake-builder',
			'ttfmakeBuilderData',
			$data
		);

		// Modifications to Edit Page UI
		global $pagenow;

		wp_enqueue_script(
			'ttfmake-builder-edit-page',
			Make()->scripts()->get_js_directory_uri() . '/builder/core/edit-page.js',
			array( 'jquery' ),
			TTFMAKE_VERSION,
			true
		);

		$data = array(
			'featuredImage' => esc_html__( 'Note: the Builder Template does not display a featured image.', 'make' ),
			'pageNow'       => esc_js( $pagenow ),
		);

		/**
		 * Filter: Modify whether new pages default to the Builder template.
		 *
		 * @since 1.7.0.
		 *
		 * @param bool $is_default
		 */
		$data['defaultTemplate'] = apply_filters( 'make_builder_is_default', true );

		wp_localize_script(
			'ttfmake-builder-edit-page',
			'ttfmakeEditPageData',
			$data
		);

		// Expose Make Plus version number, if active
		$installed_plugins = get_plugins();

		if ( Make()->plus()->is_plus() ) {
			$plugin_data = array('Version' => Make()->plus()->get_plus_version() );
			wp_localize_script( 'ttfmake-builder-edit-page', 'makePlusPluginInfo', $plugin_data );
		}
	}

	/**
	 * Print additional, dynamic CSS for the builder interface.
	 *
	 * @since  1.0.0.
	 *
	 * @return void
	 */
	public function admin_print_styles() {
		global $pagenow;

		// Do not complete the function if the product template is in use (i.e., the builder needs to be shown)
		if ( ! ttfmake_post_type_supports_builder( get_post_type() ) ) {
			return;
		}
	?>
		<style type="text/css">
			<?php if ( 'post-new.php' === $pagenow || ( 'post.php' === $pagenow && ttfmake_is_builder_page() ) ) : ?>
			#postdivrich {
				display: none;
			}
			<?php else : ?>
			#ttfmake-builder {
				display: none;
			}
			.ttfmake-duplicator {
				display: none;
			}
			<?php endif; ?>

			<?php foreach ( ttfmake_get_sections() as $key => $section ) : ?>
			#ttfmake-menu-list-item-link-<?php echo esc_attr( $section['id'] ); ?> .ttfmake-menu-list-item-link-icon-wrapper {
				background-image: url(<?php echo addcslashes( esc_url_raw( $section['icon'] ), '"' ); ?>);
			}
			<?php endforeach; ?>
		</style>
	<?php
	}

	/**
	 * Add a class to indicate the current template being used.
	 *
	 * @since  1.0.4.
	 *
	 * @param  array    $classes    The current classes.
	 * @return array                The modified classes.
	 */
	function admin_body_class( $classes ) {
		global $pagenow;

		// Do not complete the function if the product template is in use (i.e., the builder needs to be shown)
		if ( ttfmake_post_type_supports_builder( get_post_type() ) ) {
			if ( 'post-new.php' === $pagenow || ( 'post.php' === $pagenow && ttfmake_is_builder_page() ) ) {
				$classes .= ' ttfmake-builder-active';
			} else {
				$classes .= ' ttfmake-default-active';
			}
		}

		return $classes;
	}

	/**
	 * Reusable component for adding an image uploader.
	 *
	 * @since  1.0.0.
	 *
	 * @param  string    $section_name      Name of the current section.
	 * @param  int       $image_id          ID of the current image.
	 * @param  string    $title             Title for the media modal.
     * @param  string    $field_name        The name of the image field.
     * @param  string	 $field_name_path	The second part of the name (if applicable). E.g. `[1][textarea]` for `columns[1][textarea]`.
	 * @return string                       Either return the string or echo it.
	 */
	public function add_uploader( $section_name, $image_id = 0, $title = '', $field_name = 'background-image-url', $field_name_path = '' ) {
		$image = ttfmake_get_image_src( $image_id, 'large' );
		$title = ( ! empty( $title ) ) ? $title : esc_html__( 'Set image', 'make' );
		ob_start();
?>
	<div class="ttfmake-uploader{{ get('<?php echo $field_name; ?>')<?php echo $field_name_path; ?> && ' ttfmake-has-image-set' || '' }}">
		<div data-title="<?php echo $title; ?>" class="ttfmake-media-uploader-placeholder ttfmake-media-uploader-add" style="background-image: url({{ get('<?php echo $field_name; ?>')<?php echo $field_name_path; ?> }});"></div>
	</div>
	<?php
		$output = ob_get_clean();
		return $output;
	}

	/**
	 * Create an iframe preview area that is connected to a TinyMCE modal window.
	 *
	 * @since  1.4.0.
	 *
	 * @param  string    $id					The unique ID to identify the different areas.
	 * @param  string    $textarea_name			The name of the textarea.
	 * @param  string	 $textarea_name_path	The second part of the name (if applicable). E.g. `[1][textarea]` for `columns[1][textarea]`.
	 * @param  string    $content				The content for the text area.
	 * @param  bool      $iframe				Whether or not to add an iframe to preview content.
	 * @return void
	 */
	public function add_frame( $id, $textarea_name, $textarea_name_path = '', $content = '', $iframe = true ) {
		global $ttfmake_is_js_template;
		$iframe_id   = 'ttfmake-iframe-' . $id;
		$textarea_id = 'ttfmake-content-' . $id;
		$textarea_attr_name = 'ttfmake-section[' .$id. '][' .$textarea_name. ']';
	?>
		<?php if ( true === $iframe ) : ?>
		<span class="ttfmake-iframe-content-placeholder{{ (!get('<?php echo $textarea_name; ?>')) ? ' show' : '' }}">
			<?php esc_html_e( 'Click to edit', 'make' ); ?>
		</span>
		<div class="ttfmake-iframe-wrapper">
			<div class="ttfmake-iframe-overlay">
				<a href="#" class="edit-content-link" data-textarea="<?php echo esc_attr( $textarea_id ); ?>" data-iframe="<?php echo esc_attr( $iframe_id ); ?>">
					<span class="screen-reader-text">
						<?php esc_html_e( 'Edit content', 'make' ); ?>
					</span>
				</a>
			</div>
			<iframe width="100%" height="300" id="<?php echo esc_attr( $iframe_id ); ?>" scrolling="no"></iframe>
		</div>
		<?php endif; ?>

		<textarea id="<?php echo esc_attr( $textarea_id ); ?>" name="<?php echo esc_attr( $textarea_attr_name ); ?>" data-model-attr="<?php echo esc_attr( $textarea_name ); ?>" style="display:none;">{{ get('<?php echo esc_attr( $textarea_name ); ?>')<?php echo $textarea_name_path; ?> }}</textarea>

		<?php if ( true !== $ttfmake_is_js_template && true === $iframe ) : ?>
		<script type="text/javascript">
			var ttfMakeFrames = ttfMakeFrames || [];
			ttfMakeFrames.push('<?php echo $id; ?>');
		</script>
		<?php endif;
	}

	/**
	 * Load a section template with an available data payload for use in the template.
	 *
	 * @since  1.0.0.
	 *
	 * @param  string    $section     The section data.
	 * @param  array     $data        The data payload to inject into the section.
	 * @param  boolean   $return      Specifies if the template should be included or returned as string.
	 * @return void
	 */
	public function load_section( $section, $data = array(), $return = false ) {
		if ( ! isset( $section['id'] ) ) {
			return;
		}

		// Globalize the data to provide access within the template
		global $ttfmake_section_data;
		$ttfmake_section_data = array(
			'data'    => $data,
			'section' => $section,
		);

		$templates = $ttfmake_section_data['section']['builder_template'];
		$path = $ttfmake_section_data['section']['path'];

		if ( !is_array( $templates ) ) {
			$templates = array ( $templates );
		}

		foreach ( $templates as $key => $template ) {
			// Include the template
			$templates[$key] = ttfmake_load_section_template( $template, $path, $return );
		}

		// Destroy the variable as a good citizen does
		unset( $GLOBALS['ttfmake_section_data'] );

		if ( $return ) {
			return count( $templates ) == 1 ? $templates[0]: $templates;
		}
	}

	/**
	 * Print out the JS section templates
	 *
	 * @since  1.0.0.
	 *
	 * @return void
	 */
	public function print_templates() {
		global $hook_suffix, $typenow;

		// Only show when adding/editing pages
		if ( ! ttfmake_post_type_supports_builder( $typenow ) || ! in_array( $hook_suffix, array( 'post.php', 'post-new.php' ) )) {
			return;
		}

		// Load the overlay for TinyMCE
		get_template_part( '/inc/builder/core/templates/overlay', 'tinymce' );

		// Print the template for removing images
		?>
			<script type="text/html" id="tmpl-ttfmake-remove-image">
				<div class="ttfmake-remove-current-image">
					<h3><?php esc_html_e( 'Current image', 'make' ); ?></h3>
					<a href="#" class="ttfmake-remove-image-from-modal">
						<?php esc_html_e( 'Remove Current Image', 'make' ); ?>
					</a>
				</div>
			</script>
		<?php
	}

	/**
	 * Wrapper function to produce a WP Editor with special defaults.
	 *
	 * @since  1.0.0.
	 * @deprecated  1.4.0.
	 *
	 * @param  string    $content     The content to display in the editor.
	 * @param  string    $name        Name of the editor.
	 * @param  array     $settings    Setting to send to the editor.
	 * @return void
	 */
	public function wp_editor( $content, $name, $settings = array() ) {
		_deprecated_function( __FUNCTION__, '1.4.0', 'wp_editor' );
		wp_editor( $content, $name, $settings );
	}

	/**
	 * Add the media buttons to the text editor.
	 *
	 * This is a copy and modification of the core "media_buttons" function. In order to make the media editor work
	 * better for smaller width screens, we need to wrap the button text in a span tag. By doing so, we can hide the
	 * text in some situations.
	 *
	 * @since  1.0.0.
	 * @deprecated  1.4.0.
	 *
	 * @param  string    $editor_id    The value of the current editor ID.
	 * @return void
	 */
	public function media_buttons( $editor_id = 'content' ) {
		_deprecated_function( __FUNCTION__, '1.4.0', 'media_buttons' );
		media_buttons( $editor_id );
	}

	/**
	 * Append the editor styles to the section editors.
	 *
	 * Unfortunately, the `wp_editor()` function does not support a "content_css" argument. As a result, the stylesheet
	 * for the "content_css" parameter needs to be added via a filter.
	 *
	 * @since  1.0.0.
	 *
	 * @param  array     $mce_init     The array of tinyMCE settings.
	 * @param  string    $editor_id    The ID for the current editor.
	 * @return array                   The modified settings.
	 */
	function tiny_mce_before_init( $mce_init, $editor_id ) {
		_deprecated_function( __FUNCTION__, '1.4.0' );
		return $mce_init;
	}

	/**
	 * Retrieve all of the data for the sections.
	 *
	 * Note that in 1.2.0, this function was changed to call the global function. This global function was added to
	 * provide easier reuse of the function. In order to maintain backwards compatibility, this function is left in
	 * place.
	 *
	 * @since  1.0.0.
	 *
	 * @param  string    $post_id    The post to retrieve the data from.
	 * @return array                 The combined data.
	 */
	public function get_section_data( $post_id ) {
		return ttfmake_get_section_data( $post_id );
	}

	/**
	 * Convert an array with array keys that map to a multidimensional array to the array.
	 *
	 * Note that in 1.2.0, this function was changed to call the global function. This global function was added to
	 * provide easier reuse of the function. In order to maintain backwards compatibility, this function is left in
	 * place.
	 *
	 * @since  1.0.0.
	 *
	 * @param  array    $arr    The array to convert.
	 * @return array            The converted array.
	 */
	function create_array_from_meta_keys( $arr ) {
		return ttfmake_create_array_from_meta_keys( $arr );
	}
}
endif;

if ( ! function_exists( 'ttfmake_get_builder_base' ) ) :
/**
 * Instantiate or return the one TTFMAKE_Builder_Base instance.
 *
 * @since  1.0.0.
 *
 * @return TTFMAKE_Builder_Base
 */
function ttfmake_get_builder_base() {
	return TTFMAKE_Builder_Base::instance();
}
endif;

// Add the base immediately
if ( is_admin() ) {
	ttfmake_get_builder_base();
}

if ( ! function_exists( 'ttfmake_get_post_types_supporting_builder' ) ) :
/**
 * Get all post types that support the Make builder.
 *
 * @since  1.2.0.
 *
 * @return array    Array of all post types that support the builder.
 */
function ttfmake_get_post_types_supporting_builder() {
	$post_types_supporting_builder = array();

	// Inspect each post type for builder support
	foreach ( get_post_types() as $name => $data ) {
		if ( post_type_supports( $name, 'make-builder' ) ) {
			$post_types_supporting_builder[] = $name;
		}
	}

	return $post_types_supporting_builder;
}
endif;

if ( ! function_exists( 'ttfmake_will_be_builder_page' ) ):
/**
 * Determines if a page in the process of being saved will use the builder template.
 *
 * @since  1.2.0.
 *
 * @return bool    True if the builder template will be used; false if it will not.
 */
function ttfmake_will_be_builder_page() {
	$template    = isset( $_POST[ 'page_template' ] ) ? $_POST[ 'page_template' ] : '';
	$use_builder = isset( $_POST['use-builder'] ) ? (int) isset( $_POST['use-builder'] ) : 0;

	/**
	 * Allow developers to dynamically change the builder page status.
	 *
	 * @since 1.2.3.
	 *
	 * @param bool      $will_be_builder_page    Whether or not this page will be a builder page.
	 * @param string    $template                The template name.
	 * @param int       $use_builder             Value of the "use-builder" input. 1 === use builder. 0 === do not use builder.
	 */
	return apply_filters( 'make_will_be_builder_page', ( 'template-builder.php' === $template || 1 === $use_builder ), $template, $use_builder );
}
endif;

if ( ! function_exists( 'ttfmake_load_section_header' ) ) :
/**
 * Load a consistent header for sections.
 *
 * @since  1.0.0.
 *
 * @return void
 */
function ttfmake_load_section_header() {
	global $ttfmake_section_data;
	get_template_part( 'inc/builder/core/templates/section', 'header' );

	/**
	 * Allow for script execution in the header of a builder section.
	 *
	 * This action is a variable action that allows a developer to hook into specific section types (e.g., 'text'). Do
	 * not confuse "id" in this context as the individual section id (e.g., 14092814910).
	 *
	 * @since 1.2.3.
	 *
	 * @param array    $ttfmake_section_data    The array of data for the section.
	 */
	do_action( 'make_section_' . $ttfmake_section_data['section']['id'] . '_before', $ttfmake_section_data );

	// Backcompat
	do_action( 'ttfmake_section_' . $ttfmake_section_data['section']['id'] . '_before', $ttfmake_section_data );
}
endif;

if ( ! function_exists( 'ttfmake_load_section_footer' ) ) :
/**
 * Load a consistent footer for sections.
 *
 * @since  1.0.0.
 *
 * @return void
 */
function ttfmake_load_section_footer() {
	global $ttfmake_section_data;
	get_template_part( 'inc/builder/core/templates/section', 'footer' );

	/**
	 * Allow for script execution in the footer of a builder section.
	 *
	 * This action is a variable action that allows a developer to hook into specific section types (e.g., 'text'). Do
	 * not confuse "id" in this context as the individual section id (e.g., 14092814910).
	 *
	 * @since 1.2.3.
	 *
	 * @param array    $ttfmake_section_data    The array of data for the section.
	 */
	do_action( 'make_section_' . $ttfmake_section_data['section']['id'] . '_after', $ttfmake_section_data );

	// Backcompat
	do_action( 'ttfmake_section_' . $ttfmake_section_data['section']['id'] . '_after', $ttfmake_section_data );
}
endif;

if ( ! function_exists( 'ttfmake_load_section_template' ) ) :
/**
 * Load a section front- or back-end section template. Searches for child theme versions
 * first, then parent themes, then plugins.
 *
 * @since  1.0.4.
 *
 * @param  string    $slug    The relative path and filename (w/out suffix) required to substitute the template in a child theme.
 * @param  string    $path    An optional path extension to point to the template in the parent theme or a plugin.
  * @param  boolean   $return  Specifies if the template should be included or returned as string.
 * @return string             The template filename if one is located.
 */
function ttfmake_load_section_template( $slug, $path, $return = false ) {
	$templates = array(
		$slug . '.php',
		trailingslashit( $path ) . $slug . '.php'
	);

	/**
	 * Filter the templates to try and load.
	 *
	 * @since 1.2.3.
	 *
	 * @param array    $templates    The list of template to try and load.
	 * @param string   $slug         The template slug.
	 * @param string   $path         The path to the template.
	 */
	$templates = apply_filters( 'make_load_section_template', $templates, $slug, $path );

	if ( '' === $located = locate_template( $templates, true, false ) ) {
		if ( isset( $templates[1] ) && file_exists( $templates[1] ) ) {
			if ( $return ) {
				ob_start();
			}

			require( $templates[1] );
			$located = $templates[1];

			if ( $return ) {
				$located = ob_get_clean();
			}
		}
	}

	return $located;
}
endif;

if ( ! function_exists( 'ttfmake_get_wp_editor_id' ) ) :
/**
 * Generate the ID for a WP editor based on an existing or future section number.
 *
 * @since  1.0.0.
 *
 * @param  array     $data              The data for the section.
 * @param  array     $is_js_template    Whether a JS template is being printed or not.
 * @return string                       The editor ID.
 */
function ttfmake_get_wp_editor_id( $data, $is_js_template ) {
	$id_base = 'ttfmakeeditor' . $data['section']['id'];

	if ( $is_js_template ) {
		$id = $id_base . 'temp';
	} else {
		$id = $id_base . $data['data']['id'];
	}

	/**
	 * Alter the wp_editor ID.
	 *
	 * @since 1.2.3.
	 *
	 * @param string    $id                The ID for the editor.
	 * @param array     $data              The section data.
	 * @param bool      $is_js_template    Whether or not this is in the context of a JS template.
	 */
	return apply_filters( 'make_get_wp_editor_id', $id, $data, $is_js_template );
}
endif;

if ( ! function_exists( 'ttfmake_get_section_name' ) ) :
/**
 * Generate the name of a section.
 *
 * @since  1.0.0.
 *
 * @param  array     $data              The data for the section.
 * @param  array     $is_js_template    Whether a JS template is being printed or not.
 * @return string                       The name of the section.
 */
function ttfmake_get_section_name( $data, $is_js_template = true ) {
	$name = 'ttfmake-section[{{ id }}]';

	/**
	 * Alter section name.
	 *
	 * @since 1.2.3.
	 *
	 * @param string    $name              The name of the section.
	 * @param array     $data              The section data.
	 * @param bool      $is_js_template    Whether or not this is in the context of a JS template.
	 */
	return apply_filters( 'make_get_section_name', $name, $data, true );
}
endif;

if ( ! function_exists( 'ttfmake_get_image' ) ) :
/**
 * Get an image to display in page builder backend or front end template.
 *
 * This function allows image IDs defined with a negative number to surface placeholder images. This allows templates to
 * approximate real content without needing to add images to the user's media library.
 *
 * @since  1.0.4.
 *
 * @param  int       $image_id    The attachment ID. Dimension value IDs represent placeholders (100x150).
 * @param  string    $size        The image size.
 * @return string                 HTML for the image. Empty string if image cannot be produced.
 */
function ttfmake_get_image( $image_id, $size ) {
	$return = '';

	if ( false === strpos( $image_id, 'x' ) ) {
		$return = wp_get_attachment_image( $image_id, $size );
	} else {
		$image = ttfmake_get_placeholder_image( $image_id );

		if ( ! empty( $image ) && isset( $image['src'] ) && isset( $image['alt'] ) && isset( $image['class'] ) && isset( $image['height'] ) && isset( $image['width'] ) ) {
			$return = '<img src="' . $image['src'] . '" alt="' . $image['alt'] . '" class="' . $image['class'] . '" height="' . $image['height'] . '" width="' . $image['width'] . '" />';
		}
	}

	/**
	 * Filter the image HTML.
	 *
	 * @since 1.2.3.
	 *
	 * @param string    $return      The image HTML.
	 * @param int       $image_id    The ID for the image.
	 * @param bool      $size        The requested image size.
	 */
	return apply_filters( 'make_get_image', $return, $image_id, $size );
}
endif;

if ( ! function_exists( 'ttfmake_get_image_src' ) ) :
/**
 * Get an image's src.
 *
 * @since  1.0.4.
 *
 * @param  int       $image_id    The attachment ID. Dimension value IDs represent placeholders (100x150).
 * @param  string    $size        The image size.
 * @return string                 URL for the image.
 */
function ttfmake_get_image_src( $image_id, $size ) {
	$src = '';

	if ( false === strpos( $image_id, 'x' ) ) {
		$image = wp_get_attachment_image_src( $image_id, $size );

		if ( false !== $image && isset( $image[0] ) ) {
			$src = $image;
		}
	} else {
		$image = ttfmake_get_placeholder_image( $image_id );

		if ( isset( $image['src'] ) ) {
			$wp_src = array(
				0 => $image['src'],
				1 => $image['width'],
				2 => $image['height'],
			);
			$src = array_merge( $image, $wp_src );
		}
	}

	/**
	 * Filter the image source attributes.
	 *
	 * @since 1.2.3.
	 *
	 * @param string    $src         The image source attributes.
	 * @param int       $image_id    The ID for the image.
	 * @param bool      $size        The requested image size.
	 */
	return apply_filters( 'make_get_image_src', $src, $image_id, $size );
}
endif;

global $ttfmake_placeholder_images;

if ( ! function_exists( 'ttfmake_get_placeholder_image' ) ) :
/**
 * Gets the specified placeholder image.
 *
 * @since  1.0.4.
 *
 * @param  int      $image_id    Image ID. Should be a dimension value (100x150).
 * @return array                 The image data, including 'src', 'alt', 'class', 'height', and 'width'.
 */
function ttfmake_get_placeholder_image( $image_id ) {
	global $ttfmake_placeholder_images;
	$return = array();

	if ( isset( $ttfmake_placeholder_images[ $image_id ] ) ) {
		$return = $ttfmake_placeholder_images[ $image_id ];
	}

	/**
	 * Filter the image source attributes.
	 *
	 * @since 1.2.3.
	 *
	 * @param string    $return                        The image source attributes.
	 * @param int       $image_id                      The ID for the image.
	 * @param bool      $ttfmake_placeholder_images    The list of placeholder images.
	 */
	return apply_filters( 'make_get_placeholder_image', $return, $image_id, $ttfmake_placeholder_images );
}
endif;

if ( ! function_exists( 'ttfmake_register_placeholder_image' ) ) :
/**
 * Add a new placeholder image.
 *
 * @since  1.0.4.
 *
 * @param  int      $id      The ID for the image. Should be a dimension value (100x150).
 * @param  array    $data    The image data, including 'src', 'alt', 'class', 'height', and 'width'.
 * @return void
 */
function ttfmake_register_placeholder_image( $id, $data ) {
	global $ttfmake_placeholder_images;
	$ttfmake_placeholder_images[ $id ] = $data;
}
endif;

if ( ! function_exists( 'ttfmake_get_section_json_data' ) ) :
	/**
	 * Filters the json representation of saved sections.
	 *
	 * This filters allows for dynamically altering json section data
	 * before it gets passed to client.
	 *
	 * @since 1.8.0
	 *
	 * @param array    $data    The array of sections being jsonified.
	 */
	function ttfmake_get_section_json_data( $data = array() ) {
		foreach ($data as $s => $section) {
			/**
			 * Filters the json representation of a single section.
			 *
			 * This filters allows for dynamically altering this section
			 * json representation.
			 *
			 * @since 1.8.0
			 *
			 * @param array    $section    The section being jsonified.
			 */
			$data[$s] = apply_filters( 'make_get_section_json', $section );
		}

		return $data;
	}
endif;
