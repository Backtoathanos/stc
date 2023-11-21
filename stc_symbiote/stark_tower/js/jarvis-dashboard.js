$(document).ready(function(){
	var d = new Date($.now());
	
	stc_product_reload();
	function stc_product_reload(){
		$.ajax({
			url : "asgard/backtodashboard.php",
			method : "post",
			data : {dashboard:1},
			dataType : 'JSON',
			success : function(data){
				// console.log(data);
				$('.toproducts').html(data[0]);
				$('.toinventory').html(data[1]);
				$('.tomerchants').html(data[2]);
				$('.tocustomers').html(data[3]);
				$('.topurchased').html(data[4]);
				$('.tosoled').html(data[5]);
				$('.tomerpaid').html(data[6]);
				$('.tocustpaid').html(data[7]);
			}
		});
	}

	stc_purchasedCharts();
	function stc_purchasedCharts() {
	    $.ajax({
			url : "asgard/backtodashboard.php",
	        type: 'POST',
			data : {dashboardpurchasedcharts:1},
			// dataType : 'JSON',
	        success: function(data) {
	            // console.log(data);
	            chartData = data;
	            var chartProperties = {
	                "caption": "Yearly Purchased Bar "+d.getFullYear(),
	                "xAxisName": "Months",
	                "yAxisName": "Purchased Amounts",
	                "rotatevalues": "1",
	                "theme": "zune"
	            };

	            apiChart = new FusionCharts({
	                type: 'column2d',
	                renderAt: 'purchased-container',
	                width: '650',
	                height: '350',
	                dataFormat: 'json',
	                dataSource: {
	                    "chart": chartProperties,
	                    "data": chartData
	                }
	            });
	            apiChart.render();
	        }
	    });
	};

	stc_soleddCharts();
	function stc_soleddCharts() {
	    $.ajax({
			url : "asgard/backtodashboard.php",
	        type: 'POST',
			data : {dashboardsoledcharts:1},
			// dataType : 'JSON',
	        success: function(data) {
	            // console.log(data);
	            chartData = data;
	            var chartProperties = {
	                "caption": "Yearly Soled Bar "+d.getFullYear(),
	                "xAxisName": "Months",
	                "yAxisName": "Soled Amounts",
	                "rotatevalues": "1",
	                "theme": "zune"
	            };

	            apiChart = new FusionCharts({
	                type: 'column2d',
	                renderAt: 'soled-container',
	                width: '650',
	                height: '350',
	                dataFormat: 'json',
	                dataSource: {
	                    "chart": chartProperties,
	                    "data": chartData
	                }
	            });
	            apiChart.render();
	        }
	    });
	};

	// check login
	// $('.stc-login-form').on('submit', function(e){
	// 	e.preventDefault();
	// 	$.ajax({
	// 		url : "asgard/stcusercheck.php",
	// 		method : "post",
	// 		data : new FormData(this),
	// 		contentType : false,
	// 		processData : false,
	// 		// dataType : 'JSON',
	// 		success : function (argument) {
	// 			// console.log(argument);
	// 			if(argument="success"){
	// 				window.location.href="dashboard.php"
	// 			}else{
	// 				alert(argument);
	// 			}
	// 			// $('#showcase').html(argument);
	// 			// $("#showcase").show().delay(3000).fadeOut();
	// 			$('.stc-login-form')[0].reset();
	// 		}
	// 	});		
	// });


	

	
});
