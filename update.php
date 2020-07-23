<?php
add_filter('plugins_api', 'mine_video_plugin_info', 30, 30);
function mine_video_plugin_info( $res, $action, $args ){
	if( 'plugin_information' !== $action ) {
		return false;
	}

	$plugin_slug = 'mine-video';

	if( $plugin_slug !== $args->slug ) {
		return false;
	}

	// trying to get from cache first
	if( false == $remote = get_transient( 'mine-video_update_' . $plugin_slug ) ) {
		$remote = wp_remote_get( 'http://wp.zwtt8.com/mv_update/info.json', array(
			'timeout' => 10,
			'headers' => array(
				'Accept' => 'application/json'
			) )
		);

	 	if ( !is_wp_error( $remote ) && isset( $remote['response']['code'] ) && $remote['response']['code'] == 200 && !empty( $remote['body'] ) ) {
	 		set_transient( 'mine-video_update_' . $plugin_slug, $remote, 3600 ); 
	 	}
	 }

	if ( !is_wp_error( $remote ) && isset( $remote['response']['code'] ) && $remote['response']['code'] == 200 && !empty( $remote['body'] ) ) {

		$remote = json_decode( $remote['body'] );
		$res = new stdClass();

		$res->name = $remote->name;
		$res->slug = $plugin_slug;
		$res->version = $remote->version;
		$res->tested = $remote->tested;
		$res->requires = $remote->requires;
		$res->author = '<a href="https://www.zwtt8.com">mine27</a>';
		$res->author_profile = 'https://www.zwtt8.com';
		$res->download_link = $remote->download_url;
		$res->trunk = $remote->download_url;
		$res->requires_php = '5.3';
		$res->last_updated = $remote->last_updated;
		$res->sections = array(
			'description' => $remote->sections->description,
			'installation' => $remote->sections->installation,
			'changelog' => $remote->sections->changelog
		);
		if( !empty( $remote->sections->screenshots ) ) {
			$res->sections['screenshots'] = $remote->sections->screenshots;
		}

		//$res->banners = array(
		//	'low' => 'https://YOUR_WEBSITE/banner-772x250.jpg',
		//  'high' => 'https://YOUR_WEBSITE/banner-1544x500.jpg'
		//);
		return $res;

	}

	return false;

}

add_filter( 'site_transient_update_plugins', 'mine_video_push_update' );

function mine_video_push_update( $transient ){
	if ( ! is_object( $transient ) )
			return $transient;

	if ( ! isset( $transient->response ) || ! is_array( $transient->response ) )
		$transient->response = array();

	if( false == $remote = get_transient( 'mine-video_upgrade_mine-video' ) ) {
		$remote = wp_remote_get( 'http://wp.zwtt8.com/mv_update/info.json', array(
			'timeout' => 10,
			'headers' => array(
				'Accept' => 'application/json'
			) )
		);

	 	if ( !is_wp_error( $remote ) && isset( $remote['response']['code'] ) && $remote['response']['code'] == 200 && !empty( $remote['body'] ) ) {
	 		set_transient( 'mine-video_upgrade_mine-video', $remote, 3600 ); 
	 	}
	 }

	if( $remote ) {
		$remote = json_decode( $remote['body'] );
		if( $remote && version_compare( MINEVIDEO_VERSION, $remote->version, '<' )
			&& version_compare($remote->requires, get_bloginfo('version'), '<' ) ) {
				$res = new stdClass();
				$res->slug = 'mine-video';
				$res->plugin = 'mine-video/mine-video.php';
				$res->new_version = $remote->version;
				$res->tested = $remote->tested;
				$res->requires_php = '5.3';
				$res->url = 'https://www.zwtt8.com/wordpress-plugin-mine-video/';
				$res->package = $remote->download_url;
				$res->compatibility = new stdClass();

           		$transient->response[$res->plugin] = $res;
           	}

	}
    return $transient;
}

add_action( 'upgrader_process_complete', 'mine_video_after_update', 10, 2 );

function mine_video_after_update( $upgrader_object, $options ) {
	if ( $options['action'] == 'update' && $options['type'] === 'plugin' )  {
		delete_transient( 'mine-video_upgrade_mine-video' );
	}
}