$(function(){var e={workno:1},t={init:function(){initRadio("sex"),t.intitCropper(),t.initWork("work1"),$("#addwork").on("click",function(){t.addwork()}),$("#fiedset").on("click",".close",t.closeUl),$(".form").on("submit",t.formSubmit),t.fillProvince(),t.fillCity(44e4),$("#province").on("change",t.fillCity($(this).val()))},fillProvince:function(){$.ajax({url:"./index.php?m=Home&c=Area&a=province",type:"get",dataType:"json",success:function(e){for(var t=e.data,n="",o=0,i=t.length;i>o;o++)n+='<option data-id="'+t[o].provinceid+'">'+t[o].province+"</option>";$("#provice").html(n)}})},fillCity:function(e){$.ajax({url:"./index.php?m=Home&c=Area&a=city",type:"get",data:{provinceid:e},dataType:"json",success:function(e){for(var t=e.data,n="",o=0,i=t.length;i>o;o++)n+="<option>"+t[o].city+"</option>";$("#city").html(n)}})},intitCropper:function(){var e=new Cropper({element:document.getElementById("mypic-target"),aspectRatio:.688,previews:[document.getElementById("mypic-large")],onCroppedRectChange:function(e){console.log(e)}}),t=document.getElementById("mypic");t.onchange=function(){if("undefined"!=typeof FileReader){var n=new FileReader;n.onload=function(t){e.setImage(t.target.result)},t.files&&t.files[0]&&n.readAsDataURL(t.files[0])}else{t.select(),t.blur();var o=document.selection.createRange().text;e.setImage(o)}}},initWork:function(e){var t=new Cropper({element:document.getElementById(e+"-target"),aspectRatio:.875,previews:[document.getElementById(e+"-large")],onCroppedRectChange:function(e){console.log(e)}}),n=document.getElementById(e);n.onchange=function(){if("undefined"!=typeof FileReader){var e=new FileReader;e.onload=function(e){t.setImage(e.target.result)},n.files&&n.files[0]&&e.readAsDataURL(n.files[0])}else{n.select(),n.blur();var o=document.selection.createRange().text;t.setImage(o)}}},addwork:function(){e.workno++,$("#fiedset").append('<ul class="worklist"><a href="javascript:;" class="close">X</a><li><label for="work'+e.workno+'name">标题'+e.workno+'：</label><div class=""><input type="text" name="work'+e.workno+'name" value=""></div></li><div class="uploadpic"><p><button class="btn-upload btn-lg">选择图片</button><input type="file" name="work'+e.workno+'pic" id="work'+e.workno+'"/>支持格式: JPG, PNG</p><div class="preview-container"><div class="image-container target" id="work'+e.workno+'-target"><img src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7" class="noavatar" /></div><div class="large-wrapper"><div class="image-container largework" id="work'+e.workno+'-large"><img src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7" class="noavatar" /></div></div></div></div></ul>'),t.initWork("work"+e.workno)},closeUl:function(){$(this).parent().remove()},formSubmit:function(){return/^.+$/.test($(":text[name='name']").val())?0==$(":radio[name='sex'][checked='checked']").length?alert("请选择性别！"):/^.+$/.test($("textarea[name='brief']").val())||alert("请填入简介"):alert("请填入姓名"),!1}};t.init()});