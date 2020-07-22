<?php
/*
Plugin Name: Mine Video Player
Plugin URI: https://www.zwtt8.com/wordpress-plugin-mine-video/
Description: 轻松实现视频在wp页面播放,支持剧集列表,支持多组视频来源,支持m3u8/mp4等网页视频格式,同时支持直播源,也可将主流视频站的视频通过第三方解析程序来播放,视频来源和解析接口可随意配置.
Version: 2.5
Author: mine27
Author URI: https://www.zwtt8.com/
*/
if(!defined('ABSPATH'))exit;

define('MINEVIDEO_VERSION', '2.5');
define('MINEVIDEO_URL', plugins_url('', __FILE__));
define('MINEVIDEO_PATH', dirname(__FILE__));
define('MINEVIDEO_ADMINURL', admin_url());

require MINEVIDEO_PATH . '/Mine_Video.class.php';

$action = isset($_GET['action'])?$_GET['action']:'';
switch($action){
	case 'win':
		include 'mine-win.php';
		exit;
		break;
}
if (class_exists('Mine_Video')) {
	$minevideo = new Mine_Video();
}

?>