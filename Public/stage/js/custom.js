$(function(){
	$("#showAlert1").click(function(){
		$(".popforstarbox").show();
	})
	$("#showAlert2").click(function(){
		$(".popforjudgebox").show();
	})
	$(".popforstar .btnD").click(function(){
		$(".popforstarbox").hide();
	})
	$(".popforstar .btnA").click(function(){
		$(".popforstarbox").hide();
	})

	$(".popforjudge .btnD").click(function(){
		$(".popforjudgebox").hide();
	})
	$(".popforjudge .btnA").click(function(){
		$(".popforjudgebox").hide() ;
	})
  $(".slt1").click(function(){
    $(".slt").removeClass("active");
    $(this).addClass("active");

    $(".actors").hide();
    $(".actorsall").show();
    $(".judge").hide() ;
  })
  $(".slt2").click(function(){
    $(".slt").removeClass("active");
    $(this).addClass("active");
    $(".actors").hide();
    $(".judge").show() ;
  });
  $(".slt3").click(function(){
    $(".slt").removeClass("active");
    $(this).addClass("active");
    $(".actors").hide();
    $(".judge").hide() ;
    $(".actors36").show();
  })
  $(".slt4").click(function(){
    $(".slt").removeClass("active");
    $(this).addClass("active");
    $(".actors").hide();
    $(".judge").hide() ;
    $(".actors6").show();
  })
})