/**
 * Created by admin on 2015/10/10.
 */

$(function(){
  var scope = {
    type: "redcom",
    totalpage: 0
  }
  var page = {
    init:function(){
      $("#typelist").on("click","li",page.tabGroup);
      page.getTotalPage(initPage(parseInt(scope.totalpage), page.getData));
    },
    tabGroup: function(){
      $("#typelist").find("li").removeClass("active");
      $(this).addClass("active");
      scope.type = $(this).data("type");
      page.getTotalPage(function(){
        initPage(parseInt(scope.totalpage), function(page){
          page.getData(page);
        })
      });
    },
    getData: function(page){
      $.ajax({
        url: "./index.php?m=Home&c=Committee",
        dataType: "json",
        type: "get",
        data: {
          a: scope.type,
          p: page
        },
        success: function(json){
          //写数据
          console.log(page)
        },
        error: function(){

        }
      })
    },
    getTotalPage: function(fn){
      $.ajax({
        url: "./index.php?m=Home&c=Committee",
        dataType: "json",
        type: "get",
        data: {
          a: scope.type,
          p: 1
        },
        success: function(json){
          scope.totalpage = json.data.page;
          fn();
        },
        error: function(){

        }
      })
    }
  };
  page.init();
})
