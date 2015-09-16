$(function(){


	// 图文消息列表
	var list = {
		init: function(){
			list.dom = $("#wechatArticleAssetsList");
			if (list.dom.find(".itemList .item").length){
				$.waterfall.init(list.dom.find(".itemList"), "item", 3, 289, 15);
			}
			list.dom.on("click", ".itemList .item .tools .del", list.onItemDel);
			$.cookieTips.check();
		},
		onItemDel: function(e){
			var id = $(this).data("id");
			$.alert({
				title: "温馨提示",
				txt: "确定要删除此素材吗？",
				btnY: "删除",
				btnYcss: "btnC",
				btnN: "取消",
				callbackY: function(){
					$.ajaxSubmit({
						url: "_delete.php",
						data: {id: id},
						success: function(d){
							if (d.status == 0){
								$.cookieTips.set("成功删除一条图文消息");
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
		}
	};
	list.init();


});