
var webPath = "http://m2.nadoo.cn/p/zyw";
var imgUploadFile = ""; // 图片上传后的文件夹目录
var imgUploadServer = ""; // 图片上传接口地址
var swfUrl = "__PUBLIC__/statics/swf"; // flash文件夹目录
var mp3UploadServer = ""; // mp3上传接口地址
var mp3UploadFile = ""; // mp3上传后的文件夹目录

$(function(){

	$("#tabList").click("li",addClass)
	function addClass(){
	setTimeout(function(){
	$("#tabList").find("li").removeClass("current")
		$(this).addClass("current")
	}
	},30)
})