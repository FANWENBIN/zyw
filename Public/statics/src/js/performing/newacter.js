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
      $("#fiedset").on("click",".close",page.closeUl);
      $(".form").on("submit",page.formSubmit);
      page.fillProvince();
      $("#province").on("change",page.fillCity($(this).val()));
    },
    fillProvince: function(){
      $.ajax({
        url: "./index.php?m=Home&c=Area&a=province",
        type: "get",
        dataType: "json",
        success: function(json){
          var _arr = json.data.data;
          var _html = '';
          for(var i = 0, len = _arr.length; i < len ; i++){
            _html += '<option data-id="'+ _arr.provinceid +'">'+ _arr.province +'</option>'
          }
          $("#provice").html(_html);
        }
      })
    },
    fillCity: function(sCity){
      $.ajax({
        url: "./index.php?m=Home&c=Area&a=city",
        type: "get",
        data: {provinceid: sCity}
        dataType: "json",
        success: function(json){
          var _arr = json.data.data;
          var _html = '';
          for(var i = 0, len = _arr.length; i < len ; i++){
            _html += '<option>'+ _arr.city +'</option>'
          }
          $("#city").html(_html);
        }
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
      var input = document.getElementById(workname);
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
    addwork: function(){
      scope.workno++;
      $("#fiedset").append('<ul class="worklist"><a href="javascript:;" class="close">X</a><li>'
          +'<label for="work'+ scope.workno +'name">标题' + scope.workno + '：</label>'
          +'<div class="">'
            +'<input type="text" name="work'+ scope.workno +'name" value="">'
          +'</div>'
        +'</li>'
        // +'<li>'
        //   +'<label for="name">作品'+ scope.workno +'：</label>'
        //   +'<div class="">'
        //     +'<input type="text" name="name" value="">'
        //   +'</div>'
        // +'</li>'
      +'<div class="uploadpic"><p>'
          +'<button class="btn-upload btn-lg">选择图片</button>'
          +'<input type="file" name="work'+ scope.workno +'pic" id="work'+ scope.workno +'"/>支持格式: JPG, PNG</p>'
        +'<div class="preview-container">'
          +'<div class="image-container target" id="work'+ scope.workno +'-target">'
            +'<img src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7" class="noavatar" />'
          +'</div>'
        +'<div class="large-wrapper">'
          +'<div class="image-container largework" id="work'+ scope.workno +'-large">'
            +'<img src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7" class="noavatar" />'
          +'</div>'
        +'</div>'
      +'</div>'
      +'</div></ul>');
      page.initWork("work"+scope.workno);
    },
    closeUl: function(){
      $(this).parent().remove();
    },
    formSubmit: function(){
      if(!/^.+$/.test($(":text[name='name']").val())){
        alert("请填入姓名")
      }else if($(":radio[name='sex'][checked='checked']").length == 0){
        alert("请选择性别！")
      }else if(!/^.+$/.test($("textarea[name='brief']").val())){
        alert("请填入简介")
      }else if(true){

      }
      return false;
    }
  };
  page.init();
})
