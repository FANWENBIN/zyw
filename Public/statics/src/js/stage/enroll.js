$(function(){
  var scope = {
    faceReview: "",
    expReview: "",
    representReview: ""
  };
  var page = {
    init: function(){
      // page.intitCropper();
      $("#form").on("submit",page.formSubmit);
      $("#face").on("change",page.changeFace);
      $("#exp").on("change",page.changeExp);
      $("#represent").on("change",page.changeRepresent);
    },
    changeFace: function(){
      scope.faceReview = window.URL.createObjectURL(this.files[0]);
      $("#faceReview").attr("src",scope.faceReview)
    },
    changeExp: function(){
      scope.expReview = window.URL.createObjectURL(this.files[0])
      $("#expReview").attr("src",scope.expReview)
    },
    changeRepresent: function(){
      scope.representReview = window.URL.createObjectURL(this.files[0])
      $("#representReview").attr("src",scope.representReview)
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
      if(!/^.+$/.test($(":text[name=acname]").val())){
        alert("请填入姓名");
        return false;
      }else if(!$(":radio[name='acsex']:checked").length){
        alert("选择性别")
        return false;
      }else if(!/^.+$/.test($(":text[name='acbirthday']").val())){
        alert("请填入生日")
        return false;
      }else if(!/^.+$/.test($(":text[name='acheight']").val())){
        alert("请填入身高")
        return false;
      }else if(!/^.+$/.test($(":text[name='acweight']").val())){
        alert("请填入体重")
        return false;
      }else if(!/^.+$/.test($(":text[name='acschool']").val())){
        alert("请填入最高学历")
        return false;
      }else if(!/^.+$/.test($(":text[name='phone']").val())){
        alert("请填入手机")
        return false;
      }else if(!/^.+$/.test($(":text[name='accity']").val())){
        alert("请填入联系地址")
        return false;
      }else if(!scope.faceReview){
        alert("请上传个人照片")
        return false;
      }else if(!scope.expReview){
        alert("请上传演艺经历")
        return false;
      }else if(!scope.representReview){
        alert("请上传代表作封面")
        return false;
      }else if(!scope.faceReview){
        alert("请上传个人照片")
        return false;
      }else if(!/^.+$/.test($(":text[name='title']").val())){
        alert("请填入代表作标题")
        return false;
      }else if(!/^.+$/.test($(":text[name='href']").val())){
        alert("请填入代表作视频链接")
        return false;
      }else if(!/^.+$/.test($("textarea[name='comment']").val())){
        alert("请填入代表作简介")
        return false;
      }
      // console.log($(".form").serialize())
      // return false;
    }
  };
  page.init();
})
