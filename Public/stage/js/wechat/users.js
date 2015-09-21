$(function(){

	var users = {
		init: function(){
			users.table = $("#userTable");
			users.table.find("thead .th1 a.all").on("click", users.checkAll);
			users.table.find("tbody .t1 a.js_checkbox").on("click", users.checkOne);
			users.table.find("thead select").change(users.allSelectChange);
			users.table.find("tbody select").change(users.selectChange);
			$("#appendTeam").on("click", users.appendTeam);
			$("#renameTeam").on("click", users.renameTeam);
			$.cookieTips.check();
		},
		checkOne: function(){
			users.checkInputChecked();
		},
		checkAll: function(){
			if (users.table.find("thead .th1 input.all")[0].checked){
				users.table.find("tbody .t1 input").attr("checked", "true").next(".js_checkbox").addClass("js_checkboxChecked");
			} else {
				users.table.find("tbody .t1 input").removeAttr("checked").next(".js_checkbox").removeClass("js_checkboxChecked");
			}
			users.checkInputChecked();
		},
		checkInputChecked: function(){
			users.openidArr = [];
			users.groupArr = [];
			users.table.find("tbody .t1 input").each(function(){
				var th = $(this);
				if (th[0].checked){
					users.openidArr.push(th.data("openid"));
					users.groupArr.push(th.data("gid"));
				}
			});
			if (users.openidArr.length != 0){
				users.table.find("thead .th2 select.team").removeAttr("disabled").next("div.team").removeClass("js_selectDisabled");
			} else {
				users.table.find("thead .th2 select.team").attr("disabled", "disabled").next("div.team").addClass("js_selectDisabled");
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
					data: {openid: users.openidArr, group: users.groupArr, gid: gid},
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
	users.init();


});




