define(function(require, exports, module){
    require("common");

	$(function(){


		// 图片列表
		var page = {
			init: function(){
				page.dom = $("#wechatPhotoAssetsList");
				if (page.dom.find(".itemList .item").length){
					$.waterfall.init(page.dom.find(".itemList"), "item", 4, 213, 15);
					page.dom.find(".itemList").css("visibility", "visible");
					page.dom.on("click", ".itemList .item .tools .del", page.onItemDel).on("click", ".itemList .item .tools .edit", page.onItemEdit);
				}
				$("#flashupload").on("click", page.upload);
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
			onItemEdit: function(){
				var id = $(this).data("id");
				var name = $(this).parents(".item").find(".name h6");
				$.alert({
					title: "编辑名称",
					txt: "<p>名称不多于8个汉字或16个字母</p><input type='text' class='input' value='" + name.html() + "' />",
					btnY: "确定",
					btnN: "取消",
					css: "pop-alert-photoAssetsItemRename",
					callbackY: function(){
						var input = $("#pop-alert .pop .bd .input");
						if (input.inputEmpty()){
							input.inputError("名称不能为空");
							return false;
						} else if (input.inputLengthOverflow(12)){
							input.inputError("名称不多于6个汉字或12个字母");
							return false;
						} else {
							var val = input.val();
							val = $.trim(val);
							$.ajaxSubmit({
								url: "_aaa.php",
								data: {},
								success: function(d){
									if (d.status == 0){
										name.html(val);
									} else {
										$.tips.error(d.msg);
									}
								},
								error: function(){
									$.tips.error("系统错误，请稍后再试！");
								}
							});
						}
					}
				});
			},
			upload: function(){
				$.imgUploadPop({
	                id: "media-image",
	                root: "media-image",
					width: 500,
					height: -1,
					complete: page.uploadComplete
				});
			},
			uploadComplete: function(d){
				if (d.status == 0){
					$.ajaxSubmit({
						url: "_addmedia.php",
						data: {name: d.data.name, type: "image"},
						success: function(d){
							if (d.status == 0){
								$.cookieTips.set("成功添加一张图片");
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
		page.init();

	});

});