$(function(){var a={group:0},i={init:function(){$("#sorting").on("click","li",i.tabGroup),i.loadData()},tabGroup:function(){$("#sorting").find("li").removeClass("active"),$(this).addClass("active"),a.group=$(this).data("group"),i.loadData()},loadData:function(){$.ajax({url:"./index.php?m=Home&c=Performing&a=allperforming",type:"get",dataType:"json",data:{type:a.group},success:function(a){if(0==a.status){var i="",t=a.data;for(key in t){var e='<li class="alphabet-list"><div class="title"><h3 id="a">'+key+'</h3></div><div class="inner clearFix">';if(t[key])for(var r=0,o=t[key].length;o>r;r++)e+='<a href = "#'+t[key][r].id+'"><div class="item"><img src="'+t[key][r].headimg+'" alt="" /><p>'+t[key][r].name+"</p><sub></sub></div></a>";e+="</div></li>",i+=e}$("#actorgroup").html(i)}},error:function(){}})}};i.init()});