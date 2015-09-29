define(function(require, exports, module){
    require("common");

	$(function(){
		 var uploadImgFile = $("#imgfile");
        //var trigger = $("#photoAndEmotion .gallery .upload");
       
        
        //初始化个计数器，用于记录上传了几个图片
		//var hasUploadImg=0;
       
        // 申明个数组用于存放图片
        var imgData;
        
		function getPhoto(uploadImgFile){
            
			
			uploadImgFile.on("change", function(evt){
				 // 如果浏览器不支持FileReader，则不处理
				 if (!window.FileReader) return;
				 var files = evt.target.files;
				 for (var i = 0, f; f = files[i]; i++) {
	            	 if (!f.type.match('image.*')) {
	            		 continue; 
	           		 }
	           		 var reader = new FileReader();
	           		 reader.onload = (function(theFile) {
	           		 	 return function(e) {
	           		 	 	
	                		imgData = e.target.result;
	                	 };
	                 })(f);
	                 reader.readAsDataURL(f);
	             }
			});
		}
		
		var page = {
			init: function(){
				$("#submitbtn").on("click",page.submitclick);
				getPhoto(uploadImgFile);
			},
			submitclick:function(){
				console.log(imgData);
				$.ajax({
					url: "../_Aupload.php",
					data: {imgData:imgData},
					type: "POST",
					dataType: "json",
					success: function(e){
						if(e.status==0){
						   location.reload(); 
						}else{
						   alert(e.msg);
						}                            
						
					},
					error: function(e){
						alert(e.status);
						alert("网络超时,请稍后重试");
					}
				});
				
			}
		};
		page.init();


	});

});