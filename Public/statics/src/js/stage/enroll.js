$(function(){
  var scope = {

  };
  var page = {
    init: function(){
      // page.intitCropper();
      $("#form").on("submit",page.formSubmit);
      $("#face").on("change",page.changeFace);
      $("#exp").on("change",page.changeExp);
    },
    changeFace: function(){
      $("#faceReview").attr("src",window.URL.createObjectURL(this.files[0]))
    },
    changeExp: function(){
      $("#expReview").attr("src",window.URL.createObjectURL(this.files[0]))
    },
    intitCropper: function(){
      var cropper = new Cropper({
        element: document.getElementById('mypic-target'),
        aspectRatio:0.6875,
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
    formSubmit: function(){
      if(!/^.+$/.test($(":text[name='name']").val())){
        alert("请填入姓名");
        return false;
      }else if(!/^.+$/.test($(":text[name='company']").val())){
        alert("请填入所属学校或公司")
        return false;
      }else if(!/^.+$/.test($(":text[name='depart']").val())){
        alert("请填入院系或部门")
        return false;
      }else if(!/^.+$/.test($(":text[name='address']").val())){
        alert("请填入地址")
        return false;
      }else if(!/^.+$/.test($(":text[name='mb']").val())){
        alert("请填入手机")
        return false;
      }else if(!/^.+$/.test($(":text[name='email']").val())){
        alert("请填入邮件")
        return false;
      }else if(!/^.+$/.test($("textarea[name='comment']").val())){
        alert("请填入备注")
        return false;
      }
      // console.log($(".form").serialize())
      // return false;
    }
  };
  page.init();
})
