$(function(){var a={group:"1",pageNum:"1"},t={init:function(){$("#group").on("click","li",t.changeGroup),t.getPage(function(){pageInit(parseInt(a.pageNum),10,function(a){return 0==a?!1:void t.getData(a)})})},changeGroup:function(){$("#group").find("li").removeClass("active"),$(this).addClass("active"),a.group=$(this).data("group"),t.getPage(function(){pageInit(parseInt(a.pageNum),10,function(a){return 0==a?!1:void t.getData(a)})})},getPage:function(t){$.ajax({url:"./index.php?m=Home&c=Video&a=vial",type:"get",dataType:"json",data:{type:a.group,p:1},success:function(e){a.pageNum=e.data.page,t()},error:function(){}})},getData:function(t){$.ajax({url:"./index.php?m=Home&c=Video&a=vial",type:"get",dataType:"json",data:{type:a.group,p:t},success:function(a){var t=a.data.data,e="";if(0==a.data.page)return $("#itemgroup").html(e),!1;for(var i=0,n=t.length;n>i;i++)e+='<a href="./index.php?m=Home&c=Video&a=video_details&id='+t[i].id+'" class="item"><img src="./Uploads'+t[i].bigimg+'" alt="" /><div class="text"><h1>'+t[i].title+"</h1><h3>"+t[i].instime+"</h3></div><sub></sub></a>";$("#itemgroup").html(e)},error:function(){}})}};t.init()});