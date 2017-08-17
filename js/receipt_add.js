$( document ).ready(function() {
    

  //  var arr = {};
//	var arr_pd = {};
    var key = 0; 

   
	
	
    /** Edit Site **/
   

    /** 
     * Btn submit
     */
    $('#btn-submit').click(function(e) {   
        e.preventDefault();
        if( $('#receipt-no').val() == '' ) {
            alert("Please Enter Receipt No.");
        }
        else if( $('#receipt-date').val() == '' ) {
            alert("Please Select Date");
        }
        else {
            $.ajax({
                type: "POST",
                url: burl + "receipt/create", 
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
                  $('#btn-submit').html('<i class="fa fa-save ico-btn"> Save').prop('disabled', false);
                  var result = $.parseJSON(data);
                  if(result['status'] == 'success') {
                   // arr = {};
					//arr_pd = {};
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