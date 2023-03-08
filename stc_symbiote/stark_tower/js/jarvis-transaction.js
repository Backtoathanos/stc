// date time 
  var todaydate = new Date();
  var day = todaydate.getDate();
  var month = todaydate.getMonth() - 3;
  var nmonth = todaydate.getMonth();
  var year = todaydate.getFullYear();
  var begdateon = year + "/" + month + "/" + day;
  var enddateon = year + "/" + nmonth + "/" + day;

/*-------------------------------------------------------------------------*/
/*--------------------------Merchant Quotations----------------------------*/
/*-------------------------------------------------------------------------*/

$(document).ready(function(){

  var search_quote_pd_name;
  // search product on product Vendor Quotation Page
  $('#searchbystcquotepdname').on('keyup input', function(){
    search_quote_pd_name=$(this).val();
    $.ajax({
      url : "asgard/get_quotation.php",
      method : "post",
      data : {search_quote_pd_name_in:search_quote_pd_name},
      dataType : 'JSON',
      success : function(data){
        // console.log(data);
        $('.stc-call-view-quote-product-row').html(data);
         // $(this)[0].reset();
      }
    });
  });

  // call vendor on Quotation page
  stc_vendor_on_quote_page();
  function stc_vendor_on_quote_page(){
    $.ajax({
      url : "asgard/get_quotation.php",
      method : "post",
      data : {friday_quote_vendor:1},
      dataType : 'JSON',
      success : function(data){
        // console.log(data);
        $('#expire_date').val(data['quote_time']);
        $('.stc-select-quote-merchant').html(data['quote_vendor']);
      }
    });
  }

  // add cart to Quotation session
  $('body').delegate('.add_to_cart_for_vendor_quote','click',function(e){
    e.preventDefault();
    var product_id = $(this).attr("id");  
    var product_name = $('#stcquotepdname'+product_id).val();
    var product_unit = $('#stcquotepdunit'+product_id).val();
    var product_quantity = $('#stcquoteqty'+product_id).val();
    var product_rate = $('#stcquoterate'+product_id).val();
    var product_hsncode = $('#stcquotepdhsncode'+product_id).val();
    var product_gst = $('#stcquotepdgst'+product_id).val();
    var add_to_cart_for_quote="add";
    $.ajax({
      url:"asgard/get_quotation.php",
      method:"POST",
      data:{
        product_id:product_id,
        product_name:product_name,  
        product_unit:product_unit,
        product_quantity:product_quantity,
        product_rate:product_rate,
        product_hsncode:product_hsncode,
        product_gst:product_gst,
        add_quote:add_to_cart_for_quote  
      },  
      success:function(data){
        show_quote_cart();
        alert(data);
        // console.log(data);                              
      }  
    });      
  });

  // show cart on product purchase add sectin page
  show_quote_cart();
  function show_quote_cart(){
      $.ajax({
        url:"asgard/get_quotation.php",
        method:'POST',
        data:{stc_show_quote_sess:1},
        // dataType: 'JSON',
        success:function(data){
          // console.log(data);
            $('.quotelineitem').html(data["orderTable"]);
        }
      });
  }

  // quantity change Quotation session
  $(document).on('click', '.stcqtyquotehit', function(e){  
      e.preventDefault();
        var product_id = $(this).data("product_id");  
        var quantity = $('#stcqtyquotet'+product_id).val();   
        // var change_cart = $('#cartprice'+product_id).val();
        var action = "quantity_change"; 
        if(quantity != ''){  
          $.ajax({  
               url:"asgard/get_quotation.php",
               method:"POST",  
               data:{
                  product_id:product_id, 
                  quantity:quantity, 
                  quantity_action:action},  
               success:function(data){ 
                show_quote_cart();
                // console.log(data);
               }  
          });  
        } 
  });

  // delete from cart Vendor Quotation session
  $('body').delegate('.stcdelquotebtn','click',function(e){
      e.preventDefault();
      var product_id = $(this).attr("id");
        if(confirm("Are you sure you want to remove this product?")){   
          $.ajax({  
            url:"asgard/get_quotation.php",  
            method:"POST",
            data:{  
                product_id:product_id,
                stcdelvendorquotelinei:1  
            },  
            success:function(data){  
              console.log(data);
              show_quote_cart();
              alert(data);                        
            }  
          });  
        }         
  });

  // onsave quote
  $('body').delegate('.stcsavequote', 'click',function(e){     
      e.preventDefault();
      var merchantid=$('#stc_vendor_quotation').val();
      var quote_termandc=$('#stcquotetandc').val();
      var quote_notes=$('#stcquotenotes').val();
      $.ajax({  
        url:"asgard/get_quotation.php",
        method:"POST",  
        data:{
          stcprosavevendquote:1,
          vmerchantid:merchantid,
          vtermandc:quote_termandc,
          vquotenotes:quote_notes
        },
        dataType: 'JSON',
        success:function(data){ 
          // console.log(data);
          stc_call_quote();
          if(data['objloki']=="Success"){
            // window.location.href="print-preview.php?p"
            alert("Your Quotation is Saved!!!");
            $('.stc-add').fadeOut(500);
            $('.stc-view').toggle(1000);                
          }else{
            alert("Please Check Form & Try Again!!");
          }
        }  
      });  
  });   

  // call product quote section
  stc_call_quote();
  function stc_call_quote(){
      $.ajax({
        url:"asgard/get_quotation.php",
        method:'POST',
        data:{stccallquotations:1},
        dataType: 'JSON',
        success:function(data){
          // console.log(data);
            $('.stc-view-quote-form').html(data);
        }
      });     
  }  

  // on perticular Quotation click record call & show
  $('body').delegate('.stc_edit_quotation', 'click', function(e){
    e.preventDefault();
    $('.quotelineitem').hide();
    $('.editquotelineitem').show();
    $('.stc-view').fadeOut(500);
    $('.stc-add').toggle(1000);
    var stc_pp_edit_product_id = $(this).attr("id");
    $.ajax({
        url:"asgard/get_quotation.php",
        method:'POST',
        data:{
          stc_view_for_edit:1,
          stc_ppe_pid:stc_pp_edit_product_id
        },
        dataType: 'JSON',
        success:function(data){
          // console.log(data);
          $('#gtonumbershow').val('STC/Q/'+data['gto_number']);
          $('#stc_vendor_quotation_edit').html(data['Vendor_name']);
          $('.editquotelineitem').html(data['line_items']);
          $('#stcquotetandc').val(data['termandcond']);
          $('#stcquotenotes').val(data['quotnotes']);
        }
    });
  });
});

/*-------------------------------------------------------------------------*/
/*--------------------------Purchase Products------------------------------*/
/*-------------------------------------------------------------------------*/

$(document).ready(function(){
	// call vendor on purchase product page
	stc_vendor_on_v_page();
	function stc_vendor_on_v_page(){
		$.ajax({
			url : "asgard/purchase_product.php",
			method : "post",
			data : {friday_vendor:1},
			dataType : 'JSON',
			success : function(data){
				// console.log(data);
				$('#expire_date').val(data['time']);
				$('.stc-select-vendor').html(data['vendor']);
			}
		});
	}

	// add cart to session
	$('body').delegate('.add_to_cart','click',function(e){
		e.preventDefault();
      var product_id = $(this).attr("id");  
      var product_name = $('#stcpopdname'+product_id).val(); 
      var product_unit = $('#stcpopdunit'+product_id).val();  
      var product_quantity = $('#stcpoqty'+product_id).val();  
      var product_price = $('#stcpdprice'+product_id).val();
      var product_hsncode = $('#stcpopdhsncode'+product_id).val();
      var product_gst = $('#stcpopdgst'+product_id).val();
      var add_to_cart="add";
      $.ajax({  
        url:"asgard/purchase_product.php",  
        method:"POST",
        data:{  
            product_id:product_id,
            product_name:product_name,  
            product_unit:product_unit,
            product_quantity:product_quantity,
            product_price:product_price,
            product_hsncode:product_hsncode,
            product_gst:product_gst,
            add_po:add_to_cart  
        },  
        success:function(data){
          show_cart();
          alert(data);
          // console.log(data);                              
        }  
      });      
  });

	// show cart on product purchase add sectin page
  show_cart();
  function show_cart(){
    $.ajax({
      url:"asgard/purchase_product.php",
      method:'POST',
      data:{stc_show_po_sess:1},
      dataType: 'JSON',
      success:function(data){
        // console.log(data);
          $('.flase').html(data["orderTable"]);
      }
    });
  }

  // quantity change purchase product session
  $(document).on('click', '.stcqtypo', function(e){  
    	e.preventDefault();
        var product_id = $(this).data("product_id");  
        var quantity = $('#stcqtypot'+product_id).val();   
        // var change_cart = $('#cartprice'+product_id).val();
        var action = "quantity_change"; 
        if(quantity != ''){  
          $.ajax({  
               url:"asgard/purchase_product.php",
               method:"POST",  
               data:{
               		product_id:product_id, 
               		quantity:quantity, 
               		quantity_action:action},  
               success:function(data){ 
                show_cart();
                // console.log(data);
               }  
          });  
      	} 
  });  

  // Rate change purchase product session
  $(document).on('click', '.stcratepo', function(e){  
    	e.preventDefault();
        var product_id = $(this).data("product_id");  
        var price = $('#stcratepot'+product_id).val();   
        // var change_cart = $('#cartprice'+product_id).val();
        var action = "quantity_change"; 
        if(price != ''){  
	    	$.ajax({  
	    		url:"asgard/purchase_product.php",
	    		method:"POST",  
	    		data:{
	    				product_id:product_id, 
	    				price:price, 
	    				price_action:action},  
	    		success:function(data){ 
	    		 show_cart();
	    		 // console.log(data);
	    		}  
	    	});  
      	}
  });  

  // delete from cart purchase product session
  $('body').delegate('.stcdelpobtn','click',function(e){
      e.preventDefault();
      var product_id = $(this).attr("id");
        if(confirm("Are you sure you want to remove this product?")){   
          $.ajax({  
            url:"asgard/purchase_product.php",  
            method:"POST",
            data:{  
                product_id:product_id,
                stcdelpolinei:1  
            },  
            success:function(data){  
              show_cart();
              alert(data);                        
            }  
          });  
        }         
  });

  // call product purchase section
  stc_call_product_purchase(begdateon, enddateon);
  function stc_call_product_purchase(begdateon, enddateon){
    var begdate=begdateon;
    var enddate=enddateon; 
    	$.ajax({
        url:"asgard/purchase_product.php",
        method:'POST',
        data:{
          stccallpp:1,
          begdate:begdate,
          enddate:enddate
        },
        dataType: 'JSON',
        success:function(data){
          // console.log(data);
            $('.stc-view-purchase-order-form').html(data);
        }
      });    	
  }

  $(document).on('click', '#purchaseproddatefilt', function(e){
    e.preventDefault();
    var begdate=$('.begdate').val();
    var enddate=$('.enddate').val(); 
    stc_call_product_purchase(begdate, enddate);
  });

  // onsave po
  $('body').delegate('.stcsaveandppp', 'click',function(e){
    	e.preventDefault();
    	var merchantid=$('#stc_vendor_purchase_product').val();
    	var basicvalue=$('#stcbasicvalue').val();
      var termsandcond=$('#stcpdtandc').val();
      var gtonumberinst=$('#gtonumberinst').val();
    	$.ajax({  
  			url:"asgard/purchase_product.php",
  			method:"POST",  
  			data:{
  				stcprosavepo:1,
  				merchantid:merchantid,
  				basicvalue:basicvalue,
          termsandcond:termsandcond,
          gtonumberinst:gtonumberinst
  			},
  			dataType: 'JSON',
  			success:function(data){ 
  				// console.log(data);
          stc_call_product_purchase();
  				if(data['objloki']=="Success"){
  					alert("Your order is saved!!!");
            // window.location.reload(500);
            show_cart();
  					$('.stc-add').fadeOut(500);
  					$('.stc-view').toggle(1000);            		
  				}else{
  					alert("Please Check Form & Try Again!!");
  				}
  			}  
  		});  
  });   

  // on perticular purchase order click record call & show
  $('body').delegate('.stc_edit_page', 'click', function(e){
    	e.preventDefault();
    	$('.stc-view').fadeOut(500);
    	$('.stc-add').toggle(1000);
    	var stc_pp_edit_product_id = $(this).attr("id");
    	$.ajax({
	        url:"asgard/purchase_product.php",
	        method:'POST',
	        data:{
	        	stc_view_for_edit:1,
	        	stc_ppe_pid:stc_pp_edit_product_id
	        },
	        dataType: 'JSON',
	        success:function(data){
	        	// console.log(data);
	        	$('#gtonumbershow').val('STC/'+data['gto_number']);
	        	$('#stc_view_merchant_name').html(data['Vendor_name']);
	        	$('.flase').html(data['line_items']);
	        }
    	});
  });

  //  edit po
  $('body').delegate('.stcchangepoqty', 'click', function(e){
    e.preventDefault();
    var poid=$(this).attr("id");
    var product_qty = $('.stcqtyepoi'+poid).val();
    $.ajax({
      url : "asgard/purchase_product.php",
      method : "post",
      data : {
        updatepdqty:1,
        poid:poid,
        product_qty:product_qty
      },
      dataType : 'JSON',
      success : function(data){
        // console.log(data);
        alert(data);
        // window.location.reload();
      }
    });
  });

  $('body').delegate('.stcchangepoprice', 'click', function(e){
    e.preventDefault();
    var poid=$(this).attr("id");
    var product_price = $('.stcpriceepoi'+poid).val();
    $.ajax({
      url : "asgard/purchase_product.php",
      method : "post",
      data : {
        updatepdprice:1,
        poid:poid,
        product_price:product_price
      },
      dataType : 'JSON',
      success : function(data){
        // console.log(data);
        alert(data);
        // window.location.reload();
      }
    });
  });

  $('body').delegate('.deltopoitems', 'click', function(e){
    e.preventDefault();
    var poid=$(this).attr("id");
    $.ajax({
      url : "asgard/purchase_product.php",
      method : "post",
      data : {
        delpoli:1,
        poid:poid
      },
      dataType : 'JSON',
      success : function(data){
        // console.log(data);
        alert(data);
        // window.location.reload();
      }
    });
  });

  // edit po items 
  $('body').delegate('.add_to_cartforedit','click',function(e){
    e.preventDefault();
    $.urlParam = function(name){
    var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
      return results[1] || 0;
    }
    $.urlParam('epid');
    // console.log($.urlParam('epid'));

    var product_id = $(this).attr("id");  
    var product_poid = $.urlParam('epid');  
    var product_quantity = $('#stceditpoqty'+product_id).val();  
    var product_price = $('#stceditpdprice'+product_id).val();
    var add_to_cartforedit="edit";      

    $.ajax({  
      url:"asgard/purchase_product.php",  
      method:"POST",
      data:{  
          product_id:product_id,
          product_poid:product_poid,
          product_quantity:product_quantity,
          product_price:product_price,
          edit_po:add_to_cartforedit  
      },  
      success:function(data){
        // show_editpocart();
        alert(data);
        // console.log(data);     
        // window.location.reload();                          
      }  
    });
  });

  // save po
  $('body').delegate('.stcsaveeditpo', 'click', function(e){
    e.preventDefault();
    $.urlParam = function(name){
    var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
      return results[1] || 0;
    }
    $.urlParam('epid');

    var epono=$.urlParam('epid');
    var epomerchantid=$('#stc_mer_edit_purchase_product').val();
    var epotermanc=$('#stceditpdtandc').val();
    var gto_number=$('.edit-gto-number').val();
    // alert(epono + epomerchantid + epotermanc);
    $.ajax({  
      url:"asgard/purchase_product.php",  
      method:"POST",
      data:{  
          product_id:epono,
          product_merchant:epomerchantid,
          product_tandc:epotermanc,
          gto_number:gto_number,
          saveedit_po:1  
      },  
      success:function(data){
        alert(data);
        // console.log(data);    
        window.location.reload();                          
      }  
    });    
  });

  // filter po by po number
  // $('#stcfilterbypdname').on('keyup input', function(){
  $('body').delegate('.searchbypdnamehit','click',function(e){
    e.preventDefault();
    var begdate=$('.begdate').val();
    var enddate=$('.enddate').val(); 
    search_po_pd_name=$('#stcfilterbypdname').val();
    if(begdate=='' || enddate=='' || search_po_pd_name==''){
      alert("Date to choose kar");
    }else{
      $.ajax({
        url : "asgard/purchase_product.php",
        method : "post",
        data : {
          search_po_pd_name_in_po:search_po_pd_name,
          begdate:begdate,
          enddate:enddate
        },
        dataType : 'JSON',
        success : function(data){
          // console.log(data);
          $('.stc-view-purchase-order-form').html(data);
           // $(this)[0].reset();
        }
      });
    }
  });
});

/*-------------------------------------------------------------------------*/
/*------------------------------GRN Process--------------------------------*/
/*-------------------------------------------------------------------------*/

$(document).ready(function(){
	// show_grn_cart();
	function show_grn_cart() {
		$.ajax({  
      url:"asgard/do_grn.php",  
      method:"POST",
      data:{  
          comon_grn_sess:1  
      },  
      dataType: 'JSON',
      success:function(data){
        // console.log(data);    
        $('.grn_data').html(data['orderTable']);                    
      }  
    });
	}

  $(document).on('click', '#showgrn', function(e){
    e.preventDefault();
    show_grn_cart();
  });

	// For Doing GRN Product Show
	$(document).on('click', '.addtogrn', function(e){
  	e.preventDefault();
  	var product_id = $(this).attr("id");
    var product_qty = $('.stcqtygrnt'+product_id).val();
    var product_price = $('.stcpricegrnt'+product_id).val();
    var pro_order_id = $('.stcorderid'+product_id).val();
    if(product_qty == 0){
      alert("Wah chhori wah!!! Quantity to kam se kam daliye!");
    }else{
    	$.ajax({  
    		url:"asgard/do_grn.php",
    		method:"POST",  
    		data:{
    			purchase_pro_id:product_id,
          product_qty:product_qty,
          product_price:product_price,
          product_order_id:pro_order_id,
    			add_grn_sess_action:1
    		},  
    		// dataType: `JSON`,
    		success:function(data){ 
    		 // show_grn_cart();
    		 // console.log(data);
    		 alert(data);
    		}  
    	}); 
    } 
  });  

  $(document).on('click', '.stcchangegrnsessqty', function(e){
    e.preventDefault();
    var product_id = $(this).attr("id");
    var product_qty = $('.stcchangegrnsessqty'+product_id).val();
    $.ajax({  
      url:"asgard/do_grn.php",
      method:"POST",  
      data:{
        purchase_pro_id:product_id,
        product_qty:product_qty,
        change_grn_sess_qty_action:1
      },  
      // dataType: `JSON`,
      success:function(data){ 
       show_grn_cart();
       console.log(data);
       alert(data);
      }  
    });  
  }); 

	// charges packing & forwarding & freight
	$(document).on('click', '.stcgrnfcpo', function(e){
   	e.preventDefault();
    var freightcharge=$('.stcfc').val();
    var packingandforwardingcharge=$('.stcpf').val();
    var othercharge=$('.stcoc').val();
    var grand_total=$('#stc_grand_offset_table_value').val();
    $.ajax({  
			url:"asgard/do_grn.php",
			method:"POST",  
			data:{
				freightcharge:freightcharge,
				packingandforwardingcharge:packingandforwardingcharge,
				othercharge:othercharge,
				grand_total:grand_total,
				do_plus_minus_on_grn:1
			},  
			// dataType: `JSON`,
			success:function(data){ 
			 	$('#stc_final_grn_value').html(data);
			}  
		});   	
  });

  // for cancel grn session destroy
  $(document).on('click', '#docancel', function(e){
    	e.preventDefault();
  		$.ajax({  
  			url:"asgard/do_grn.php",
  			method:"POST",  
  			data:{
  				cancel_grn_sess_action:1
  			},  
  			// dataType: `JSON`,
  			success:function(data){ 
  			 // console.log(data);
  			 alert(data);
  			 window.location.reload();
  			}  
  		});  
  });  

  // save grn to db
  $(document).on('click', '.stcsavegrn', function(e){
    e.preventDefault();
    var order_id = $(this).attr("id");
    var order_invodate = $('.stcinvodate').val();
    var order_invonumber = $('.stcinvonumber').val();
    var order_stcfc = $('.stcfc').val();
    var order_stcpf = $('.stcpf').val();
    var order_stcoc = $('.stcoc').val();
    var notesgrn = $('.notesgrn').val();
    $.ajax({  
      url:"asgard/do_grn.php",
      method:"POST",  
      data:{
        grn_order_id:order_id,
        grn_invodate:order_invodate,
        grn_invonumber:order_invonumber,
        grn_stcfc:order_stcfc,
        grn_stcpf:order_stcpf,
        grn_scoc:order_stcoc,
        grn_notes:notesgrn,
        save_grn_action:1
      },  
      // dataType: `JSON`,
      success:function(data){ 
      // console.log(data);
      alert(data);
      window.location.reload();
      }  
    });  
  });

  // show grn
  stc_call_grn(begdateon, enddateon);
  function stc_call_grn(begdateon, enddateon){
    var begdate=begdateon;
    var enddate=enddateon; 
    $.ajax({
      url:"asgard/do_grn.php",
      method:'POST',
      data:{
        stccallgrn:1,
        begdate:begdate,
        enddate:enddate
      },
      // dataType: 'JSON',
      success:function(data){
        // console.log(data);
          $('.stc-view-grn-form').html(data);
      }
    });     
  }

  $(document).on('click', '#grnproddatefilt', function(e){
    e.preventDefault();
    var begdate=$('.begdate').val();
    var enddate=$('.enddate').val(); 
    stc_call_grn(begdate, enddate);
  });

  $(document).on('click', '.stc_show_grn_page', function(e){
      e.preventDefault();
      $('.stc-view').fadeOut(500);
      $('.stc-add').toggle(1000);
      var stc_grn_edit_product_id = $(this).attr("id");
      $.ajax({
          url:"asgard/do_grn.php",
          method:'POST',
          data:{
            stc_grn_view_for_edit:1,
            stc_grn_edit_pid:stc_grn_edit_product_id
          },
          dataType: 'JSON',
          success:function(data){
            console.log(data);
            $('#stcgrnnumbershow').val('GRN/'+data['grn_number']);
            $('#grn_date_show').val(data['grn_date']);
            $('#show_grn_po_no').val('STC/'+data['po_number']);
            $('#show_grn_po_date').val(data['po_date']);
            $('#show_merchant_name').val(data['merchant_name']);
            $('#show_grn_invonumber').val(data['chalan_number']);
            $('#show_grn_invdate').val(data['challan_date']);
            $('.grn_show_items_div').html(data['line_items_grn']);
          }
      });
    });
});

/*-------------------------------------------------------------------------*/
/*---------------------------Merchant Payment------------------------------*/
/*-------------------------------------------------------------------------*/

$(document).ready(function(){
  // call po on change merchant in advance payment section 
  $('#apmerchantshow').on('change', function(e){
    e.preventDefault();
    var js_merchant_id=$(this).val();
    $.ajax({
      url : "asgard/payment_process.php",
      method : "post",
      data : {
        call_po_on_choose_merchant:1,
        merchant_id:js_merchant_id
      },
      dataType : 'JSON',
      success : function(data){
        // console.log(data);
        $('.stc-select-order').html(data);
      }
    });
  });

  // call date and due amount on po change  
  $('#apponumbershow').on('change', function(e){
    e.preventDefault();
    var js_po_number=$(this).val();
    $.ajax({
      url : "asgard/payment_process.php",
      method : "post",
      data : {
        call_po_date_on_choose_merchant:1,
        po_number:js_po_number
      },
      dataType : 'JSON',
      success : function(data){
        // console.log(data);
        $('.appodateshow').val(data['order_date']);
        $('#apdueamountshow').val(data['due_amount']);
      }
    });
  });

  // call payments
  call_payments(begdateon, enddateon);
  function call_payments(begdateon, enddateon){
    var begdate=begdateon;
    var enddate=enddateon; 
    $.ajax({  
      url:"asgard/payment_process.php",  
      method:"POST",
      data:{  
          call_mer_pay:1,
          begdate:begdate,
          enddate:enddate
      },  
      dataType: 'JSON',
      success:function(data){
        // console.log(data);    
        // $('.call-advance-payment-records').html(data['stc_adv_payment']); 
        // $('.call-regular-payment-records').html(data['stc_reg_payment']);
        $('.stc-view-all-payment-form').html(data['stc_all_payment']);      
      }  
    });
  }

  $(document).on('click', '#payprocdatefilt', function(e){
    e.preventDefault();
    var begdate=$('.begdate').val();
    var enddate=$('.enddate').val();
    call_payments(begdate, enddate);
  });

  // on advance payment in db
  $('.stc-add-advance-po-payment-form').on('submit', function(e){
    e.preventDefault();
    $.ajax({
      url : "asgard/payment_process.php",
      method : "post",
      data : new FormData(this),
      contentType : false,
      processData : false,
      // dataType : 'JSON',
      success : function (argument) {
        // console.log(argument);
          alert(argument);
        $('.stc-add-advance-po-payment-form')[0].reset();
        call_payments();
      }
    });
  });

  // call invoice on merchant change in regular payment section
  $('#stcrp_merchant').on('change', function(e){
    e.preventDefault();
    var js_merchant_id=$(this).val();
    $.ajax({
      url : "asgard/payment_process.php",
      method : "post",
      data : {
        call_invo_on_choose_merchant:1,
        merchant_id:js_merchant_id
      },
      dataType : 'JSON',
      success : function(data){
        // console.log(data);
        $('#stcrp_invonumber').html(data);
      }
    });
  });

  // call data on change invoice number in regular payment section
  $('#stcrp_invonumber').on('change', function(e){
    e.preventDefault();
    // alert("js_notes");
    var js_invo_number=$(this).val();
    var js_merchant_id=$('#stcrp_merchant').val();
    $.ajax({
      url : "asgard/payment_process.php",
      method : "post",
      data : {
        call_rec_for_reg_pay:1,
        merchant_id:js_merchant_id,
        invo_number:js_invo_number
      },
      dataType : 'JSON',
      success : function(data){
        // console.log(data);
        $('#stcrp_invodate').val(data['invo_date']);
        $('#stcrp_grnnumber').val(data['hidden_grn_number']);
        $('.stcrp_grnnumber').val(data['grn_number']);
        $('#stcrp_grndate').val(data['grn_date']);
        $('#stcrp_ponumber').val(data['po_number']);
        $('#stcrp_podate').val(data['po_date']);
        $('#stcrp_grnbscamount').val(data['due_basic_amount']);
        $('#stcrp_grnbscamountincgst').val(data['due_gst_amount']);
        $('#stcrp_paidamount').val(data['paid_amount']);
        $('#stcrp_dueamount').val(data['due_amount']);
        call_payments();
      }
    });
  });

  // on submit of regular payment on db
  $('.stc-add-regular-po-payment-form').on('submit', function(e){
    e.preventDefault();
    $.ajax({
      url : "asgard/payment_process.php",
      method : "post",
      data : new FormData(this),
      contentType : false,
      processData : false,
      // dataType : 'JSON',
      success : function (argument) {
        // console.log(argument);
          alert(argument);
        $('.stc-add-regular-po-payment-form')[0].reset();
      }
    });
  });
});

/*-------------------------------------------------------------------------*/
/*--------------------------------Challan----------------------------------*/
/*-------------------------------------------------------------------------*/

$(document).ready(function(){
  stc_customer_on_sale_page();
  function stc_customer_on_sale_page(){
    $.ajax({
      url : "asgard/sale_order.php",
      method : "post",
      data : {friday_customer:1},
      dataType : 'JSON',
      success : function(data){
        // console.log(data);
        $('#expire_date').val(data['time']);
        $('.stc-select-customer').html(data['customer']);
      }
    });
  }

  // Sale Order
  stc_call_sale_orders(begdateon, enddateon);
  function stc_call_sale_orders(begdateon, enddateon){
    var begdate=begdateon;
    var enddate=enddateon; 
    $.ajax({
      url:"asgard/sale_order.php",
      method:'POST',
      data:{
        stccallss:1,
        begdate:begdate,
        enddate:enddate
      },
      dataType: 'JSON',
      success:function(data){
        // console.log(data);
          $('.stc-view-Sale-order-form').html(data);
      }
    });     
  }

  $(document).on('click', '#challandatefilt', function(e){
    e.preventDefault();
    var begdate=$('.begdate').val();
    var enddate=$('.enddate').val();
    stc_call_sale_orders(begdate, enddate);
  });

  // show sale order items sesson cart
  sale_order_cart();
  function sale_order_cart(){
    $.ajax({
      url:"asgard/sale_order.php",
      method:'POST',
      data:{stc_show_sale_sess:1},
      dataType: 'JSON',
      success:function(data){
        // console.log(data);
          $('.saleflase').html(data["orderTable"]);
      }
    });
  }

  // add cart to sale session
  $('body').delegate('.add_to_sale_cart','click',function(e){
    e.preventDefault();
    var product_id = $(this).attr("id");  
    var product_name = $('#stcpopdname'+product_id).val(); 
    var product_unit = $('#stcpopdunit'+product_id).val();  
    var product_quantity = $('#stcpoqty'+product_id).val();  
    var product_price = $('#stcpdprice'+product_id).val();
    var product_saleperc = $('#stcpdsaleperc'+product_id).val();
    var product_hsncode = $('#stcpopdhsncode'+product_id).val();
    var product_gst = $('#stcpopdgst'+product_id).val();
    var product_invent = $('#stcpdinvent'+product_id).val();
    var add_to_sale_cart="addsalecart";
    if(product_invent >= product_quantity){
      $.ajax({  
        url:"asgard/sale_order.php",  
        method:"POST",
        data:{  
            product_id:product_id,
            product_name:product_name,  
            product_unit:product_unit,
            product_quantity:product_quantity,
            product_price:product_price,
            product_sale_percent:product_saleperc,
            product_hsncode:product_hsncode,
            product_gst:product_gst,
            add_sale:add_to_sale_cart  
        },  
        success:function(data){
          sale_order_cart();
          alert(data);
          // console.log(data);                              
        }  
      });  
    }else{
      alert("Invalid quantity please recheck!!!");
    }
  });

  // quantity change Sale product session
  $(document).on('click', '.stcqtysale', function(e){  
    e.preventDefault();
      var product_id = $(this).data("product_id");  
      var quantity = $('#stcqtypot'+product_id).val();   
      // var change_cart = $('#cartprice'+product_id).val();
      var action = "quantity_change"; 
      if(quantity != ''){  
        $.ajax({  
             url:"asgard/sale_order.php",
             method:"POST",  
             data:{
                product_id:product_id, 
                quantity:quantity, 
                sale_quantity_action:action},  
             success:function(data){ 
              sale_order_cart();
              // console.log(data);
             }  
        });  
      } 
  });  

  // Rate change Sale product session
  $(document).on('click', '.stcratesale', function(e){  
    e.preventDefault();
      var product_id = $(this).data("product_id");  
      var price = $('#stcratepot'+product_id).val();   
      // var change_cart = $('#cartprice'+product_id).val();
      var action = "rate_change"; 
      if(price != ''){  
      $.ajax({  
        url:"asgard/sale_order.php",
        method:"POST",  
        data:{
            product_id:product_id, 
            price:price, 
            stc_price_action:action},  
        success:function(data){ 
         sale_order_cart();
         // console.log(data);
        }  
      });  
      }
  });  

  // delete from cart purchase product session
  $('body').delegate('.stcdelsalebtn','click',function(e){
    e.preventDefault();
    var product_id = $(this).attr("id");
      if(confirm("Are you sure you want to remove this product?")){   
        $.ajax({  
          url:"asgard/sale_order.php",  
          method:"POST",
          data:{  
              product_id:product_id,
              stcdelsalelinei:1  
          },  
          success:function(data){  
            sale_order_cart();
            alert(data);                        
          }  
        });  
      }         
  });

  // calculate charges to sale order
  $(document).on('click', '.stcsalec', function(e){  
    e.preventDefault();
    var freightcharge=$('.stcfc').val();
    var packingandforwardingcharge=$('.stcpf').val();
    var grand_total=$('#stc_grand_offset_table_value').val();
    $.ajax({  
      url:"asgard/sale_order.php",
      method:"POST",  
      data:{
        freightcharge:freightcharge,
        packingandforwardingcharge:packingandforwardingcharge,
        grand_total:grand_total,
        do_plus_minus_on_sale:1
      },  
      // dataType: `JSON`,
      success:function(data){ 
        $('#stc_final_sale_value').html(data);
      }  
    });     
  });

  // save sale order to db
  $(document).on('click', '.stcsavesaleorder', function(e){
    e.preventDefault();
    var customer_id = $('#stc_customer_sale_product').val();
    var order_invodate = $('.saleordercustorderdate').val();
    var order_invonumber = $('.saleordercustordernumber').val();
    var order_waybillno = $('.saleorderwaybillno').val();
    var order_lrno = $('.saleorderlrno').val();
    var order_supplydate = $('.saleorderdatesupply').val();
    var order_supplyplace = $('.saleorderplacesupply').val();
    var order_refrence = $('.saleorderordrefrence').val();
    var order_sitename = $('.saleordercustsitename').val();
    var order_contperson = $('.saleordercustcontperson').val();
    var order_contnumber = $('.saleordercustcontnumber').val();
    var order_shipaddress = $('#stcsoshipaddress').val();
    var order_stcfc = $('.stcfc').val();
    var order_stcpf = $('.stcpf').val();
    var order_notes = $('#stcpdnotes').val();
    var order_tandc = $('#stcsotandc').val();       
    $.ajax({  
      url:"asgard/sale_order.php",
      method:"POST",  
      data:{
        customer_id:customer_id,
        sale_custorderdate:order_invodate,
        sale_custordernumber:order_invonumber,
        sale_waybillno:order_waybillno,
        sale_lrno:order_lrno,
        sale_supplydate:order_supplydate,
        sale_supplyplace:order_supplyplace,
        sale_refrence:order_refrence,
        sale_sitename:order_sitename,
        sale_contperson:order_contperson,
        sale_contnumber:order_contnumber,
        sale_shipaddress:order_shipaddress,
        sale_stcfc:order_stcfc,
        sale_stcpf:order_stcpf,
        order_notes:order_notes,
        order_tandc:order_tandc,
        save_sale_action:1
      },
      // dataType: `JSON`,
      success:function(data){
       console.log(data);
        alert(data);
        $('.stc-add-sale-product-form')[0].reset();
        // window.location.reload();
        $('.stc-add').fadeOut(500);
        $('.stc-view').toggle(1000);
        sale_order_cart();
      }
    });
  });

  // edit challan search items
  $('#searchbystceditchallanpdname').on('keyup input', function(){
    search_po_pd_name=$(this).val();
    $.ajax({
      url : "asgard/sale_order.php",
      method : "post",
      data : {search_edit_sale_pd_name_in:search_po_pd_name},
      dataType : 'JSON',
      success : function(data){
        // console.log(data);
        // alert(data);
        $('.stc-call-view-sale-product-row').html(data);
         // $(this)[0].reset();
      }
    });
  });

  // edit add challan items 
  $('body').delegate('.add_to_cartforeditchallan','click',function(e){
    e.preventDefault();
    $.urlParam = function(name){
    var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
      return results[1] || 0;
    }
    $.urlParam('cid');
    var product_id = $(this).attr("id");  
    var product_soid = $.urlParam('cid');  
    var product_quantity = $('#stceditchallanqty'+product_id).val();  
    var product_price = $('#stceditchallanprice'+product_id).val(); 
    var product_price_perc = $('#stcpdchallanperc'+product_id).val();    
    var add_to_cartforeditchallan="edit";  
    $.ajax({  
      url:"asgard/sale_order.php",  
      method:"POST",
      data:{  
          product_id:product_id,
          product_soid:product_soid,
          product_quantity:product_quantity,
          product_price:product_price,
          product_price_perc:product_price_perc,
          edit_challan:add_to_cartforeditchallan  
      },  
      success:function(data){
        // show_editpocart();
        alert(data);
        // console.log(data);
        // window.location.reload();                          
      }  
    });
  });

  // update quantity
  $('body').delegate('.stcchangechallanqty', 'click', function(e){
    e.preventDefault();
    var challanid=$(this).attr("id");
    var product_qty = $('.stcqtyechallani'+challanid).val();
    var inward_qty = $('.stcdelqtyechallani'+challanid).val();
    $.ajax({
      url : "asgard/sale_order.php",
      method : "post",
      data : {
        updatechallanqty:1,
        challanid:challanid,
        inward_qty:inward_qty,
        product_qty:product_qty
      },
      // dataType : 'JSON',
      success : function(data){
        // console.log(data);
        alert(data);
        window.location.reload();
      }
    });
  });

  // update price
  $('body').delegate('.stcchangechallanprice', 'click', function(e){
    e.preventDefault();
    var soid=$(this).attr("id");
    var product_price = $('.stcpriceechalani'+soid).val();
    $.ajax({
      url : "asgard/sale_order.php",
      method : "post",
      data : {
        updatechallanprice:1,
        soid:soid,
        product_price:product_price
      },
      dataType : 'JSON',
      success : function(data){
        // console.log(data);
        alert(data);
        window.location.reload();
      }
    });
  });

  // delete items
  $('body').delegate('.deltochallanitems', 'click', function(e){
    e.preventDefault();
    var soid=$(this).attr("id");
    var delqty=$('.stcdelqtyechallani'+soid).val();
    $.ajax({
      url : "asgard/sale_order.php",
      method : "post",
      data : {
        delchallanli:1,
        delqty:delqty,
        soid:soid
      },
      // dataType : 'JSON',
      success : function(data){
        // console.log(data);
        alert(data);
        window.location.reload();
      }
    });
  });

  // save challan
  $('body').delegate('.stcsaveeditsaleorder', 'click', function(e){
    e.preventDefault();
    $.urlParam = function(name){
    var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
      return results[1] || 0;
    }
    $.urlParam('cid');

    var esono               =         $.urlParam('cid');
    var esomerchantid       =         $('#stc_customer_edit_sale_product').val();
    var esocustorderno      =         $('#stceditcustorderno').val();
    var esowaybillno        =         $('#stceditsaleorderwaybillno').val();
    var esowaylrno          =         $('#stceditsaleorderlrno').val();
    var esocustorddate      =         $('#stceditsaleordercustorderdate').val();
    var esodosupply         =         $('#stceditsaleorderdatesupply').val();
    var esoposupply         =         $('#stceditsaleorderplacesupply').val();
    var esoorderrefrence    =         $('#stceditsaleorderfrence').val();
    var esocustsitename     =         $('#stceditsaleordercustsitename').val();
    var esocustcontperson   =         $('#stceditsaleordercustcontperson').val();
    var esocustcontnumber   =         $('#stceditsaleordercustcontnumber').val();
    var esoshipaddres       =         $('#stceditsoshipaddress').val();
    var esotermanc          =         $('#stceditsotandc').val();
    var esontes             =         $('#stceditsonotes').val();
    $.ajax({  
      url:"asgard/sale_order.php",  
      method:"POST",
      data:{  
        esono               :   esono,
        esomerchantid       :   esomerchantid,
        esocustorderno      :   esocustorderno,
        esowaybillno        :   esowaybillno,
        esowaylrno          :   esowaylrno,
        esocustorddate      :   esocustorddate,
        esodosupply         :   esodosupply,
        esoposupply         :   esoposupply,
        esoorderrefrence    :   esoorderrefrence,
        esocustsitename     :   esocustsitename,
        esocustcontperson   :   esocustcontperson,
        esocustcontnumber   :   esocustcontnumber,
        esoshipaddres       :   esoshipaddres,
        esotermanc          :   esotermanc,
        esontes             :   esontes,
        edit_save_challan   :   1
      },  
      success:function(data){
        alert(data);
        // console.log(data);    
        window.location.reload();                          
      }  
    });    
  });

  $('body').delegate('.searchbycpdnamehit', 'click', function(e){
    search_ch_pd_name=$('#stcfilterbypdnamec').val();
    $.ajax({
      url : "asgard/sale_order.php",
      method : "post",
      data : {search_ch_pd_name_in_ch:search_ch_pd_name},
      dataType : 'JSON',
      success : function(data){
        // console.log(data);
        $('.stc-view-Sale-order-form').html(data);
         // $(this)[0].reset();
      }
    });
  });
});

/*-------------------------------------------------------------------------*/
/*----------------------------Direct Challan-------------------------------*/
/*-------------------------------------------------------------------------*/

$(document).ready(function(){

  // direct challan
  stc_call_direct_challan(begdateon, enddateon);
  function stc_call_direct_challan(begdateon, enddateon){
    var begdate=begdateon;
    var enddate=enddateon; 
    $.ajax({
      url:"asgard/exp_dir_challan.php",
      method:'POST',
      data:{
        stccalldc:1,
        begdate:begdate,
        enddate:enddate
      },
      dataType: 'JSON',
      success:function(data){
        // console.log(data);
          $('.stc-view-Direct-Challan-form').html(data);
      }
    });     
  }

  $(document).on('click', '#dirchallandatefilt', function(e){
    e.preventDefault();
    var begdate=$('.begdate').val();
    var enddate=$('.enddate').val(); 
    stc_call_direct_challan(begdate, enddate);
  });

  // show direct challan items sesson cart
  direct_challan_cart();
  function direct_challan_cart(){
    $.ajax({
      url:"asgard/exp_dir_challan.php",
      method:'POST',
      data:{stc_show_sale_sess:1},
      dataType: 'JSON',
      success:function(data){
        // console.log(data);
          $('.saleflase').html(data["orderTable"]);
      }
    });
  }

  // add cart to direct challan session
  $('body').delegate('.add_to_direct_cart','click',function(e){
    e.preventDefault();
    var product_id = $(this).attr("id");  
    var product_name = $('#stcpopdname'+product_id).val(); 
    var product_unit = $('#stcpopdunit'+product_id).val();  
    var product_quantity = $('#stcpoqty'+product_id).val();  
    var product_price = $('#stcpdprice'+product_id).val();
    var product_saleperc = $('#stcpdsaleperc'+product_id).val();
    var product_hsncode = $('#stcpopdhsncode'+product_id).val();
    var product_gst = $('#stcpopdgst'+product_id).val();
    var add_to_sale_cart="addsalecart";
    $.ajax({  
      url:"asgard/exp_dir_challan.php",  
      method:"POST",
      data:{  
          product_id:product_id,
          product_name:product_name,  
          product_unit:product_unit,
          product_quantity:product_quantity,
          product_price:product_price,
          product_sale_percent:product_saleperc,
          product_hsncode:product_hsncode,
          product_gst:product_gst,
          add_dc:add_to_sale_cart  
      },  
      success:function(data){
        direct_challan_cart();
        alert(data);
        // console.log(data);                              
      }  
    });      
  });

  // quantity change direct challan session
  $(document).on('click', '.stcqtydc', function(e){  
    e.preventDefault();
      var product_id = $(this).data("product_id");  
      var quantity = $('#stcqtypot'+product_id).val();   
      // var change_cart = $('#cartprice'+product_id).val();
      var action = "quantity_change"; 
      if(quantity != ''){  
        $.ajax({  
             url:"asgard/exp_dir_challan.php",
             method:"POST",  
             data:{
                product_id:product_id, 
                quantity:quantity, 
                sale_quantity_action:action},  
             success:function(data){ 
              direct_challan_cart();
              // console.log(data);
             }  
        });  
      } 
  });  

  // Rate change direct challan session
  $(document).on('click', '.stcratedc', function(e){  
    e.preventDefault();
      var product_id = $(this).data("product_id");  
      var price = $('#stcratepot'+product_id).val();   
      // var change_cart = $('#cartprice'+product_id).val();
      var action = "rate_change"; 
      if(price != ''){  
      $.ajax({  
        url:"asgard/exp_dir_challan.php",
        method:"POST",  
        data:{
            product_id:product_id, 
            price:price, 
            stc_price_action:action},  
        success:function(data){ 
         direct_challan_cart();
         // console.log(data);
        }  
      });  
      }
  });  

  // delete from cart purchase product session
  $('body').delegate('.stcdeldcbtn','click',function(e){
    e.preventDefault();
    var product_id = $(this).attr("id");
      if(confirm("Are you sure you want to remove this product?")){   
        $.ajax({  
          url:"asgard/exp_dir_challan.php",  
          method:"POST",
          data:{  
              product_id:product_id,
              stcdelsalelinei:1  
          },  
          success:function(data){  
            direct_challan_cart();
            alert(data);                        
          }  
        });  
      }         
  });

  // calculate charges to sale order
  $(document).on('click', '.stcdcc', function(e){  
    e.preventDefault();
    var freightcharge=$('.stcfc').val();
    var packingandforwardingcharge=$('.stcpf').val();
    var grand_total=$('#stc_grand_offset_table_value').val();
    $.ajax({  
      url:"asgard/exp_dir_challan.php",
      method:"POST",  
      data:{
        freightcharge:freightcharge,
        packingandforwardingcharge:packingandforwardingcharge,
        grand_total:grand_total,
        do_plus_minus_on_sale:1
      },  
      // dataType: `JSON`,
      success:function(data){ 
        $('#stc_final_sale_value').html(data);
      }  
    });     
  });

  // save sale order to db
  $(document).on('click', '.stcsavedirectchalan', function(e){
    e.preventDefault();
    var customer_id           =       $('#stc_customer_sale_product').val();
    var order_invodate        =       $('.saleordercustorderdate').val();
    var order_invonumber      =       $('.saleordercustordernumber').val();
    var order_mername         =       $('#stceditsale_dc_merchant_name').val();
    var order_billinvono      =       $('.stceditsalebillinvonumber').val();
    var order_billinvodate    =       $('.stceditsalebillinvodate').val();
    var order_waybillno       =       $('.saleorderwaybillno').val();
    var order_lrno            =       $('.saleorderlrno').val();
    var order_supplydate      =       $('.saleorderdatesupply').val();
    var order_supplyplace     =       $('.saleorderplacesupply').val();
    var order_refrence        =       $('.saleorderordrefrence').val();
    var order_sitename        =       $('.saleordercustsitename').val();
    var order_contperson      =       $('.saleordercustcontperson').val();
    var order_contnumber      =       $('.saleordercustcontnumber').val();
    var order_shipaddress     =       $('#stcsoshipaddress').val();
    var order_stcfc           =       $('.stcfc').val();
    var order_stcpf           =       $('.stcpf').val();
    var order_notes           =       $('#stcpdnotes').val();
    var order_tandc           =       $('#stcsotandc').val();     
    $.ajax({  
      url:"asgard/exp_dir_challan.php",
      method:"POST",  
      data:{
        customer_id             :     customer_id,
        sale_custorderdate      :     order_invodate,
        sale_custordernumber    :     order_invonumber,
        sale_mername            :     order_mername,
        sale_billinvono         :     order_billinvono,
        sale_billinvodate       :     order_billinvodate,
        sale_waybillno          :     order_waybillno,
        sale_lrno               :     order_lrno,
        sale_supplydate         :     order_supplydate,
        sale_supplyplace        :     order_supplyplace,
        sale_refrence           :     order_refrence,
        sale_sitename           :     order_sitename,
        sale_contperson         :     order_contperson,
        sale_contnumber         :     order_contnumber,
        sale_shipaddress        :     order_shipaddress,
        sale_stcfc              :     order_stcfc,
        sale_stcpf              :     order_stcpf,
        order_notes             :     order_notes,
        order_tandc             :     order_tandc,
        save_dc_action          :     1
      },
      // dataType: `JSON`,
      success:function(data){
       console.log(data);
        alert(data);
        $('.stc-add-direct-challan-form')[0].reset();
        // window.location.reload();
        direct_challan_cart();
        stc_call_direct_challan();
        $('.stc-add').fadeOut(500);
        $('.stc-view').toggle(1000);
      }
    });
  });

  // edit add challan items 
  $('body').delegate('.add_to_cartforeditdirectchallan','click',function(e){
    e.preventDefault();
    $.urlParam = function(name){
    var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
      return results[1] || 0;
    }
    $.urlParam('dcid');
    var product_id = $(this).attr("id");  
    var product_soid = $.urlParam('dcid');  
    var product_quantity = $('#stceditchallanqty'+product_id).val();  
    var product_price = $('#stceditchallanprice'+product_id).val(); 
    var product_price_perc = $('#stcpdchallanperc'+product_id).val();    
    var add_to_cartforeditdirectchallan="edit";  
    $.ajax({  
      url:"asgard/exp_dir_challan.php",  
      method:"POST",
      data:{  
          product_id:product_id,
          product_soid:product_soid,
          product_quantity:product_quantity,
          product_price:product_price,
          product_price_perc:product_price_perc,
          edit_direct_challan:add_to_cartforeditdirectchallan  
      },  
      success:function(data){
        // show_editpocart();
        alert(data);
        // console.log(data);
        // window.location.reload();                          
      }  
    });
  });

  // update quantity
  $('body').delegate('.stcchangedchallanqty', 'click', function(e){
    e.preventDefault();
    var challanid=$(this).attr("id");
    var product_qty = $('.stcqtyechallani'+challanid).val();
    var inward_qty = $('.stcdelqtyechallani'+challanid).val();
    $.ajax({
      url : "asgard/exp_dir_challan.php",
      method : "post",
      data : {
        updatedirectchallanqty:1,
        challanid:challanid,
        inward_qty:inward_qty,
        product_qty:product_qty
      },
      // dataType : 'JSON',
      success : function(data){
        // console.log(data);
        alert(data);
        window.location.reload();
      }
    });
  });

  // update price
  $('body').delegate('.stcchangedchallanprice', 'click', function(e){
    e.preventDefault();
    var soid=$(this).attr("id");
    var product_price = $('.stcpriceechalani'+soid).val();
    $.ajax({
      url : "asgard/exp_dir_challan.php",
      method : "post",
      data : {
        updatediectchallanprice:1,
        soid:soid,
        product_price:product_price
      },
      dataType : 'JSON',
      success : function(data){
        // console.log(data);
        alert(data);
        window.location.reload();
      }
    });
  });

  // update purchase price
  $('body').delegate('.stcchangedchallanpurprice', 'click', function(e){
    e.preventDefault();
    var soid=$(this).attr("id");
    var product_price = $('.stcpurpriceechalani'+soid).val();
    $.ajax({
      url : "asgard/exp_dir_challan.php",
      method : "post",
      data : {
        updatediectchallanpurprice:1,
        soid:soid,
        product_price:product_price
      },
      dataType : 'JSON',
      success : function(data){
        // console.log(data);
        alert(data);
        window.location.reload();
      }
    });
  });

  // delete items
  $('body').delegate('.deltodchallanitems', 'click', function(e){
    e.preventDefault();
    var soid=$(this).attr("id");
    var delqty=$('.stcdelqtyechallani'+soid).val();
    $.ajax({
      url : "asgard/exp_dir_challan.php",
      method : "post",
      data : {
        deldchallanli:1,
        delqty:delqty,
        soid:soid
      },
      // dataType : 'JSON',
      success : function(data){
        // console.log(data);
        alert(data);
        window.location.reload();
      }
    });
  });

  // save challan
  $('body').delegate('.stcsaveeditdirectchallan', 'click', function(e){
    e.preventDefault();
    $.urlParam = function(name){
    var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
      return results[1] || 0;
    }
    $.urlParam('dcid');

    var esono               =         $.urlParam('dcid');
    var esomerchantid       =         $('#stc_customer_edit_sale_product').val();
    var esocustorderno      =         $('#stceditcustorderno').val();
    var esomername          =         $('#stceditsale_dc_merchant_name').val();
    var esobillinvono       =         $('.stceditsalebillinvonumber').val();
    var esobillinvodate     =         $('.stceditsalebillinvodate').val();
    var esowaybillno        =         $('#stceditsaleorderwaybillno').val();
    var esowaylrno          =         $('#stceditsaleorderlrno').val();
    var esocustorddate      =         $('#stceditsaleordercustorderdate').val();
    var esodosupply         =         $('#stceditsaleorderdatesupply').val();
    var esoposupply         =         $('#stceditsaleorderplacesupply').val();
    var esoorderrefrence    =         $('#stceditsaleorderfrence').val();
    var esocustsitename     =         $('#stceditsaleordercustsitename').val();
    var esocustcontperson   =         $('#stceditsaleordercustcontperson').val();
    var esocustcontnumber   =         $('#stceditsaleordercustcontnumber').val();
    var esoshipaddres       =         $('#stceditsoshipaddress').val();
    var esotermanc          =         $('#stceditsotandc').val();
    var esontes             =         $('#stceditsonotes').val();
    $.ajax({  
      url:"asgard/exp_dir_challan.php",  
      method:"POST",
      data:{  
        esono                       :   esono,
        esomerchantid               :   esomerchantid,
        esocustorderno              :   esocustorderno, 
        esomername                  :   esomername,  
        esobillinvono               :   esobillinvono,  
        esobillinvodate             :   esobillinvodate,
        esowaybillno                :   esowaybillno,
        esowaylrno                  :   esowaylrno,
        esocustorddate              :   esocustorddate,
        esodosupply                 :   esodosupply,
        esoposupply                 :   esoposupply,
        esoorderrefrence            :   esoorderrefrence,
        esocustsitename             :   esocustsitename,
        esocustcontperson           :   esocustcontperson,
        esocustcontnumber           :   esocustcontnumber,
        esoshipaddres               :   esoshipaddres,
        esotermanc                  :   esotermanc,
        esontes                     :   esontes,
        edit_save_direct_challan    :   1
      },  
      success:function(data){
        alert(data);
        // console.log(data);    
        window.location.reload();                          
      }  
    });    
  });

  $('body').delegate('.stcsearchdchit', 'click', function(){
    search_ch_pd_name=$('#stcfilterbypdnamedc').val();
    $.ajax({
      url : "asgard/exp_dir_challan.php",
      method : "post",
      data : {search_ch_pd_name_in_ch:search_ch_pd_name},
      dataType : 'JSON',
      success : function(data){
        // console.log(data);
        $('.stc-view-Direct-Challan-form').html(data);
         // $(this)[0].reset();
      }
    });
  });
});

/*-------------------------------------------------------------------------*/
/*-------------------------------Sale Order--------------------------------*/
/*-------------------------------------------------------------------------*/

$(document).ready(function(){
  // load invoices
  
  function load_invoices(){
    $.ajax({
      url      : 'asgard/set_invoices.php',
      method   : 'post',
      data     : {load_invoices:1},
      dataType : 'JSON',
      success  : function(invoices){
        // console.log(invoices);
        $('.stc-view-Sale-order-invoice-form').html(invoices);
      }
    })
  }

  // load invoices session
  show_invo_cart();
  function show_invo_cart() {
    $.ajax({  
      url:"asgard/set_invoices.php",  
      method:"POST",
      data:{  
          comon_invo_sess:1  
      },  
      // dataType: 'JSON',
      success:function(data){
        // console.log(data);    
        // $('.invoflase').html(data['invoTable']); 
        $('.invoflase').html(data);                    
      }  
    });
  }

  // load challan on change cust
  $('#stc_customer_sale_product_invoice').on('change', function(e){
    e.preventDefault();
    var js_cust_id=$(this).val();
    // alert(js_cust_id);
    $.ajax({
      url : "asgard/set_invoices.php",
      method : "post",
      data : {
        call_site_on_choose_customer:1,
        customer_id:js_cust_id
      },
      // dataType : 'JSON',
      success : function(data){
        // console.log(data);
        $('.stc_customer_sale_site_refr').html(data);
        // $('.stc-call-view-invoice-sale-product-row').html(data);
      }
    });
  });

  // load challan on change site
  $('#stc_customer_sale_site_refr').on('change', function(e){
    e.preventDefault();
    var js_sitename=$(this).val();
    var js_custid=$('#stc_customer_sale_product_invoice').val();
    // alert(js_cust_id);
    $.ajax({
      url : "asgard/set_invoices.php",
      method : "post",
      data : {
        call_challan_on_choose_customer_site:1,
        js_sitename:js_sitename,
        js_custid:js_custid
      },
      // dataType : 'JSON',
      success : function(data){
        // console.log(data);
        $('.stc-call-view-invoice-sale-product-row').html(data);
      }
    });
  });

  // add to invoices session
  $(document).on('click', '.add_to_invo_cart', function(e){  
    e.preventDefault();
    var challan_id = $(this).attr("id");
    $.ajax({  
      url:"asgard/set_invoices.php",
      method:"POST",  
      data:{
        invo_challan_id:challan_id,
        add_invo_sess_action:1
      },  
      // dataType: `JSON`,
      success:function(data){ 
       // console.log(data);
       alert(data);
        show_invo_cart();
      }  
    });  
  }); 

  // delete from invoices session
  $('body').delegate('.stcdelinvobtn','click',function(e){
    e.preventDefault();
    var product_id = $(this).attr("id");
      if(confirm("Are you sure you want to remove this product?")){   
        $.ajax({  
          url:"asgard/set_invoices.php",  
          method:"POST",
          data:{  
              product_id:product_id,
              stcdelinvolinei:1  
          },  
          success:function(data){  
            show_invo_cart();
            alert(data);                        
          }  
        });  
      }         
  });

  // save invoice
  $(document).on('click', '.stcsaveinvo', function(e){
    e.preventDefault();
    var stcinvodate=$('.stcinvodate').val();    
    var cust_id=$('#stc_customer_sale_product_invoice').val();
    var stcinvonotes=$('#stcinvonotes').val();
    $.ajax({  
      url:"asgard/set_invoices.php",
      method:"POST",  
      data:{
        stcinvodate:stcinvodate,
        invo_cust_id:cust_id,
        stcinvonotes:stcinvonotes,
        save_invo_action:1
      },  
      // dataType: `JSON`,
      success:function(data){ 
        // console.log(data);
        // window.location.reload(500);
          alert(data);
          load_invoices();
          show_invo_cart();
          $('.new-sale-order-create-response').fadeOut(1000);
          $('.new-sale-order-show-response').toggle(500);
          $('.stc-add-sale-product-form')[0].reset();
      }  
    });
  });

  // load additional invoices
  load_additional_invoices();
  function load_additional_invoices(){
    $.ajax({
      url      : 'asgard/set_invoices.php',
      method   : 'post',
      data     : {load_additional_invoices:1},
      dataType : 'JSON',
      success  : function(invoices){
        // console.log(invoices);
        $('.stc-additional-sale-order-response-call-form').html(invoices['bills_all']);
        $('.grand_totaladd_invo').html(invoices['value_total']);
      }
    })
  }

  // additional invoice call customer
  $('#stc_customer_addiional_sale_order_invo').on('change', function(e){
    e.preventDefault();
    var js_cust_id=$(this).val();
    // alert(js_cust_id);
    $.ajax({
      url : "asgard/set_invoices.php",
      method : "post",
      data : {
        call_challan_on_choose_customer_addinvo:1,
        customer_id:js_cust_id
      },
      // dataType : 'JSON',
      success : function(data){
        // console.log(data);
        $('.stc-call-view-add-invoice-sale-product-row').html(data);
      }
    });
  });

  // load invoices session
  show_additional_invo_cart();
  function show_additional_invo_cart() {
    $.ajax({  
      url:"asgard/set_invoices.php",  
      method:"POST",
      data:{  
          comon_additional_invo_sess:1  
      },  
      // dataType: 'JSON',
      success:function(data){
        // console.log(data);    
        // $('.invoflase').html(data['invoTable']); 
        $('.additionalinvoflase').html(data);                    
      }  
    });
  }

  // additional invoice add to invoices session
  $(document).on('click', '.add_to_add_invo_cart', function(e){  
    e.preventDefault();
    var challan_id = $(this).attr("id");
    $.ajax({  
      url:"asgard/set_invoices.php",
      method:"POST",  
      data:{
        invo_challan_id:challan_id,
        add_add_invo_sess_action:1
      },  
      // dataType: `JSON`,
      success:function(data){ 
       // console.log(data);
       alert(data);
        show_additional_invo_cart();
      }  
    });  
  }); 

  // delete from invoices session
  $('body').delegate('.stcdeladdinvobtn','click',function(e){
    e.preventDefault();
    var product_id = $(this).attr("id");
      if(confirm("Are you sure you want to remove this product?")){   
        $.ajax({  
          url:"asgard/set_invoices.php",  
          method:"POST",
          data:{  
              product_id:product_id,
              stcdeladdinvolinei:1  
          },  
          success:function(data){  
            show_additional_invo_cart();
            alert(data);                        
          }  
        });  
      }         
  });

 
  $(document).on('click', '.stcsaveaddinvo', function(e){
    e.preventDefault();
    var stcinvodate=$('.stcaddinvodate').val();    
    var cust_id=$('.stc_customer_sale_product_add_invoice').val();
    var stcinvonotes=$('#stcaddinvonotes').val();
    // if(cust_id = "NA"){
    //   alert("Please Select Customer First!!!");
    // }else{
      $.ajax({  
        url:"asgard/set_invoices.php",
        method:"POST",  
        data:{
          stcinvodate:stcinvodate,
          invo_cust_id:cust_id,
          stcinvonotes:stcinvonotes,
          save_add_invo_action:1
        },  
        // dataType: `JSON`,
        success:function(data){ 
          // console.log(data);
          // window.location.reload(500);
            alert(data);
            load_additional_invoices();
            show_invo_cart();
            $('.additional-sale-order-create-response').fadeOut(1000);
            $('.additional-sale-order-show-response').toggle(500);
            // $('.stc-add-sale-product-form')[0].reset();
        }  
      });
    // }
  });
});

/*-------------------------------------------------------------------------*/
/*---------------------------Customer Payment------------------------------*/
/*-------------------------------------------------------------------------*/

$(document).ready(function(){
  // call po on change merchant in advance payment section 
  $('#stccust').on('change', function(e){
    e.preventDefault();
    var js_customer_id=$(this).val();
    // alert(js_customer_id);
    $.ajax({
      url : "asgard/payment_process.php",
      method : "post",
      data : {
        call_so_on_choose_cust:1,
        customer_id:js_customer_id
      },
      dataType : 'JSON',
      success : function(data){
        // console.log(data);
        $('#custsoshow').html(data);
      }
    });
  });

  // call date and due amount on po change  
  $('#custsoshow').on('change', function(e){
    e.preventDefault();
    var js_so_number=$(this).val();
    $.ajax({
      url : "asgard/payment_process.php",
      method : "post",
      data : {
        call_so_date_on_choose_customer:1,
        so_number:js_so_number
      },
      dataType : 'JSON',
      success : function(data){
        // console.log(data);
        $('.custsodateshow').val(data['order_date']);
        $('#sodueamountshow').val(data['due_amount']);
      }
    });
  });
  
  // on advance payment in db
  $('.stcsavecustp').on('click', function(e){
    e.preventDefault();
    var js_cust=$('#stccust').val();
    var js_invo=$('#custsoshow').val();
    var js_cattype=$('#stcsocatpaymenttype').val();
    var js_type=$('#stcsopaymenttype').val();
    var js_value=$('#stcsoamount').val();
    var js_notes=$('#stcsotandc').val();
    // alert(js_notes);
    $.ajax({
      url : "asgard/payment_process.php",
      method : "post",
      data : {
        cust:js_cust,
        invo:js_invo,
        cattype:js_cattype,
        type:js_type,
        value:js_value,
        notes:js_notes,
        on_cust_pay_hit:1
      },
      dataType : 'JSON',
      success : function (argument) {
        // console.log(argument);
          alert(argument);
        // $('.stc-add-advance-po-payment-form')[0].reset();
        // call_payments();
      }
    });
  });
});

/*-------------------------------------------------------------------------*/
/*--------------------------dc merchant Payment----------------------------*/
/*-------------------------------------------------------------------------*/

$(document).ready(function(){
  // call so on change merchant in advance payment section 
  $('#stcdcmer').on('change', function(e){
    e.preventDefault();
    var js_merchant_id=$(this).val();
    // alert(js_merchant_id);
    $.ajax({
      url : "asgard/payment_process.php",
      method : "post",
      data : {
        call_dc_on_choose_mer:1,
        dc_merchant_id:js_merchant_id
      },
      dataType : 'JSON',
      success : function(data){
        // console.log(data);
        $('#invonodcshow').html(data);
      }
    });
  });

  // call date and due amount on po change  
  $('#invonodcshow').on('change', function(e){
    e.preventDefault();
    var js_dc_number=$(this).val();
    $.ajax({
      url : "asgard/payment_process.php",
      method : "post",
      data : {
        call_dc_date_on_choose_merchant:1,
        dc_number:js_dc_number
      },
      dataType : 'JSON',
      success : function(data){
        // console.log(data);
        $('.invodatedcshow').val(data['order_date']);
        $('#dcdueamountshow').val(data['due_amount']);
      }
    });
  });
  
  // // on advance payment in db
  $('.stcsavedcmerp').on('click', function(e){
    e.preventDefault();
    var js_mer=$('#stcdcmer').val();
    var js_invo=$('#invonodcshow').val();
    var js_type=$('#stcdcpaymenttype').val();
    var js_value=$('#stcdcamount').val();
    var js_notes=$('#stcdctandc').val();
    $.ajax({
      url : "asgard/payment_process.php",
      method : "post",
      data : {
        mer:js_mer,
        invo:js_invo,
        type:js_type,
        value:js_value,
        notes:js_notes,
        on_dc_mer_pay_hit:1
      },
      dataType : 'JSON',
      success : function (argument) {
        // console.log(argument);
          alert(argument);
          $('.stc-add-dcmerchant-po-payment-form')[0].reset();
          // window.location.reload();
      }
    });
  });
});