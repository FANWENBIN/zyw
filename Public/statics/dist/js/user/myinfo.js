$(function(){var i={init:function(){initRadio("sex"),$(".sublist").on("click","li",i.leftClick),$(".group").on("click","li",i.upClick)},leftClick:function(){$(this).parent().find("li").removeClass("active"),$(this).addClass("active"),$(this).parents(".bottomitem").find(".item").removeClass("active"),$(this).parents(".bottomitem").find(".item").eq($(this).index()).addClass("active")},upClick:function(){$(this).parent().find("li").removeClass("active"),$(this).addClass("active"),$(".list").find(".bottomitem").removeClass("active"),$(".list").find(".bottomitem").eq($(this).index()).addClass("active")}};i.init()});