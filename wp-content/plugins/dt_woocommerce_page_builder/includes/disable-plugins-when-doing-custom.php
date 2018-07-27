<?php
class DTWPB_Disable_Plugins_When_Custom {
	static $instance;
	private $disabled = array( );
	/**
	 * Sets up the options filter, and optionally handles an array of plugins to disable
	 * @param array $disables Optional array of plugin filenames to disable
	 */
	public function __construct( Array $disables = NULL ) {
		// Handle what was passed in
		if( is_array( $disables ) ) {
			foreach( $disables as $disable )
				$this->disable( $disable );
		}
		// Add the filter to both single site and multisite plugin lists.
		add_filter( 'option_active_plugins', array( $this, 'do_disabling' ), 10, 1 );
		add_filter( 'site_option_active_sitewide_plugins', array( $this, 'do_disabling' ), 10, 1 );
		// Allow other plugins to access this instance
		self::$instance = $this;
	}
	/**
	 * Adds a filename to the list of plugins to disable
	 */
	public function disable( $file ) {
		$this->disabled[] = $file;
	}
	/**
	 * Hooks in to the option_active_plugins and site_option_active_sitewide_plugins
	 * filter and does the disabling
	 * @param array $plugins WP-provided list of plugin filenames
	 * @return array The filtered array of plugin filenames
	 */
	public function do_disabling( $plugins ) {
		if( count( $this->disabled ) ) {
			foreach( (array)$this->disabled as $plugin ) {
				if( current_filter() == 'option_active_plugins' )
					$key = array_search( $plugin, $plugins );
				else
					$key = !empty( $plugins[$plugin] ) ? $plugin : false;
				if( false !== $key )
					unset( $plugins[$key] );
			}
		}
		return $plugins;
	}
}

/* Begin customization */
$_pluginsToAutoDisable = array(
	'wordpress-seo/wp-seo.php',
);

$DTWPB_Disable = new DTWPB_Disable_Plugins_When_Custom( $_pluginsToAutoDisable );
