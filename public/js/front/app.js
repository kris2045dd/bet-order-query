!function(){var t=!1;$.ajaxSetup({headers:{"X-CSRF-TOKEN":$('meta[name="csrf-token"]').attr("content")},beforeSend:function(){t=!0},error:function(t){"419"==t.status?confirm("Session 已失效，请重新整理页面.")&&location.reload():alert("网络连线错误.")},complete:function(e,n){t=!1}});var e=angular.module("jsApp",["ui.bootstrap"]);function n(t,e){var n=[],r={activity1:new function(){this.isMatch=function(t,e){return"BB电子"==t.platform&&(!(t.bet_amount<1)&&(reg=new RegExp(e.split("|")[0]+"$"),!!reg.test(t.bet_order_id)))}},activity2:new function(){this.isMatch=function(t,e){return"BB电子"!=t.platform&&-1!=t.platform.indexOf("电子")&&(!(t.bet_amount<1)&&(!(t.payout_amount<=0)&&t.payout_amount/t.bet_amount>=e.split("|")[0]))}},activity3:new function(){this.isMatch=function(t,e){return("开元棋牌"==t.platform||"BB棋牌"==t.platform)&&(!(t.bet_amount<1)&&(!(t.payout_amount<=0)&&t.payout_amount/t.bet_amount>=e.split("|")[0]))}},activity4:new function(){this.isMatch=function(t,e){return("BB捕鱼大师"==t.platform||"BB捕鱼达人"==t.platform)&&(!(t.bet_amount<1)&&(!(t.payout_amount<=0)&&t.payout_amount>=e.split("|")[0]))}}};t.get(e+"/getActivities").then(function(t){n=t.data.data}),this.getMatched=function(t){for(var e=0,o=n.length;e<o;e++){var a=r["activity"+n[e].activity_id];if(a){var i=0;for(j_len=n[e].rules.length;i<j_len;i++)if(a.isMatch(t,n[e].rules[i]))return n[e].activity_id}}return 0}}e.config(["$interpolateProvider","$compileProvider",function(t,e){t.startSymbol("{*"),t.endSymbol("*}"),e.commentDirectivesEnabled(!1),e.cssClassDirectivesEnabled(!1)}]),e.filter("offset",function(){return function(t,e){return e=parseInt(e,10),t.slice(e)}}),e.filter("platform",function(){return function(t,e){if(""==e)return t;for(var n=[],r=0,o=t.length;r<o;r++){var a=t[r];a.platform==e&&n.push(a)}return n}}),e.filter("tailNo",function(){return function(t,e){if(""==e)return t;for(var n=[],r=0,o=t.length,a=new RegExp(e+"$");r<o;r++){var i=t[r];a.test(i.bet_order_id)&&n.push(i)}return n}}),e.filter("amountGreaterThan",function(){return function(t,e){if(isNaN(e)||""==e)return t;var n=[],r=0,o=t.length;for(e=parseFloat(e);r<o;r++){var a=t[r];parseFloat(a.bet_amount)>=e&&n.push(a)}return n}}),e.filter("matchedOnly",function(){return function(t,e){if(""===e)return t;for(var n=[],r=0,o=t.length;r<o;r++){var a=t[r];a.matched&&("all"==e?n.push(a):a.matched==e&&n.push(a))}return n}}),e.filter("appliedStatus",function(){return function(t){switch(t){case 0:return"进度查询";case 1:return"已派彩";case 2:return"拒绝";default:return"一键办理"}}}),e.service("activityService",n),n.$inject=["$http","BASE_URI"],e.controller("BodyCtrl",["$scope","$http","platformFilter","tailNoFilter","amountGreaterThanFilter","matchedOnlyFilter","activityService","username","BASE_URI",function(e,n,r,o,a,i,u,c,s){var f=this;function l(){var t=i(f.bet_orders,f.qs.matched);t=o(t,f.qs.tail_no),t=r(t,f.qs.platform),t=a(t,f.qs.amount),f.filtered_bet_orders=t}function d(t){$(".waiting p").html(t),$(".waiting").fadeIn()}function m(){$(".waiting").fadeOut()}f.username=c,f.bet_orders=[],f.filtered_bet_orders=[],f.msg=c?2:1,f.paginator={current:1,per_page:10},f.qs={platform:"",amount:"",tail_no:"",matched:""},e.$watch(function(){return f.qs},function(t,e){l()},!0),f.loginPopup=function(){$(".login_pop").fadeIn().find("input[name='username']").focus()},f.closeLoginPopup=function(){$(".login_pop").fadeOut()},f.login=function(){if(!t){var n=login_form;if(""!=n.username.value){if(""!=n.balance.value)return isNaN(n.balance.value)?(alert("帳戶餘額格式不正確 !"),n.balance.focus(),void n.balance.select()):void $.ajax({url:s+"/login",type:"post",dataType:"json",data:$(n).serialize(),beforeSend:function(){t=!0,d("登录中...")},success:function(t){-1==t.error||100==t.error?(alert("登录成功."),f.closeLoginPopup(),f.username=t.msg,f.msg=2,n.username.value="",n.balance.value="",e.$broadcast("afterLogin",f.username),e.$digest()):t.msg?alert(t.msg):alert("发生未知的错误.")},complete:function(){m(),t=!1}});n.balance.focus()}else n.username.focus()}},f.logout=function(){confirm("是否登出 ?")&&n.get(s+"/logout").then(function(t){var n=f.username;f.username="",f.bet_orders=f.filtered_bet_orders=[],f.msg=1,e.$broadcast("afterLogout",n),alert("登出成功.")},function(t){alert("登出失败. ("+t.status+": "+t.statusText+")")})},f.getBetOrders=function(){t||(f.username?$.ajax({url:s+"/getBetOrders",type:"post",dataType:"json",beforeSend:function(){t=!0,d("注单数据获取中，请耐心等待...")},success:function(t){-1==t.error?(f.bet_orders=t.data,f.msg=t.data.length?0:3,function(){for(var t=0,e=f.bet_orders.length;t<e;t++)f.bet_orders[t].matched=u.getMatched(f.bet_orders[t])}(),l(),e.$broadcast("afterGettingData"),e.$digest()):t.msg?alert(t.msg):alert("发生未知的错误.")},complete:function(){m(),t=!1}}):f.loginPopup())},f.applicable=function(t){return!!t.matched},f.activityApplying=function(n){t||$.ajax({url:s+"/activityApplying",type:"post",data:{bet_order_id:n.bet_order_id},dataType:"json",beforeSend:function(){t=!0,d("申请办理中，请耐心等待...")},success:function(t){-1==t.error?alert("申请成功."):t.msg?alert(t.msg):alert("发生未知的错误."),""!==t.data&&(!function(t,e){$.extend(t,e);for(var n=0,r=f.bet_orders.length;n<r;n++){var o=f.bet_orders[n];if(o.bet_order_id==t.bet_order_id){$.extend(o,e);break}}}(n,t.data),e.$digest())},complete:function(){m(),t=!1}})},f.showMemo=function(t){alert(t)}}]),e.controller("SearchFormCtrl",["$scope","username",function(t,e){var n,r=this,o=e;function a(e){r.countdown_sec=e||600,n&&clearInterval(n),n=setInterval(function(){--r.countdown_sec<=0&&i(),t.$digest()},1e3)}function i(){r.countdown_sec=0,n&&clearInterval(n)}function u(){return"countdown_time:"+o}function c(){if("undefined"!=typeof Storage){var t=localStorage.getItem(u());if(t){var e=new Date;if((t=parseInt(t,10))>e.getTime())a(parseInt((t-e.getTime())/1e3,10))}}}r.countdown_sec=0,r.toggleSearchBet=function(){$(".search_bet").toggleClass("showSB")},t.$on("afterLogin",function(t,e){o=e,c()}),t.$on("afterLogout",function(t,e){i()}),t.$on("afterGettingData",function(t){a(),function(){if("undefined"!=typeof Storage&&r.countdown_sec){var t=new Date;t.setTime(t.getTime()+1e3*r.countdown_sec),localStorage.setItem(u(),t.getTime())}}()}),t.$on("$destroy",function(t){i()}),c()}])}();