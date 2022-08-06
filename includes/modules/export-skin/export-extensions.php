<?php
namespace Crocoblock_Wizard\Modules\Export_Skin;

class Export_Extensions {

	public function __construct() {
		add_filter( 'crocoblock-wizard/export/tables-to-export', array( $this, 'export_cct_tables' ) );
	}

	public function export_cct_tables( $tables ) {
		
		if ( ! class_exists( '\Jet_Engine\Modules\Custom_Content_Types\Module' ) ) {
			return $tables;
		}

		$all_cct = \Jet_Engine\Modules\Custom_Content_Types\Module::instance()->manager->get_content_types();

		if ( empty( $all_cct ) ) {
			return $tables;
		}

		foreach ( $all_cct as $cct ) {
			$tables[] = $cct->db::$prefix . $cct->db->table;
		}

		return $tables;

	}

}