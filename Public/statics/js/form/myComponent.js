/**
 * Created by admin on 2015/9/18.
 */
;
(function () {
  //载入css判断
  var bAdd = false;

  //获取当前js地址，并在项目中引用。
  var scripts=document.getElementsByTagName('script');
  var lastUrl=scripts[scripts.length-1].src;
  var str = lastUrl.substring(0,lastUrl.lastIndexOf("/"));
  //获取元素byclass
  function getByClass(oParent,sClass){
    if(oParent.getElementsByClassName){
      return oParent.getElementsByClassName(sClass);
    }
    var result = [];
    var aEle = oParent.getElementsByTagName("*");
    var re = new RegExp("\\b" + sClass + "\\b");
    for(var i = 0; i < aEle.length; i++){
      if(re.test(aEle[i].className)){
        result.push(aEle[i]);
      }
    }
    return result;
  }

  //select组件
  window.initSelect=function(name){
    //1.写布局
    var oSelect=document.getElementsByName(name)[0];
    var oDiv=document.createElement('div');
    oDiv.className='mySelect';
    var oSpan=document.createElement('span');
    oSpan.innerHTML=oSelect.value;
    //2.加事件
    oSpan.onclick=function(ev){
      var oEvt=ev||event;
      oUl.style.display='block';
      oEvt.cancelBubble=true;
    };
    var oUl=document.createElement('ul');
    oUl.className = "selectul"
    for(var i=0;i<oSelect.options.length;i++){
      var oLi=document.createElement('li');
      //2.加事件
      (function(index){
        oLi.onclick=function(){
          //oUl.style.display='none';
          oSpan.innerHTML=this.innerHTML;
          oSelect.selectedIndex=index;	//修改select的选中的下标值
        };
      })(i);
      oLi.innerHTML=oSelect.options[i].text;
      oUl.appendChild(oLi);
    }
    oDiv.appendChild(oSpan);
    oDiv.appendChild(oUl);

    oSelect.parentNode.insertBefore(oDiv,oSelect);
    oSelect.style.display='none';
    aUl = getByClass(document,"selectul");
    console.log(aUl);
    document.onclick=function(){
      for(var i = 0, len = aUl.length ; i < len ; i++){
      aUl[i].style.display='none';
      }
    };
    //插入css样式表
    if (!bAdd) {//判断是否已经调用css
      var oLink = document.createElement("link");
      oLink.href = str +"/myComponent.css";
      oLink.rel = "stylesheet";
      oLink.type = "text/css";
      var oHead = document.getElementsByTagName("head")[0];
      oHead.insertBefore(oLink, oHead.children[0]);
      bAdd = true;
    }
  }

  //radio组件
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
      oLink.href = str +"/myComponent.css";

      oLink.rel = "stylesheet";
      oLink.type = "text/css";
      var oHead = document.getElementsByTagName("head")[0];
      oHead.insertBefore(oLink, oHead.children[0]);
      bAdd = true;
    }
  };

  //checkbox 组件
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
      oLink.href = str +"/myComponent.css";
      oLink.rel = "stylesheet";
      oLink.type = "text/css";
      var oHead = document.getElementsByTagName("head")[0];
      oHead.insertBefore(oLink, oHead.children[0]);
      bAdd = true;
    }
  };

})();
