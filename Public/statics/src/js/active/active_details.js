$(function(){
    var scope = {
        now: 0,
        timer: null
    };
    var page = {
        init: function(){
            $("#newActive").on("click",page.createActive)
        },
        createActive: function(){
            $("#mask").show();
        }
    };
    page.init()
});
