$(function() {
  var scope = {
    currentpage: 1,
    minpage: 1,
    maxpage: 10,
    totalpage: 1
  };
  window.pageInit = function(totalpage,fn) {

    $("#pagelist .pre").off().on("click", function(){
      prePage(fn);
    });
    $("#pagelist .next").off().on("click", function(){
      nextPage(fn);
    });
    $("#pagelist .num").off().on("click", "li", function(){
      _this = this;
      numclick(fn,_this);
    });
    listInit(totalpage,fn);
  };
  //页数点击 切换同步换下一屏
  /*

  <div class="pagelist" id="pagelist">
    <a href="javacript:;" class="pre">上一页</a>
    <ul class="num">
      <li class="active">1</li>
      <li>2</li>
      <li>3</li>
    </ul>
    <a href="javacript:;" class="next">下一页</a>
  </div>



  */

  function listInit(totalpage,fn){
    scope.totalpage = parseInt(totalpage);
    if((typeof scope.totalpage) == "number"){
      if(scope.totalpage > 10){
        $("#pagelist .num").html('<li class="active">1</li>' + '<li>2</li>' + '<li>3</li>' + '<li>4</li>' + '<li>5</li>' + '<li>6</li>' + '<li>7</li>' + '<li>8</li>' + '<li>9</li>' + '<li>10</li>')
      }else{
        scope.maxpage = scope.totalpage;
        var _html = '';
        for (var i = 0, len = totalpage; i < len; i++) {
          if (i == 0) {
            _html += '<li class="active">' + ( i + 1 ) + '</li>';
          } else {
            _html += '<li>' + ( i + 1 ) + '</li>';
          }
        }
        $("#pagelist .num").html(_html);
        fn(1);
      }
    }else{
      console.log("页数是非数字，请确认！");
      scope.totalpage = 1;
      scope.minpage = 1;
      scope.maxpage = 1;
    };
    console.log(scope.totalpage,scope.currentpage,scope.minpage,scope.maxpage)
  };
 
  //上一页
  function prePage(fn) {

    if(scope.totalpage == 0)return 0;
    if (scope.currentpage == 1 && scope.currentpage == 1) {
      console.log("这是第一页，不能切换！");
    } else if (scope.currentpage <= scope.minpage) {
      scope.minpage -= 10;
      scope.maxpage = scope.minpage + 9;
      scope.currentpage--;
      $("#pagelist .num").find("li").removeClass("active");
      $("#pagelist .num").find("li").eq(scope.currentpage).addClass("active");
      $("#pagelist .num").html('<li>' + scope.minpage + '</li>' + '<li>' + (scope.minpage + 1) + '</li>' + '<li>' + (scope.minpage + 2) + '</li>' + '<li>' + (scope.minpage + 3) + '</li>' + '<li>' + (scope.minpage + 4) + '</li>' + '<li>' + (scope.minpage + 5) + '</li>' + '<li>' + (scope.minpage + 6) + '</li>' + '<li>' + (scope.minpage + 7) + '</li>' + '<li>' + (scope.minpage + 8) + '</li>' + '<li class="active">' + (scope.minpage + 9) + '</li>')
      fn(scope.currentpage);
    } else {
      scope.currentpage--;
      $("#pagelist .num").find("li").removeClass("active");
      $("#pagelist .num").find("li").eq(scope.currentpage % 10 - 1).addClass("active");
      fn(scope.currentpage);
    };
  };
  //下一页
  function nextPage(fn) {
    if(scope.totalpage == 0)return 0;
    if (scope.currentpage == scope.totalpage) {
      console.log("这是最后一页，不能切换！");
      //当前页大于等于最大页&&+10小于总数
    } else if (scope.currentpage == scope.maxpage && (scope.maxpage + 10 <= scope.totalpage)) {
      scope.minpage += 10;
      scope.maxpage = scope.minpage + 9;
      scope.currentpage++;
      $("#pagelist .num").find("li").removeClass("active");
      $("#pagelist .num").find("li").eq(scope.currentpage % 10 - 1).addClass("active");
      $("#pagelist .num").html('<li class="active">' + scope.minpage + '</li>' + '<li>' + (scope.minpage + 1) + '</li>' + '<li>' + (scope.minpage + 2) + '</li>' + '<li>' + (scope.minpage + 3) + '</li>' + '<li>' + (scope.minpage + 4) + '</li>' + '<li>' + (scope.minpage + 5) + '</li>' + '<li>' + (scope.minpage + 6) + '</li>' + '<li>' + (scope.minpage + 7) + '</li>' + '<li>' + (scope.minpage + 8) + '</li>' + '<li>' + (scope.minpage + 9) + '</li>')
      fn(scope.currentpage);
      //当前页大于等于最大页&&+10大于总数
    } else if (scope.currentpage == scope.maxpage) {
      scope.minpage += 10;
      scope.maxpage = scope.totalpage;
      scope.currentpage++;
      var _html = '';
      for (var i = 0, len = scope.maxpage - scope.minpage; i <= len; i++) {
        if (i == 0) {
          _html += '<li class="active">' + (scope.minpage + i) + '</li>';
        } else {
          _html += '<li>' + (scope.minpage + i) + '</li>';
        }
      }
      $("#pagelist .num").html(_html);
      fn(scope.currentpage);
      //其他
    } else {
      scope.currentpage++;
      $("#pagelist .num").find("li").removeClass("active");
      $("#pagelist .num").find("li").eq(scope.currentpage % 10 - 1).addClass("active");
      fn(scope.currentpage);
    };
  };

  function numclick(fn,_this) {
    $("#pagelist .num").find("li").removeClass("active");
    $(_this).addClass("active");
    scope.currentpage = parseInt($(_this).html());
    fn(scope.currentpage);
  }
})
