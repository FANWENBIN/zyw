$(function(){var e={workno:1},t={init:function(){initRadio("sex"),$("#addwork").on("click",function(){t.addwork()}),$("#fiedset").on("click",".close",t.closeUl),$(".form").on("submit",t.formSubmit),t.fillProvince(),t.fillCity("440000"),$("#provice").on("change",function(){t.fillCity($(this).find("option:selected").data("id"))})},fillProvince:function(){$.ajax({url:"./index.php?m=Home&c=Area&a=province",type:"get",dataType:"json",success:function(e){for(var t=e.data,n="",o=0,a=t.length;a>o;o++)n+='<option data-id="'+t[o].provinceid+'">'+t[o].province+"</option>";$("#provice").html(n)}})},fillCity:function(e){$.ajax({url:"./index.php?m=Home&c=Area&a=city",type:"get",data:{provinceid:e},dataType:"json",success:function(e){for(var t=e.data,n="",o=0,a=t.length;a>o;o++)n+="<option>"+t[o].city+"</option>";$("#city").html(n)}})},intitCropper:function(){var e=new Cropper({element:document.getElementById("mypic-target"),aspectRatio:.688,previews:[document.getElementById("mypic-large")],onCroppedRectChange:function(e){console.log(e)}}),t=document.getElementById("mypic");t.onchange=function(){if("undefined"!=typeof FileReader){var n=new FileReader;n.onload=function(t){e.setImage(t.target.result)},t.files&&t.files[0]&&n.readAsDataURL(t.files[0])}else{t.select(),t.blur();var o=document.selection.createRange().text;e.setImage(o)}}},initWork:function(e){var t=new Cropper({element:document.getElementById(e+"-target"),aspectRatio:.875,previews:[document.getElementById(e+"-large")],onCroppedRectChange:function(e){console.log(e)}}),n=document.getElementById(e);n.onchange=function(){if("undefined"!=typeof FileReader){var e=new FileReader;e.onload=function(e){t.setImage(e.target.result)},n.files&&n.files[0]&&e.readAsDataURL(n.files[0])}else{n.select(),n.blur();var o=document.selection.createRange().text;t.setImage(o)}}},addwork:function(){e.workno++,$("#fiedset").append('<ul class="worklist"><a href="javascript:;" class="close">X</a><li><label for="workname['+e.workno+']">标题'+e.workno+'：</label><div class=""><input type="text" name="workname['+e.workno+']" value=""></div></li><input type="file" name="workpic'+e.workno+'" id="work'+e.workno+'"/></ul>')},closeUl:function(){$(this).parent().remove()},formSubmit:function(){return/^.+$/.test($(":text[name='name']").val())?0==$(":radio[name='sex'][checked='checked']").length?(alert("请选择性别"),!1):/^.+$/.test($("textarea[name='brief']").val())?/^.+$/.test($(":text[name='fromTime']").val())?/^.+$/.test($(":text[name='height']").val())?/^.+$/.test($(":text[name='weight']").val())?/^.+$/.test($(":text[name='company']").val())?void 0:(alert("请填入经纪公司"),!1):(alert("请填入体重"),!1):(alert("请填入身高"),!1):(alert("请填入生日"),!1):(alert("请填入简介"),!1):(alert("请填入姓名"),!1)}};t.init()});