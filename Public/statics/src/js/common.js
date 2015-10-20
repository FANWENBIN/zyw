$(function() {

  var scope = {

  };
  var page = {
    init: function(){
      $("#login").on("click",page.showInfo);
      $("body").on("click",page.hideInfo)
    },
    showInfo: function(e){
      $("#myinfoalert").show();
      return false;
    },
    hideInfo: function(){
        $("#myinfoalert").hide();
    }
  };
  page.init();



})
