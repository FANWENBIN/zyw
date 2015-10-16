$(function(){var n={now:0},i={init:function(){$("#preview").click(i.preClick),$("#next").click(i.nextClick)},preClick:function(){n.now--,n.now<0&&(n.now=0),i.repoUl()},nextClick:function(){n.now++,n.now>3&&(n.now=3),i.repoUl()},repoUl:function(){$("#list").css("left",-(410*n.now))}};i.init()});
//# sourceMappingURL=news_fans.js.map
