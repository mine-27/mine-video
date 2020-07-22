<?php
class Mine_Video{
	public function __construct(){
		add_action('admin_menu',			array($this, 'minevideo_admin_menu'));//admin menu
		add_action('admin_init',			array($this, 'minevideo_admin_style'));// admin style
		add_shortcode('mine_video',			array($this, 'minevideo_shortcode'));//register shortcode
		add_filter("mce_external_plugins",	array($this, "add_minevideo_tinymce_plugin"), 9999);
		add_filter('mce_buttons',			array($this, 'register_minevideo_button'), 9999);
		add_filter('plugin_action_links',	array($this, 'add_minevideo_settings_link'), 10, 2);
		register_activation_hook(__FILE__,	array($this, 'register_minevideo_init'));
	}
	
	public function minevideo_admin_menu() {
		add_menu_page('Mine视频播放', 'Mine视频播放', 'manage_options', 'mine_video', array($this, 'minevideo_options'), MINEVIDEO_URL.'/images/minevideo.png');
	}

	public function minevideo_admin_style(){
		wp_enqueue_style('mine_setting_layui',  MINEVIDEO_URL.'/js/layui/css/layui.css');
		wp_enqueue_script('mine_setting_layuijs', MINEVIDEO_URL.'/js/layui/layui.js');
	}

	public function add_minevideo_tinymce_plugin($plugins) {
		$plugins['minevideo'] = MINEVIDEO_URL.'/js/editor_plugin.js';
		return $plugins;
	}

	public function register_minevideo_button($buttons) {
		array_push($buttons, "separator", "minevideo");
		return $buttons;
	}

	public function add_minevideo_settings_link($links, $file) {
		if (strpos($file, 'mine-video') !== false && is_plugin_active($file)){
			$settings_link = '<a href="'.wp_nonce_url("admin.php?page=mine_video").'">Settings</a>';
			array_unshift($links, $settings_link);
		}
		return $links;
	}
	
	public function minevideo_options() {
		if (!current_user_can('manage_options'))  {
			wp_die(__('您没有操作权限！'));
		}
		if(isset($_POST['mine_video_player_height'])) {
			$mine_video_player_jxapi =		sanitize_text_field($_POST['mine_video_player_jxapi']);
			$mine_video_player_from =		sanitize_textarea_field($_POST['mine_video_player_from']);
			$mine_video_player_height =		sanitize_text_field($_POST['mine_video_player_height']);
			$mine_video_player_height_m =	sanitize_text_field($_POST['mine_video_player_height_m']);
			$mine_video_playertop =			sanitize_text_field($_POST['mine_video_playertop']);

			update_option('mine_video_player_jxapi', $mine_video_player_jxapi);
			update_option('mine_video_player_from', $mine_video_player_from);
			update_option('mine_video_player_height', $mine_video_player_height);
			update_option('mine_video_player_height_m', $mine_video_player_height_m);
			update_option('mine_video_playertop', $mine_video_playertop);
	?>
	<div class="updated"><p><strong>保存成功！</strong></p></div>
	<?php
		}
		echo '<div class="wrap">';
		echo "<h2>Mine视频播放</h2>";
	?>
	<form name="form1" method="post" class="layui-form" action="">
	<div class="layui-tab layui-tab-brief" lay-filter="docDemoTabBrief">
	  <ul class="layui-tab-title">
		<li class="layui-this">插件设置</li>
	  </ul>
	  <div class="layui-tab-content">
		<div class="layui-tab-item layui-show">
			<div class="layui-form-item">
				<label class="layui-form-label">通用接口</label>
				<div class="layui-input-block">
					<input type="text" name="mine_video_player_jxapi" value="<?php echo get_option('mine_video_player_jxapi');?>"  class="layui-input">
				</div>
			</div>
			<div class="layui-form-item layui-form-text">
				<label class="layui-form-label">播放来源</label>
				<div class="layui-input-block">
					<textarea placeholder="请输入内容" class="layui-textarea" name="mine_video_player_from" style="min-height:200px;"><?php echo get_option('mine_video_player_from');?></textarea>
				</div>
			</div>
			<div class="layui-form-item">
				<div class="layui-inline">
					<label class="layui-form-label">PC高度</label>
					<div class="layui-input-inline">
						<input type="tel" name="mine_video_player_height" autocomplete="off" class="layui-input" value="<?php echo get_option('mine_video_player_height');?>">
					</div>
				</div>
				<div class="layui-inline">
					<label class="layui-form-label">手机高度</label>
					<div class="layui-input-inline">
						<input type="text" name="mine_video_player_height_m" autocomplete="off" class="layui-input" value="<?php echo get_option('mine_video_player_height_m');?>">
					</div>
				</div>
			</div>
			<div class="layui-form-item">
				<label class="layui-form-label">头部信息</label>
				<div class="layui-input-block">
					<input type="radio" name="mine_video_playertop" value="show" title="显示" <?php if(get_option('mine_video_playertop')=='show')echo 'checked=""';?>>
					<input type="radio" name="mine_video_playertop" value="hide" title="隐藏" <?php if(get_option('mine_video_playertop')=='hide')echo 'checked=""';?>>
				</div>
			</div>
		</div>
	  </div>
	</div> 

	<hr class="layui-bg-green">

	<div class="layui-form-item">
		<div class="layui-input-block">
		<button type="submit" class="layui-btn"><?php esc_attr_e('Save Changes') ?></button>
	</div>

	</form>
	<script>
	layui.use(['form', 'element'], function(){
		var $ = layui.jquery
		,element = layui.element
		,form = layui.form; 
	});
	</script>
	</div>
	<?php
	}

	public function register_minevideo_init() {
		if(!get_option('mine_video_player_jxapi'))add_option('mine_video_player_jxapi','https://vip.52jiexi.top/?url={vid}');
		if(!get_option('mine_video_player_from'))add_option('mine_video_player_from','youku==优酷==https://vip.52jiexi.top/?url={vid}
iqiyi==爱奇异
qq==腾讯
sohu==搜狐
mgtv==芒果
weibo==微博==http://minevideo.sxl.me/api.php?url={vid}
m3u8==M3U8/Mp4==dplayer
iframe==IFrame==self
live==直播==dplayer_live');
		if(!get_option('mine_video_player_height'))add_option('mine_video_player_height','500');
		if(!get_option('mine_video_player_height_m'))add_option('mine_video_player_height_m','300');
		if(!get_option('mine_video_playertop'))add_option('mine_video_playertop','hide');
	}

	public function minevideo_shortcode($atts, $content=null){
		extract(shortcode_atts(array("type"=>'common'),$atts));

		$url = $content ? $content : ($atts['vid'] ? $atts['vid'] : '');
		if(!$url) return '视频ID/URL不能为空';
		if(wp_is_mobile()){
			$h = $atts['height_wap'] ? $atts['height_wap'] : (get_option('mine_video_player_height_m') ? get_option('mine_video_player_height_m') : '300');
		}
		else{
			$h = $atts['height'] ? $atts['height'] : (get_option('mine_video_player_height') ? get_option('mine_video_player_height') : '500');
		}
		$mine_video_player_jxapi = get_option('mine_video_player_jxapi') ? get_option('mine_video_player_jxapi') : '';
		$mine_video_player_from = get_option('mine_video_player_from');
		$mine_video_playlist_position = get_option('mine_video_playlist_position') ? get_option('mine_video_playlist_position') : 'bottom';
		$mine_video_playertop = get_option('mine_video_playertop') ? get_option('mine_video_playertop') : 'show';
		$parr = $this->minevideo_get_players($mine_video_player_from);
		$typearr = explode('^', $type);
		$type = $typearr[0];
		$typestr = '';
		$urlarr = explode('^', $url);
		$vlistarr = array();
		$vliststr = '';
		$jxapistr = '';
		$r = rand(1000,99999);
		$typelen = count($typearr);
		$vgshoworhide = '';
		for($ti=0;$ti<$typelen;$ti++){
			if($ti == 0){
				$typestr .= '<li class="layui-this">'.$parr[$typearr[$ti]].'</li>';
				$vliststr .= '<div class="layui-tab-item layui-show"><div id="MineBottomList_'.$typearr[$ti].'_'.$r.'" class="MineBottomList"><ul class="result_album" id="result_album_'.$typearr[$ti].'_'.$r.'">';
			}else{
				$typestr .= '<li>'.$parr[$typearr[$ti]].'</li>';
				$vliststr .= '<div class="layui-tab-item"><div id="MineBottomList_'.$typearr[$ti].'_'.$r.'" class="MineBottomList"><ul class="result_album" id="result_album_'.$typearr[$ti].'_'.$r.'">';
			}
			$vidgroup = explode(',', $urlarr[$ti]);
			$vidlen = count($vidgroup);
			if($typelen == 1 && $vidlen == 1) $vgshoworhide = 'display:none;';
			$jxapi_cur = trim($parr[$typearr[$ti].'_api']?$parr[$typearr[$ti].'_api']:$mine_video_player_jxapi);
			$isurlencode = true;
			if($jxapi_cur == 'self'){
					$jxapi_cur = '{vid}';
					$isurlencode = false;
			}
			
			for($vi=0;$vi<$vidlen;$vi++){
				$vidtemp = explode('$', $vidgroup[$vi]);
				if(!$vidtemp[1]){
					$vidtemp[1]=$vidtemp[0];
					$vidtemp[0]='第'.(intval($vi+0)<9?'0':'') . ($vi+1).'集';
				}
				$vlid = $vi;
				if(count($vlistarr[$typearr[$ti]])>$vi){
					$vlid = count($vlistarr[$typearr[$ti]]);
				}
				$vlistarr[$typearr[$ti]][] = array('id'=>$vlid, 'pre'=>$vidtemp[0],'video'=>($isurlencode?urlencode($vidtemp[1]):$vidtemp[1]));
				$vliststr .= '<li><a href="javascript:void(0)" onclick="MP_'.$r.'.Go('.$vlid.', \''.$typearr[$ti].'\');return false;">'.$vidtemp[0].'</a></li>';
			}
			$vliststr .= '</ul></div></div>';
			switch($jxapi_cur){
				case 'dplayer':
					$jxapistr .= '<link href="'.MINEVIDEO_URL.'/dplayer/CBPlayer.min.css" rel="stylesheet"><script src="'.MINEVIDEO_URL.'/dplayer/hlsjs-p2p-engine.min.js"></script><script src="'.MINEVIDEO_URL.'/dplayer/hls.js"></script><script src="'.MINEVIDEO_URL.'/dplayer/cbplayer2@latest.js"></script><input type="hidden" id="mine_ifr_'.$typearr[$ti].'_'.$r.'" value=\'dplayer\'/>';
					break;
				case 'dplayer_live':
					$jxapistr .= '<link href="'.MINEVIDEO_URL.'/dplayer/CBPlayer.min.css" rel="stylesheet"><script src="'.MINEVIDEO_URL.'/dplayer/hlsjs-p2p-engine.min.js"></script><script src="'.MINEVIDEO_URL.'/dplayer/hls.js"></script><script src="'.MINEVIDEO_URL.'/dplayer/cbplayer2@latest.js"></script><input type="hidden" id="mine_ifr_'.$typearr[$ti].'_'.$r.'" value=\'dplayer_live\'/>';
					break;
				default:
					$jxapistr .= '<input type="hidden" id="mine_ifr_'.$typearr[$ti].'_'.$r.'" value=\'<i'.'fr'.'ame border="0" src="'.$jxapi_cur.'" width="100%" height="'.$h.'" marginwidth="0" framespacing="0" marginheight="0" frameborder="0" scrolling="no" vspale="0" noresize="" allowfullscreen="true" id="minewindow_'.$typearr[$ti].'_'.$r.'"></'.'if'.'rame>\'/>';
			}
		}
		
		$style = '<link rel="stylesheet" id="minevideo-css" href="'.MINEVIDEO_URL.'/css/minevideo.css?v='.MINEVIDEO_VERSION.'" type="text/css" media="all" />';
		$script = '<script type="text/javascript">var mine_di_'.$r.'="第",mine_ji_'.$r.'="集",mine_playing_'.$r.'="正在播放 ";var minevideo_type_'.$r.'="'.$type.'";</script><script>var minevideo_vids_'.$r.'='.json_encode($vlistarr).';var MP_'.$r.' = new MinePlayer('.$r.');MP_'.$r.'.Go(0);</script>';
		$player = '<div id="MinePlayer_'.$r.'" class="MinePlayer"><table border="0" cellpadding="0" cellspacing="0" width="100%"><tbody><tr'.($mine_video_playertop=='show'?'':' style="display:none;"').'><td height="26"><table border="0" cellpadding="0" cellspacing="0" id="playtop_'.$r.'" class="playtop"><tbody><tr><td id="topleft"><a target="_self" href="javascript:void(0)" onclick="MP_'.$r.'.GoPreUrl();return false;">上一集</a> <a target="_self" href="javascript:void(0)" onclick="MP_'.$r.'.GoNextUrl();return false;">下一集</a></td><td id="topcc"><div id="topdes_'.$r.'" class="topdes">正在播放</div></td><td id="topright_'.$r.'" class="topright"></td></tr></tbody></table></td></tr><tr><td><table border="0" cellpadding="0" cellspacing="0"><tbody><tr><td id="playleft_'.$r.'" class="playleft" valign="top" style="height:'.$h.'px;"></td><td id="playright_'.$r.'" valign="top"></td></tr></tbody></table></td></tr></tbody></table></div>'.$jxapistr.'<link rel="stylesheet" type="text/css" href="'.MINEVIDEO_URL.'/js/layui/css/layui.css" /><div class="layui-tab layui-tab-brief" lay-filter="videoGroup" style="margin:10px auto;'.$vgshoworhide.'"><ul class="layui-tab-title">'.$typestr.'</ul><div class="layui-tab-content" style="height: auto;padding-left:0;">'.$vliststr.'</div></div><script src="'.MINEVIDEO_URL.'/js/layui/layui.js"></script><script type="text/javascript" src="'.MINEVIDEO_URL.'/js/mineplayer.js?v='.MINEVIDEO_VERSION.'" charset="UTF-8"></script><script>layui.use(\'element\', function(){var $ = layui.jquery,element = layui.element;$(".layui-tab-content a").click(function(){$(".layui-tab-content a").removeClass("list_on");$(this).addClass("list_on");});});</script>';
		return $style.$player.$script;
	}

	public function minevideo_get_players($players){
		$players = explode("\n", $players);
		$arr = array();
		foreach($players as $p){
			if($p){
				$tmp = explode('==', $p);
				if(count($tmp)>=2){
					$arr[$tmp[0]] = $tmp[1];
					$arr[$tmp[0].'_api'] = isset($tmp[2])?$tmp[2]:'';
				}
			}
		}
		return $arr;
	}
}