$(function(){function e(e,a){if(t.totalpage=parseInt(e),t.currentpage=1,"number"==typeof t.totalpage)if(t.totalpage>t.eachpage){for(var g="",i=0,p=t.eachpage;p>i;i++)g+=0==i?'<li class="active">'+(i+1)+"</li>":"<li>"+(i+1)+"</li>";$("#pagelist .num").html(g)}else{t.maxpage=t.totalpage;for(var g="",i=0,p=e;p>i;i++)g+=0==i?'<li class="active">'+(i+1)+"</li>":"<li>"+(i+1)+"</li>";$("#pagelist .num").html(g),a(1)}else console.log("页数是非数字，请确认！"),t.totalpage=1,t.minpage=1,t.maxpage=1;console.log("总页数:"+t.totalpage,"当前页:"+t.currentpage,"最小页:"+t.minpage,"最大页:"+t.maxpage)}function a(e){if(0==t.totalpage)return 0;if(1==t.currentpage&&1==t.currentpage)console.log("这是第一页，不能切换！");else if(t.currentpage<=t.minpage){t.minpage-=t.eachpage,t.maxpage=t.minpage+t.eachpage-1,t.currentpage--,$("#pagelist .num").find("li").removeClass("active"),$("#pagelist .num").find("li").eq(t.currentpage).addClass("active");for(var a="",g=0,i=t.eachpage;i>g;g++)a+=g==i-1?'<li class="active">'+t.minpage+"</li>":"<li>"+(t.minpage+g)+"</li>";$("#pagelist .num").html(a),e(t.currentpage)}else t.currentpage--,$("#pagelist .num").find("li").removeClass("active"),$("#pagelist .num").find("li").eq(t.currentpage%t.eachpage-1).addClass("active"),e(t.currentpage);console.log("总页数:"+t.totalpage,"当前页:"+t.currentpage,"最小页:"+t.minpage,"最大页:"+t.maxpage)}function g(e){if(0==t.totalpage)return 0;if(t.currentpage==t.totalpage)console.log("这是最后一页，不能切换！");else if(t.currentpage==t.maxpage&&t.maxpage+t.eachpage<=t.totalpage){t.minpage+=t.eachpage,t.maxpage=t.minpage+t.eachpage-1,t.currentpage++,$("#pagelist .num").find("li").removeClass("active"),$("#pagelist .num").find("li").eq(t.currentpage%t.eachpage-1).addClass("active");for(var a="",g=0,i=t.eachpage;i>g;g++)a+=0==g?'<li class="active">'+t.minpage+"</li>":"<li>"+(t.minpage+g)+"</li>";$("#pagelist .num").html(a),e(t.currentpage)}else if(t.currentpage==t.maxpage){t.minpage+=t.eachpage,t.maxpage=t.totalpage,t.currentpage++;for(var a="",g=0,i=t.maxpage-t.minpage;i>=g;g++)a+=0==g?'<li class="active">'+(t.minpage+g)+"</li>":"<li>"+(t.minpage+g)+"</li>";$("#pagelist .num").html(a),e(t.currentpage)}else t.currentpage++,$("#pagelist .num").find("li").removeClass("active"),$("#pagelist .num").find("li").eq(t.currentpage%t.eachpage-1).addClass("active"),e(t.currentpage);console.log("总页数:"+t.totalpage,"当前页:"+t.currentpage,"最小页:"+t.minpage,"最大页:"+t.maxpage)}function i(e,a){$("#pagelist .num").find("li").removeClass("active"),$(a).addClass("active"),t.currentpage=parseInt($(a).html()),e(t.currentpage),console.log("总页数:"+t.totalpage,"当前页:"+t.currentpage,"最小页:"+t.minpage,"最大页:"+t.maxpage)}var t={currentpage:1,minpage:1,maxpage:10,totalpage:1,eachpage:10};window.pageInit=function(p,l,n){t.maxpage=l,t.eachpage=l,$("#pagelist .pre").off().on("click",function(){a(n)}),$("#pagelist .next").off().on("click",function(){g(n)}),$("#pagelist .num").off().on("click","li",function(){_this=this,i(n,_this)}),e(p,n)}});