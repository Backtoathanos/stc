
$(document).ready(function(){


	/* ---- Countdown timer ---- */

	// $('#counter').countdown({
	// 	timestamp : (new Date()).getTime() + 11*24*60*60*1000
	// });


	/* ---- Animations ---- */

	$('#links a').hover(
		function(){ $(this).animate({ left: 3 }, 'fast'); },
		function(){ $(this).animate({ left: 0 }, 'fast'); }
	);

	$('footer a').hover(
		function(){ $(this).animate({ top: 3 }, 'fast'); },
		function(){ $(this).animate({ top: 0 }, 'fast'); }
	);

 	
 	$('body').delegate('.w3ls-cart', 'click', function(e){
 		e.preventDefault();
 		$('.modal').modal("hide");
 		var pdid=$(this).attr("id");
 		// alert(pdid);
 		$('#routmailmodal').modal("show");
 		$('.modpdidmailroute').val(pdid);
 	});

 	$('body').delegate('.routeformailsubmit', 'click', function(e){
 		e.preventDefault();
 		var pdid=$('.modpdidmailroute').val();
 		var username=$('.routeformailname').val();
 		var useremail=$('.routeformailemail').val();
 		var userphone=$('.routeformailphone').val();
 		var userqty=$('.routeformailqty').val();
 		var usernotes=$('.routeformailnotes').val();
 		var thuderstrike='value';
 		$.ajax({
 			url : "necrosword/loki_main.php",
 			type : "GET",
 			data : {
 				thuderstrike:1,
 				pdid:pdid,
 				username:username,
 				useremail:useremail,
 				userphone:userphone,
 				userqty:userqty,
 				usernotes:usernotes
 			},
 			success : function(responsemod){
 				// console.log(responsemod);
 				if(responsemod=="empty"){
 					alert("Please fill all fields!!!");
 				}else if(responsemod=="fail"){
 					alert("Please try again later!!!");
 				}else{
 					$('#routmailmodal').modal("hide");
	 				alert(responsemod);
 				}	 				
 			}
 		});
 	});

	 


});
