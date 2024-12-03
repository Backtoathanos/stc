$(document).ready(function(){
  stc_call_ag_order_requi_badges();
  function stc_call_ag_order_requi_badges(){
    $.ajax({
      url       : "kattegat/ragnar_order.php",
      method    : "post",
      data      : {callorderrequisitionbadge:1},
      dataType  : 'JSON',
      success   : function(data){
        // console.log(data);
        $('.stc-notf-badge-for-order-left').html(data['agent_order']);
        $('.stc-notf-badge-for-order-right').html(data['agent_requisition']);
        
      }
    });
  }

	var d = new Date($.now());

	// js call product support data
	stc_product_page_on_call();
	function stc_product_page_on_call(){
		$.ajax({
			url : "kattegat/ragnar_product.php",
			method : "post",
			data : {friday:1},
			dataType : 'JSON',
			success : function(data){
				// console.log(data);
				$('.call_cat').html(data[0]);
				$('.call_sub_cat').html(data[1]);
				$('.call_rack').html(data[2]);
				$('.call_brand').html(data[3]);
        // Get the text from the <span> element
        var unit = $('h5[for="unit"] span').text().trim(); // Fetch "NOS"

        // Match the text to an <option> and select it
        var found = false;
        $('.stcpdunit option').each(function () {
            if ($(this).text().trim().toLowerCase() === unit.toLowerCase()) { // Case-insensitive comparison
                $(this).prop('selected', true); // Select the matching <option>
                found = true;
                return false; // Exit the loop
            }
        });

        
        // Get the text from the <span> element
        var gst = $('h5[for="gst"] span').text().trim(); // Fetch "NOS"
        $('.gst').val(gst);

        // Get the text from the <span> element
        var subcat = $('h5[for="subcategory"] span').text().trim(); // Fetch "NOS"

        // Match the text to an <option> and select it
        var found = false;
        $('.call_sub_cat option').each(function () {
            if ($(this).text().trim().toLowerCase() === subcat.toLowerCase()) { // Case-insensitive comparison
                $(this).prop('selected', true); // Select the matching <option>
                found = true;
                return false; // Exit the loop
            }
        });

        // Get the text from the <span> element
        var category = $('h5[for="category"] span').text().trim(); // Fetch "NOS"

        // Match the text to an <option> and select it
        var found = false;
        $('.call_cat option').each(function () {
            if ($(this).text().trim().toLowerCase() === category.toLowerCase()) { // Case-insensitive comparison
                $(this).prop('selected', true); // Select the matching <option>
                found = true;
                return false; // Exit the loop
            }
        });

        // Get the text from the <span> element
        var brand = $('h5[for="make"] span').text().trim(); // Fetch "NOS"

        // Match the text to an <option> and select it
        var found = false;
        $('.call_brand option').each(function () {
            if ($(this).text().trim().toLowerCase() === brand.toLowerCase()) { // Case-insensitive comparison
                $(this).prop('selected', true); // Select the matching <option>
                found = true;
                return false; // Exit the loop
            }
        });

			}
		});
	}
});

      var hands = [];
      hands.push(document.querySelector('#secondhand > *'));
      hands.push(document.querySelector('#minutehand > *'));
      hands.push(document.querySelector('#hourhand > *'));

      var cx = 100;
      var cy = 100;

      function shifter(val) {
        return [val, cx, cy].join(' ');
      }

      var date = new Date();
      var hoursAngle = 360 * date.getHours() / 12 + date.getMinutes() / 2;
      var minuteAngle = 360 * date.getMinutes() / 60;
      var secAngle = 360 * date.getSeconds() / 60;

      hands[0].setAttribute('from', shifter(secAngle));
      hands[0].setAttribute('to', shifter(secAngle + 360));
      hands[1].setAttribute('from', shifter(minuteAngle));
      hands[1].setAttribute('to', shifter(minuteAngle + 360));
      hands[2].setAttribute('from', shifter(hoursAngle));
      hands[2].setAttribute('to', shifter(hoursAngle + 360));

      for(var i = 1; i <= 12; i++) {
        var el = document.createElementNS('http://www.w3.org/2000/svg', 'line');
        el.setAttribute('x1', '100');
        el.setAttribute('y1', '30');
        el.setAttribute('x2', '100');
        el.setAttribute('y2', '40');
        el.setAttribute('transform', 'rotate(' + (i*360/12) + ' 100 100)');
        el.setAttribute('style', 'stroke: #ffffff;');
        document.querySelector('svg').appendChild(el);
      }