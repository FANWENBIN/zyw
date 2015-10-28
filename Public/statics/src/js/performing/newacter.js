$(function(){
  var scope = {
    workno: 1
  };
  var page = {
    init: function(){
      initRadio("sex");
      // page.intitCropper();
      // page.initWork("work1");
      $("#addwork").on("click",function(){
        page.addwork();
      })
      $("#fiedset").on("click",".close",page.closeUl);
      $(".form").on("submit",page.formSubmit);
      page.fillProvince();
      page.fillCity("440000");
      $("#provice").on("change",function(){
        page.fillCity($(this).find("option:selected").data("id"));
      });
    },
    fillProvince: function(){
      $.ajax({
        url: "./index.php?m=Home&c=Area&a=province",
        type: "get",
        dataType: "json",
        success: function(json){
          var _arr = json.data;
          var _html = '';
          for(var i = 0, len = _arr.length; i < len ; i++){
            _html += '<option data-id="'+ _arr[i].provinceid +'">'+ _arr[i].province +'</option>'
          }
          $("#provice").html(_html);
        }
      })
    },
    fillCity: function(sCity){
      $.ajax({
        url: "./index.php?m=Home&c=Area&a=city",
        type: "get",
        data: {provinceid: sCity},
        dataType: "json",
        success: function(json){
          var _arr = json.data;
          var _html = '';
          for(var i = 0, len = _arr.length; i < len ; i++){
            _html += '<option>'+ _arr[i].city +'</option>'
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
          +'<label for="workname['+ scope.workno +']">标题' + scope.workno + '：</label>'
          +'<div class="">'
            +'<input type="text" name="workname['+ scope.workno +']" value="">'
          +'</div>'
        +'</li>'



        // +'<li>'
        //   +'<label for="name">作品'+ scope.workno +'：</label>'
        //   +'<div class="">'
        //     +'<input type="text" name="name" value="">'
        //   +'</div>'
        // +'</li>'



      // +'<div class="uploadpic"><p>'
      //     +'<button class="btn-upload btn-lg">选择图片</button>'
          +'<input type="file" name="workpic['+ scope.workno +']" id="work'+ scope.workno +'"/>'
          // +'支持格式: JPG, PNG</p>'
      //   +'<div class="preview-container">'
      //     +'<div class="image-container target" id="work'+ scope.workno +'-target">'
      //       +'<img src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7" class="noavatar" />'
      //     +'</div>'
      //   +'<div class="large-wrapper">'
      //     +'<div class="image-container largework" id="work'+ scope.workno +'-large">'
      //       +'<img src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7" class="noavatar" />'
      //     +'</div>'
      //   +'</div>'
      // +'</div>'
      // +'</div>'
      +'</ul>');
      // page.initWork("work"+scope.workno);
    },
    closeUl: function(){
      $(this).parent().remove();
    },
    formSubmit: function(){
      if(!/^.+$/.test($(":text[name='name']").val())){
        alert("请填入姓名");
        return false;
      }else if($(":radio[name='sex'][checked='checked']").length == 0){
        alert("请选择性别")
        return false;
      }else if(!/^.+$/.test($("textarea[name='brief']").val())){
        alert("请填入简介")
        return false;
      }else if(!/^.+$/.test($(":text[name='fromTime']").val())){
        alert("请填入生日")
        return false;
      }else if(!/^.+$/.test($(":text[name='height']").val())){
        alert("请填入身高")
        return false;
      }else if(!/^.+$/.test($(":text[name='weight']").val())){
        alert("请填入体重")
        return false;
      }else if(!/^.+$/.test($(":text[name='company']").val())){
        alert("请填入经纪公司")
        return false;
      }
    }
  };
  page.init();
})
