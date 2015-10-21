/**
 * Created by admin on 2015/9/18.
 */
;
(function () {
  //获取当前js地址，并在项目中引用。
  var scripts=document.getElementsByTagName('script');
  var lastUrl=scripts[scripts.length-1].src;
  var str = lastUrl.substring(0,lastUrl.lastIndexOf("/"));

  var bAdd = false;
  window.initRadio = function (args1) {
    var aInput = document.getElementsByName(args1);
    var aSpan = [];
    //生成新节点
    for (var i = 0; i < aInput.length; i++) {
      var oSpan = document.createElement("span");
      oSpan.className = "my-radio";
      (function (index) {
        oSpan.onclick = function () {
          for (var i = 0; i < aSpan.length; i++) {
            aSpan[i].className = "my-radio"
          }
          this.className = "my-radio-active";
          aInput[index].checked = true;
        };
      })(i);
      //插入新新节点
      aInput[i].parentNode.insertBefore(oSpan, aInput[i]);
      aInput[i].style.display = "none";
      aSpan.push(oSpan);
    }
    //插入css样式表
    if (!bAdd) {//判断是否已经调用css
      var oLink = document.createElement("link");
      oLink.href = str+"/myComponent.css";
      oLink.rel = "stylesheet";
      oLink.type = "text/css";
      var oHead = document.getElementsByTagName("head")[0];
      oHead.insertBefore(oLink, oHead.children[0]);
      bAdd = true;
    }
  };
  window.initCheckbox = function (args1) {
    var aInput = document.getElementsByName(args1);
    var aSpan = [];
    //生成新节点
    for (var i = 0; i < aInput.length; i++) {
      var oSpan = document.createElement("span");
      oSpan.className = "my-checkbox";
      (function (index) {
        oSpan.onclick = function () {
          if (this.className == "my-checkbox-active") {
            this.className = "my-checkbox";
            aInput[index].checked = false;
          } else {
            this.className = "my-checkbox-active";
            aInput[index].checked = true;
          }
        };
      })(i);
      //插入新新节点
      aInput[i].parentNode.insertBefore(oSpan, aInput[i]);
      aInput[i].style.display = "none";
      aSpan.push(oSpan);
    }
    //插入css样式表
    if (!bAdd) {//判断是否已经调用css
      var oLink = document.createElement("link");
      // oLink.href = "./myComponent.css";
      oLink.href = str+"/myComponent.css";
      oLink.rel = "stylesheet";
      oLink.type = "text/css";
      var oHead = document.getElementsByTagName("head")[0];
      oHead.insertBefore(oLink, oHead.children[0]);
      bAdd = true;
    }
  };
})();
