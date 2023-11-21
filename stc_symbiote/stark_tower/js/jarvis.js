$(document).ready(function(){
	// check login
	$('.stc-login-form').on('submit', function(e){
		e.preventDefault();
		$.ajax({
			url : "asgard/stcusercheck.php",
			method : "post",
			data : new FormData(this),
			contentType : false,
			processData : false,
			// dataType : 'JSON',
			success : function (argument) {
				// console.log(argument);
				var argument=argument.trim();
            	// console.log(trimst.length);
            	if(argument=="success"){
					window.location.href="dashboard.php";
					$('.stc-login-form')[0].reset();
            	}else{
            	    alert(argument);
            	}
			}
		});		
	});


	stc_product_reload();
	function stc_product_reload(){
		$.ajax({
			url : "asgard/mjolnir.php",
			method : "post",
			data : {mask:1},
			dataType : 'JSON',
			success : function(data){
				// console.log(data);
				$('#count_rack').html(data[0]);
				$('#count_cat').html(data[1]);
				$('#count_sub_cat').html(data[2]);
				$('#count_pro').html(data[3]);
				$('#count_inv').html(data[4]);
				$('#count_brand').html(data[5]);
			}
		});
	}

	// rack directions
	$('.stc-rack-form').on('submit', function(e){
		e.preventDefault();
		$.ajax({
			url : "asgard/mjolnir.php",
			method : "post",
			data : new FormData(this),
			contentType : false,
			processData : false,
			dataType : 'JSON',
			success : function (argument) {
				$('#showcase').html(argument);
				$("#showcase").show().delay(3000).fadeOut();
				$('.stc-rack-form')[0].reset();
				stc_product_reload();
			}
		});		
	});

	// category direction
	$('.stc-add-category-form').on('submit', function(e){
		e.preventDefault();
		$.ajax({
			url : "asgard/mjolnir.php",
			method : "post",
			data : new FormData(this),
			contentType : false,
			processData : false,
			dataType : 'JSON',
			success : function (argument) {
				$('#showcase').html(argument);
				$("#showcase").show().delay(3000).fadeOut();
				$('.stc-add-category-form')[0].reset();
				stc_product_reload();
			}
		});		
	});

	// sub category direction
	$('.stc-sub-category-form').on('submit', function(e){
		e.preventDefault();
		$.ajax({
			url : "asgard/mjolnir.php",
			method : "post",
			data : new FormData(this),
			contentType : false,
			processData : false,
			dataType : 'JSON',
			success : function (argument) {
				$('#showcase').html(argument);
				$("#showcase").show().delay(3000).fadeOut();
				$('.stc-sub-category-form')[0].reset();
				stc_product_reload();
			}
		});		
	}); 

	// brand direction
	$('.stc-brand-form').on('submit', function(e){
		e.preventDefault();
		$.ajax({
			url : "asgard/mjolnir.php",
			method : "post",
			data : new FormData(this),
			contentType : false,
			processData : false,
			dataType : 'JSON',
			success : function (argument) {
				$('#showcase').html(argument);
				$("#showcase").show().delay(3000).fadeOut();
				$('.stc-brand-form')[0].reset();
				stc_product_reload();
			}
		});		
	}); 

	// call query direction
	stc_product_page_on_call();
	function stc_product_page_on_call(){
		$.ajax({
			url : "asgard/mjolnir.php",
			method : "post",
			data : {friday:1},
			dataType : 'JSON',
			success : function(data){
				// console.log(data);
				$('.call_cat').html(data[0]);
				$('.call_sub_cat').html(data[1]);
				$('.call_rack').html(data[2]);
				$('.call_brand').html(data[3]);
				// $('count_pro').html(data[4]);
			}
		});
	}



	/*---------------------------product add form----------------------------*/

	$('.stc-add-product-form').on('submit',function(e){
		e.preventDefault();
		$.ajax({
			xhr: function() {
                var xhr = new window.XMLHttpRequest();
                xhr.upload.addEventListener("progress", function(evt) {
                    if (evt.lengthComputable) {
                        var percentComplete = ((evt.loaded / evt.total) * 100);
                        $(".progress-bar").width(percentComplete + '%');
                        $(".progress-bar").html(percentComplete+'%');
                    }
                }, false);
                return xhr;
            },
			url : "asgard/mjolnir.php",
			method : "post",
			data : new FormData(this),
			contentType : false,
			cache: false,
			processData : false,
			success : function(data){
				alert(data);
				if(data=="Product's added!!"){
					$('.stc-add-product-form')[0].reset();
				}
			}
		});
	});

	// call product on product page
	// stc_call_pro_on_view();
	function stc_call_pro_on_view(){
		$.ajax({
			url : "asgard/mjolnir.php",
			method : "post",
			data : {fridaycallproonview:1},
			dataType : 'JSON',
			success : function(data){
				// console.log(data);
				$('.stc-call-view-product-row').html(data);
			}
		});
	}

	// seacrch query set
	var search_alo;
	var jsfilter;
	// search_alo=$('#searchbystcname').val();
	$('.product-search-hit').on('click',function(e){
		e.preventDefault();
		search_alo=$('#searchbystcname').val();
		$.ajax({
			url : "asgard/mjolnir.php",
			method : "post",
			data : {search_alo_in:search_alo},
			dataType : 'JSON',
			success : function(data){
				// console.log(data);
				$('.stc-call-view-product-row').html(data);
			}
		});
	});

	// filter product by rack
    $("#filterbyrack").change(function(){
        jsfilterrack = $(this).val();
        $.ajax({
			url : "asgard/mjolnir.php",
			method : "post",
			data : {
				jsfilterrackout:jsfilterrack
			},
			// dataType : 'JSON',
			success : function(data){
				// console.log(data);
				$('.stc-call-view-product-row').html(data);
			}
		});
    });

    // filter product by cat
    $("#filterbycat").change(function(){
        jsfiltercat = $(this).val();
        $.ajax({
			url : "asgard/mjolnir.php",
			method : "post",
			data : {
				jsfiltercatout:jsfiltercat
			},
			// dataType : 'JSON',
			success : function(data){
				// console.log(data);
				$('.stc-call-view-product-row').html(data);
			}
		});
    });

    // filter product by subcat
    $("#filterbysubcat").change(function(){
        jsfiltersubcat = $(this).val();
        $.ajax({
			url : "asgard/mjolnir.php",
			method : "post",
			data : {
				jsfiltersubcatout:jsfiltersubcat
			},
			// dataType : 'JSON',
			success : function(data){
				// console.log(data);
				$('.stc-call-view-product-row').html(data);
			}
		});
    });

    // edit product page
    $('.stc-edit-product-form').on('submit',function(e){
		e.preventDefault();
		$.ajax({
			url : "asgard/mjolnir.php",
			method : "post",
			data : new FormData(this),
			contentType : false,
			cache: false,
			processData : false,
			success : function(data){
				alert(data);
				// if(data=="Product Updated Successfully!!!"){
				// 	$('.stc-edit-product-form')[0].reset();
				// }
				$('.stc-edit-product-form')[0].reset();
			}
		});
	});

    $('.product-id-search-hit').on('click', function(e){
    	e.preventDefault();
        jsfilterprobyid = $('#searchbystcpdid').val();
        $.ajax({
			url : "asgard/mjolnir.php",
			method : "post",
			data : {
				jsfilterprobypoidout:1,
				jsfilterprobyid:jsfilterprobyid
			},
			dataType : 'JSON',
			success : function(data){
				// console.log(data);
				$('.stc-call-view-product-withid-row').html(data);
			}
		});
    });

    $('.product-id-sosearch-hit').on('click', function(e){
    	e.preventDefault();
        jsfilterprobyid = $('#searchbystcsopdid').val();
        $.ajax({
			url : "asgard/mjolnir.php",
			method : "post",
			data : {
				jsfilterprobysoidout:1,
				jsfilterprobyid:jsfilterprobyid
			},
			dataType : 'JSON',
			success : function(data){
				// console.log(data);
				$('.stc-call-view-product-sale-withid-row').html(data);
			}
		});
    });
	/*---------------------------Merchant add form----------------------------*/
	// add merchant
	$('.stc-add-merchant-form').on('submit',function(e){
		e.preventDefault();
		$.ajax({
			url : "asgard/mjolnir.php",
			method : "post",
			data : new FormData(this),
			contentType : false,
			cache: false,
			processData : false,
			success : function(data){
				alert(data);
				$('.stc-add-merchant-form')[0].reset();
			}
		});
	});

	// state & city call
	stc_vendor_page_on_call();
	function stc_vendor_page_on_call(){
		$.ajax({
			url : "asgard/mjolnir.php",
			method : "post",
			data : {indialocation:1},
			dataType : 'JSON',
			success : function(data){
				// console.log(data);
				$('.call_city').html(data[0]);
				$('.call_state').html(data[1]);
			}
		});
	}

	// merchant search
	var search_mer_byname_var;
	$('#searchbystcmername').on('keyup input', function(){
		search_mer_byname_var=$(this).val();
		$.ajax({
			url : "asgard/mjolnir.php",
			method : "post",
			data : {search_mer_byname_var_in:search_mer_byname_var},
			dataType : 'JSON',
			success : function(data){
				// console.log(data);
				$('.stc-call-view-Merchant-row').html(data);
			}
		});
	});
	
	// merchant search 2
	var search_mer_var_byskf_var;	
	$('#searchbystcmerskf').on('keyup input', function(){
		search_mer_var_byskf_var=$(this).val();
		$.ajax({
			url : "asgard/mjolnir.php",
			method : "post",
			data : {search_mer_var_byskf_var_in:search_mer_var_byskf_var},
			dataType : 'JSON',
			success : function(data){
				// console.log(data);
				$('.stc-call-view-Merchant-row').html(data);
			}
		});
	});	
});

/*---------------------------Customer add form----------------------------*/
$(document).ready(function(){
	$('.add-customer-response-customer-form').on('submit', function(e){
		e.preventDefault();
		$.ajax({
			url : "asgard/mjolnir.php",
			method : "post",
			data : new FormData(this),
			contentType : false,
			cache: false,
			processData : false,
			success : function(data){
				// console.log(data);
				alert(data);
				$('.stc-add-merchant-form')[0].reset();
			}
		});
	});

	// customer search
	var search_cust_byname_var;
	$('#searchbystccustname').on('keyup input', function(){
		search_cust_byname_var=$(this).val();
		$.ajax({
			url : "asgard/mjolnir.php",
			method : "post",
			data : {search_cust_byname_var_in:search_cust_byname_var},
			dataType : 'JSON',
			success : function(data){
				// console.log(data);
				$('.stc-call-view-Customer-row').html(data);
			}
		});
	});
	
	// Customer search 2
	var search_cust_var_byskf_var;	
	$('#searchbystccustskf').on('keyup input', function(){
		search_cust_var_byskf_var=$(this).val();
		$.ajax({
			url : "asgard/mjolnir.php",
			method : "post",
			data : {search_cust_var_byskf_var_in:search_cust_var_byskf_var},
			dataType : 'JSON',
			success : function(data){
				// console.log(data);
				$('.stc-call-view-Customer-row').html(data);
			}
		});
	});	
});

/*---------------------------inventory add form----------------------------*/
$(document).ready(function(){

	$('body').delegate('.changerack', 'click', function(e){
		e.preventDefault();
		var stc_edit_product_id = $(this).attr("id");
		 var js_rack_id = $('#stcpdrack'+stc_edit_product_id).val();
		// alert("hi");
		$.ajax({
			url : "asgard/inventory_system.php",
			method : "post",
			data : {
				inventchange_rack:1,
				pdid_edit_rck:stc_edit_product_id,
				rack_id:js_rack_id
			},
			// dataType : 'JSON',
			success : function(data){
				// console.log(data);
				alert(data);
				// inventory();
			}
		});
	});

	// seacrch query set
	var search_alo;
	var jsfilter;
});
 