function MinePlayer(pid){
	this.Num = 0;
	this.pid = pid;
	this.Videos=eval('minevideo_vids_'+pid);
	for(var vg in this.Videos){
		this.vtype=vg;break;
	}
	this.PlayUrlLen = this.Videos.length;
}
MinePlayer.prototype.GoPreUrl = function(){
	if (this.Num - 1 >= 0) {
		this.Go(this.Num - 1);
	}
};
MinePlayer.prototype.GoNextUrl = function(){
	if (this.Num + 1 < this.PlayUrlLen) {
		this.Go(this.Num + 1);
	}
};
MinePlayer.prototype.Go = function(n,t){
	if(!t){
		t=this.vtype;
	}else{
		this.vtype = t;
	}
	var pstr = document.getElementById('mine_ifr_'+t+'_'+this.pid).value;
	var cur = eval('this.Videos.'+t)[n];
	
	document.getElementById("topdes_"+this.pid).innerHTML = '' + eval('mine_playing_'+this.pid) + cur.pre + '';
	if(pstr == 'dplayer'){
		if(!this.dp){
			document.getElementById('playleft_'+this.pid).innerHTML = '';
			this.dp = new CBPlayer({container: document.getElementById("playleft_"+this.pid),autoplay: true,hotkey: true,video: {url:unescape(cur.video)}});
		}else this.dp.switchVideo({url:unescape(cur.video)});
	}else if(pstr == 'dplayer_live'){
		if(!this.dp){
			document.getElementById('playleft_'+this.pid).innerHTML = '';
			this.dp = new CBPlayer({container: document.getElementById("playleft_"+this.pid),autoplay: true,hotkey: true,live: true,video: {url:unescape(cur.video)}});
		}else this.dp.switchVideo({url:unescape(cur.video)});
	}else{
		this.dp = false;
		pstr = pstr.replace('{type}', t);
		pstr = pstr.replace('{vid}', cur.video);
		document.getElementById('playleft_'+this.pid).innerHTML = pstr;
	}
	this.Num = n;
	var bottoma = document.getElementById('MineBottomList_'+t+'_'+this.pid).getElementsByTagName('a');
	for(var i = 0; i<bottoma.length; i++){
		if(i==parseInt(n)) bottoma[i].className = bottoma[i].className.replace('list_on', '') + ' list_on';
		else bottoma[i].className = bottoma[i].className.replace('list_on', '');
	}
};