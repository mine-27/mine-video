function insertMineVideocode() {
	if(document.getElementById("mvurl").value.replace(/(^\s*)|(\s*$)/g, "")==''){
		document.getElementById("mvurl").focus();
		return;
	}
    var mvurl = " vid=\"" + document.getElementById("mvurl").value.replace(/\r|\n/g,',')+"\"";
	var mvheight = " height=\"" + document.getElementById("mvheight").value+"\"";
	var mvmheight = " height_wap=\"" + document.getElementById("mvmheight").value+"\"";
	var mvtype = " type=\"" + document.getElementById("mvtype").value+"\"";
	
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
}
function checkMineVideo(tid){
	var arr1,s1,s2,urlarr,urlarrcount;
	s1 = document.getElementById('mvurl'+tid).value; s2="";
	if (s1.length==0){document.getElementById('mvurl'+tid).focus();return false;}
	s1 = s1.replace('\r',"");
	arr1 = s1.split('\n');
	arr1len = arr1.length;
	var js = 0;
	for(j=0;j<arr1len;j++){
		if(arr1[j].length>0){
			urlarr = arr1[j].split('$'); urlarrcount = urlarr.length-1;
			if(urlarrcount==0){
				arr1[j]= getPatName(js,arr1len,arr1[j]) + '$' + arr1[j];
			}
			s2+=arr1[j]+"\r\n";
			js++;
		}
	}
	document.getElementById('mvurl'+tid).value=s2.trim();
}
function getPatName(n,l,s){
	var res="";
	res = '第' + (n<9 ? '0' : '') + (n+1) + '集';
	return res;
}
function isDefaultPara(t){
	if(t=='1'){
		$('.minedisplay').hide();
	}
	else{
		$('.minedisplay').show();
	}
}