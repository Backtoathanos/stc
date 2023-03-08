$(document).ready(function(){
	stc_call_ag_order();
	function stc_call_ag_order(){
		$.ajax({
			url			: "asgard/mjolnirgetorder.php",
			method		: "post",
			data		: {callorder:1},
			dataType	: 'JSON',
			success		: function(data){
				// console.log(data);
				$('.stc-view-ag-order-form').html(data);
			}
		});
	}

	// show orders
	$('body').delegate('.stc_view_order', 'click', function(e){
    	e.preventDefault();
    	$('.stc-view').fadeOut(500);
    	$('.stc-add').toggle(1000);
    	var stc_order_id = $(this).attr("id");
    	$.ajax({
	        url			:"asgard/mjolnirgetorder.php",
	        method		:'POST',
	        data		:{
	        	call_order_sub:1,
	        	stc_order_id:stc_order_id
	        },
	        dataType	: 'JSON',
	        success:function(data){
	        	// console.log(data);
	        	$('#order-challan').val('STC/A/'+data['order_number']);
	        	$('#ag-challan-order').val(data['real_order_number']);
	        	$('#ag-order-date').val(data['order_date']);
	        	$('#cust_name').val(data['customer_name']);
	        	$('#ag-info').val('STC/AT/'+data['agentname']);
	        	$('#site-name').val(data['sitename']);
	        	$('#ag-refrence').val(data['sitename']);
	        	$('#site-address').val(data['siteaddress']);
	        	$('.ag-order-items').html(data['line_items']);
	        	$('#stc-proj-id').val(data['project_id']);
	        	$('#stc-super-id').val(data['supervisor_id']);
	        	$('#stc-requis-id').val(data['requis_id']);
	        }
    	});
  	});

	// go to cart sess
  	$('body').delegate('.stcagordgochcart', 'click', function(e){
  		e.preventDefault();
  		var stc_pd_id = $(this).attr("id");
  		var stc_rem_ord_qty = $('.disp-qty'+stc_pd_id).val();
  		var stc_inv_ord_qty = $('.inv-qty'+stc_pd_id).val();
  		var stc_pd_price = $('.pd-price'+stc_pd_id).val();
    	$.ajax({
	        url			:"asgard/mjolnirgetorder.php",
	        method		:'POST',
	        data		:{
	        	gooritemoncart:1,
	        	stc_pd_id:stc_pd_id,
	        	stc_inv_ord_qty:stc_inv_ord_qty,
	        	stc_pd_price:stc_pd_price,
	        	stc_rem_ord_qty:stc_rem_ord_qty
	        },
	        // dataType	: 'JSON',
	        success:function(data){
	        	// console.log(data);
	        	alert(data);
	        }
    	});
  	});

  	// save challan
  	$('body').delegate('.save-ag-challan', 'click', function(e){
  		e.preventDefault();
  		var stc_ch_id=$('#ag-challan-order').val();
  		var stc_ch_notes=$('#ag-notes').val();
  		var stc_ch_tandc=$('#ag-tandc').val();
  		var stc_ch_waybilno=$('#ag-way-bill-no').val();
  		var stc_ch_lrno=$('#ag-lr-no').val();
  		var stc_invqty=$('#inv-qty').val();
  		var proj_id=$('#stc-proj-id').val();
	    var super_id=$('#stc-super-id').val();
	    var requise_id=$('#stc-requis-id').val();
  		$.ajax({
	        url			:"asgard/mjolnirgetorder.php",
	        method		:'POST',
	        data		:{
	        	go_to_challan:1,
	        	stc_ch_id:stc_ch_id,
	        	stc_ch_notes:stc_ch_notes,
	        	stc_ch_tandc:stc_ch_tandc,
	        	stc_ch_waybilno:stc_ch_waybilno,
	        	stc_ch_lrno:stc_ch_lrno,
	        	stc_invqty:stc_invqty,
	        	proj_id:proj_id, 
	        	super_id:super_id,
	        	requise_id:requise_id
	        },
	        dataType	: 'JSON',
	        success:function(response){
	        	// console.log(response);
				alert(response);
				stc_call_ag_order();
				$('.stc-add').fadeOut(1000);
				$('.stc-view').toggle(500);
	        	// window.location.reload();
	        }
	    });
  	});

  	// set to cleaned
  	$('body').delegate('.set-to-cleaned', 'click', function(e){
  		e.preventDefault();
  		var stc_order_no=$('#ag-challan-order').val();
  		$.ajax({
	        url			:"asgard/mjolnirgetorder.php",
	        method		:'POST',
	        data		:{
	        	set_to_clean:1,
	        	stc_order_no:stc_order_no
	        },
	        dataType	: 'JSON',
	        success:function(response){
	        	// console.log(response);
	        	alert(response);
				stc_call_ag_order();
				$('.stc-add').fadeOut(1000);
				$('.stc-view').toggle(500);
	        }
	    });
  	});

 //  	setInterval(function(){
	// 	stc_get_ag_notif();
	// }, 500);

  	// call notification
  	stc_get_ag_notif();
  	function stc_get_ag_notif(){
  		$.ajax({
  			url : "asgard/mjolnirgetorder.php",
  			method : "POST",
  			data : {stc_ag_noti:1},
  			success : function(notification){
  				// console.log(notification);
  				$('.badge').html(notification);
  			}
  		})
  	}

  	// add to cart on po
  	$('body').delegate('.stcagordgopocart', 'click', function(e){
		e.preventDefault();
		var product_id = $(this).attr("id");  
  		var stc_rem_ord_qty = $('.disp-qty'+product_id).val(); 
		var add_to_sale_cart="addsalecart";
		$.ajax({  
			url:"asgard/mjolnirgetorder.php",
			method:"POST",
			data:{  
				product_id:product_id,
				stc_rem_ord_qty:stc_rem_ord_qty,
				add_po_fr_ag:1  
			},  
			success:function(data){
				alert(data);
				console.log(data);                              
			}  
		});      
	});
});

// requisition section
$(document).ready(function(){
	stc_call_ag_order();
	function stc_call_ag_order(){
		$.ajax({
			url			: "asgard/mjolnirgetorder.php",
			method		: "post",
			data		: {callrequisition:1},
			dataType	: 'JSON',
			success		: function(data){
				// console.log(data);
				$('.stc-view-ag-requisition-form').html(data);
			}
		});
	}

	// // show orders
	// $('body').delegate('.stc_view_requist', 'click', function(e){
 //    	e.preventDefault();
 //    	$('.stc-req-view').fadeOut(500);
 //    	$('.stc-req-add').toggle(1000);
 //    	var stc_order_id = $(this).attr("id");
 //    	$.ajax({
	//         url			:"asgard/mjolnirgetorder.php",
	//         method		:'POST',
	//         data		:{
	//         	call_requist_sub:1,
	//         	stc_order_id:stc_order_id
	//         },
	//         dataType	: 'JSON',
	//         success:function(req){
	//         	// console.log(req);
	//         	$('#order-req-nos').val('STC/A/'+req['order_number']);
	//         	$('#ag-challan-req').val(req['real_order_number']);
	//         	$('#ag-req-date').val(req['order_date']);
	//         	$('#cust_name_req').val(req['customer_name']);
	//         	$('#ag-req-info').val('STC/AT/'+req['agentname']);
	//         	$('#req-site-name').val(req['sitename']);
	//         	$('#ag-req-refrence').val(req['sitename']);
	//         	$('#site-req-address').val(req['siteaddress']);
	//         	$('.ag-requis-items').html(req['line_items']);
	//         	$('#stc-req-proj-id').val(req['project_id']);
	//         	$('#stc-req-super-id').val(req['supervisor_id']);
	//         	$('#stc-req-requis-id').val(req['requis_id']);
	//         }
 //    	});
 //  	});
});
// own agents order
$(document).ready(function(){

	stc_call_own_ag_order();
	function stc_call_own_ag_order(){
		$.ajax({
			url			: "asgard/mjolnirownagorder.php",
			method		: "post",
			data		: {callorder:1},
			dataType	: 'JSON',
			success		: function(data){
				// console.log(data);
             $('.stc-view-own-ag-order-form').html(data['ag_all_record']);
             $('.tot-ag-sale-amt').html(data['total_value']);
			}
		});
	}

	// show orders
	$('body').delegate('.stc_view_ag_order', 'click', function(e){
    	e.preventDefault();
    	$('.stc-view').fadeOut(500);
    	$('.stc-add').toggle(1000);
    	var stc_order_id = $(this).attr("id");
    	$.ajax({
	        url			:"asgard/mjolnirownagorder.php",
	        method		:'POST',
	        data		:{
	        	call_order_sub:1,
	        	stc_order_id:stc_order_id
	        },
	        dataType	: 'JSON',
	        success:function(data){
	        	// console.log(data);
	        	$('#ag-single-number').val(data['agents_id']);
	        	$('#ag-onumber').val('STC/A/'+data['order_number']);
	        	$('#ag-challan-order').val(data['real_order_number']);
	        	$('#ag-order-date').val(data['order_date']);
	        	$('#ag-info').val('STC/AT/'+data['agentname']);
	        	$('.ag-order-items').html(data['line_items']);
	        }
    	});
  	});

	// go to cart sess
  	$('body').delegate('.stcagordgoaginv', 'click', function(e){
  		e.preventDefault();
  		var stc_pd_id = $(this).attr("id");
  		var stc_pd_qty = $('.pd-ag-qty'+stc_pd_id).val();
  		var ag_id=$('#ag-single-number').val();
    	$.ajax({
	        url			:"asgard/mjolnirownagorder.php",
	        method		:'POST',
	        data		:{
	        	gooritemoncart:1,
	        	stc_pd_id:stc_pd_id,
	        	stc_pd_qty:stc_pd_qty,
	        	ag_id:ag_id
	        },
	        // dataType	: 'JSON',
	        success:function(data){
	        	// console.log(data);
	        	alert(data);
	        }
    	});
  	});

  	// save challan
  	$('body').delegate('.save-own-ag-challan', 'click', function(e){
  		e.preventDefault();
  		$.ajax({
	        url			:"asgard/mjolnirownagorder.php",
	        method		:'POST',
	        data		:{
	        	go_to_challan:1
	        },
	        // dataType	: 'JSON',
	        success:function(response){
	        	// console.log(response);
	        	alert(response);
				stc_call_own_ag_order();
				$('.stc-add').fadeOut(1000);
				$('.stc-view').toggle(500);
	        }
	    });
  	});

  	// set to cleaned
  	$('body').delegate('.set-o-to-cleaned', 'click', function(e){
  		e.preventDefault();
  		var stc_order_no=$('#ag-challan-order').val();
  		$.ajax({
	        url			:"asgard/mjolnirownagorder.php",
	        method		:'POST',
	        data		:{
	        	set_to_clean:1,
	        	stc_order_no:stc_order_no
	        },
	        dataType	: 'JSON',
	        success:function(response){
	        	// console.log(response);
	        	alert(response);
	        	stc_call_own_ag_order();
		    	$('.stc-view').toggle(500);
		    	$('.stc-add').fadeOut(1000);	        	
	        }
	    });
  	});
});