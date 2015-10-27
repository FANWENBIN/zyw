$(function(){
  var scope = {
    workno: 1
  };
  var page = {
    init: function(){
      initRadio("sex");
      page.intitCropper();
      page.initWork("work1");
      $("#addwork").on("click",function(){
        page.addwork();
      })
    },
    intitCropper: function(){
      var cropper = new Cropper({
        element: document.getElementById('mypic-target'),
        aspectRatio:0.688,
        previews: [
          document.getElementById('mypic-large'),
          // document.getElementById('preview-medium'),
          // document.getElementById('preview-small')
        ],
        onCroppedRectChange: function(rect) {
          console.log(rect);
        }
      });
      var input = document.getElementById('mypic');
      input.onchange = function() {
        if (typeof FileReader !== 'undefined') {
          var reader = new FileReader();
          reader.onload = function (event) {
            cropper.setImage(event.target.result);
          };
          if (input.files && input.files[0]) {
            reader.readAsDataURL(input.files[0]);
          }
        } else { // IE10-
          input.select();
          input.blur();

          var src = document.selection.createRange().text;
          cropper.setImage(src);
        }
      };
    },
    initWork: function(workname){
      var cropper = new Cropper({
        element: document.getElementById(workname + '-target'),
        aspectRatio:0.875,
        previews: [
          document.getElementById(workname + '-large'),
          // document.getElementById('preview-medium'),
          // document.getElementById('preview-small')
        ],
        onCroppedRectChange: function(rect) {
          console.log(rect);
        }
      });
      scope[workname] = document.getElementById(workname);
      scope[workname].onchange = function() {
        if (typeof FileReader !== 'undefined') {
          var reader = new FileReader();
          reader.onload = function (event) {
            cropper.setImage(event.target.result);
          };
          if (scope[workname].files && scope[workname].files[0]) {
            reader.readAsDataURL(scope[workname].files[0]);
          }
        } else { // IE10-
          scope[workname].select();
          scope[workname].blur();

          var src = document.selection.createRange().text;
          cropper.setImage(src);
        }
      };
    },
    addwork: function(){
      scope.workno++;
      $("#fiedset").append('<ul class="worklist"><li>'
          +'<label for="name">标题' + scope.workno + '：</label>'
          +'<div class="">'
            +'<input type="text" name="name" value="">'
          +'</div>'
        +'</li>'
        +'<li>'
          +'<label for="name">作品'+ scope.workno +'：</label>'
          +'<div class="">'
            +'<input type="text" name="name" value="">'
          +'</div>'
        +'</li>'
      +'<div class="uploadpic"><p>'
          +'<button class="btn-upload btn-lg">选择图片</button>'
          +'<input type="file" name="avatar" id="work'+ scope.workno +'"/>支持格式: JPG, PNG</p>'
        +'<div class="preview-container">'
          +'<div class="image-container target" id="work'+ scope.workno +'-target">'
            +'<img src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7" class="noavatar" />'
          +'</div>'
        +'<div class="large-wrapper">'
          +'<div class="image-container large" id="work'+ scope.workno +'-large">'
            +'<img src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7" class="noavatar" />'
          +'</div>'
        +'</div>'
      +'</div>'
      +'</div></ul>');
      page.initWork("work"+scope.workno);
    }
  };
  page.init();
})
