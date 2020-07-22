(function() {
	// Load plugin specific language pack
	//tinymce.PluginManager.requireLangPack('minevideo');
	tinymce.create('tinymce.pluginss.minevideo', {
		
		init : function(ed, murl) {
		// Register the command so that it can be invoked by using tinyMCE.activeEditor.execCommand('mceExample');
			pluginurl = murl.replace("/js",""); 
			ed.addCommand('minevideo', function() {
				ed.windowManager.open({
					file : 'admin.php?page=mine_video&action=win',
					width : 600,
					height : 450,
					inline : 1
				}, {
					plugin_url : murl // Plugin absolute URL
				});
			});

			// Register example button
			ed.addButton('minevideo', {
				title : 'Mine视频',
				cmd : 'minevideo',
				image : pluginurl + '/images/minevideo.ico'
			});
			
			// Add a node change handler, selects the button in the UI when a image is selected
			ed.onNodeChange.add(function(ed, cm, n) {
				cm.setActive('minevideo', n.nodeName == 'IMG');
			});
		},
		createControl : function(n, cm) {
			return null;
		},
		getInfo : function() {
			return {
					longname  : 'minevideo',
					author 	  : 'mine27',
					authorurl : 'https://www.zwtt8.com/',
					infourl   : 'https://www.zwtt8.com/wordpress-plugin-mine-video/',
					version   : "2.3"
			};
		}
	});

	// Register plugin
	tinymce.PluginManager.add('minevideo', tinymce.pluginss.minevideo);
})();


