$(function(){
  var scope = {
    group: 0
  };
  var page = {
    init: function(){
      $("#sorting").on("click","li",page.tabGroup)
    },
    tabGroup: function(){
      $("#sorting").find("li").removeClass("active");
      $(this).addClass("active");
      //回调 刷新列表
      scope.group = $(this).data("group");
      page.loadData();
    },
    loadData: function(){
      $.ajax({
        url: "./index.php?m=Home&c=Performing&a=allperforming",
        type: "get",
        dataType: "json",
        data: {
          type: scope.group
        },
        success: function(json){
          /*

          <li class="alphabet-list">
            <div class="title">
              <h3 id="a">A</h3>
            </div>
            <div class="inner clearFix">
              <a href = "#">
                <div class="item">
                  <img src="__PUBLIC__/statics/images/p_starwars_1.jpg" alt="" />
                  <p>angularbaby</p>
                  <sub></sub>
                </div>
              </a>

            </div>
          </li>
          */

          if(json.status == 0){
            var _outerhtml = '';
            var item = json.data;
            for(key in item){
              var _innerhtml = '<li class="alphabet-list"><div class="title"><h3 id="a">'+ key +'</h3></div>'
                +'<div class="inner clearFix">'
              ;
              //item[key]
              if(item[key]){
                for(var i = 0, len = item[key].length; i < len ; i++){
                  //item[key][i].
                  _innerhtml += '<a href = "#'+ item[key][i].id +'">'
                      +'<div class="item">'
                      +'<img src="'+ item[key][i].headimg +'" alt="" />'
                      +'<p>'+ item[key][i].name +'</p>'
                      +'<sub></sub>'
                    +'</div>'
                  +'</a>'
                  +'</div>'
                  +'</li>'

                }
              }else{
                _innerhtml += '</div></li>'
              }
            _outerhtml += _innerhtml;
            }
            $("#actorgroup").html(_outerhtml);
          }else{
          }
        },
        error: function(){

        }

      })
    }
  };
  page.init();
})
