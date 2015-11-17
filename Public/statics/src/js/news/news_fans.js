$(function(){

var scope = {
    now : 0,
    max: 0
}
var page = {
    init: function(){
        $("#preview").click(page.preClick);
        $("#next").click(page.nextClick);
        scope.max = $("#list").find(".item").length;
        if(scope.max < 4){
          $("#preview").hide();
          $("#next").hide();
        }
    },
    preClick: function(){
        scope.now--;
        if(scope.now < 0)scope.now = 0;
        page.repoUl();
    },
    nextClick: function(){
        scope.now++;
        if(scope.now > scope.max -3)scope.now = scope.max - 3;
         page.repoUl();
    },
    repoUl: function(){
        $("#list").css("left",-(scope.now*410))
    }
};
page.init();


});
