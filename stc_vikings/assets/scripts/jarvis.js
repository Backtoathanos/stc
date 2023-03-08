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