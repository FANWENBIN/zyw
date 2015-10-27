$(function(){
  var scope = {
    type: "hot",
    totalpage: 0
  };
  var page = {
    init: function(){
      tabinit();
      $("#sortinglist").on("click","li",page.tabSorting);
      page.getTotalPage(function(){
        pageInit(scope.totalpage, 6,function(index){
          page.getData(index);
        })
      });
    },
    tabSorting: function(){
      $("#sortinglist").find("li").removeClass("active");
      $(this).addClass("active");
      scope.type = $(this).data("type");
      page.getTotalPage(function(){
        pageInit(scope.totalpage, 6,function(index){
          page.getData(index);
        })
      })
    },
    getTotalPage: function(fn){
      $.ajax({
        url: "./index.php?m=Home&c=Stage&a=stageworks",
        type: "get",
        dataType: "json",
        data: {
          condition: scope.type,
          p: 1
        },
        success: function(json){
          scope.totalpage = parseInt(json.data.page);
          fn();
        },
        error: function(){

        }
      })
    },
    getData: function(index){
      $.ajax({
        url: "./index.php?m=Home&c=Stage&a=stageworks",
        type: "get",
        dataType: "json",
        data: {
          condition: scope.type,
          p: index
        },
        success: function(json){
          //写数据
          var _arr = json.data.data;
          var _html = '';
          for(var i= 0,len = _arr.length ; i < len; i++){
            _html += '<a class="itembig" href="#'+ _arr[i].id +'">'
              +'<img src="./Uploads'+ _arr[i].img +'" alt="" />'
              +'<div class="text">'
                +'<h1>'+ _arr[i].title +'</h1>'
                +'<h3>'+ _arr[i].instime +'</h3>'
              +'</div>'
              +'<sub></sub>'
            +'</a>'


          }
          $("#sortinggroup").html(_html);
        },
        error: function(){

        }
      })
    },
  };
  page.init();
})
