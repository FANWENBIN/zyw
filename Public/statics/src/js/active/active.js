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
            page.getPage(1,function(){
              pageinit(scope.pageNum,function(index){
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
            console.log(scope.type)
        },
        tabGroupByTime: function(){
            $("#groupByTime").find("li").removeClass("active");
            $(this).addClass("active");
            scope.time = $(this).data("time");
            console.log(scope.time)
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
              var _html = "";
              for(var i = 0, len = json.data.data.length; i < len; i++ ){
                _html +=
                +'<li>'
                +'<a>'
                +'<img src="__PUBLIC__/statics/images/p_active.jpg">'
                +'<div class="tags">'
                +'<h3>伟来眼中的他们</h3>'
                +'<p>10.25-11.2</p>'
                +'</div>'
                +'<sub></sub>'
                +'</a>'
                +'<div class="desc">'
                +'<span class="txt">伟来眼中的他们伟来眼中的他们伟来眼中的他们伟来眼中的他们伟来眼中的他们伟来眼中的他们伟来眼中的他们伟来眼中的他们伟来眼中的他们伟来眼中的他们伟来眼中的他们伟来眼中的他们伟来眼中的他们</span>'
                +'<div><span class="num">12313</span><i class="focus"></i></div>'
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
