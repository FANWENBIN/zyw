$(function () {


    var page = {
        init: function () {
            //$("#J_ConStars .inner .list a").hover(page.hover);
            $("#J_ConRule .inner .content1 .item").hover(page.hover);

            $("#J_CommentSendbox .submit").on("click", page.commentSendClick);
            $("#J_ConRule .tab a").on("click", page.ruleTabClick);
            $("#groupColorList").on("click","li",page.loadStar);
            $("#groupSexList").on("click","li",page.loadStar);

            $("#conStarGroup").on("click","li",page.swtichTab);
            $("#judgeRuleName").on("click","li",page.swtichRule);
            page.initStar()
        },
        ruleTabClick: function () {
            var i = $(this).data("i");
            $("#J_ConRule .inner .content").hide();
            $("#J_ConRule .inner .content" + i).show();
            $("#J_ConRule .tab a.c").removeClass("c");
            $(this).addClass("c");
        },
        swtichRule: function(){
            $("#judgeRuleName").find("li").removeClass("active");
            $(this).addClass("active");
            var _index = $(this).index();
            $("#judgeRuleContent").find("li").hide();
            $("#judgeRuleContent").find("li").eq(_index).show()
        },
        hover: function (e) {
            if (e.type == "mouseenter") {
                $(this).find(".hover").stop(true, true).fadeIn(100);
            } else {
                $(this).find(".hover").stop(true, true).fadeOut(100);
            }
        },
        voteIn: function () {
            $(this).find(".hover").stop(true, true).fadeIn(300);
            $(this).find(".txt").stop(true, true).fadeOut(100);
        },
        voteOut: function () {
            $(this).find(".hover").stop(true, true).fadeOut(100);
            $(this).find(".txt").stop(true, true).fadeIn(100);
        },
        commentSendClick: function (e) {
            var text = $.trim($("#J_CommentSendbox textarea").val());
            if (text == "") {
                alert("请输入评论内容");
            } else {
                var html = "<div class='item clearFix'><div class='head'><img src='" + STATIC_FILE_ROOT + "statics/images/p/a10.jpeg' /></div><div class='info'><span>V网友：清晰白阳2 发表日期：2015-06-07 19:56</span><p>" + text + "</p></div></div>";
                $("#J_CommentList").prepend(html);
                $("#J_CommentSendbox textarea").val("");
            }
        },
        swtichTab: function(){
            $("#conStarGroup").find("li").removeClass("active");
            $(this).addClass("active");
            var _index = $(this).index();
            $("#starGroups").find(".group").hide();
            $("#starGroups").find(".group").eq(_index).show();
        },
        //每次点击后加载 列表
        loadStar: function(){
            $(this).parent().find("li").removeClass("active");
            $(this).addClass("active");
            var _color = $("#groupColorList").find(".active").data("color");
            var _sex = $("#groupSexList").find(".active").data("sex");
            $.ajax({
                type: "get",
                dataType: "json",
                data: {
                    url: "/index.php?m=Home&c=Index&",
                    a: _color,
                    sex: _sex
                },
                success: function(json){
                    //alert(json.status)
                    if(json.status == 0){
                        var _html = "";
                        for(var i = 0; i < json.data.length; i++ ){
                            _html += '<div class="item">\
                            <div class="vote hover">\
                                <p>扫描二维码投票</p>\
                                <img src="'+ json.data[i].codeimg +'"/>\
                                </div>\
                                <div class="txt">\
                                <p>'+ json.data[i].name +'</p>\
                        <span>当前票数：'+ json.data[i].votes +'</span>\
                        </div>\
                        <img src="./Uploads'+ json.data[i].headimg +'"/>\
                            </div>'
                        }
                        $("#insertgroup").html(_html);

                        $("#J_ConVote .inner .item").off().hover(page.voteIn, page.voteOut);

                    }else{
                        //alert(json.msg)
                    }
                }
            })
        },
        //初始化加载 明星列表
        initStar: function(){
            $.ajax({
                type: "get",
                dataType: "json",
                data: {
                    url: "/index.php?m=Home&c=Vote&",
                    a: "redgroup",
                    sex: "2"
                },
                success: function(json){
                    if(json.status == 0){
                        var _html = "";
                        for(var i = 0; i < json.data.length; i++ ){
                            _html += '<div class="item">\
                            <div class="vote hover">\
                                <p>扫描二维码投票</p>\
                                <img src="'+ json.data[i].codeimg +'"/>\
                                </div>\
                                <div class="txt">\
                                <p>'+ json.data[i].name +'</p>\
                        <span>当前票数：'+ json.data[i].votes +'</span>\
                        </div>\
                        <img src="./Uploads'+ json.data[i].img +'"/>\
                            </div>'
                        }
                        $("#insertgroup").html(_html);

                        $("#J_ConVote .inner .item").off().hover(page.voteIn, page.voteOut);

                    }else{
                        alert(json.msg)
                    }
                }
            })
        }
    };
    page.init();


});