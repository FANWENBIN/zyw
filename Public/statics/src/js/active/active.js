$(function(){
    var scope = {
        now: 0,
        timer: null,
        type: 0,
        time: 0
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
            page.getActiveData(1);
            pageinit(5,function(index){
              console.log(index);
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
        getActiveData: function(page){
          $.ajax({
            url: "./index.php?m=Home&c=Active&a=activetype",
            type: "get",
            dateType: "json",
            data: {
              type: scope.type,
              time: scope.time,
              p: page
            },
            success: function(json){
              console.log(json);
            },
            error: function(){

            }

          })
        }
    };
    page.init()
});
