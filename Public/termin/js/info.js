/**
 * Created by admin on 2015/9/23.
 */

define(function (require, exports, module) {
    var app = require("./app");
    var nd = app.nd;

    $(function () {
        var scope = {};
        var page = {
            init: function () {
                $(".back").touchend(function () {
                    window.history.go(-1);
                });

                $(".menu").touchend(function () {
                    var $mask = $(".mask");


                    $mask.addClass("active");
                    $(".popUpWin").addClass("active")
                });
                $(".mask").touchend(function(){
                    $(this).removeClass("active");
                    $(".popUpWin").removeClass("active")
                });
                $(".news").touchend("li",page.linkToDetail)

            },
            linkToDetail: function(){
                window.location.href= "./index.php?m=Termin&c=News&a=newsdetail&newid="+$(this).data("para")
            }
        }

        page.init()

    })
});