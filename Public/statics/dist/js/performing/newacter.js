$(function(){var e={workno:1},t={init:function(){initRadio("sex"),t.intitCropper(),t.initWork("work1"),$("#addwork").on("click",function(){t.addwork()})},intitCropper:function(){var e=new Cropper({element:document.getElementById("mypic-target"),aspectRatio:.688,previews:[document.getElementById("mypic-large")],onCroppedRectChange:function(e){console.log(e)}}),t=document.getElementById("mypic");t.onchange=function(){if("undefined"!=typeof FileReader){var n=new FileReader;n.onload=function(t){e.setImage(t.target.result)},t.files&&t.files[0]&&n.readAsDataURL(t.files[0])}else{t.select(),t.blur();var a=document.selection.createRange().text;e.setImage(a)}}},initWork:function(t){var n=new Cropper({element:document.getElementById(t+"-target"),aspectRatio:.688,previews:[document.getElementById(t+"-large")],onCroppedRectChange:function(e){console.log(e)}});e[t]=document.getElementById(t),e[t].onchange=function(){if("undefined"!=typeof FileReader){var a=new FileReader;a.onload=function(e){n.setImage(e.target.result)},e[t].files&&e[t].files[0]&&a.readAsDataURL(e[t].files[0])}else{e[t].select(),e[t].blur();var i=document.selection.createRange().text;n.setImage(i)}}},addwork:function(){e.workno++,$("#fiedset").append('<ul class="worklist"><li><label for="name">标题'+e.workno+'：</label><div class=""><input type="text" name="name" value=""></div></li><li><label for="name">作品'+e.workno+'：</label><div class=""><input type="text" name="name" value=""></div></li><div class="uploadpic"><p><button class="btn-upload btn-lg">选择图片</button><input type="file" name="avatar" id="work'+e.workno+'"/>支持格式: JPG, PNG</p><div class="preview-container"><div class="image-container target" id="work'+e.workno+'-target"><img src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7" class="noavatar" /></div><div class="large-wrapper"><div class="image-container large" id="work'+e.workno+'-large"><img src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7" class="noavatar" /></div></div></div></div></ul>'),t.initWork("work"+e.workno)}};t.init()});