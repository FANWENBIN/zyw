function lazyload(option) {
  var settings = {
    defObj: null,
    defHeight: 0
  };
  settings = $.extend(settings, option || {});
  var defHeight = settings.defHeight;
  var defObj = (typeof settings.defObj == "object") ? settings.defObj.find("img") : $(settings.defObj).find("img");
  var pageTop = function() {
    return document.documentElement.clientHeight + Math.max(document.documentElement.scrollTop, document.body.scrollTop) - settings.defHeight;
  };
  var imgLoad = function() {
    console.log(defObj,defHeight,pageTop())
    defObj.each(function() {
      if ($(this).offset().top <= pageTop()) {
        var _src = $(this).attr("_src");
        //已显示的不用再显示
        if (_src) {
          //显示后，去掉src2属性
          $(this).attr("src", _src).removeAttr("_src");
        }
      }
    });
  };
  imgLoad();
  $(window).bind("scroll", function() {
    imgLoad();
  });
}
lazyload({
  defObj: ".lazyload"
});
