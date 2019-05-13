<?php
namespace Crocoblock_Wizard\Modules\Import_Content;

use Crocoblock_Wizard\Base\Module as Module_Base;
use Crocoblock_Wizard\Plugin as Plugin;
use Crocoblock_Wizard\Tools\Cache as Cache;
use Crocoblock_Wizard\Tools\DB_Tables as DB_Tables;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class Module extends Module_Base {

	private $import_file = null;
	private $importer    = null;
	private $chunk_size  = null;

	/**
	 * Returns module slug
	 *
	 * @return void
	 */
	public function get_slug() {
		return 'import-content';
	}

	/**
	 * Enqueue module-specific assets
	 *
	 * @return void
	 */
	public function enqueue_module_assets() {

		wp_enqueue_script(
			'crocoblock-wizard-content',
			CB_WIZARD_URL . 'assets/js/content.js',
			array( 'cx-vue-ui' ),
			CB_WIZARD_VERSION,
			true
		);

	}

	/**
	 * License page config
	 *
	 * @param  array  $config  [description]
	 * @param  string $subpage [description]
	 * @return [type]          [description]
	 */
	public function page_config( $config = array(), $subpage = '' ) {

		$skin         = isset( $_GET['skin'] ) ? $_GET['skin'] : false;
		$is_uploaded  = isset( $_GET['is_uploaded'] ) ? $_GET['is_uploaded'] : false;

		$config['title']            = __( 'We’re almost there!', 'crocoblock-wizard' );
		$config['import_title']     = __( 'Importing sample data', 'crocoblock-wizard' );
		$config['regenerate_title'] = __( 'Regenerating thumbnails', 'crocoblock-wizard' );
		$config['cover']            = CB_WIZARD_URL . 'assets/img/cover-4.png';
		$config['cover_import']     = CB_WIZARD_URL . 'assets/img/cover-5.png';
		$config['body']             = 'cbw-content';
		$config['wrapper_css']      = 'vertical-flex';
		$config['is_uploaded']      = $is_uploaded;
		$config['skin']             = $skin;
		$config['regenerate_chunk'] = Plugin::instance()->settings->get( array( 'import', 'regenerate_chunk_size' ) );
		$config['summary']          = array(
			'posts'    => __( 'Posts', 'crocoblock-wizard' ),
			'authors'  => __( 'Authors', 'crocoblock-wizard' ),
			'media'    => __( 'Media', 'crocoblock-wizard' ),
			'comments' => __( 'Comments', 'crocoblock-wizard' ),
			'terms'    => __( 'Terms', 'crocoblock-wizard' ),
			'tables'   => __( 'Custom DB Tables', 'crocoblock-wizard' ),
		);
		$config['prev_step']        = add_query_arg(
			array(
				'skin'        => $skin,
				'is_uploaded' => $is_uploaded,
			),
			Plugin::instance()->dashboard->page_url( 'install-plugins' )
		);
		$config['next_step']        = Plugin::instance()->dashboard->page_url( 'onboarding' );
		$config['import_types']     = array(
			array(
				'value'       => 'append',
				'label'       => __( 'Append demo content to my existing content', 'crocoblock-wizard' ),
				'description' => __( 'Skip child theme installation and continute with parent theme.', 'crocoblock-wizard' ),
			),
			array(
				'value'       => 'replace',
				'label'       => __( 'Replace my existing content with demo content', 'crocoblock-wizard' ),
				'description' => __( 'Download and install child theme. We recommend doing this, because it’s the most safe way to make future modifications.', 'crocoblock-wizard' ),
			),
			array(
				'value'       => 'skip',
				'label'       => __( 'Skip demo content installation', 'crocoblock-wizard' ),
				'description' => __( 'Download and install child theme. We recommend doing this, because it’s the most safe way to make future modifications.', 'crocoblock-wizard' ),
			),
		);

		$this->get_import_file( $skin, $is_uploaded );

		return $config;

	}

	/**
	 * Add license component template
	 *
	 * @param  array  $templates [description]
	 * @param  string $subpage   [description]
	 * @return [type]            [description]
	 */
	public function page_templates( $templates = array(), $subpage = '' ) {

		$templates['content']          = 'import-content/main';
		$templates['select_type']      = 'import-content/select-type';
		$templates['import_content']   = 'import-content/import-content';
		$templates['regenerate_thumb'] = 'import-content/regenerate-thumb';
		$templates['clear_content']    = 'import-content/clear-content';
		return $templates;

	}

	public function chunk_size() {

		if ( ! $this->chunk_size ) {
			$this->chunk_size = Plugin::instance()->settings->get( array( 'import', 'chunk_size' ) );
		}

		return $this->chunk_size;

	}

	/**
	 * Returns true if regenerate thumbnails step is required, false - if not.
	 *
	 * @return boolean
	 */
	private function is_regenerate_required() {

		$count = wp_count_attachments();
		$count = (array) $count;

		if ( empty( $count ) ) {
			return false;
		}

		$total = 0;

		if ( ! empty( $count['image/jpeg'] ) ) {
			$total += absint( $count['image/jpeg'] );
		}

		if ( ! empty( $count['image/png'] ) ) {
			$total += absint( $count['image/png'] );
		}

		if ( 0 === $total ) {
			return false;
		}

		return true;

	}

	/**
	 * Clear content before import
	 *
	 * @return [type] [description]
	 */
	public function clear_content() {

		$cache = new Cache();

		if ( empty( $_REQUEST['password'] ) ) {

			$cache->write_cache();

			wp_send_json_error( array(
				'message' => esc_html__( 'Password is empty', 'jet-data-importer' ),
			) );

		}

		$password = esc_attr( $_REQUEST['password'] );
		$user_id  = get_current_user_id();
		$data     = get_userdata( $user_id );

		if ( wp_check_password( $password, $data->user_pass, $user_id ) ) {

			DB_Tables::clear_content();

			$cache->write_cache();

			wp_send_json_success( array(
				'message' => esc_html__( 'Content successfully removed', 'jet-data-importer' ),
			) );

		} else {

			$cache->write_cache();

			wp_send_json_error( array(
				'message' => esc_html__( 'Entered password is invalid', 'jet-data-importer' ),
			) );
		}

	}

	/**
	 * Process single chunk import
	 *
	 * @return void
	 */
	public function import_chunk() {

		$importer = $this->get_importer();

		if ( empty( $_REQUEST['chunk'] ) ) {

			$importer->cache->write_cache();
			wp_send_json_error( array(
				'message' => esc_html__( 'Chunk number is missing in request', 'crocoblock-wizard' ),
			) );
		}

		$chunk  = intval( $_REQUEST['chunk'] );
		$chunks = $importer->cache->get( 'chunks_count' );

		if ( ! $chunks ) {
			wp_send_json_error( array(
				'message' => __( 'Can`t calculate import steps. Please relaod page and try again.', $domain = 'default' )
			) );
		}

		new Importer_Extensions();

		switch ( $chunk ) {

			case $chunks:

				// Process last step (remapping and finalizing)
				$this->remap_all( $importer );
				$importer->cache->clear_cache();
				flush_rewrite_rules();

				$processed = $importer->cache->get( 'processed_summary' );

				/**
				 * Hook on last import chunk
				 */
				do_action( 'crocoblock-wizard/import/finish' );

				$data = array(
					'isLast'     => true,
					'complete'   => 100,
					'processed'  => $processed,
					'regenerate' => $this->is_regenerate_required(),
				);

				// Remove XML file for remote files after successfull import.
				$file = $this->get_import_file();

				$importer->close_reader();

				if ( $file ) {
					unlink( $file );
				}

				break;

			default:

				// Process regular step
				$offset = $this->chunk_size() * ( $chunk - 1 );

				$importer->chunked_import( $this->chunk_size(), $offset );

				$processed = $importer->cache->get( 'processed_summary' );

				/**
				 * Hook on last import chunk
				 */
				do_action( 'crocoblock-wizard/import/chunk', $chunk );

				$data = array(
					'action'    => 'jet-data-import-chunk',
					'chunk'     => $chunk + 1,
					'complete'  => round( ( $chunk * 100 ) / $chunks ),
					'processed' => $processed,
				);

				break;
		}

		$importer->cache->write_cache();
		wp_send_json_success( $data );

	}

	/**
	 * Process single regenerate chunk
	 *
	 * @return void
	 */
	public function regenerate_chunk() {

		$required = array(
			'offset',
			'step',
			'total',
		);

		$cache = new Cache();

		foreach ( $required as $field ) {

			if ( ! isset( $_REQUEST[ $field ] ) ) {

				$cache->write_cache();

				wp_send_json_error( array(
					'message' => sprintf(
						esc_html__( '%s is missing in request', 'jet-data-importer' ), $field
					),
				) );
			}

		}

		$offset = (int) $_REQUEST['offset'];
		$step   = (int) $_REQUEST['step'];
		$total  = (int) $_REQUEST['total'];

		if ( empty( $total ) ) {

			$count = wp_count_attachments();
			$count = (array) $count;

			$total = 0;

			foreach ( $count as $mime => $num ) {

				if ( false === strpos( $mime, 'image' ) ) {
					continue;
				}

				$total = $total + (int) $num;
			}

		}

		$is_last = ( $total * $step <= $offset + $step ) ? true : false;

		$attachments = get_posts( array(
			'post_type'   => 'attachment',
			'numberposts' => $step,
			'offset'      => $offset,
		) );

		if ( ! empty( $attachments ) ) {
			foreach ( $attachments as $attachment ) {

				$id       = $attachment->ID;
				$file     = get_attached_file( $id );
				$metadata = wp_generate_attachment_metadata( $id, $file );

				wp_update_attachment_metadata( $id, $metadata );
			}
		}

		$data = array(
			'offset'   => $offset + $step,
			'step'     => $step,
			'total'    => $total,
			'isLast'   => $is_last,
			'complete' => round( ( $offset + $step ) * 100 / ( $total * $step ) ),
		);

		$cache->write_cache();

		wp_send_json_success( $data );

	}

	/**
	 * Remap all required data after installation completed
	 *
	 * @return void
	 */
	public function remap_all( $importer ) {

		//require_once jdi()->path( 'includes/import/class-jet-data-importer-remap-callbacks.php' );

		/**
		 * Attach all posts remapping related callbacks to this hook
		 *
		 * @param  array Posts remapping data. Format: old_id => new_id
		 */
		do_action( 'jet-data-importer/import/remap-posts', $importer->cache->get( 'posts', 'mapping' ) );

		/**
		 * Attach all terms remapping related callbacks to this hook
		 *
		 * @param  array Terms remapping data. Format: old_id => new_id
		 */
		do_action( 'jet-data-importer/import/remap-terms', $importer->cache->get( 'term_id', 'mapping' ) );

		/**
		 * Attach all comments remapping related callbacks to this hook
		 *
		 * @param  array COmments remapping data. Format: old_id => new_id
		 */
		do_action( 'jet-data-importer/import/remap-comments', $importer->cache->get( 'comments', 'mapping' ) );

		/**
		 * Attach all posts_meta remapping related callbacks to this hook
		 *
		 * @param  array posts_meta data. Format: new_id => related keys array
		 */
		do_action( 'jet-data-importer/import/remap-posts-meta', $importer->cache->get( 'posts_meta', 'requires_remapping' ) );

		/**
		 * Attach all terms meta remapping related callbacks to this hook
		 *
		 * @param  array terms meta data. Format: new_id => related keys array
		 */
		do_action( 'jet-data-importer/import/remap-terms-meta', $importer->cache->get( 'terms_meta', 'requires_remapping' ) );

	}


	/**
	 * Returns information about current import session
	 *
	 * @return [type] [description]
	 */
	public function get_import_info() {

		$importer = $this->get_importer();
		$importer->prepare_import();

		$total        = $importer->cache->get( 'total_count' );
		$summary      = $importer->cache->get( 'import_summary' );
		$chunks_count = ceil( intval( $total ) / $this->chunk_size() );

		// Adds final step with ID and URL remapping. Sometimes it's expensive step so its separated
		$chunks_count++;

		$importer->cache->update( 'chunks_count', $chunks_count );
		$importer->cache->write_cache();

		wp_send_json_success( array(
			'total'   => $total,
			'summary' => $summary,
		) );

	}

	/**
	 * Get path to imported XML file
	 *
	 * @return [type] [description]
	 */
	public function get_import_file( $skin = null, $is_uploaded = null ) {

		if ( null !== $this->import_file ) {
			return $this->import_file;
		}

		$file = null;

		if ( ! $skin ) {
			$skin = ! empty( $_REQUEST['skin'] ) ? esc_attr( $_REQUEST['skin'] ) : false;
		}

		if ( ! $skin ) {
			return false;
		}

		if ( null === $is_uploaded ) {
			$is_uploaded =  ! empty( $_REQUEST['is_uploaded'] ) ? esc_attr( $_REQUEST['is_uploaded'] ) : false;
		}

		if ( ! empty( $is_uploaded ) ) {
			$file = $this->get_uploaded_file( $skin );
		} else {
			$file = $this->get_remote_file( $skin );
		}

		if ( ! $file || ! file_exists( $file ) ) {
			return false;
		} else {
			$this->import_file = $file;
			return $this->import_file;
		}

	}

	/**
	 * Copy file into root of base dir and return file path
	 *
	 * @return [type] [description]
	 */
	public function get_uploaded_file( $skin = null ) {

		$filename  = 'sample-data.xml';
		$from_path = Plugin::instance()->files_manager->base_path() . $skin . '/' . $filename;
		$to_path   = Plugin::instance()->files_manager->base_path() . $skin . '.xml';

		if ( file_exists( $to_path ) ) {
			return $to_path;
		}

		if ( ! file_exists( $from_path ) ) {
			return false;
		}

		$copied = copy( $from_path, $to_path );

		if ( $copied ) {
			return $to_path;
		} else {
			return false;
		}

	}

	/**
	 * Returns remote file
	 *
	 * @param  [type] $skin [description]
	 * @return [type]       [description]
	 */
	public  function get_remote_file( $skin = null ) {

		$file_url = Plugin::instance()->skins->get_skin_data( 'full_xml', $skin );

		if ( ! $file_url ) {
			return false;
		}

		$filename  = $skin . '.xml';
		$base_path = Plugin::instance()->files_manager->base_path();
		$to_path   = $base_path . $filename;

		if ( file_exists( $to_path ) ) {
			return $to_path;
		}

		$tmpath = download_url( esc_url( $file_url ) );

		if ( ! $tmpath ) {
			return false;
		}

		if ( ! copy( $tmpath, $to_path ) ) {
			return false;
		}

		unlink( $tmpath );

		return $to_path;

	}

	/**
	 * Returns importer instance
	 *
	 * @return WXR_Importer
	 */
	public function get_importer() {

		if ( null !== $this->importer ) {
			return $this->importer;
		}

		$options = array();
		$file    = $this->get_import_file();

		if ( ! $file ) {
			return false;
		}

		if ( ! class_exists( '\\WP_Importer' ) ) {
			require_once ABSPATH . '/wp-admin/includes/class-wp-importer.php';
		}

		return $this->importer = new WXR_Importer( $options, $file );

	}

}
