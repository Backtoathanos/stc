$(document).ready(function(e){
	// $('.buy').click(function(e){
	// 	e.preventDefault();
	//   $('.bottom').addClass("clicked");
	//   // alert("yes");
	// });

	// $('.remove').click(function(e){
	// 	e.preventDefault();
	//   $('.bottom').removeClass("clicked");
	// });
	// $('.close-co').on('click', function(){
	// 	$('.app-page-title').hide(200);
	// })

	// $('.agent-pro-search').on('keyup', function(e){
	// 	e.preventDefault();
	// 	var search_prod_name=$(this).val();
	// 	$.ajax({
	// 		url			: "nemesis/stc_product.php",
	// 		method		: "POST",
	// 		data		: {search_prod_name:search_prod_name},
	// 		success		: function(products){
	// 			$('.called-product').html(products);
	// 		}
	// 	});
	// });

	$('body').delegate('.addtocartagent', 'click', function(e){
		e.preventDefault();
		var prod_id=$(this).attr("id");
		var prod_price=$('#stcpdprice'+prod_id).val();
		var prod_qty=$('#stcpoqty'+prod_id).val();
		$.ajax({
			url			: "nemesis/stc_agcart.php",
			method		: "POST",
			data		: {
				prod_id:prod_id,
				prod_qty:prod_qty,
				prod_price:prod_price,
				prodsuccess:1
			},
			// dataType	: "JSON",
			success		: function(products){
				// console.log(products);
				alert(products);
				// $('.called-product').html(products);
			}
		});
	});

	load_ag_cart();
	function load_ag_cart(){
		$.ajax({
			url 	: "nemesis/stc_agcart.php",
			method 	: "POST",
			data 	: {load_cart_ag:1},
			// dataType: "JSON",
			success : function(cart){
				// console.log(cart);
				$('#load-ag-cart').html(cart);
			}
		});
	}

	$('body').delegate('.removitems', 'click', function(e){
		e.preventDefault();
		var pd_id=$(this).attr("id");
		$.ajax({
			url 	: "nemesis/stc_agcart.php",
			method 	: "POST",
			data 	: {
				items_id:pd_id,
				delitems:1
			},
			// dataType: "JSON",
			success : function(cart){
				// console.log(cart);
				load_ag_cart();
				// $('#load-ag-cart').html(cart);
			}
		});
	});

	$('body').delegate('.updateitem', 'click', function(e){
		e.preventDefault();
		var pd_id=$(this).attr("id");
		var pd_qty=$('#itemqty'+pd_id).val();
		$.ajax({
			url 	: "nemesis/stc_agcart.php",
			method 	: "POST",
			data 	: {
				items_id:pd_id,
				pd_qty:pd_qty,
				updateitems:1
			},
			// dataType: "JSON",
			success : function(cart){
				// console.log(cart);
				alert(cart);
				load_ag_cart();
				// $('#load-ag-cart').html(cart);
			}
		});
	});

	load_cusomer_dor_agent();
	function load_cusomer_dor_agent(){
		$.ajax({
			url : "nemesis/stc_agcart.php",
			method : "POST",
			data : {load_cust:1},
			success : function(customer){
				// console.log(customer);
				$('.load_cust_agents').html(customer);
			}
		});
	}

	// place order
	$('body').delegate('#agent-place-it', 'click', function(e){
		e.preventDefault();
		var cust_id=$('#order-go-cust').val();
		var sitename=$('#order-go-sitename').val();
		var siteaddress=$('#order-go-site-address').val();
		$.ajax({
			url : "nemesis/stc_agcart.php",
			method : "POST",
			data : {
				order_add:1,
				cust_id:cust_id,
				sitename:sitename,
				siteaddress:siteaddress
			},
			success : function(customer){
				// console.log(customer);
				alert(customer);
				window.location.href="dashboard.php";
				// $('.load_cust_agents').html(customer);
			},
			error : function(e){
				// alert(e);
			}
		});
	});

	load_ag_orders();
	function load_ag_orders(){
		$.ajax({
			url 	: "nemesis/stc_product.php",
			method 	: "POST",
			data 	: {load_order_ag:1},
			// dataType: "JSON",
			success : function(cart){
				// console.log(cart);
				$('.get-my-orders').html(cart);
			}
		});
	}
});

$(document).ready(function(){
    var url = document.location.href;
    var qs = url.substring(url.indexOf('?') + 1).split('&');
    for(var i = 0, result = {}; i < qs.length; i++){
        qs[i] = qs[i].split('=');
        result[qs[i][0]] = decodeURIComponent(qs[i][1]);
    }
    $("."+result.page).addClass("mm-active");
    $("."+result.subpage).addClass("mm-active");
    if(result.page==undefined){
        $(".home").addClass("mm-active");
    }
});