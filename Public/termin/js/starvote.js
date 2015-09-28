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
                var _sex = $groupS.find(".active").data("sex");
                var _color = $groupC.find(".active").data("color");
                //获取明星列表
                page.getRenew(_sex, _color)

            },
            getRenew: function (_sex, _color) {
                nd.ajax({
                    url: "./index.php?m=Home&c=Index",
                    type: "get",
                    dataType: "json",
                    data: {
                        a: _color,
                        sex: _sex
                    },
                    success: function (json) {
                        if (json.status == 0) {
                            var _html = "";
                            var _rankimgStyle;
                            for (var i = 0; i < json.data.length; i++) {
                                if(json.data[i].lifting == 0){
                                    json.data[i].lifting = "";
                                    _rankimgStyle = 'background: url('+ app.ImgUrl +'/img/icon_duce.jpg) 50% no-repeat; background-size: cover'
                                }else if(json.data[i].lifting > 0){
                                    _rankimgStyle = 'background: url('+ app.ImgUrl +'/img/icon_increase.jpg) 50% no-repeat; background-size: cover'
                                }else{
                                    json.data[i].lifting = -(json.data[i].lifting);
                                    _rankimgStyle = 'background: url('+ app.ImgUrl +'/img/icon_decrease.jpg) 50% no-repeat; background-size: cover'
                                }
                                var _deNum = Math.floor((i+1)/10);
                                var _tempSig = ""+ (i+1);
                                var _sigNum = parseInt(_tempSig.charAt(_tempSig.length -1));
                                console.log(_deNum,_sigNum);
                                var _deStyle = 'background: url('+ app.ImgUrl +'/img/num/num_'+ _deNum +'.jpg) 50% no-repeat; background-size: cover';
                                var _sigStyle = 'background: url('+ app.ImgUrl +'/img/num/num_'+ _sigNum +'.jpg) 50% no-repeat; background-size: cover';
                                if(i == 0){
                                    _deStyle = 'background: url('+ app.ImgUrl +'/img/num/num_'+ _deNum +'_1st.jpg) 50% no-repeat; background-size: cover';
                                    _sigStyle = 'background: url('+ app.ImgUrl +'/img/num/num_'+ _sigNum +'_1st.jpg) 50% no-repeat; background-size: cover';
                                }
                                if(i == 1){
                                    _deStyle = 'background: url('+ app.ImgUrl +'/img/num/num_'+ _deNum +'_2nd.jpg) 50% no-repeat; background-size: cover';
                                    _sigStyle = 'background: url('+ app.ImgUrl +'/img/num/num_'+ _sigNum +'_2nd.jpg) 50% no-repeat; background-size: cover';
                                }
                                if(i == 3){
                                    _deStyle = 'background: url('+ app.ImgUrl +'/img/num/num_'+ _deNum +'_3rd.jpg) 50% no-repeat; background-size: cover';
                                    _sigStyle = 'background: url('+ app.ImgUrl +'/img/num/num_'+ _sigNum +'_3rd.jpg) 50% no-repeat; background-size: cover';
                                }
                                _html += '' +
                                    '<li class="r">'+
                                    '<div class="no"><span class="de" style="'+ _deStyle +'"></span><span class="sig" style="'+ _sigStyle +'"></span></div>'+
                                '<span class="face" style="background: url(./Uploads'+ json.data[i].headimg +') 50% no-repeat; background-size: cover;"></span>'+
                                    '<span class="name">'+ json.data[i].name +'</span>'+
                                    '<span class="hot">热度：'+ json.data[i].votes +'</span>'+
                                '<div class="ranking"><span class="rankIcon" style="'+ _rankimgStyle +'"></span><span class="lift">'+ json.data[i].lifting +'</span></div>'+
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