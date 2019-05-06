## Configuration example:

#### Plugins:

```PHP

add_action( 'init', 'my_plugins_wizard_config', 0 );

function my_plugins_wizard_config() {

	if ( ! function_exists( 'crocoblock_wizard' ) ) {
		return;
	}

	crocoblock_wizard()->settings->register_external_config( array(
		'plugins' => array(
			'plugin-1' => array(
				'name'   => esc_html__( 'Plugin 1', 'crocoblock-wizard' ),
				'sourse' => 'wordpress', // 'git', 'local', 'remote', 'wordpress' (default).
				'path'   => false, // git repository, remote URL or local path.
			),
			'plugin-2' => array(
				'name'   => esc_html__( 'Plugin 2', 'crocoblock-wizard' ),
				'sourse' => 'git', // 'git', 'local', 'remote', 'wordpress' (default).
				'path'   => false, // git repository, remote URL or local path.
			),
		)
	) );

	// Or from remote url
	crocoblock_wizard()->settings->register_external_config( array(
		'plugins' => array(
			'get_from' => URL which is returns JSON with plugins configuration,
		)
	) );

}
```

#### Skins:

```PHP

add_action( 'init', 'my_plugins_wizard_config', 0 );

function my_plugins_wizard_config() {

	if ( ! function_exists( 'crocoblock_wizard' ) ) {
		return;
	}

	crocoblock_wizard()->settings->register_external_config( array(
		'skins' => array(
			'skin-name' => array(
				'full'  => array(
					'plugin-1',
					'plugin-2',
				),
				'lite'  => array(
					'plugin-1',
				),
				'demo'  => false,
				'thumb' => false,
				'name'  => esc_html__( 'Skin Name', 'crocoblock-wizard' ),
				'type'  => 'skin', // skin or model
			),
		),
	) );

	// Or from remote url
	crocoblock_wizard()->settings->register_external_config( array(
		'skins' => array(
			'get_from' => URL which is returns JSON with skins configuration,
		)
	) );

}
```
