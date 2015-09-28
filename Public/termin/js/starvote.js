/**
 * Created by admin on 2015/9/23.
 */

define(function (require, exports, module) {
    var app = require("./app");
    var nd = app.nd;
    $(function () {
        var page = {
            init: function () {
                $("#topBack").touchend(page.backToPreviewPage);
                $("#groupSex").touchend("li", page.changeGroup);
                $("#groupColor").touchend("li", page.changeGroup);
                page.getRenew(1, "redgroup")
            },

            //返回上一页
            backToPreviewPage: function () {
                window.history.go(-1);
            },
            //获取分组列表
            changeGroup: function () {
                var $groupS = $("#groupSex");
                var $groupC = $("#groupColor");
                $(this).parent().find("li").removeClass("active");
                $(this).addClass("active");
                var _sex = $groupS.find("active").data("sex");
                var _color = $groupS.find("active").data("color");
                //获取明星列表
                page.getRenew()

            },
            getRenew: function (_sex, _color) {
                nd.ajax({
                    type: "get",
                    dataType: "json",
                    data: {
                        url: "/index.php?m=Home&c=Index&",
                        a: _color,
                        sex: _sex
                    },
                    success: function (json) {
                        if (json.status == 0) {
                            var _html = "";
                            for (var i = 0; i < json.data.length; i++) {
                                if(json.data.lifting == 0){
                                    json.data.lifting = "";
                                    var _rankimgStyle = 'background: url("../img/icon_increase.jpg") 50% no-repeat; background-size: cover'
                                }
                                var _deNum = Math.floor(i/10);
                                var _tempSig = ""+ i;
                                var _sigNum = _tempSig.charAt(_tempSig.length -1);
                                var _deStyle = 'background: url("../img/num/num_'+ _deNum +'.jpg") 50% no-repeat; background-size: cover';
                                var _sigStyle = 'background: url("../img/num/num_'+ _sigNum +'.jpg") 50% no-repeat; background-size: cover';

                                _html += '' +
                                    '<li class="r">'+
                                    '<div class="no"><span class="de" style="'+ _deStyle +'"></span><span class="sig" style="'+ _sigStyle +'"></span></div>'+
                                '<span class="face" style="background: url(./Uploads'+ json.data[i].headimg +')"></span>'+
                                    '<span class="name">'+ json.data[i].name +'</span>'+
                                    '<span class="hot">热度：'+ json.data[i].votes +'</span>'+
                                '<div class="ranking"><span class="rankIcon" style="'+ _rankimgStyle +'"></span><span>'+ json.data.lifting +'</span></div>'+
                                '</li>'




                            }
                            $("#outerHtml").html(_html);
                        } else {
                            alert(json.msg)
                        }
                    }
                });
            }
        };
        page.init()
    })
});