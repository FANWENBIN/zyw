function setCookie(e,o){var t=0,i=new Date;i.setTime(i.getTime()+24*t*60*60*1e3),document.cookie=e+"="+escape(o)+";expires="+i.toGMTString()}function getCookie(e){var o,t=new RegExp("(^| )"+e+"=([^;]*)(;|$)");return(o=document.cookie.match(t))?unescape(o[2]):null}function delCookie(e){var o=new Date;o.setTime(o.getTime()-1);var t=getCookie(e);null!=t&&(document.cookie=e+"="+t+";expires="+o.toGMTString())}var username=document.cookie.split(";")[0].split("=")[1];