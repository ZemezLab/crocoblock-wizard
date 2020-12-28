<?php
namespace Crocoblock_Wizard\Modules\Import_Content;
/**
 * Post processing callbacks
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Define Remap_Callbacks class
 */
class Remap_Callbacks {

	/**
	 * Holder for terms data
	 *
	 * @var array
	 */
	public $terms = array();

	public $pages = null;

	public $importer;

	public $visual_filters = array();

	/**
	 * Store processed shortcodes data
	 *
	 * @var array
	 */
	private $shortcodes_data = array();

	/**
	 * Constructor for the class
	 */
	public function __construct( $importer ) {

		$this->importer = $importer;

		// Manipulations with posts remap array
		add_action( 'crocoblock-wizard/import/remap-posts', array( $this, 'process_options' ) );
		add_action( 'crocoblock-wizard/import/remap-posts', array( $this, 'postprocess_posts' ) );
		add_action( 'crocoblock-wizard/import/remap-posts', array( $this, 'process_thumbs' ) );
		add_action( 'crocoblock-wizard/import/remap-posts', array( $this, 'process_elementor_pages_posts' ) );
		add_action( 'crocoblock-wizard/import/remap-posts', array( $this, 'process_elementor_active_kit' ) );
		add_action( 'crocoblock-wizard/import/remap-posts', array( $this, 'process_home_page' ) );
		add_action( 'crocoblock-wizard/import/remap-posts', array( $this, 'process_posts_visual_filters' ) );

		// Manipulations with terms remap array
		add_action( 'crocoblock-wizard/import/remap-terms', array( $this, 'process_term_parents' ) );
		add_action( 'crocoblock-wizard/import/remap-terms', array( $this, 'process_nav_menu' ) );
		add_action( 'crocoblock-wizard/import/remap-terms', array( $this, 'process_nav_menu_widgets' ) );
		add_action( 'crocoblock-wizard/import/remap-terms', array( $this, 'process_elementor_pages_terms' ) );
		add_action( 'crocoblock-wizard/import/remap-terms', array( $this, 'process_home_page' ) );
		add_action( 'crocoblock-wizard/import/remap-terms', array( $this, 'process_terms_visual_filters' ) );

		// Various changes
		add_action( 'crocoblock-wizard/import/remap-posts', array( $this, 'adjust_profile_builder' ) );

	}

	public function process_elementor_active_kit( $data ) {

		$active_kit_id = get_option( 'elementor_active_kit' );

		if ( ! $active_kit_id ) {
			return;
		}

		$new_id = isset( $data[ $active_kit_id ] ) ? $data[ $active_kit_id ] : false;

		if ( $new_id ) {
			update_option( 'elementor_active_kit', $new_id );
		}

	}

	public function adjust_profile_builder( $data ) {

		$profile_builder_options = get_option( 'profile-builder' );

		if ( empty( $profile_builder_options ) ) {
			return;
		}

		if ( ! empty( $profile_builder_options['not_logged_in_redirect'] ) ) {
			$initial_url = $this->importer->cache->get( 'initial_url' );

			if ( false !== strpos( $profile_builder_options['not_logged_in_redirect'], $initial_url ) ) {
				$new_url = str_replace( $initial_url, home_url( '' ), $profile_builder_options['not_logged_in_redirect'] );
				$profile_builder_options['not_logged_in_redirect'] = $new_url;
			}

		}

		update_option( 'profile-builder', $profile_builder_options );

	}

	public function elementor_pages() {

		if ( null === $this->pages ) {
			$this->pages = get_posts( array(
				'post_type'      => array( 'page', 'jet-theme-core', 'elementor_library' ),
				'posts_per_page' => -1,
			) );
		}

		return $this->pages;

	}

	/**
	 * Remap elementor images
	 *
	 * @todo   remplace images in elementor widgets with imported.
	 * @return void
	 */
	public function process_elementor_pages_posts( $data ) {

		$pages = $this->elementor_pages();

		foreach ( $pages as $page ) {

			$elementor_data = get_post_meta( $page->ID, '_elementor_data', true );

			if ( empty( $elementor_data ) ) {
				continue;
			}

			$new_data = preg_replace_callback(
				'/(\"id\":\"?(\d+)\"?,\"url\":\"([^\"\']*?)\"|\"url\":\"([^\"\']*?)\",\"id\":\"?(\d+)\"?)/',
				function( $match ) use ( $data ) {

					$id = false;

					if ( ! empty( $match[1] ) ) {
						$id = $match[1];
					} elseif ( ! empty( $match[4] ) ) {
						$id = $match[4];
					}

					if ( ! $id || ! isset( $data[ $id ] ) ) {
						return $match[0];
					} else {

						$result = sprintf(
							'"url":%2$s,"id":%1$s',
							$data[ $id ],
							json_encode( wp_get_attachment_url( $data[ $id ] ) )
						);

						return $result;
					}

				},
				$elementor_data
			);

			$ids_keys = apply_filters( 'crocoblock-wizard/import/posts/elementor-ids-to-remap', array(
				'panel_template_id',
				'item_template_id',
			) );

			$ids_keys  = implode( '|', $ids_keys );
			$ids_regex = "/\\\"({$ids_keys})\\\":\\\"(\d+)\\\"/";

			$new_data = preg_replace_callback( $ids_regex, function( $match ) use ( $data ) {

				if ( isset( $data[ $match[2] ] ) ) {
					return sprintf(
						'"%1$s":"%2$s"',
						$match[1],
						$data[ $match[2] ]
					);
				} else {
					return $match[0];
				}

			}, $new_data );

			if ( class_exists( '\Elementor\Utils' ) ) {

				$initial_url = $this->importer->cache->get( 'initial_url' );
				$current_url = home_url( '' );

				try {
					\Elementor\Utils::replace_urls( $initial_url, $current_url );
				} catch ( \Exception $e ) {
					var_dump( $e->getMessage() );
				}

			}

			update_post_meta( $page->ID, '_elementor_data', wp_slash( $new_data ) );

		}

	}

	public function process_elementor_pages_terms( $data ) {

		$pages    = $this->elementor_pages();
		$ids_keys = apply_filters( 'crocoblock-wizard/import/terms/elementor-ids-to-remap', array(
			'category_ids',
			'menu',
			'nav_menu',
		) );

		$ids_keys = implode( '|', $ids_keys );
		$regex    = '\"(' . $ids_keys . ')\":(\".*?\"|\[.*?\])';

		foreach ( $pages as $page ) {

			$elementor_data = get_post_meta( $page->ID, '_elementor_data', true );

			if ( empty( $elementor_data ) ) {
				continue;
			}

			$new_data = preg_replace_callback( '/' . $regex . '/', function( $match ) use ( $data ) {

				$val = json_decode( $match[2], true );

				if ( ! is_array( $val ) ) {
					$new = isset( $data[ $val ] ) ? $data[ $val ] : $val;
					$new = '"' . $new . '"';
				} else {
					$new = array();
					foreach ( $val as $old_id ) {
						$new = isset( $data[ $old_id ] ) ? $data[ $old_id ] : $old_id;
					}
					$new = json_encode( $new );
				}

				return sprintf(
					'"%1$s":%2$s',
					$match[1],
					$new
				);

			}, $elementor_data );

			update_post_meta( $page->ID, '_elementor_data', wp_slash( $new_data ) );

		}

	}

	/**
	 * Remap IDs in home page content
	 *
	 * @param  array $data Mapped terms data.
	 * @return void|false
	 */
	public function process_home_page( $data ) {

		$regex = apply_filters( 'crocoblock-wizard/import/home-regex-replace', array() );

		if ( empty( $regex ) ) {
			return false;
		}

		$pages = apply_filters( 'crocoblock-wizard/import/add-pages-to-replace', array() );

		if ( ! empty( $pages ) ) {
			$pages = array_map( array( $this, 'get_page_ids' ), $pages );
		}

		$home_id = get_option( 'page_on_front' );

		$pages = array_merge( array( $home_id ), $pages );
		$pages = array_filter( $pages );

		if ( ! $pages ) {
			return false;
		}

		$regex = array_map( array( $this, 'prepare_regex' ), $regex );

		foreach ( $pages as $page_id ) {

			$page = get_post( $page_id );

			$this->terms = $data;

			$content = preg_replace_callback( $regex, array( $this, 'replace_ids' ), $page->post_content );

			$new_page = array(
				'ID'           => $page_id,
				'post_content' => $content,
			);

			wp_update_post( $new_page );

		}
	}

	/**
	 * Get page ids by slug
	 *
	 * @return int|bool
	 */
	public function get_page_ids( $slug ) {

		$page = get_page_by_path( $slug );
		if ( $page ) {
			return $page->ID;
		} else {
			return false;
		}

		return get_page_by_path( $slug );
	}

	/**
	 * Replace ids in shortcodes
	 *
	 * @return string
	 */
	public function replace_ids( $matches ) {

		if ( 5 !== count( $matches ) ) {
			return $matches[0];
		}

		$tag       = $matches[2];
		$attr      = $matches[3];
		$data      = $this->shortcodes_data;
		$delimiter = isset( $data[ $tag ][ $attr ] ) ? $data[ $tag ][ $attr ] : ',';
		$ids       = explode( $delimiter, $matches[4] );
		$new_ids   = array();

		foreach ( $ids as $id ) {

			if ( isset( $this->terms[ $id ] ) ) {
				$new_ids[] = $this->terms[ $id ];
			} else {
				$new_ids[] = $id;
			}

		}

		$new_ids = implode( $delimiter, $new_ids );
		$return  = sprintf( '%1$s="%2$s"', $matches[1], $new_ids );

		return $return;
	}

	/**
	 * Callback for regex map
	 *
	 * @param  array $item Regex item.
	 * @return string
	 */
	public function prepare_regex( $item ) {

		$delimiter = isset( $item['delimiter'] ) ? $item['delimiter'] : ',';
		$tag       = $item['shortcode'];
		$attr      = $item['attr'];

		if ( ! isset( $this->shortcodes_data[ $tag ] ) ) {
			$this->shortcodes_data[ $tag ] = array();
		}

		$this->shortcodes_data[ $tag ][ $attr ] = $delimiter;

		return '/(\[(' . $item['shortcode'] . ')[^\]]*(' . $item['attr'] . '))="([0-9\,\s]*)"/';

	}

	/**
	 * Set correct term IDs for visual filters
	 *
	 * @param  [type] $data [description]
	 * @return [type]       [description]
	 */
	public function process_posts_visual_filters( $data ) {

		global $wpdb;
		$postmeta = $wpdb->postmeta;
		$this->visual_filters = $wpdb->get_results( "SELECT postmeta1.post_id, postmeta2.meta_value AS source, postmeta1.meta_value FROM $postmeta AS postmeta1 INNER JOIN $postmeta AS postmeta2 ON postmeta1.post_id = postmeta2.post_id WHERE postmeta2.meta_key = '_data_source' AND postmeta2.meta_value IN ( 'taxonomies', 'posts' ) AND postmeta1.meta_key = '_source_color_image_input'" );

		if ( empty( $this->visual_filters ) ) {
			return;
		}

		$this->update_visual_filters_meta( 'posts', $data );

	}

	/**
	 * Iterate visual filters meta
	 *
	 * @param  string $context [description]
	 * @param  array  $data    [description]
	 * @return [type]          [description]
	 */
	public function update_visual_filters_meta( $context = 'posts', $data = array() ) {

		foreach ( $this->visual_filters as $filter_index => $row ) {

			$meta_value = maybe_unserialize( $row->meta_value );
			$changed    = false;

			foreach ( $meta_value as $index => $item_value ) {

				if ( 'posts' === $context && ! empty( $item_value['source_image'] ) ) {
					$current = $item_value['source_image'];
					$item_value['source_image'] = ! empty( $data[ $current ] ) ? $data[ $current ] : $current;

					if ( 'posts' === $row->source ) {
						$meta_value[ $index ] = $item_value;
						$changed = true;
					} else {
						$meta_value[ $index ] = $item_value;
						$this->visual_filters[ $filter_index ]->meta_value = maybe_serialize( $meta_value );
					}
				}

				if ( $row->source === $context && ! empty( $item_value['selected_value'] ) ) {
					$current = $item_value['selected_value'];
					$item_value['selected_value'] = ! empty( $data[ $current ] ) ? $data[ $current ] : $current;
					$meta_value[ $index ] = $item_value;
					$changed = true;
				}

			}

			if ( $changed ) {
				update_post_meta( $row->post_id, '_source_color_image_input', $meta_value );
			}

		}

	}

	/**
	 * Set correct term IDs for visual filters
	 *
	 * @param  [type] $data [description]
	 * @return [type]       [description]
	 */
	public function process_terms_visual_filters( $data ) {

		if ( empty( $this->visual_filters ) ) {
			return;
		}

		$this->update_visual_filters_meta( 'taxonomies', $data );
	}

	/**
	 * Set correctly term parents
	 *
	 * @param  array $data Mapped terms data.
	 * @return void|false
	 */
	public function process_term_parents( $data ) {

		$remap_terms         = $this->importer->cache->get( 'terms', 'requires_remapping' );
		$processed_term_slug = $this->importer->cache->get( 'term_slug', 'mapping' );

		if ( empty( $remap_terms ) ) {
			return false;
		}

		foreach ( $remap_terms as $term_id => $taxonomy ) {

			$parent_slug = get_term_meta( $term_id, '_wxr_import_parent', true );

			if ( ! $parent_slug ) {
				continue;
			}

			$term_mapping_key = $taxonomy . '-' . $parent_slug;

			if ( empty( $processed_term_slug[ $term_mapping_key ] ) ) {
				continue;
			}

			wp_update_term( $term_id, $taxonomy, array(
				'parent' => (int) $processed_term_slug[ $term_mapping_key ],
			) );

		}

	}

	/**
	 * Replace term thumbnails IDs with new ones
	 *
	 * @param  array $data
	 * @return void
	 */
	public function process_thumbs( $data ) {

		global $wpdb;

		$query_term = "
			SELECT term_id, meta_key, meta_value
			FROM $wpdb->termmeta
			WHERE meta_key LIKE '%_thumb'
		";

		$thumb_term = $wpdb->get_results( $query_term, ARRAY_A );

		$query_post = "
			SELECT post_id, meta_key, meta_value
			FROM $wpdb->postmeta
			WHERE meta_key = '_thumbnail_id'
		";

		$thumb_post = $wpdb->get_results( $query_post, ARRAY_A );

		if ( empty( $thumb_term ) ) {
			$thumb_term = array();
		}

		if ( empty( $thumb_post ) ) {
			$thumb_post = array();
		}

		$thumbnails = array_merge( $thumb_term, $thumb_post );

		foreach ( $thumbnails as $thumb_data ) {

			$meta_key = $thumb_data['meta_key'];
			$current  = $thumb_data['meta_value'];

			if ( '_thumbnail_id' === $meta_key ){
				$id   = $thumb_data['post_id'];
				$func = 'update_post_meta';
			} else {
				$id   = $thumb_data['term_id'];
				$func = 'update_term_meta';
			}

			if ( ! empty( $data[ $current ] ) ) {
				call_user_func( $func, $id, $meta_key, $data[ $current ] );
			}

		}

	}

	/**
	 * Post-process posts.
	 *
	 * @param  array $todo Remap data.
	 * @return void
	 */
	public function postprocess_posts( $mapping ) {

		$todo      = $this->importer->cache->get( 'posts', 'requires_remapping' );
		$user_slug = $this->importer->cache->get( 'user_slug', 'mapping' );
		$url_remap = $this->importer->cache->get_group( 'url_remap' );

		foreach ( $todo as $post_id => $_ ) {

			$data          = array();
			$updated_links = '';
			$old_links     = '';
			$post          = get_post( $post_id );

			$parent_id = get_post_meta( $post_id, '_wxr_import_parent', true );

			if ( ! empty( $parent_id ) && isset( $mapping['post'][ $parent_id ] ) ) {
				$data['post_parent'] = $mapping['post'][ $parent_id ];
			}

			$author_slug = get_post_meta( $post_id, '_wxr_import_user_slug', true );
			if ( ! empty( $author_slug ) && isset( $user_slug[ $author_slug ] ) ) {
				$data['post_author'] = $user_slug[ $author_slug ];
			}

			$has_attachments = get_post_meta( $post_id, '_wxr_import_has_attachment_refs', true );

			if ( ! empty( $has_attachments ) ) {

				$content = $post->post_content;

				// Replace all the URLs we've got
				$new_content = str_replace( array_keys( $url_remap ), $url_remap, $content );
				if ( $new_content !== $content ) {
					$data['post_content'] = $new_content;
				}
			}

			if ( in_array( get_post_type( $post_id ), array( 'page', 'post' ) ) ) {

				$old_links     = ! empty( $data['post_content'] ) ? $data['post_content'] : $post->post_content;
				$updated_links = str_replace( $this->importer->cache->get( 'home' ), home_url(), $old_links );

				if ( $updated_links !== $old_links ) {
					$data['post_content'] = $updated_links;
				}

			}

			if ( get_post_type( $post_id ) === 'nav_menu_item' ) {
				$this->postprocess_menu_item( $post_id );
			}

			// Do we have updates to make?
			if ( empty( $data ) ) {
				continue;
			}

			// Run the update
			$data['ID'] = $post_id;
			$result     = wp_update_post( $data, true );

			if ( is_wp_error( $result ) ) {
				continue;
			}

			// Clear out our temporary meta keys
			delete_post_meta( $post_id, '_wxr_import_parent' );
			delete_post_meta( $post_id, '_wxr_import_user_slug' );
			delete_post_meta( $post_id, '_wxr_import_has_attachment_refs' );
		}

	}

	/**
	 * Post-process menu items.
	 *
	 * @param  int $post_id Processed post ID
	 * @return void
	 */
	public function postprocess_menu_item( $post_id ) {

		$menu_object_id = get_post_meta( $post_id, '_wxr_import_menu_item', true );

		if ( empty( $menu_object_id ) ) {
			// No processing needed!
			return;
		}

		$processed_term_id = $this->importer->cache->get( 'term_id', 'mapping' );
		$processed_posts   = $this->importer->cache->get( 'posts', 'mapping' );

		$menu_item_type = get_post_meta( $post_id, '_menu_item_type', true );

		switch ( $menu_item_type ) {
			case 'taxonomy':
				if ( isset( $processed_term_id[ $menu_object_id ] ) ) {
					$menu_object = $processed_term_id[ $menu_object_id ];
				}
				break;

			case 'post_type':
				if ( isset( $processed_posts[ $menu_object_id ] ) ) {
					$menu_object = $processed_posts[ $menu_object_id ];
				}
				break;

			default:
				// Cannot handle this.
				return;
		}

		if ( ! empty( $menu_object ) ) {
			update_post_meta( $post_id, '_menu_item_object_id', wp_slash( $menu_object ) );
		}

		delete_post_meta( $post_id, '_wxr_import_menu_item' );

	}

	/**
	 * Remap page ids in imported options
	 *
	 * @param  array $data Remap data.
	 * @return void
	 */
	public function process_options( $data ) {

		$options_to_process = array(
			'page_on_front',
			'page_for_posts',
		);

		foreach ( $options_to_process as $key ) {

			$current = get_option( $key );

			if ( ! $current || ! isset( $data[ $current ] ) ) {
				continue;
			}

			update_option( $key, $data[ $current ] );

		}

		// Update Jet Theme Core conditions
		$conditions = get_option( 'jet_site_conditions' );

		if ( ! empty( $conditions ) ) {

			$new_conditions = array();

			foreach ( $conditions as $location => $condition ) {

				$new_conditions[ $location ] = array();

				foreach ( $condition as $template_id => $rules ) {

					if ( isset( $data[ $template_id ] ) ) {
						$new_conditions[ $location ][ $data[ $template_id ] ] = $rules;
					} else {
						$new_conditions[ $location ][ $template_id ] = $rules;
					}

				}
			}

			update_option( 'jet_site_conditions', $new_conditions );

		}


	}

	/**
	 * Remap nav menu ids
	 *
	 * @param  array $data Remap data.
	 * @return void
	 */
	public function process_nav_menu( $data ) {

		$locations = get_nav_menu_locations();

		if ( empty( $locations ) ) {
			return;
		}

		$new_locations = array();

		foreach ( $locations as $location => $id ) {

			if ( isset( $data[ $id ] ) ) {
				$new_locations[ $location ] = $data[ $id ];
			} else {
				$new_locations[ $location ] = $id;
			}

		}

		set_theme_mod( 'nav_menu_locations', $new_locations );

	}

	/**
	 * Remap menu IDs in widgets
	 *
	 * @param  array $data Remap data.
	 * @return void
	 */
	public function process_nav_menu_widgets( $data ) {

		$widget_menus = get_option( 'widget_nav_menu' );

		if ( empty( $widget_menus ) ) {
			return;
		}

		$new_widgets = array();

		foreach ( $widget_menus as $key => $widget ) {

			if ( '_multiwidget' === $key ) {
				$new_widgets['_multiwidget'] = $widget;
				continue;
			}

			if ( empty( $widget['nav_menu'] ) ) {
				$new_widgets[] = $widget;
				continue;
			}

			$id = $widget['nav_menu'];

			if ( isset( $data[ $id ] ) ) {
				$widget['nav_menu'] = $data[ $id ];
			}

			$new_widgets[ $key ] = $widget;

		}

		update_option( 'widget_nav_menu', $new_widgets );

	}

}
