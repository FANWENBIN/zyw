
var webPath = "http://m2.nadoo.cn/p/zyw";
var imgUploadFile = ""; // 图片上传后的文件夹目录
var imgUploadServer = ""; // 图片上传接口地址
var swfUrl = "__PUBLIC__/statics/swf"; // flash文件夹目录
var mp3UploadServer = ""; // mp3上传接口地址
var mp3UploadFile = ""; // mp3上传后的文件夹目录

$(function(){
	// 获取url参数
	var getQueryString = function(name, url){
		if (url) {
			url = url.split("?")[1];
		}
        var str = url || window.location.search.substr(1);
        if (str.indexOf(name) != -1) {
            var pos_start = str.indexOf(name) + name.length + 1;
            var pos_end = str.indexOf("&", pos_start);
            if (pos_end == -1) {
                return decodeURI(str.substring(pos_start));
            } else {
                return decodeURI(str.substring(pos_start, pos_end));
            }
        } else {
            return "";
        }
    }
	
	
	
	
	$("#tabList").click("li",changeCurrentTab);
	
	//改变当前导航栏的状态
	function changeCurrentTab(){
		var c = getQueryString("c");
		switch(c){
			case "":
			break;
		}
	}
})