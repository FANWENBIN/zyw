$(function () {


    var page = {
        init: function () {
            $("#J_ConStars .inner .list a").hover(page.hover);
            $("#J_ConRule .inner .content1 .item").hover(page.hover);
            $("#J_ConVote .inner .item").hover(page.voteHover);
            $("#J_CommentSendbox .submit").on("click", page.commentSendClick);
            $("#J_ConRule .tab a").on("click", page.ruleTabClick);
            $("#groupColorList").on("click","li",page.loadStar);
            $("#groupSexList").on("click","li",page.loadStar);

            $("#conStarGroup").on("click","li",page.swtichTab)
            page.initStar()
        },
        ruleTabClick: function () {
            var i = $(this).data("i");
            $("#J_ConRule .inner .content").hide();
            $("#J_ConRule .inner .content" + i).show();
            $("#J_ConRule .tab a.c").removeClass("c");
            $(this).addClass("c");
        },
        hover: function (e) {
            if (e.type == "mouseenter") {
                $(this).find(".hover").stop(true, true).fadeIn(100);
            } else {
                $(this).find(".hover").stop(true, true).fadeOut(100);
            }
        },
        voteHover: function (e) {
            if (e.type == "mouseenter") {
                $(this).find(".hover").stop(true, true).fadeIn(300);
                $(this).find(".txt").stop(true, true).fadeOut(100);
            } else {
                $(this).find(".hover").stop(true, true).fadeOut(100);
                $(this).find(".txt").stop(true, true).fadeIn(100);
            }
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
        loadStar: function(){
            $(this).parent().find("li").removeClass("active");
            $(this).addClass("active");
            var _color = $("#groupColorList").find(".active").data("color");
            var _sex = $("#groupSexList").find(".active").data("sex");
            $.ajax({
                type: "get",
                data: {
                    url: "/index.php?m=Home&c=Index&",
                    a: _color,
                    sex: _sex
                },
                success: function(json){
                    if(json.status == "0"){
                        var _html = "";
                        json.data
                        for(var i = 0; i < json.data.length; i++ ){
                            _html += '<div class="item">\
                            <div class="vote hover">\
                                <p>扫描二维码投票</p>\
                                <img src="'+ json.data.codeimg +'"/>\
                                </div>\
                                <div class="txt">\
                                <p>'+ json.data.name +'</p>\
                        <span>当前票数：'+ json.data.votes +'</span>\
                        </div>\
                        <img src="./Uploads'+ json.data.headimg +'"/>\
                            </div>'
                        }
                        $("#insertgroup").html(_html)

                    }else{
                        alert(json.msg)
                    }
                }
            })
        },
        initStar: function(){
            $.ajax({
                type: "get",
                data: {
                    url: "/index.php?m=Home&c=Index&",
                    a: "redgroup",
                    sex: "2"
                },
                success: function(json){
                    if(json.status == "0"){
                        var _html = "";
                        for(var i = 0; i < json.data.length; i++ ){
                            _html += '<div class="item">\
                            <div class="vote hover">\
                                <p>扫描二维码投票</p>\
                                <img src="'+ json.data.codeimg +'"/>\
                                </div>\
                                <div class="txt">\
                                <p>'+ json.data.name +'</p>\
                        <span>当前票数：'+ json.data.votes +'</span>\
                        </div>\
                        <img src="./Uploads'+ json.data.headimg +'"/>\
                            </div>'
                        }
                        $("#insertgroup").html(_html)

                    }else{
                        alert(json.msg)
                    }
                }
            })
        }
    };
    page.init();


});