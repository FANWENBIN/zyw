$(function(){var t={type:"redcom",totalpage:0},a={init:function(){$("#typelist").off().on("click","li",a.tabGroup),a.getTotalPage(function(){pageInit(t.totalpage,12,function(t){a.getData(t)})})},tabGroup:function(){$("#typelist").find("li").removeClass("active"),$(this).addClass("active"),t.type=$(this).data("type"),a.getTotalPage(function(){pageInit(t.totalpage,12,function(t){a.getData(t)})})},getData:function(a){$.ajax({url:"./index.php?m=Home&c=Committee",type:"get",dataType:"json",data:{a:t.type,p:a},success:function(t){for(var a=t.data.data,e="",i=0,n=a.length;n>i;i++)e+='<div class="item"><a href="#" class="pic"><img src="./Uploads'+a[i].img+'" alt="" /><div class="text"><h3>'+a[i].title+"</h3><p>"+a[i].instime+"</p></div><sub></sub></a></div>";$("#typegroup").html(e)},error:function(){}})},getTotalPage:function(a){$.ajax({url:"./index.php?m=Home&c=Committee",type:"get",dataType:"json",data:{a:t.type,p:1},success:function(e){t.totalpage=parseInt(e.data.page),a()},error:function(){}})}};a.init()});