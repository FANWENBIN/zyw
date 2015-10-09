$(function(){

var scope = {
    now : 0
}
var page = {
    init: function(){
        $("#preview").click(page.preClick);
        $("#next").click(page.nextClick);
    },
    preClick: function(){
        scope.now--;
        if(scope.now < 0)scope.now = 0;
        page.repoUl();
    },
    nextClick: function(){
        scope.now++;
        if(scope.now > 3)scope.now = 3;
         page.repoUl();
    },
    repoUl: function(){
        $("#list").css("left",-(scope.now*410))
    }
};
page.init();


});
