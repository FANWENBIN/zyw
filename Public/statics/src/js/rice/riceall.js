$(function(){
  var scope = {
    type: "fanssum",
    pageNum: 1
  };
  var page = {
    init: function(){
      $(".group").on("click","li",page.tab);
      page.getPage(function(){
        pageInit(scope.pageNum,10,function(index){
          page.getData(index);
        });
      });
    },
    tab: function(){
      scope.type = $(this).data("type");
      $(".group li").removeClass("active");
      $(this).addClass("active");
      page.getPage(function(){
        pageInit(scope.pageNum,10,function(index){
          page.getData(index);
        });
      });

    },
    getPage: function(fn){
      $.ajax({
        type: "get",
        url: "./index.php?m=Home&c=Rice&a=morefans",
        data: {
          condition: scope.type,
          p: 1
        },
        dataType: "json",
        success: function(json){
          scope.pageNum = json.data.page;
          fn();
        },
        error: function(){
        }
      })
    },
    getData: function(page){
      $.ajax({
        url: "./index.php?m=Home&c=Rice&a=morefans",
        type: "get",
        dataType: "json",
        data: {
          condition: scope.type,
          p: page
        },
        success: function(json){
          var _arr = json.data.data;
          var _html = "";
          if(json.data.page == 0) {
            $("#itemlist").html(_html);
            return false;
          }
          for(var i = 0, len = _arr.length; i < len; i++ ){
            _html += '<div class="item">'+
              '<img src="'+ _arr[i].img +'" alt="" />'+
              '<p>'+ _arr[i].name +'</p>'+
              '<div class="cover">'+
                '<div class="info">'+
                  '粉丝数 '+ _arr[i].fanssum +'&nbsp;&nbsp;贴&nbsp;量 '+ _arr[i].posts +
                '</div>'+
                '<br/>'+
                '<a href="/zyw/index.php?m=Home&c=Rice&a=homepage&id='+ _arr[i].actorid +'">我要报名</a>'+
              '</div>'+
            '</div>'
          }
          $("#itemlist").html(_html);
        },
        error: function(){
        }
      })
    }
  }
  page.init();
})
