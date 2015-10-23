$(function() {

  var scope = {
    color1: "Redgroup",
    sex1: 1,
    color2: "Redgroup",
    sex2: 1

  };

  var page = {
    init: function() {
      //$("#J_ConStars .inner .list a").hover(page.hover);
      //$("#J_ConRule .inner .content1 .item").hover(page.hover);

      $("#J_CommentSendbox .submit").on("click", page.commentSendClick);
      $("#J_ConRule .tab a").on("click", page.ruleTabClick);
      $("#groupColorList").on("click", "li", page.loadStar);
      $("#groupSexList").on("click", "li", page.loadStar);

      $("#conStarGroup").on("click", "li", page.swtichTab);
      $("#judgeRuleName").on("click", "li", page.swtichRule);
      // 36强
      $("#colorlist1").on("click", "li", page.tabType1);
      $("#sexlist1").on("click", "li", page.tabSex1);
      // 6强
      $("#colorlist2").on("click", "li", page.tabType2);
      $("#sexlist2").on("click", "li", page.tabSex2);


      page.refleshgroup1();
      page.refleshgroup2();

      page.initStar();
    },
    // 36强 查询
    tabSex1: function() {
      $("#sexlist1").find("li").removeClass("active");
      $(this).addClass("active");
      scope.sex1 = $(this).data("sex");
      page.refleshgroup1();
    },
    tabType1: function() {
      $("#colorlist1").find("li").removeClass("active");
      $(this).addClass("active");
      scope.color1 = $(this).data("color");
      page.refleshgroup1();
    },
    // 36强 刷新列表
    refleshgroup1: function() {

      $.ajax({
        url: "/index.php?m=Home&c=Vote&a=wininter&condition=36",
        type: "get",
        dataType: "json",
        data: {
          a: scope.color1,
          sex: scope.sex1
        },
        success: function(json) {
          //alert(json.status)
          if(json.status == 102 ){
            $(".successlist").hide();
          }else if (json.status == 0) {
              var _html = "";
              for (var i = 0, len = json.data.length; i < len; i++) {
                _html += '<div class="item">'
                    +'<a href="#'+ json.data[i].id +'" class="hover">'
                    +'</a>'
                    +'<img class="img1" src="'+ json.data[i].headimg +'" alt="" />'
                    +'<div class="text">'
                      +'<h1>'+ json.data[i].name +'</h1>'
                      +'<h3>当前票数:'+ json.data[i].votes +'</h3>'
                    +'</div>'
                +'</div>'
              }
              $("#group1").html(_html);
              $("#content1commet").hide();
          } else {
            //alert(json.msg)
          }

        }
      })
    },

    // 36强 查询
    tabSex2: function() {
      $("#sexlist2").find("li").removeClass("active");
      $(this).addClass("active");
      scope.sex2 = $(this).data("sex");
      page.refleshgroup2();
    },
    tabType2: function() {
      $("#colorlist2").find("li").removeClass("active");
      $(this).addClass("active");
      scope.color2 = $(this).data("color");
      page.refleshgroup2();
    },
    // 36强 刷新列表
    refleshgroup2: function() {

      $.ajax({
        url: "/index.php?m=Home&c=Vote&a=wininter&condition=36",
        type: "get",
        dataType: "json",
        data: {
          a: scope.color2,
          sex: scope.sex2
        },
        success: function(json) {
          //alert(json.status)
          if(json.status == 102){
            $(".successlist").hide();
          }else if (json.status == 0) {
            var _html = "";
            for (var i = 0, len = json.data.length; i < len; i++) {
              _html += '<div class="item">'
                  +'<a href="#'+ json.data[i].id +'" class="hover">'
                  +'</a>'
                  +'<img class="img1" src="'+ json.data[i].headimg +'" alt="" />'
                  +'<div class="text">'
                    +'<h1>'+ json.data[i].name +'</h1>'
                    +'<h3>当前票数:'+ json.data[i].votes +'</h3>'
                  +'</div>'
              +'</div>'
            }
            $("#group2").html(_html);
          } else {
            //alert(json.msg)
          }

        }
      });

      $.ajax({
        url: "/index.php?m=Home&c=Vote&a=wininter&condition=6",
        type: "get",
        dataType: "json",
        data: {
        },
        success: function(json) {
          //alert(json.status)
          if (json.status == 102) {
            $("#finallylist").hide();
          }else if(json.status == 0){
            var _html = "";
            for(var i = 0, len = json.status.length ; i < len ; i++){
              if(json.status.data[i].groupid == "红组"){
                _html += '<div class="item">'
                  +'<a href="#'+ json.data[i].id +'"></a>'
                  +'<img src="'+ json.data[i].headimg +'" alt="" />'
                  +'<div class="frame"></div>'
                  +'<span class="name">'+ json.data[i].name +'</span>'
                  +'<div class="group redgroup">红组</div>'
                  +'<p class="vote">总票数</p>'
                  +'<p class="num">'+ json.data[i].votes +'</p>'
                +'</div>'


              }else if(json.status.data[i].groupid == "蓝组"){
                _html += '<div class="item">'
                  +'<a href="#'+ json.data[i].id +'"></a>'
                  +'<img src="'+ json.data[i].headimg +'" alt="" />'
                  +'<div class="frame"></div>'
                  +'<span class="name">'+ json.data[i].name +'</span>'
                  +'<div class="group bluegroup">蓝组</div>'
                  +'<p class="vote">总票数</p>'
                  +'<p class="num">'+ json.data[i].votes +'</p>'
                +'</div>'
              }else if(json.status.data[i].groupid == "绿组"){
                _html += '<div class="item">'
                  +'<a href="#'+ json.data[i].id +'"></a>'
                  +'<img src="'+ json.data[i].headimg +'" alt="" />'
                  +'<div class="frame"></div>'
                  +'<span class="name">'+ json.data[i].name +'</span>'
                  +'<div class="group greengroup">绿组</div>'
                  +'<p class="vote">总票数</p>'
                  +'<p class="num">'+ json.data[i].votes +'</p>'
                +'</div>'
              }
              $("#finallygroup").html(_html);
            }
            $("#content2commet").hide();
          }
        }
      });
    },


    ruleTabClick: function() {
      var i = $(this).data("i");
      $("#J_ConRule .inner .content").hide();
      $("#J_ConRule .inner .content" + i).show();
      $("#J_ConRule .tab a.c").removeClass("c");
      $(this).addClass("c");
    },
    swtichRule: function() {
      $("#judgeRuleName").find("li").removeClass("active");
      $(this).addClass("active");
      var _index = $(this).index();
      $("#judgeRuleContent").find("li").hide();
      $("#judgeRuleContent").find("li").eq(_index).show()
    },
    hover: function(e) {
      if (e.type == "mouseenter") {
        $(this).find(".hover").stop(true, true).fadeIn(100);
      } else {
        $(this).find(".hover").stop(true, true).fadeOut(100);
      }
    },
    voteIn: function() {
      $(this).find(".hover").stop(true, true).fadeIn(300);
      $(this).find(".txt").stop(true, true).fadeOut(100);
    },
    voteOut: function() {
      $(this).find(".hover").stop(true, true).fadeOut(100);
      $(this).find(".txt").stop(true, true).fadeIn(100);
    },
    commentSendClick: function(e) {
      var text = $.trim($("#J_CommentSendbox textarea").val());
      if (text == "") {
        alert("请输入评论内容");
      } else {
        var html = "<div class='item clearFix'><div class='head'><img src='" + STATIC_FILE_ROOT + "statics/images/p/a10.jpeg' /></div><div class='info'><span>V网友：清晰白阳2 发表日期：2015-06-07 19:56</span><p>" + text + "</p></div></div>";
        $("#J_CommentList").prepend(html);
        $("#J_CommentSendbox textarea").val("");
      }
    },
    swtichTab: function() {
      $("#conStarGroup").find("li").removeClass("active");
      $(this).addClass("active");
      var _index = $(this).index();
      $("#starGroups").find(".group").hide();
      $("#starGroups").find(".group").eq(_index).show();
    },
    //每次点击后加载 列表
    loadStar: function() {
      $(this).parent().find("li").removeClass("active");
      $(this).addClass("active");
      var _color = $("#groupColorList").find(".active").data("color");
      var _sex = $("#groupSexList").find(".active").data("sex");
      $.ajax({
        type: "get",
        dataType: "json",
        data: {
          url: "/index.php?m=Home&c=Vote&",
          a: _color,
          sex: _sex
        },
        success: function(json) {
          //alert(json.status)
          if (json.status == 0) {
            var _html = "";
            for (var i = 0; i < json.data.length; i++) {
              _html += '<div class="item">\
                            <div class="vote hover">\
                                <p>扫描二维码投票</p>\
                                <img src="' + json.data[i].codeimg + '"/>\
                                </div>\
                                <div class="txt">\
                                <p>' + json.data[i].name + '</p>\
                        <span>当前票数：' + json.data[i].votes + '</span>\
                        </div>\
                        <img src="./Uploads' + json.data[i].headimg + '"/>\
                            </div>'
            }
            $("#insertgroup").html(_html);

            $("#J_ConVote .inner .item").off().hover(page.voteIn, page.voteOut);

          } else {
            //alert(json.msg)
          }
        }
      })
    },
    //初始化加载 明星列表
    initStar: function() {
      $.ajax({
        type: "get",
        dataType: "json",
        data: {
          url: "/index.php?m=Home&c=Vote&",
          a: "redgroup",
          sex: "2"
        },
        success: function(json) {
          if (json.status == 0) {
            var _html = "";
            for (var i = 0; i < json.data.length; i++) {
              _html += '<div class="item">\
                            <div class="vote hover">\
                                <p>扫描二维码投票</p>\
                                <img src="' + json.data[i].codeimg + '"/>\
                                </div>\
                                <div class="txt">\
                                <p>' + json.data[i].name + '</p>\
                        <span>当前票数：' + json.data[i].votes + '</span>\
                        </div>\
                        <img src="./Uploads' + json.data[i].img + '"/>\
                            </div>'
            }
            $("#insertgroup").html(_html);

            $("#J_ConVote .inner .item").off().hover(page.voteIn, page.voteOut);

          } else {
            alert(json.msg)
          }
        }
      })
    }
  };
  page.init();


});
