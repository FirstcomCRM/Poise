$( document ).ready(function() {

	//updateTable(detail_arr);
	//updatePDTable(payment_detail_arr);

	
	
	

    /** 
     * Btn submit
     */
    $('#btn-submit').click(function(e) {   
      e.preventDefault();
      if( $('#quotation-id').val() == '' ) {
        alert("Please Select Quotation");
      }
      else if( $('#receipt-date').val() == '' ) {
        alert("Please Select Date");
      }
      else {
        $.ajax({
          type: "POST",
          url: burl + "receipt/edit/" + $('#hid-receipt-id').val(), 
          beforeSend : function( xhr ) {
            $('#btn-submit').html('<i class="fa fa-spinner ico-btn"> Processing...').prop('disabled', true);
          },
          data: { 
				hid_submitted       		: $('#hid-submitted').val(),
				receipt_no          		: $('#receipt-no').val(),
				receipt_date        		: $('#receipt-date').val(),
				transact_id      			: $('#transact-id').val(),
				deliver_to         			: $('#deliver-to').val(),
				attention           		: $('#attention').val(),
				address           			: $('#address').val(),
				payment_commission        	: $('#payment-commission').val(),
				user_id           			: $('#user-id').val(),
				sub_total          			: $('#sub-total').val(),
				gst           				: $('#gst').val(),
				cheque           			: $('#cheque').val(),

          },
          success: function(data){  
            $('#btn-submit').html('<i class="fa fa-save ico-btn"> Update').prop('disabled', false);
            var result = $.parseJSON(data);
            if(result['status'] == 'success') {
				//alert($('#transact-id').val());
              window.location = burl + "receipt";
            }
            else {
              var regex = /(<([^>]+)>)/ig;
              result['msg'] = result['msg'].replace(regex, "");
              alert(result['msg']);
            }
          },
          error: function(XMLHttpRequest, textStatus, errorThrown) { 
            alert("ERROR!!!");           
          } 
        });   
      }
    }); 

 
	
	
	
	
	
	
	
	
	
	
	
});