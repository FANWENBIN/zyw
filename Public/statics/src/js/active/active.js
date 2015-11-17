$(function(){
    var scope = {
        now: 0,
        timer: null,
        type: 0,
        time: 0,
        pageNum: 1,
        imgUrl: "",
        activeType: ""
    };
    var page = {
        init: function(){
            $("#dotList").on("click","li",page.tabBanner);
            $("#groupByType").on("click","li",page.tabGroupByType);
            $("#groupByTime").on("click","li",page.tabGroupByTime);
            $("#imgList").on("mouseover",page.mouseover);
            $("#imgList").on("mouseout",page.mouseout);
            $("#newActive").on("click",page.createActive);
            $(".close").on("click",page.closeAlert)
            $(".activeType").on("change",page.SelectChange);
            $("form").on("submit",page.submit)
            //banner切换
            tabinit();

            //分页
            page.getPage(function(){
              pageInit(parseInt(scope.pageNum),10,function(index){
                //回调，刷新内容页
                if(index == 0)return false;
                page.getActiveData(index);
              });
            });
            // 上传图片
            $('#file_upload').uploadify({
             'swf'      : './Public/statics/js/uploadify/uploadify.swf',
             'uploader' : './Public/statics/js/uploadify/uploadify.php',
             'buttonText' : '活动图片',
             'onUploadSuccess' : function(file, data, response) {
                  console.log(file,data,response);
                  var str = data.match(/\.\\\/Uploads.+"/)[0];
                  scope.imgUrl = str.substring(0,str.length-1);
              }
             // Put your options here
            });
        },
        submit: function(){
          if(!/^.+$/.test($(":text[name=activeName]").val())){
            $(".errorsubmit").html("活动名称不能为空")
          }else if(!/^.+$/.test($(":text[name=fromTime]").val())){
            $(".errorsubmit").html("活动起始时间不能为空")
          }else if(!/^.+$/.test($(":text[name=toTime]").val())){
            $(".errorsubmit").html("活动结束时间不能为空")
          }else if(!/^.+$/.test($(":text[name=activebrief]").val())){
            $(".errorsubmit").html("活动简介不能为空")
          }else if(!/^.+$/.test($("textarea[name=activeinfo]").val())){
            $(".errorsubmit").html("活动信息不能为空")
          }else if(!/^.+$/.test($(":text[name=hostname]").val())){
            $(".errorsubmit").html("主办人姓名不能为空")
          }else if(!/^.+$/.test($(":text[name=hosttel]").val())){
            $(".errorsubmit").html("主办人电话不能为空")
          }else if(!/^.+$/.test($(":text[name=hostaddress]").val())){
            $(".errorsubmit").html("主办人地址不能为空")
          }else if(!/^.+$/.test($(":text[name=hostemail]").val())){
            $(".errorsubmit").html("主办人邮箱不能为空")
          }else{
            var _val = $(this).find("option:selected").val();
            if(_val == "0"){
              if(!/^.+$/.test($(".activeAdress").val())){
                $(".errorsubmit").html("活动地址不能为空")
                return false;
              }
            }

            $.ajax({
              type: "post",
              url: "./index.php?m=Home&c=Active&a=useraddactive",
              data: {
                title: $(":text[name=activeName]").val(),
                img: scope.imgUrl,
                content: $(":text[name=activebrief]").val(),
                info: $("textarea[name=activeinfo]").val(),
                phone: $(":text[name=hosttel]").val(),
                begin_time: $(":text[name=fromTime]").val(),
                last_time: $(":text[name=toTime]").val(),
                line_type: _val,
                line_address: $(".activeAdress").val(),
                sponsor_name: $(":text[name=hostname]").val(),
                sponsor_phone: $(":text[name=hosttel]").val(),
                sponsor_address: $(":text[name=hostaddress]").val(),
                sponsor_email: $(":text[name=hostemail]").val()
              },
              dataType: "json",
              success: function(json){
                console.log(json.msg)
                if(json.status === 0){
                  $("#mask").hide();
                  alert("活动发起成功静待审核");
                }else if(json.status === 101){
                  $(".errorsubmit").html("系统错误，请稍后再试")
                }else if(json.status === 102){
                  $(".errorsubmit").html("请检查信息是否填完整")
                }else if(json.status === 103){
                  $(".errorsubmit").html("活动日期有误")
                }
              },
              error: function(){
              }
            })
          }
          return false;
        },
        SelectChange: function(){
          var _val = $(this).find("option:selected").val();
          if(_val == "0"){
            $(".activeAdress").prop("placeholder","请输入活动地址").removeAttr("disabled","disabled");
          }else{
            $(".activeAdress").prop("placeholder","无需活动地址").val("").attr("disabled","disabled");
          }
        },
        closeAlert: function(){
          $(this).parents(".mask").hide();
        },
        tabBanner: function(){
            clearTimeout(scope.timer);
            scope.now = $(this).index();
            var _aDotLi = $("#dotList");
            var _aImgLi = $("#imgList");
            _aDotLi.find("li").removeClass("active");
            $(this).addClass("active");
            _aImgLi.find("a").removeClass("active");
            _aImgLi.find("a").eq($(this).index()).addClass("active");
            page.autoTab();
        },
        autoTab: function(){
            scope.timer = setInterval(function(){
                var _aDotLi = $("#dotList");
                var _aImgLi = $("#imgList");
                scope.now++;
                if(scope.now >= _aDotLi.find("li").length)scope.now = 0;
                _aDotLi.find("li").removeClass("active");
                _aDotLi.find("li").eq(scope.now).addClass("active");
                _aImgLi.find("a").removeClass("active");
                _aImgLi.find("a").eq(scope.now).addClass("active");
            },3000)
        },
        tabGroupByType: function(){
            $("#groupByType").find("li").removeClass("active");
            $(this).addClass("active");
            scope.type = $(this).data("type");
            page.getPage(function(){
              pageInit(scope.pageNum,10,function(index){
                page.getActiveData(index);
              });
            });
        },
        tabGroupByTime: function(){
            $("#groupByTime").find("li").removeClass("active");
            $(this).addClass("active");
            scope.time = $(this).data("time");
            page.getPage(function(){
              pageInit(scope.pageNum,10,function(index){
                page.getActiveData(index);
              });
            });
        },
        mouseover: function(){
            clearTimeout(scope.timer);
        },
        mouseout: function(){
            page.autoTab();
        },
        createActive: function(){
            $("#mask").show();
        },


        getPage: function(fn){
          $.ajax({
            url: "./index.php?m=Home&c=Active&a=activetype",
            type: "get",
            dataType: "json",
            data: {
              type: scope.type,
              time: scope.time,
              p: 1
            },
            success: function(json){
              scope.pageNum = json.data.page;
              fn();
            },
            error: function(){

            }

          })
        },

        getActiveData: function(page){

          $.ajax({
            url: "./index.php?m=Home&c=Active&a=activetype",
            type: "get",
            dataType: "json",
            data: {
              type: scope.type,
              time: scope.time,
              p: page
            },
            success: function(json){

              var _arr = json.data.data;
              var _html = "";
              if(json.data.page == 0) {
                $("#itemlist").html(_html);
                return false;
              }
              for(var i = 0, len = _arr.length; i < len; i++ ){
                _html += '<li>'
                +'<a href="./index.php?m=Home&c=Active&a=active_details&id='+ _arr[i].id +'">'
                +'<img src="./Uploads'+ _arr[i].img +'">'
                +'<div class="tags">'
                +'<h3>' + _arr[i].title + '</h3>'
                +'<p>'+ _arr[i].begin_time +'-'+ _arr[i].last_time +'</p>'
                +'</div>'
                +'<sub></sub>'
                +'</a>'
                +'<div class="desc">'
                +'<span class="txt">'+ _arr[i].content +'</span>'
                +'<div><span class="num">'+ _arr[i].concern +'</span><i class="focus"></i></div>'
                +'</div>'
                +'</li>'
              }
              $("#itemlist").html(_html);
            },
            error: function(){
            }

          })
        }
    };
    page.init()
});
