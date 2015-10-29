$(function() {

  var scope = {

  };
  var page = {
    init: function(){
      $("#login").on("click",page.showInfo);
      $("body").on("click",page.hideInfo);
      page.addLogin();
      if($.testLogin()){
        $("#islogin").show();
      }else{
        $("#nologin").show();
      }
      $("#reg").on("click",regShow);
      $("#log").on("click",logShow);
    },
    regShow: function(){
      
    },
    logShow: function(){

    },
    showInfo: function(e){
      $("#myinfoalert").show();
      return false;
    },
    hideInfo: function(){
        $("#myinfoalert").hide();
    },
    addLogin: function(){
      $.extend({
        testLogin: function(){
          $.ajax({
            url: "./index.php?m=Home&c=Login&a=checklogin",
            dataType: "json",
            type: "get"
            success: function(json){
              if(json.status == "0")return false;
              if(json.status == "1")return true;
            },
            error: function(){

            }
          })
        }
      })
    }
  };
  page.init();



})
