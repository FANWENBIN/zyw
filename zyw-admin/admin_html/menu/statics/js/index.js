define(function(require, exports, module){
    require("common");
 
    $(function(){

        var page = {
			init: function(){
				page.dom = $("#wcMenu");
				page.tips = $("#wcMenuTips");
				page.edit = $("#wcMenuEdit");
				page.saved = $("#wcMenuSaved")

				// 填充菜单
				page.initMenuHtml();

				// 侦听
				page.dom.on("hover", ".l .bd .menu .item", page.onMenuHover);
				page.dom.on("click", ".l .bd .menu .item .title", page.onMenuClick);
				page.dom.on("click", ".l .header .add", page.onMenuAdd);
				page.dom.on("click", ".l .bd .menu dt.item .tools .add", page.onSubMenuAdd);
				page.dom.on("click", ".l .bd .menu dt.item .tools .del", page.onMenuDelAll);
				page.dom.on("click", ".l .bd .menu dd.item .tools .del", page.onSubMenuDel);
				page.dom.on("click", ".l .bd .menu dt.item .tools .edit", page.onMenuEdit);
				page.dom.on("click", ".l .bd .menu dd.item .tools .edit", page.onSubMenuEdit);

				// 已保存素材的修改
				page.saved.find(".btns #editBtn").on("click", page.menuReedit);

				// 保存菜单动作
				page.edit.find(".btns #saveBtn").on("click", page.menuSave);

				// 提交
				$("#submit").on("click", page.submitClick);

				// 停用或启用菜单
				$("#menuStatus").on("click", page.statusClick);
			},
			initMenuHtml: function(){
				var menuHtml = "";
				if (__menuItem.m.length != 0){
					for (var i = 0; i < __menuItem.m.length; i++){
						menuHtml += "<dl class='menu'><dt class='item'><a href='javascript:void(0)' class='title' data-i='" + i + "'>" + __menuItem.m[i].menu + "</a>";
						menuHtml += "<span class='tools'><a href='javascript:void(0)' class='add' data-i='" + i + "'></a><a href='javascript:void(0)' class='edit' data-i='" + i + "'></a><a href='javascript:void(0)' class='del' data-i='" + i + "'></a></span></dt>";
						for (var j = 0; j < __menuItem.m[i].item.length; j++){
							menuHtml += "<dd class='item'><a href='javascript:void(0)' class='title' data-i='" + i + "/" + j + "'>" + __menuItem.m[i].item[j].title + "</a><span class='tools'><a href='javascript:void(0)' class='edit' data-i='" + i + "/" + j + "'></a><a href='javascript:void(0)' class='del' data-i='" + i + "/" + j + "'></a></span></dd>";
						}
						menuHtml += "</dl>";
					}
				} else {
					menuHtml = "<dl class='menu'></dl>";
				}
				page.dom.find(".l .bd").html(menuHtml);
			},
			initEditHtml: function(id){
				var m, sm;
				if (~~id == id){
					m = id;
				} else {
					m = id.split("/")[0];
					sm = id.split("/")[1];
				}
				page.dom.find(".l .bd .current").removeClass("current");
				if (sm != undefined){
					page.dom.find(".menu:eq(" + m + ") dd.item:eq(" + sm + ")").addClass("current");
					if (!__menuItem.m[m].type){
						__menuItem.m[m].type = "txt";
						__menuItem.m[m].content = "";
					}
					page.setSavedInputHtml(m, sm);
				} else {
					page.dom.find(".menu:eq(" + m + ") dt.item").addClass("current");
					if (__menuItem.m[m].item.length != 0){
						page.tips.show().html("已有子菜单，无法设置动作");
						page.edit.hide();
						page.saved.hide();
						// __menuItem.m[m].type = __menuItem.m[m].content = null;
					} else {
						if (!__menuItem.m[m].type){
							__menuItem.m[m].type = "txt";
							__menuItem.m[m].content = "";
						}
						page.setSavedInputHtml(m);
					}
				}
				page.edit.find(".btns #saveBtn").data("id", id);
			},
			setSavedInputHtml: function(i, j){
				var type, content, html, id;
				if (j){
					type = __menuItem.m[i].item[j].type;
					content = __menuItem.m[i].item[j].content;
					if (type == "article" || type == "img" || type == "audio"){
						html = __menuItem.m[i].item[j].html;
					}
					id = i + "/" + j;
				} else {
					type = __menuItem.m[i].type;
					content = __menuItem.m[i].content;
					if (type == "article" || type == "img" || type == "audio"){
						html = __menuItem.m[i].html;
					}
					id = i;
				}
				if (!type || !content){
					page.tips.hide();
					page.edit.show().find(".bd .bditem").hide();
					page.edit.find(".bd .bdtxt").show();
					page.edit.find(".hd .c").removeClass("c");
					page.edit.find(".hd .txt").addClass("c");
					page.saved.hide();
					return false;
				}

				// 显示已保存的内容
				page.tips.hide();
				page.edit.hide();
				page.saved.show();

				switch(type){
					case "txt":
						page.saved.find("h1.title").html("文本回复").next(".content").html("<div class='txt'>" + content + "</div>");
					break;
					case "article":
						page.saved.find("h1.title").html("图文回复").next(".content").html("<div class='article'>" + html + "</div>");
					break;
					case "img":
						page.saved.find("h1.title").html("图片回复").next(".content").html("<div class='img'>" + html + "</div>");
					break;
					case "audio":
						page.saved.find("h1.title").html("语音回复").next(".content").html("<div class='audio'>" + html + "</div>").find(".play").on("click", page.playAudio);
					break;
					case "link":
						page.saved.find("h1.title").html("链接跳转").next(".content").html("<div class='link'>" + content + "</div>");
					break;
				}

				page.saved.find(".btns #editBtn").data("id", id);
			},
			setEditInputHtml: function(i, j){
				var type, content, html;
				if (j){
					type = __menuItem.m[i].item[j].type;
					content = __menuItem.m[i].item[j].content;
					if (type == "article" || type == "img"){
						html = __menuItem.m[i].item[j].html;
					}
				} else {
					type = __menuItem.m[i].type;
					content = __menuItem.m[i].content;
					if (type == "article" || type == "img"){
						html = __menuItem.m[i].html;
					}
				}

				// 清空不需要的编辑框内容
				page.edit.find(".wcInputs .bd .bdtxt textarea").val("");
				page.edit.find(".wcInputs .bd .bdarticle .content").empty();
				page.edit.find(".wcInputs .bd .bdimg .content").empty();
				page.edit.find(".wcInputs .bd .bdlink .input").val("");

				// 显示相应的编辑框
				page.tips.hide();
				page.edit.show();
				page.saved.hide();
				page.edit.find(".wcInputs .hd ." + type).addClass("c").siblings(".c").removeClass("c");
				var bditem = page.edit.find(".wcInputs .bd .bd" + type);
				bditem.show().siblings(".bditem").hide();

				// 填充相应编辑框的内容
				switch(type){
					case "txt":
						bditem.find("textarea").val(content);
					break;
					case "article":
						bditem.find(".content").html(html);
					break;
					case "img":
						bditem.find(".content").html(html);
					break;
					case "audio":
						bditem.find(".content").html(html);
					break;
					case "link":
						bditem.find(".input").val(content);
					break;
				}
			},
			onMenuHover: function(e){
				if (e.type == "mouseenter"){
					$(this).addClass("itemHover").find(".tools").show();
				} else {
					$(this).removeClass("itemHover").find(".tools").hide();
				}
			},
			onMenuClick: function(e, th){
				var _this, _i;
				if (e){
					_this = $(this);
				} else if (th){
					_this = th;
				}
				_i = _this.data("i");

				// 清空不需要的编辑框内容
				page.edit.find(".bd .bdtxt textarea").val("");
				page.edit.find(".bd .bdarticle .content").empty();
				page.edit.find(".bd .bdlink .input").val("");
				page.edit.find(".bd .bditem").hide();
				page.edit.find(".bd .bdtxt").show();
				page.edit.find(".hd .c").removeClass("c");
				page.edit.find(".hd .txt").addClass("c");

				page.initEditHtml(_i);
			},
			onMenuAdd: function(e){
				if (__menuItem.m.length < 3){
					$.alert({
						title: "添加一级菜单",
						txt: "<p>菜单名称不多于5个汉字或10个字母</p><input type='text' class='input' />",
						btnY: "添加",
						btnN: "取消",
						css: "pop-alert-appendWechatMenu",
						callbackY: function(){
							var input = $("#pop-alert .pop .bd .input");
							if (input.inputEmpty()){
								input.inputError("菜单名称不能为空");
								return false;
							} else if (input.inputLengthOverflow(10)){
								input.inputError("菜单名称不多于5个汉字或10个字母");
								return false;
							} else {
								var obj = {menu: $.trim(input.val()), item: []};
								__menuItem.m.push(obj);
								page.initMenuHtml();
							}
						}
					});
				} else {
					$.tips.error("一级菜单最多只能3个");
				}
			},
			onSubMenuAdd: function(e){
				var index = $(this).data("i");
				var length = __menuItem.m[index].item.length;
				if (length < 5){
					$.alert({
						title: "添加二级菜单",
						txt: "<p>菜单名称不多于8个汉字或16个字母</p><input type='text' class='input' />",
						btnY: "添加",
						btnN: "取消",
						css: "pop-alert-appendWechatSubMenu",
						callbackY: function(){
							var input = $("#pop-alert .pop .bd .input");
							if (input.inputEmpty()){
								input.inputError("菜单名称不能为空");
								return false;
							} else if (input.inputLengthOverflow(16)){
								input.inputError("菜单名称不多于8个汉字或16个字母");
								return false;
							} else {
								var obj = {title: $.trim(input.val())};
								__menuItem.m[index].item.push(obj);
								page.initMenuHtml();
							}
						}
					});
				}else{
					$.tips.error("二级菜单最多只能5个");
				}
			},
			onMenuDelAll: function(e){
				var index = $(this).data("i");
				$.alert({
					title: "温馨提示",
					txt: "确定要删除该一级菜单以及该菜单下的二级菜单吗？",
					btnY: "删除菜单",
					btnN: "取消",
					btnYcss: "btnC",
					callbackY: function(){
						__menuItem.m.splice(index, 1);
						page.tips.show().html("你可以先添加一个菜单，然后开始为其设置响应动作");
						page.edit.hide();
						page.saved.hide();
						page.initMenuHtml();
					}
				});
			},
			onSubMenuDel: function(){
				var index = $(this).data("i").split("/");
				$.alert({
					title: "温馨提示",
					txt: "确定要删除该二级菜单吗？",
					btnY: "删除菜单",
					btnN: "取消",
					btnYcss: "btnC",
					callbackY: function(){
						__menuItem.m[parseInt(index[0])].item.splice(parseInt(index[1]), 1);
						page.tips.show().html("你可以先添加一个菜单，然后开始为其设置响应动作");
						page.edit.hide();
						page.saved.hide();
						page.initMenuHtml();
					}
				});
			},
			onMenuEdit: function(e){
				var index = $(this).data("i");
				$.alert({
					title: "修改一级菜单",
					txt: "<p>菜单名称不多于5个汉字或10个字母</p><input type='text' class='input' value='" + __menuItem.m[index].menu + "' />",
					btnY: "修改",
					btnN: "取消",
					css: "pop-alert-editWechatMenu",
					callbackY: function(){
						var input = $("#pop-alert .pop .bd .input");
						if (input.inputEmpty()){
							input.inputError("菜单名称不能为空");
							return false;
						} else if (input.inputLengthOverflow(10)){
							input.inputError("菜单名称不多于5个汉字或10个字母");
							return false;
						} else {
							__menuItem.m[index].menu = $.trim(input.val());
							page.initMenuHtml();
						}
					}
				});
			},
			onSubMenuEdit: function(e){
				var index = $(this).data("i").split("/");
				$.alert({
					title: "修改二级菜单",
					txt: "<p>菜单名称不多于8个汉字或16个字母</p><input type='text' class='input' value='" + __menuItem.m[parseInt(index[0])].item[parseInt(index[1])].title + "' />",
					btnY: "修改",
					btnN: "取消",
					css: "pop-alert-editWechatMenu",
					callbackY: function(){
						var input = $("#pop-alert .pop .bd .input");
						if (input.inputEmpty()){
							input.inputError("菜单名称不能为空");
							return false;
						} else if (input.inputLengthOverflow(16)){
							input.inputError("菜单名称不多于8个汉字或16个字母");
							return false;
						} else {
							__menuItem.m[parseInt(index[0])].item[parseInt(index[1])].title = $.trim(input.val());
							page.initMenuHtml();
						}
					}
				});
			},
			menuReedit: function(){
				var id = $(this).data("id"), i, j;
				if (~~id != id){
					i = id.split("/")[0];
					j = id.split("/")[1];
				} else {
					i = id;
				}
				page.setEditInputHtml(i, j);
			},
			menuSave: function(){
				var id = $(this).data("id"), i, j, type, content, html;
				if (~~id != id){
					i = id.split("/")[0];
					j = id.split("/")[1];
				} else {
					i = id;
				}
				type = page.edit.find(".hd a.c").data("type").replace("bd", "");
				switch (type){
					case "txt":
						content = page.edit.find(".bd .bdtxt textarea").val();
					break;
					case "article":
						content = page.edit.find(".bd .bdarticle .content .item").data("id");
						html = page.edit.find(".bd .bdarticle .content").html();
					break;
					case "img":
						content = page.edit.find(".bd .bdimg .content .item").data("id");
						html = page.edit.find(".bd .bdimg .content").html();
					break;
					case "audio":
						content = page.edit.find(".bd .bdaudio .content .item").data("id");
						html = page.edit.find(".bd .bdaudio .content").html();
					break;
					case "link":
						content = page.edit.find(".bd .bdlink .input").val();
					break;
				}
				if (!content){
					$.tips.error("请设置动作");
					return false;
				}
				if (j){
					__menuItem.m[i].item[j].type = type;
					__menuItem.m[i].item[j].content = content;
					if (type == "article" || type == "img" || type == "audio"){
						__menuItem.m[i].item[j].html = html;
					} else {
						__menuItem.m[i].item[j].html = null;
					}
				} else {
					__menuItem.m[i].type = type;
					__menuItem.m[i].content = content;
					if (type == "article" || type == "img" || type == "audio"){
						__menuItem.m[i].html = html;
					} else {
						__menuItem.m[i].html = null;
					}
				}
				page.setSavedInputHtml(i, j);
			},
			statusClick: function(){
				var th = $(this), type = 0;
				if (th.hasClass("btnC")){
					type = 1;
				} else {
					type = 0;
				}
				$.ajaxSubmit({
					url: "_status.php",
					data: type,
					success: function(d){
						if (d.status == 0){
							if (d.data == 1){
								th.attr("class", "btnC status").html("停用菜单");
								$.tips.success("菜单已经启用！");
							} else {
								th.attr("class", "btnB status").html("启用菜单");
								$.tips.success("菜单已经停用！");
							}
						} else {
							$.tips.error(d.msg);
						}
					},
					error: function(){
						$.tips.error("系统错误，请稍后再试！");
					}
				});
			},
			submitClick: function(){
				for (var i = 0; i < __menuItem.m.length; i++){
					var mi = __menuItem.m[i];
					if ((!mi.type || !mi.content) && mi.item.length == 0){
						$("#wcMenu .l .bd .menu:eq(" + i + ") dt.item .title").click();
						$.tips.error("存在还未设置响应动作的菜单，请检查");
						return false;
					} else if (mi.item.length != 0) {
						for (var j = 0; j < mi.item.length; j++){
							if (!mi.item[j].type || !mi.item[j].content){
								$("#wcMenu .l .bd .menu:eq(" + i + ") dd.item:eq(" + j + ") .title").click();
								$.tips.error("存在还未设置响应动作的菜单，请检查");
								return false;
							}
						}
					}
				}
				if (__menuItem.m.length != 0){
					$.ajaxSubmit({
						url: "_add.php",
						data: __menuItem,
						success: function(d){
							if (d.status == 0){
								$.tips.success("菜单发布成功！");
	                            $("#menuStatus").attr("class", "btnC status").html("停用菜单");
							} else {
								$.tips.error("菜单发布失败，请稍后再试！");
							}
						},
						error: function(d){
							$.tips.error("系统错误，请稍后再试！");
						}
					});
				} else {
					$.tips.error("请添加自定义菜单");
				}
			},
			playAudio: function(){
				var th = $(this);
				if (!th.hasClass("pause")){
					$.audioPlayer.remove();
					var url = th.data("audio");
					if (url){
						th.addClass("pause");
						$.audioPlayer(url);
					}
				} else {
					th.removeClass("pause");
					$.audioPlayer.remove();
				}
			}
		};

		// 初始化微信菜单
		page.init();

    });
});


