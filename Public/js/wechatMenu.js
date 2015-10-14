$(function(){


	// 瀑布流算法
	var waterfall = {
		maxArr:function(arr){
	        var len = arr.length,temp = arr[0];
	        for(var ii= 1; ii < len; ii++){
	            if(temp < arr[ii]){
	                temp = arr[ii];
	            }
	        }
	        return temp;
	    },
	    getMar:function(node){
	        var dis = 0;
	        if(node.currentStyle){
	            dis = parseInt(node.currentStyle.marginBottom);
	        }else if(document.defaultView){
	            dis = parseInt(document.defaultView.getComputedStyle(node,null).marginBottom);
	        }
	        return dis;
	    },
		getMinCol:function(arr){
			var ca = arr,cl = ca.length,temp = ca[0],minc = 0;
			for(var ci = 0; ci < cl; ci++){
				if(temp > ca[ci]){
					temp = ca[ci];
					minc = ci;
				}
			}
			return minc;
		},
		init: function(elem, subCss, count, width, margin){
			margin = margin ? margin : 0;
	        var _this = elem;
	        var col = [], iArr = [];
	        var nodes = elem.find("." + subCss), len = nodes.length;
	        for(var i = 0; i < count; i++){
	            col[i] = 0;
	        }
	        for(var i = 0; i < len; i++){
	            nodes[i].h = nodes[i].offsetHeight + waterfall.getMar(nodes[i]);
	            iArr[i] = i;
	        }
			for(var i = 0; i < len; i++){
				var ming = waterfall.getMinCol(col);
				nodes[i].style.left = (width + margin) * ming + margin + "px";
				nodes[i].style.top = col[ming] + margin + "px";
				$(nodes[i]).attr("count", ming);
				col[ming] += nodes[i].h + margin;
			}
			elem.height(waterfall.maxArr(col) + margin);
	    }
    }

	
	var menu = {
		init: function(){
			menu.dom = $("#wcMenu");
			menu.tips = $("#wcMenuTips");
			menu.edit = $("#wcMenuEdit");
			menu.saved = $("#wcMenuSaved")

			// 填充菜单
			menu.initMenuHtml();

			// 侦听
			menu.dom.on("hover", ".l .bd .menu .item", menu.onMenuHover);
			menu.dom.on("click", ".l .bd .menu .item .title", menu.onMenuClick);
			menu.dom.on("click", ".l .header .add", menu.onMenuAdd);
			menu.dom.on("click", ".l .bd .menu dt.item .tools .add", menu.onSubMenuAdd);
			menu.dom.on("click", ".l .bd .menu dt.item .tools .del", menu.onMenuDelAll);
			menu.dom.on("click", ".l .bd .menu dd.item .tools .del", menu.onSubMenuDel);
			menu.dom.on("click", ".l .bd .menu dt.item .tools .edit", menu.onMenuEdit);
			menu.dom.on("click", ".l .bd .menu dd.item .tools .edit", menu.onSubMenuEdit);

			// 设置动作的类型切换
			menu.dom.find("#wcMenuEdit .wcInputs .hd a").on("click", menu.menuTypeSlide);

			// 弹出图文消息选择框
			menu.dom.find("#wcMenuEdit .wcInputs .bd .bdarticle .appendAsset").on("click", menu.openAssetsPop);

			// 更换素材移入与点击
			menu.dom.find("#wcMenuEdit .wcInputs .bd .bdarticle .asset").on("hover", ".item", menu.onAssetHover).on("click", ".item", menu.openAssetsPop);

			// 已保存素材的修改
			menu.saved.find(".btns #editBtn").on("click", menu.menuReedit);

			// 保存菜单动作
			menu.edit.find(".btns #saveBtn").on("click", menu.menuSave);
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
			menu.dom.find(".l .bd").html(menuHtml);
		},
		initEditHtml: function(id){
			var m, sm;
			if (~~id == id){
				m = id;
			} else {
				m = id.split("/")[0];
				sm = id.split("/")[1];
			}
			menu.dom.find(".current").removeClass("current");
			if (sm != undefined){
				menu.dom.find(".menu:eq(" + m + ") dd.item:eq(" + sm + ")").addClass("current");
				if (!__menuItem.m[m].type){
					__menuItem.m[m].type = "txt";
					__menuItem.m[m].content = "";
				}
				menu.setSavedInputHtml(m, sm);
			} else {
				menu.dom.find(".menu:eq(" + m + ") dt.item").addClass("current");
				if (__menuItem.m[m].item.length != 0){
					menu.tips.show().html("已有子菜单，无法设置动作");
					menu.edit.hide();
					menu.saved.hide();
					// __menuItem.m[m].type = __menuItem.m[m].content = null;
				} else {
					if (!__menuItem.m[m].type){
						__menuItem.m[m].type = "txt";
						__menuItem.m[m].content = "";
					}
					menu.setSavedInputHtml(m);
				}
			}
			menu.edit.find(".btns #saveBtn").data("id", id);
		},
		setSavedInputHtml: function(i, j){
			var type, content, html, id;
			if (j){
				type = __menuItem.m[i].item[j].type;
				content = __menuItem.m[i].item[j].content;
				if (type == "article"){
					html = __menuItem.m[i].item[j].html;
				}
				id = i + "/" + j;
			} else {
				type = __menuItem.m[i].type;
				content = __menuItem.m[i].content;
				if (type == "article"){
					html = __menuItem.m[i].html;
				}
				id = i;
			}
			if (!type || !content){
				menu.tips.hide();
				menu.edit.show().find(".bd .item").hide();
				menu.edit.find(".bd .bdtxt").show();
				menu.edit.find(".hd .c").removeClass("c");
				menu.edit.find(".hd .txt").addClass("c");
				menu.saved.hide();
				return false;
			}

			// 显示已保存的内容
			menu.tips.hide();
			menu.edit.hide();
			menu.saved.show();

			switch(type){
				case "txt":
					menu.saved.find("h1.title").html("文本回复").next(".content").html("<div class='txt'>" + content + "</div>");
				break;
				case "article":
					menu.saved.find("h1.title").html("图文回复").next(".content").html("<div class='article'>" + html + "</div>");
				break;
				case "img":
					// 暂时没有图片素材
				break;
				case "link":
					menu.saved.find("h1.title").html("链接跳转").next(".content").html("<div class='link'>" + content + "</div>");
				break;
			}

			menu.saved.find(".btns #editBtn").data("id", id);
		},
		setEditInputHtml: function(i, j){
			var type, content, html;
			if (j){
				type = __menuItem.m[i].item[j].type;
				content = __menuItem.m[i].item[j].content;
				if (type == "article"){
					html = __menuItem.m[i].item[j].html;
				}
			} else {
				type = __menuItem.m[i].type;
				content = __menuItem.m[i].content;
				if (type == "article"){
					html = __menuItem.m[i].html;
				}
			}

			// 清空不需要的编辑框内容
			menu.edit.find(".wcInputs .bd .bdtxt textarea").val("");
			menu.edit.find(".wcInputs .bd .bdarticle .appendAsset").show().next(".asset").hide().empty();
			menu.edit.find(".wcInputs .bd .bdlink .input").val("");

			// 显示相应的编辑框
			menu.tips.hide();
			menu.edit.show();
			menu.saved.hide();
			menu.edit.find(".wcInputs .hd ." + type).addClass("c").siblings(".c").removeClass("c");
			var bditem = menu.edit.find(".wcInputs .bd .bd" + type);
			bditem.show().siblings(".item").hide();

			// 填充相应编辑框的内容
			switch(type){
				case "txt":
					bditem.find("textarea").val(content);
				break;
				case "article":
					bditem.find(".appendAsset").hide().next(".asset").show().html(html).find(".mask").hide().html("更换素材");
				break;
				case "img":
					// 暂时没有图片素材
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
		onMenuClick: function(e){
			var _this = $(this);
			var _i = _this.data("i");

			// 清空不需要的编辑框内容
			menu.edit.find(".bd .bdtxt textarea").val("");
			menu.edit.find(".bd .bdarticle .appendAsset").show().next(".asset").hide().empty();
			menu.edit.find(".bd .bdlink .input").val("");
			menu.edit.find(".bd .item").hide();
			menu.edit.find(".bd .bdtxt").show();
			menu.edit.find(".hd .c").removeClass("c");
			menu.edit.find(".hd .txt").addClass("c");

			menu.initEditHtml(_i);
		},
		onMenuAdd: function(e){
			if (__menuItem.m.length < 3){
				$.alert({
					title: "添加一级菜单",
					txt: "<p>菜单名称不多于4个汉字或8个字母</p><input type='text' class='input' />",
					btnY: "添加",
					btnN: "取消",
					css: "pop-alert-appendWechatMenu",
					callbackY: function(){
						var input = $("#pop-alert .pop .bd .input");
						if (input.inputEmpty()){
							input.inputError("菜单名称不能为空");
							return false;
						} else if (input.inputLengthOverflow(8)){
							input.inputError("菜单名称不多于4个汉字或8个字母");
							return false;
						} else {
							var obj = {menu: $.trim(input.val()), item: []};
							__menuItem.m.push(obj);
							menu.initMenuHtml();
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
							menu.initMenuHtml();
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
					menu.initMenuHtml();
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
					menu.initMenuHtml();
				}
			});
		},
		onMenuEdit: function(e){
			var index = $(this).data("i");
			$.alert({
				title: "修改一级菜单",
				txt: "<p>菜单名称不多于4个汉字或8个字母</p><input type='text' class='input' value='" + __menuItem.m[index].menu + "' />",
				btnY: "修改",
				btnN: "取消",
				css: "pop-alert-editWechatMenu",
				callbackY: function(){
					var input = $("#pop-alert .pop .bd .input");
					if (input.inputEmpty()){
						input.inputError("菜单名称不能为空");
						return false;
					} else if (input.inputLengthOverflow(8)){
						input.inputError("菜单名称不多于4个汉字或8个字母");
						return false;
					} else {
						__menuItem.m[index].menu = $.trim(input.val());
						menu.initMenuHtml();
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
						menu.initMenuHtml();
					}
				}
			});
		},
		menuTypeSlide: function(){
			var type = $(this).data("type");
			$(this).addClass("c").siblings("a").removeClass("c");
			menu.edit.find(".wcInputs .bd ." + type).show().siblings(".item").hide();
		},
		onAssetHover: function(e){
			if (e.type == "mouseenter"){
				$(this).find(".mask").show();
			} else {
				$(this).find(".mask").hide();
			}
		},
		openAssetsPop: function(){
			menu.assetId = $(this).data("id");
			$.alert({
				title: "添加图文消息",
				txt: "<div class='assetsList' id='assetsListPop'></div>",
				init: function(){
					var html = "";
					for (var i = 0; i < 10; i++){
						if (menu.assetId != "s_" + i){
							html += "<div class='item itemSingle' data-id='s_" + i + "'>";
							html += "<div class='mask'></div>";
						} else {
							html += "<div class='item itemSingle current' data-id='s_" + i + "'>";
							html += "<div class='mask' style='display:block'></div>";
						}
						html += "<h2><a href='#'>佰草集微信服务号倾情上线，关注享有多重大礼</a></h2>";
						html += "<span class='date'>06月17日</span>";
						html += "<div class='img'><img src='images/0.jpeg'></div>";
						html += "<p>佰草集官方微信服务号即将上线啦！及时关注，三重缤纷好礼等你来拿哦！</p></div>";
						if (menu.assetId != "m_" + i){
							html += "<div class='item itemMulit' data-id='m_" + i + "'>";
							html += "<div class='mask'></div>";
						} else {
							html += "<div class='item itemMulit current' data-id='m_" + i + "'>";
							html += "<div class='mask' style='display:block'></div>";
						}
						html += "<span class='date'>06月17日</span>";
						html += "<div class='cover'><div class='img'><img src='images/0.jpeg' /><p><a href='#'>图片的描述文字</a></p></div></div>";
						html += "<div class='sub clearFix'><p><a href='#'>图片的描述文字图片的描述文字图片的描述文字</a></p><div class='img'><img src='images/0.jpeg' /></div></div>";
						html += "<div class='sub clearFix'><p><a href='#'>图片的描述文字图片的描述文字图片的描述文字</a></p><div class='img'><img src='images/0.jpeg' /></div></div></div>";
					}
					$("#assetsListPop").html(html).on("hover", ".item", menu.assetsPopItemHover).on("click", ".item", menu.assetsPopItemClick);
					waterfall.init($("#assetsListPop"), "item", 3, 289, 15);
				},
				btnY: "确定",
				btnN: "取消",
				btnYClose: false,
				css: "pop-alert-assetsList",
				callbackY: function(){
					if (menu.assetId){
						var current = $("#assetsListPop .current");
						var html = "";
						if (current.hasClass("itemSingle")){
							html = "<div class='item itemSingle' data-id='" + menu.assetId + "'>" + current.html() + "</div>";
						} else {
							html = "<div class='item itemMulit' data-id='" + menu.assetId + "'>" + current.html() + "</div>";
						}
						var dom = menu.dom.find("#wcMenuEdit .wcInputs .bd .bdarticle .asset");
						dom.show().html(html).siblings(".appendAsset").hide();
						dom.find(".item .mask").hide().html("更换素材");
						$("#pop-alert").remove();
					}
				}
			});
		},
		assetsPopItemHover: function(e){
			var _this = $(this);
			if (e.type == "mouseenter"){
				if (!_this.hasClass("current")){
					$(this).find(".mask").show();
				}
			} else {
				if (!_this.hasClass("current")){
					$(this).find(".mask").hide();
				}
			}
		},
		assetsPopItemClick: function(){
			$("#assetsListPop .current").removeClass("current").find(".mask").hide();
			$(this).addClass("current").find(".mask").show();
			menu.assetId = $(this).data("id");
		},
		menuReedit: function(){
			var id = $(this).data("id"), i, j;
			if (~~id != id){
				i = id.split("/")[0];
				j = id.split("/")[1];
			} else {
				i = id;
			}
			menu.setEditInputHtml(i, j);
		},
		menuSave: function(){
			var id = $(this).data("id"), i, j, type, content, html;
			if (~~id != id){
				i = id.split("/")[0];
				j = id.split("/")[1];
			} else {
				i = id;
			}
			type = menu.edit.find(".hd a.c").data("type").replace("bd", "");
			switch (type){
				case "txt":
					content = menu.edit.find(".bd .bdtxt textarea").val();
					break;
				case "article":
					content = menu.edit.find(".bd .bdarticle .asset .item").data("id");
					html = menu.edit.find(".bd .bdarticle .asset").html();
					break;
				case "link":
					content = menu.edit.find(".bd .bdlink .input").val();
					break;
			}
			if (!content){
				$.tips.error("请设置动作");
				return false;
			}
			if (j){
				__menuItem.m[i].item[j].type = type;
				__menuItem.m[i].item[j].content = content;
				if (type == "article"){
					__menuItem.m[i].item[j].html = html;
				} else {
					__menuItem.m[i].item[j].html = null;
				}
			} else {
				__menuItem.m[i].type = type;
				__menuItem.m[i].content = content;
				if (type == "article"){
					__menuItem.m[i].html = html;
				} else {
					__menuItem.m[i].html = null;
				}
			}
			menu.setSavedInputHtml(i, j);
		}
	};

	// 初始化微信菜单
	menu.init();










});




