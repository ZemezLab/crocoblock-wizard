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
			$table = $cct->db::$prefix . $cct->db->table;
			$tables[] = $table;
			add_filter( 'crocoblock-wizard/export/table-schema/' . $table, function( $result ) use ( $cct ) {
				
				$result = str_replace( 
					array( 'CREATE TABLE ' . $cct->db->table(), ' ' . $cct->db->wpdb()->get_charset_collate() ), 
					'', 
					$cct->db->get_table_schema()
				);

				return $result;
				
			} );
		}

		return $tables;

	}

}