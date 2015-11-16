$(function(){
	


	// 代码换行
	var shLineWrap = function () {
        $('.syntaxhighlighter').each(function(){
            // Fetch
            var $sh = $(this),
                $gutter = $sh.find('td.gutter'),
                $code = $sh.find('td.code');
            // Cycle through lines
            $gutter.children('.line').each(function (i) {
                // Fetch
                var $gutterLine = $(this),
                    $codeLine = $code.find('.line:nth-child(' + (i + 1) + ')');
                //alert($gutterLine);
                // Fetch height
                var height = $codeLine.height() || 0;
                if (!height) {
                    height = 'auto';
                }
                else {
                    height = height += 'px';
                    //alert(height);
                }
                // Copy height over
                $gutterLine.attr('style', 'height: ' + height + ' !important'); // fix by Edi, for JQuery 1.7+ under Firefox 15.0
                // console.debug($gutterLine.height(), height, $gutterLine.text(), $codeLine);
            });
        });
    };


	var page = {
		init: function(){
			SyntaxHighlighter.defaults['smart-tabs'] = true;
    		SyntaxHighlighter.defaults['tab-size'] = 4;
			$("#aside a").on("click", page.asideItemClick);
			$("#aside a").eq(0).click();
		},
		asideItemClick: function(){
			var title = $(this).html();
			var id = $(this).data("id");
			$("#main_title").html(title);
			$("#main_inner").html($("#" + id).html());
			SyntaxHighlighter.highlight();
			shLineWrap();
			$("#aside").css("height", "");
			var h = Math.max($("#aside").height(), $("#main").height());
			$("#aside").height(h + 10);
		}
	};
	page.init();

});