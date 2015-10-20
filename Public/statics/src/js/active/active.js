$(function(){
    var scope = {
        now: 0,
        timer: null,
        
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
        },
        tabGroupByTime: function(){
            $("#groupByTime").find("li").removeClass("active");
            $(this).addClass("active");
        },
        mouseover: function(){
            clearTimeout(scope.timer);
        },
        mouseout: function(){
            page.autoTab();
        },
        createActive: function(){
            $("#mask").show();
        }
    };
    page.init()
});
