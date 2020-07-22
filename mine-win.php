<?php
if (!defined('ABSPATH')) exit;
$mine_video_player_from = explode("\n", get_option('mine_video_player_from'));
$players_str = '';
foreach($mine_video_player_from as $p){
	if($p){
		$tmp = explode('==', $p);
		if(count($tmp)>=2){
			$players_str .= '<option value="'.$tmp[0].'">'.$tmp[1].'</option>';
		}
	}
}
?><html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>添加视频</title>
	<script language="javascript" type="text/javascript" src="<?php echo get_option('siteurl'); ?>/wp-includes/js/tinymce/tiny_mce_popup.js"></script>
	<script language="javascript" type="text/javascript" src="<?php echo get_option('siteurl'); ?>/wp-includes/js/tinymce/utils/form_utils.js"></script>
	<script language="javascript" type="text/javascript" src="<?php echo MINEVIDEO_URL; ?>/js/tinymce.js"></script>
	<link rel="stylesheet" type="text/css" href="<?php echo MINEVIDEO_URL; ?>/js/layui/css/layui.css" />
	<style>.minedisplay{display:none;}</style>
	<base target="_self" />
</head>
<body id="link" onload="tinyMCEPopup.executeOnLoad('init();');" >
	
<div class="layui-tab layui-tab-brief" lay-filter="videoGroup" style="margin:10px auto;" lay-allowclose="true">
    <button class="layui-btn" id="addPlayer" style="margin-top: 50px;position: absolute;right: 12px;">新增一组</button>
  <ul class="layui-tab-title">
    <li lay-id="1" class="layui-this" lay-allowclose="false">来源1</li>
  </ul>
  <div class="layui-tab-content layui-form">
    <div class="layui-tab-item layui-show">
		<div class="layui-form-item">
			<label class="layui-form-label">播放来源</label>
			<div class="layui-inline">
				<select id="mvtype1" name="mvtype" fwin="winbox">
				<?php echo $players_str;?>
				</select>
			</div>
		</div>
		<div class="layui-form-item">
			<label class="layui-form-label">视频ID/URL<button class="layui-btn layui-btn-xs" onclick="checkMineVideo(1)" >校正</button></label>
			<div class="layui-input-block">
				<textarea type="text" name="mvurl" id="mvurl1" placeholder="请填写视频ID/URL 一行一条数据" class="layui-textarea" style="min-height:160px;"></textarea>
			</div>
		</div>
	</div>
  </div>
</div>
<div class="layui-form">

		<div class="layui-form-item">
			<label class="layui-form-label">默认参数</label>
			<div class="layui-input-block">
				 <input type="radio" name="defaultpara" id="defaultpara1" onclick="isDefaultPara(1);" value="1" title="是" lay-filter="defaultpara" checked="checked" >	
				 <input type="radio" onclick="isDefaultPara(0);" name="defaultpara" id="defaultpara0" value="0" title="否" lay-filter="defaultpara">
			</div>
		</div>
		<div class="layui-form-item minedisplay">
			<label class="layui-form-label">PC高度</label>
			<div class="layui-input-block">
				<input type="text" name="mvheight" id="mvheight" value="500" placeholder="默认为500" size="20" class="layui-input"  value="<?php $mvh = get_option('mine_video_player_height'); echo empty($mvh)?'300':$mvh;?>">
			</div>
		</div>
		<div class="layui-form-item minedisplay">
			<label class="layui-form-label">手机高度</label>
			<div class="layui-input-block">
				<input type="text" name="mvmheight" id="mvmheight" value="320" placeholder="默认为300" size="20" class="layui-input"  value="<?php $mvmh = get_option('mine_video_player_height_m'); echo empty($mvmh)?'300':$mvmh;?>">
			</div>
		</div>
		<div class="layui-form-item">
			<div class="layui-input-block">
				<button class="layui-btn" lay-submit="" lay-filter="formDemo" id="e_minevideopro_btn_charu">添加视频</button>
			</div>
		</div>
		<hr class="layui-bg-green">
</div>
<script src="<?php echo MINEVIDEO_URL; ?>/js//layui/layui.js"></script>
<script>
layui.use(['form','element'], function(){
	var $ = layui.jquery
	,element = layui.element
	,form = layui.form;

	var tabid = $('.layui-tab-title li').length+1;
	$('#addPlayer').click(function(){
		element.tabAdd('videoGroup', {
			title: '来源'+ tabid
			,content: '<div class="layui-form-item"><label class="layui-form-label">播放来源'+tabid+'<\/label><div class="layui-inline"><select id="mvtype'+tabid+'" name="mvtype" fwin="winbox"><?php echo str_replace(["\r","/"],["","\/"],$players_str);?><\/select><\/div><\/div><div class="layui-form-item"><label class="layui-form-label">视频ID|URL<button class="layui-btn layui-btn-xs" onclick="checkMineVideo('+tabid+')" >校正<\/button><\/label><div class="layui-input-block"><textarea type="text" name="mvurl" id="mvurl'+tabid+'" placeholder="请填写视频ID|URL 一行一条数据" class="layui-textarea" style="min-height:160px;"><\/textarea><\/div><\/div>'
			,id: tabid
		});
		element.tabChange('videoGroup', tabid);
		tabid++;
		form.render();
		$('.layui-tab-title li[lay-id=1]').children().remove();
	});
	$('#e_minevideopro_btn_charu').click(function(){
		var mvurl = ' vid="';
		var mvheight = " height=\"" + $("#mvheight").val()+"\"";
		var mvmheight = " height_wap=\"" + $("#mvmheight").val()+"\"";
		var mvtype = ' type="';
		for(var tid=1;tid<tabid;tid++){
			mvurl += $("#mvurl"+tid).val().replace(/\r|\n/g,',')+'^';
			mvtype += $("#mvtype"+tid).val()+'^';
		}
		mvurl=mvurl.substring(0,mvurl.length-1)+'"';
		mvtype=mvtype.substring(0,mvtype.length-1)+'"';
		var para = '';
		if(document.getElementById("defaultpara0").checked){
			 para =  mvheight + mvmheight;
		}
		var shortcode = "" ;
		shortcode = shortcode+"[mine_video "+ mvtype + mvurl + para + "][/mine_video]";
		window.tinyMCE.activeEditor.insertContent(shortcode);
		tinyMCEPopup.editor.execCommand('mceRepaint');
		tinyMCEPopup.close();
		return;
	});
	form.on('radio(defaultpara)', function (data) {
		if(data.value=='1'){
			$('.minedisplay').hide();
		}
		else{
			$('.minedisplay').show();
		}
	});
	$('.layui-tab-title li[lay-id=1]').children().remove();
	$('.layui-tab-title li[lay-id=1]').on('DOMNodeInserted',function(){
        $('.layui-tab-title li[lay-id=1]').children().remove();
    });
});
</script>


</body>
</html>