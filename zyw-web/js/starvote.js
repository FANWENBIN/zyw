/**
 * Created by admin on 2015/9/23.
 */

define(function (require, exports, module) {
    var app = require("./app");
    var nd = app.nd;
    $(function () {
        var page = {
            init: function(){
                $("#topBack").touchend(page.backToPreviewPage);
                $("#groupSex").touchend("li",page.changeGroupSex);
                $("#groupColor").touchend("li",page.changeGroupColor)
            },

            //返回上一页
            backToPreviewPage:function(){
                window.history.go(-1);
            },
            //获取男女星分组列表
            changeGroupSex: function(){
                var $groupS = $("#groupSex");
                var $groupC = $("#groupColor");
                $groupS.find("li").removeClass("active");
                $(this).addClass("active");

                if($groupC.find(".active").hasClass("red")){
                    if($(this).hasClass("male")){
                        //获取男性明星列表
                        nd.ajax({
                            url: "_api.php",
                            data: { what: "abc"},
                            success: function(e){
                                // do success
                            }
                        });
                    }else{
                        //获取女性明星列表
                        nd.ajax({
                            url: "_api.php",
                            data: { what: "abc"},
                            success: function(e){
                                // do success
                            }
                        });
                    }
                    //获取蓝组明星
                }else if($groupC.find(".active").hasClass("blue")){
                    if($(this).hasClass("male")){
                        //获取男性明星列表
                        nd.ajax({
                            url: "_api.php",
                            data: { what: "abc"},
                            success: function(e){
                                // do success
                            }
                        });
                    }else{
                        //获取女性明星列表
                        nd.ajax({
                            url: "_api.php",
                            data: { what: "abc"},
                            success: function(e){
                                // do success
                            }
                        });
                    }
                }else{
                    if($(this).hasClass("male")){
                        //获取男性明星列表
                        nd.ajax({
                            url: "_api.php",
                            data: { what: "abc"},
                            success: function(e){
                                // do success
                            }
                        });
                    }else{
                        //获取女性明星列表
                        nd.ajax({
                            url: "_api.php",
                            data: { what: "abc"},
                            success: function(e){
                                // do success
                            }
                        });
                    }
                }

            },
            //获取分组列表
            changeGroupColor: function(){
                var $groupC = $("#groupColor");
                $groupC.find("li").removeClass("active");
                $(this).addClass("active");
                //男演员分组
                if($("#groupSex").find(".active").hasClass("male")){
                    if($(this).hasClass("red")){
                        //获取红组明星列表
                        nd.ajax({
                            url: "_api.php",
                            data: { what: "abc"},
                            success: function(e){
                                // do success
                            }
                        });
                    }else if($(this).hasClass("blue")){
                        //获取蓝组明星列表
                        nd.ajax({
                            url: "_api.php",
                            data: { what: "abc"},
                            success: function(e){
                                // do success
                            }
                        });
                    }else{
                        //获取绿组明星列表
                        nd.ajax({
                            url: "_api.php",
                            data: { what: "abc"},
                            success: function(e){
                                // do success
                            }
                        });
                    }
                }else{
                    //女演员分组
                    if($(this).hasClass("red")){
                        //获取红组明星列表
                        nd.ajax({
                            url: "_api.php",
                            data: { what: "abc"},
                            success: function(e){
                                // do success
                            }
                        });
                    }else if($(this).hasClass("blue")){
                        //获取蓝组明星列表
                        nd.ajax({
                            url: "_api.php",
                            data: { what: "abc"},
                            success: function(e){
                                // do success
                            }
                        });
                    }else{
                        //获取绿组明星列表
                        nd.ajax({
                            url: "_api.php",
                            data: { what: "abc"},
                            success: function(e){
                                // do success
                            }
                        });
                    }
                }

            }
        };
        page.init()
    })
});