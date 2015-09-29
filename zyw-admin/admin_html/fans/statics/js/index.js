define(function(require, exports, module){
    require("common");

	$(function(){

		var page = {
			init: function(){
				page.table = $("#userTable");
				page.table.find("thead .th1 a.all").on("click", page.checkAll);
				page.table.find("tbody .t1 a.js_checkbox").on("click", page.checkOne);
				page.table.find("thead select").change(page.allSelectChange);
				page.table.find("tbody select").change(page.selectChange);
				$("#appendTeam").on("click", page.appendTeam);
				$("#renameTeam").on("click", page.renameTeam);
				$.cookieTips.check();
			},
			checkOne: function(){
				page.checkInputChecked();
			},
			checkAll: function(){
				if (page.table.find("thead .th1 input.all")[0].checked){
					page.table.find("tbody .t1 input").attr("checked", "true").next(".js_checkbox").addClass("js_checkboxChecked");
				} else {
					page.table.find("tbody .t1 input").removeAttr("checked").next(".js_checkbox").removeClass("js_checkboxChecked");
				}
				page.checkInputChecked();
			},
			checkInputChecked: function(){
				page.openidArr = [];
				page.groupArr = [];
				page.table.find("tbody .t1 input").each(function(){
					var th = $(this);
					if (th[0].checked){
						page.openidArr.push(th.data("openid"));
						page.groupArr.push(th.data("gid"));
					}
				});
				if (page.openidArr.length != 0){
					page.table.find("thead .th2 select.team").removeAttr("disabled").next("div.team").removeClass("js_selectDisabled");
				} else {
					page.table.find("thead .th2 select.team").attr("disabled", "disabled").next("div.team").addClass("js_selectDisabled");
				}
			},
			selectChange: function(){
				var th = $(this), tr = th.parents("tr");
				var oid = tr.find(".t1 input").data("openid");
				var group = tr.find(".t1 input").data("gid");
				var gid = th.val();
				$.ajaxSubmit({
					url: "_moveuser.php",
					data: {openid: oid, group: group, gid: gid},
					success: function(d){
						if (d.status == 0){
							$.cookieTips.set("用户移动分组成功");
						} else {
							$.tips.error(d.msg);
						}
					},
					error: function(){
						$.tips.error("系统错误，请稍后再试！");
					}
				});
			},
			allSelectChange: function(){
				var gid = $(this).val();
				if (gid != ""){
					$.ajaxSubmit({
						url: "_moveuser.php",
						data: {openid: page.openidArr, group: page.groupArr, gid: gid},
						success: function(d){
							if (d.status == 0){
								$.cookieTips.set("用户移动分组成功");
							} else {
								$.tips.error(d.msg);
							}
						},
						error: function(){
							$.tips.error("系统错误，请稍后再试！");
						}
					});
				}
				
			},
			appendTeam: function(){
				$.alert({
					title: "添加分组",
					txt: "<p>分组名称不多于6个汉字或12个字母</p><input type='text' class='input' />",
					btnY: "添加",
					btnN: "取消",
					css: "pop-alert-appendWechatUserTeam",
					callbackY: function(){
						var input = $("#pop-alert .pop .bd .input");
						if (input.inputEmpty()){
							input.inputError("分组名称不能为空");
							return false;
						} else if (input.inputLengthOverflow(12)){
							input.inputError("分组名称不多于6个汉字或12个字母");
							return false;
						} else {
							var val = input.val();
							val = $.trim(val);
							$.ajaxSubmit({
								url: "_addgroup.php",
								data: {'name': val},
								success: function(d){
									if (d.status == 0){
										$("#userTeam ul").append("<li><a href='?gid=" + d.data.gid + "'>" + val + "<span>(0)</span></a></li>");
	                                    $.tips.success("分组添加成功");
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
			renameTeam: function(){
				var gid = $(this).data("group");
				$.alert({
					title: "重命名分组",
					txt: "<p>分组名称不多于6个汉字或12个字母</p><input type='text' class='input' />",
					btnY: "确定",
					btnN: "取消",
					css: "pop-alert-appendWechatUserTeam",
					callbackY: function(){
						var input = $("#pop-alert .pop .bd .input");
						if (input.inputEmpty()){
							input.inputError("分组名称不能为空");
							return false;
						} else if (input.inputLengthOverflow(12)){
							input.inputError("分组名称不多于6个汉字或12个字母");
							return false;
						} else {
							var val = input.val();
							val = $.trim(val);
							$.ajaxSubmit({
								url: "_addgroup.php",
								data: {group: gid, name: val},
								success: function(d){
									if (d.status == 0){
										$.cookieTips.set("分组重命名成功");
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
			}
		};
		page.init();


	});

});