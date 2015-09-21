$(function(){


	// 图片列表
	var list = {
		init: function(){
			list.dom = $("#wechatPhotoAssetsList");
			list.dom.on("click", ".itemList .item .tools .del", list.onItemDel);
			$("#flashupload").on("click", list.upload);
			$.cookieTips.check();
		},
		onItemDel: function(e){
			var id = $(this).data("id");
			$.alert({
				title: "温馨提示",
				txt: "确定要删除此图片吗？",
				btnY: "删除",
				btnYcss: "btnC",
				btnN: "取消",
				callbackY: function(){
					$.ajaxSubmit({
						url: "_delete.php",
						data: {id: id},
						success: function(d){
							if (d.status == 0){
								$.cookieTips.set("成功删除一张图片");
							} else {
								$.tips.error(d.msg);
							}
						},
						error: function(){
							$.tips.error("系统错误，请稍后再试！");
						}
					});
				}
			});
		},
		upload: function(){
			$.imgUploadPop({
				width: 500,
				height: -1,
				complete: list.uploadComplete
			});
		},
		uploadComplete: function(d){
			if (d.status == 0){
				$.ajaxSubmit({
					url: "_addmedia.php",
					data: {name: d.name, type: "image"},
					success: function(d){
						if (d.status == 0){
							var html = "<div class='item'><div class='img'><img src='" +imgUploadFile+ d.name + "' /></div>";
							html += "<div class='tools'><a href='javascript:void(0)' class='del'></a></div></div>";
							list.dom.find(".itemList").prepend(html);
						} else {
							$.tips.error(d.msg);
						}
					},
					error: function(){
						$.tips.error("系统错误，请稍后再试！");
					}
				});
			} else {
				$.tips.error(d.msg);
			}
		}
	};
	list.init();




});