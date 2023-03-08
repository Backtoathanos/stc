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
	$('.close-co').on('click', function(){
		$('.app-page-title').hide(200);
	});

	// $('.agent-pro-search').on('keyup', function(e){
	// 	e.preventDefault();
	// 	// $('.called-product').toggle(500);
	// 	var search_prod_name=$(this).val();
	// 	$.ajax({
	// 		url			: "sparda/stc_product.php",
	// 		method		: "POST",
	// 		data		: {search_prod_name:search_prod_name},
	// 		// dataType	: "JSON",
	// 		success		: function(products){
	// 			// console.log(products);
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
			url			: "sparda/stc_agcart.php",
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
			url 	: "sparda/stc_agcart.php",
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
			url 	: "sparda/stc_agcart.php",
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
			url 	: "sparda/stc_agcart.php",
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

	// load_cusomer_dor_agent();
	// function load_cusomer_dor_agent(){
	// 	$.ajax({
	// 		url : "sparda/stc_agcart.php",
	// 		method : "POST",
	// 		data : {load_cust:1},
	// 		success : function(customer){
	// 			// console.log(customer);
	// 			$('.load_cust_agents').html(customer);
	// 		}
	// 	});
	// }

	// place order
	$('body').delegate('#agent-place-it', 'click', function(e){
		e.preventDefault();
		$.ajax({
			url : "sparda/stc_agcart.php",
			method : "POST",
			data : {
				order_add:1
			},
			success : function(customer){
				// console.log(customer);
				alert(customer);
				window.location.href="dashboard.php";
				// $('.load_cust_agents').html(customer);
			},
			error : function(e){
				alert("Something went wrong!!! please try again later.");
			}
		});
	});

	load_ag_orders();
	function load_ag_orders(){
		$.ajax({
			url 	: "sparda/stc_product.php",
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