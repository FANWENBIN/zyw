$(function(){
    var scope = {
        now: 0,
        timer: null,
        type: 0,
        time: 0,
        pageNum: 1
    };
    var page = {
        init: function(){
            $("#dotList").on("click","li",page.tabBanner);
            $("#groupByType").on("click","li",page.tabGroupByType);
            $("#groupByTime").on("click","li",page.tabGroupByTime);
            $("#imgList").on("mouseover",page.mouseover);
            $("#imgList").on("mouseout",page.mouseout);
            $("#newActive").on("click",page.createActive);
            page.autoTab();

            //分页
            page.getPage(function(){
              pageinit(scope.pageNum,function(index){
                if(index == 0)return false;
                page.getActiveData(index);
              });
            });


        },
        tabBanner: function(){
            clearTimeout(scope.timer);
            scope.now = $(this).index();
            var _aDotLi = $("#dotList");
            var _aImgLi = $("#imgList");
            _aDotLi.find("li").removeClass("active");
            $(this).addClass("active");
            _aImgLi.find("a").removeClass("active");
            _aImgLi.find("a").eq($(this).index()).addClass("active");
            page.autoTab();
        },
        autoTab: function(){
            scope.timer = setInterval(function(){
                var _aDotLi = $("#dotList");
                var _aImgLi = $("#imgList");
                scope.now++;
                if(scope.now >= _aDotLi.find("li").length)scope.now = 0;
                _aDotLi.find("li").removeClass("active");
                _aDotLi.find("li").eq(scope.now).addClass("active");
                _aImgLi.find("a").removeClass("active");
                _aImgLi.find("a").eq(scope.now).addClass("active");
            },3000)
        },
        tabGroupByType: function(){
            $("#groupByType").find("li").removeClass("active");
            $(this).addClass("active");
            scope.type = $(this).data("type");
            page.getPage(function(){
              pageinit(scope.pageNum,function(index){
                page.getActiveData(index);
              });
            });
        },
        tabGroupByTime: function(){
            $("#groupByTime").find("li").removeClass("active");
            $(this).addClass("active");
            scope.time = $(this).data("time");
            page.getPage(function(){
              pageinit(scope.pageNum,function(index){
                page.getActiveData(index);
              });
            });
        },
        mouseover: function(){
            clearTimeout(scope.timer);
        },
        mouseout: function(){
            page.autoTab();
        },
        createActive: function(){
            $("#mask").show();
        },


        getPage: function(fn){
          $.ajax({
            url: "./index.php?m=Home&c=Active&a=activetype",
            type: "get",
            dataType: "json",
            data: {
              type: scope.type,
              time: scope.time,
              p: 1
            },
            success: function(json){
              scope.pageNum = json.data.page;
              fn();
            },
            error: function(){

            }

          })
        },

        getActiveData: function(page){

          $.ajax({
            url: "./index.php?m=Home&c=Active&a=activetype",
            type: "get",
            dataType: "json",
            data: {
              type: scope.type,
              time: scope.time,
              p: page
            },
            success: function(json){

              var _arr = json.data.data;
              var _html = "";
              if(json.data.page == 0) $("#itemlist").html(_html);
              for(var i = 0, len = _arr.length; i < len; i++ ){
                _html += '<li>'
                +'<a>'
                +'<img src="./Uploads'+ _arr[i].img +'">'
                +'<div class="tags">'
                +'<h3>' + _arr[i].title + '</h3>'
                +'<p>'+ _arr[i].begin_time +'-'+ _arr[i].last_time +'</p>'
                +'</div>'
                +'<sub></sub>'
                +'</a>'
                +'<div class="desc">'
                +'<span class="txt">'+ _arr[i].content +'</span>'
                +'<div><span class="num">'+ _arr[i].concern +'</span><i class="focus"></i></div>'
                +'</div>'
                +'</li>'
              }
              $("#itemlist").html(_html);
            },
            error: function(){
            }

          })
        }
    };
    page.init()
});
