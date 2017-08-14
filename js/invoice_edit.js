	var _validFileExtensions = [".jpg", ".jpeg", ".bmp", ".gif", ".png",".pdf", ".doc", ".docx",".xls",".xlsx",".csv"];
	var _validFileExtensionsImg = [".jpg", ".jpeg", ".bmp", ".gif", ".png"];    
	function ValidateSingleInput(oInput) {
		if (oInput.type == "file") {
			var sFileName = oInput.value;
			 if (sFileName.length > 0) {
				var blnValid = false;
				for (var j = 0; j < _validFileExtensions.length; j++) {
					var sCurExtension = _validFileExtensions[j];
					if (sFileName.substr(sFileName.length - sCurExtension.length, sCurExtension.length).toLowerCase() == sCurExtension.toLowerCase()) {
						blnValid = true;
						break;
					}
				}
				 
				if (!blnValid) {
					alert("Sorry, " + sFileName + " is invalid, allowed extensions are: " + _validFileExtensions.join(", "));
					oInput.value = "";
					return false;
				}
			}
		}
		return true;
	}


$( document ).ready(function() {

	updateTable(detail_arr);
	updatePDTable(payment_detail_arr);

	
	
	$('#btn-clear').click(function(e) { 
    //e.preventDefault();
		$('#chk-new-file-name').val('');
		$('#cheque_select').val('');
	});
	

	
    /** Edit Site **/
    $('#detail-table').on('click', '.edit-di', function(e) {
        e.preventDefault(); 
        var classname = $(this).closest("tr").attr('class');
        var row = classname.split('-');
        $('#hid-edit-id').val(row[1]);
        $.ajax({
          type: "POST",
          url: burl + "invoice/aj_getInvoicedetail/" + row[1], 
          data: { },
          success: function(data){ 
            var result = $.parseJSON(data);
            if(result['status'] == 'success') {
				
			 // $('#no').val(result['no']);
              $('#description').val(result['description']).trigger('autosize.resize');
              $('#qty').val(result['qty']);
            //  $('#uom-id').val(result['uom_id']);
              $('#amount').val(result['amount']);
              $('#detail-add').hide();
              $('#detail-update').show();
              $("#detail-row").insertAfter( $( ".id-" + row[1] ) );   
            }
            else {
              alert(result['msg']);
            }
          },
          error: function(XMLHttpRequest, textStatus, errorThrown) { 
            alert("ERROR!!!");           
          } 
        }); 
    });

	
	
	 $('#payment-detail-table').on('click', '.edit-di-pd', function(e) {
        e.preventDefault(); 
        var classname = $(this).closest("tr").attr('class');
        var row = classname.split('-');
        $('#hid-edit-id-pd').val(row[1]);
		//alert("invoice/aj_getInvoicePaymentdetail/" + row[1]);
        $.ajax({
          type: "POST",
          url: burl + "invoice/aj_getInvoicePaymentdetail/" + row[1], 
          data: { },
          success: function(data){ 
            // console.log(data); 
            var result = $.parseJSON(data);
            if(result['status'] == 'success') {
				

              $('#payment-date').val(result['date']);
              $('#bank-name').val(result['bank_name']);
			  $('#payment-amount').val(result['amount']);
              $('#payment-type').val(result['payment_type']);
              $('#cheque_select').val('');
              $('#chk-new-file-name').val(result['cheque']);
              $('#remarks').val(result['remarks']);
				   
              $('#detail-add-pd').hide();
              $('#detail-update-pd').show();
              $("#detail-row-pd").insertAfter( $( ".id-" + row[1] ) );   
				

            }
            else {
              alert(result['msg']);
            }
          },
          error: function(XMLHttpRequest, textStatus, errorThrown) { 
            alert("ERROR!!!");           
          } 
        }); 
    });
	
	
    /** Delete Site **/
    $('#detail-table').on('click', '.delete-di', function(e) {
        e.preventDefault(); 
        var r = confirm("Are you sure to remove this record!");
        if (r == true) {
          var classname = $(this).closest("tr").attr('class');
          var row = classname.split('-');
          $.ajax({
              type: "POST",
              url: burl + "invoice/aj_deleteInvoicedetail/" + row[1], 
              data: { },
              success: function(data){ 
                console.log(data);
                var result = $.parseJSON(data);
                if(result['status'] == 'success') {
                    $( "#detail-row" ).insertBefore($( "#total-row" ) );
                    updateTable(result['invoice_detail']);
                    resetForm();
                }
                else {
                  alert(result['msg']);
                }
              },
              error: function(XMLHttpRequest, textStatus, errorThrown) { 
                alert("ERROR!!!");           
              } 
          });
        }     
    });

	/** Delete Payment Detail **/
    $('#payment-detail-table').on('click', '.delete-di-pd', function(e) {
        e.preventDefault(); 
        var r = confirm("Are you sure to remove this record!");
        if (r == true) {
          var classname = $(this).closest("tr").attr('class');
          var row = classname.split('-');
          $.ajax({
              type: "POST",
              url: burl + "invoice/aj_deleteInvoicePaymentdetail/" + row[1], 
              data: { },
              success: function(data){ 
                console.log(data);
                var result = $.parseJSON(data);
                if(result['status'] == 'success') {
                    $( "#detail-row-pd" ).insertBefore($( "#total-row-pd" ) );
                    updatePDTable(result['invoice_payment_detail']);
                    resetFormPD();
                }
                else {
                  alert(result['msg']);
                }
              },
              error: function(XMLHttpRequest, textStatus, errorThrown) { 
                alert("ERROR!!!");           
              } 
          });
        }     
    });
	
	
	
	
	
	$('#ico-add').click(function(e) { 
    e.preventDefault();
    if ( $('#description').val() == '') {
      alert("Please Enter Description");
    }
    else {
      $.ajax({
          type: "POST",
          url: burl + "invoice/aj_addInvoicedetail", 
          data: { 
            hid_invoice_id    : $('#hid-invoice-id').val(),
            service_id                : $('#service-id').val(),
            description       : $('#description').val(),
            qty               : $('#qty').val(),
            amount            : $('#amount').val(),
          },
          success: function(data){ 
            //console.log(data); 
            var result = $.parseJSON(data);
            if(result['status'] == 'success') {
                updateTable(result['invoice_detail']);
                resetForm();
            }
            else {
              alert(result['msg']);
            }
          },
          error: function(XMLHttpRequest, textStatus, errorThrown) { 
            alert("ERROR!!!");           
          } 
      }); 
    }   
    });
    
	
	$('#ico-add-pd').click(function(e) { 
    e.preventDefault();
    if ( $('#payment-date').val() == '') {
      alert("Please Enter Payment Date");
    }
	else {
	   
      $.ajax({
          type: "POST",
          url: burl + "invoice/aj_addInvoicePaymentdetail", 
          data: { 
		  
            hid_invoice_id    	: $('#hid-invoice-id').val(),
            payment_date      	: $('#payment-date').val(),
            bank_name      		: $('#bank-name').val(),
            payment_amount      : $('#payment-amount').val(),
            payment_type      	: $('#payment-type').val(),
			cheque_file			: $('#chk-new-file-name').val(),
            remarks           	: $('#remarks').val(),
          },
          success: function(data){ 
            var result = $.parseJSON(data)
			//alert(result);
            if(result['status'] == 'success') {
                updatePDTable(result['invoice_payment_detail']);
                resetFormPD();
				//alert(result);
            }
            else {
              alert(result['msg']);
            }
          },
          error: function(XMLHttpRequest, textStatus, errorThrown) { 
            alert("ERROR!!!");           
          } 
      }); 
	 }
       
    });
	

    /** Add Item **/
    $('#ico-update').click(function(e) { 
        e.preventDefault();
        if ( $('#description').val() == '') {
          alert("Please Enter Description");
        }
        else { 
            var editkey = $("#hid-edit-id").val(); 
					
            $.ajax({
                type: "POST",
                url: burl + "invoice/aj_updateInvoicedetail/" + editkey, 
                data: { 
                  hid_invoice_id  : $('#hid-invoice-id').val(),
                  no                : $('#no').val(),
                  description       : $('#description').val(),
                  qty               : $('#qty').val(),
                  uom_id            : $('#uom-id').val(),
                  amount            : $('#amount').val(),
                },
                success: function(data){ 
                  // console.log(data); 
                  var result = $.parseJSON(data);
                  if(result['status'] == 'success') {
                      $( "#detail-row" ).insertBefore($( "#total-row" ) );
                      updateTable(result['invoice_detail']);
                      resetForm();
                  }
                  else {
                    alert(result['msg']);
                  }
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) { 
                  alert("ERROR!!!");           
                } 
            }); 
        }   
    });


	
	//Edit Payment Detail
	 $('#ico-update-pd').click(function(e) { 
        e.preventDefault();
        if ( $('#payment-date').val() == '') {
          alert("Please Enter Payment Date");
        }
        else { 
            var editkey = $("#hid-edit-id-pd").val(); 
			//alert(editkey);		
            $.ajax({
                type: "POST",
                url: burl + "invoice/aj_updateInvoicePaymentdetail/" + editkey, 
                data: { 
					hid_invoice_id    	: $('#hid-invoice-id').val(),
					payment_date      	: $('#payment-date').val(),
					bank_name      		: $('#bank-name').val(),
					payment_amount      : $('#payment-amount').val(),
					payment_type      	: $('#payment-type').val(),
					cheque_file			: $('#chk-new-file-name').val(),
					remarks           	: $('#remarks').val(),
                  
                },
                success: function(data){ 
                  // console.log(data); 
                  var result = $.parseJSON(data);
                  if(result['status'] == 'success') {
                      $( "#detail-row-pd" ).insertBefore($( "#total-row-pd" ) );
                      updatePDTable(result['invoice_payment_detail']);
                      resetFormPD();
					  
					 // alert('zzzz');
                  }
                  else {
                    alert(result['msg']);
					//alert('waaahh');
                  }
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) { 
                  alert("ERROR!!!");           
                } 
            }); 
        }   
    });
	
	
	
	
    /** Add Item **/
    $('#ico-cancel').click(function(e) { 
        e.preventDefault();    
        resetForm();
        $( "#detail-row" ).insertBefore($( "#total-row" ) );
    });

	$('#ico-cancel-pd').click(function(e) { 
        e.preventDefault();    
        resetForm();
        $( "#detail-row-pd" ).insertBefore($( "#total-row-pd" ) );
    });
	
	
	
	$('#cheque_select').change(function(e){3
		$('#detail-form-pd').submit();
	
		//readURL(this);
		//$('#main-img-preview').show();
	
	
		//e.preventDefault();
		var img = $('#cheque_select').val();
		var news = document.getElementById("cheque_select").files[0].name;
		//$('#path').val(img);
		//$('#path').val(burl + news);
		//$('#main-file-name').val(burl + news);
	
	
	});
	
	
	$('#detail-form-pd').on('submit', function(e){
		e.preventDefault();

	    $.ajax({
			url : burl + "invoice/upload_cheque",
			method : "POST",
			data: new FormData(this),
			contentType:false,
			processData:false,
			success: function(data){
		
               $('#cheque-form2').html(data); 

			}
		})
	});
	
	
	
	
	
	
	
	
	
	
	
	

    /** 
     * Btn submit
     */
    $('#btn-submit').click(function(e) {   
      e.preventDefault();
      if( $('#quotation-id').val() == '' ) {
        alert("Please Select Quotation");
      }
      else if( $('#invoice-date').val() == '' ) {
        alert("Please Select Date");
      }
      else {
        $.ajax({
          type: "POST",
          url: burl + "invoice/edit/" + $('#hid-invoice-id').val(), 
          beforeSend : function( xhr ) {
            $('#btn-submit').html('<i class="fa fa-spinner ico-btn"> Processing...').prop('disabled', true);
          },
          data: { 
             hid_submitted      : $('#hid-submitted').val(),
             invoice_no         : $('#invoice-no').val(),
             invoice_date       : $('#invoice-date').val(),
             transact_id      	: $('#transact-id').val(),
             entry_no       	: $('#entry-no').val(),   
             deliver_to         : $('#deliver-to').val(),
             address           	: $('#address').val(),
			 attention          : $('#attention').val(),
             customer_no        : $('#customer-no').val(),
             sub_total          : $('#sub-total').val(),
			 gst           		: $('#gst').val(),
			 add_gst           	: $('#add-gst').val(),
             discount           : $('#discount').val(),
			 total           	: $('#total').val(),
             associate      	: $('#associate').val(),
             
             
              // commission          : $('#commission').val(),
              // mf                  : $('#mf').val(),
          },
          success: function(data){  
            $('#btn-submit').html('<i class="fa fa-save ico-btn"> Update').prop('disabled', false);
            var result = $.parseJSON(data);
            if(result['status'] == 'success') {
				//alert($('#transact-id').val());
              window.location = burl + "invoice";
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

  function updateTable(arr) {
    $("#detail-table > tbody:last").children().remove();
    var no = 1; var sub_total = 0;
    $.each(arr, function( i, value ) {   
        var classname = 'id-' + value.invoice_detail_id ; 
        var unit = (value.uom_id != '') ? $('#uom-id option[value="'+value.uom_id+'"]').text() : '';
        var description = value.description.split('\n').join('<br>').split(' ').join('&nbsp;');
        var sn = (value.no != null) ? value.no : '';
         $('#detail-table > tbody:last').append("<tr class='"+classname+"'>"+
                                                   "<td>"+ description +"</td>"+
                                                   "<td>"+ value.qty +"</td>"+
                                                   "<td>"+ value.amount +"</td>"+
                                                   "<td><a href='#' class='edit-di'><i class='fa fa-edit ico'></i> / <a href='#' class='delete-di'><i class='fa fa-trash ico'></i></a></td></tr>");
        sub_total += (value.amount != '') ? parseFloat(value.amount) : 0;
    });
    $('#sub-total').val(myFixed(sub_total,2));
    calculateGST();
    } 
	
	function updatePDTable(arr) {
    $("#payment-detail-table > tbody:last").children().remove();
    $.each(arr, function( i, value ) {   
        var classname = 'id-' + value.invoice_payment_detail_id ; 
        $('#payment-detail-table > tbody:last').append("<tr class='"+classname+"'>"+
											   "<td>"+ value.date +"</td>"+
                                               "<td>"+ value.bank_name +"</td>"+
                                               "<td>"+ value.amount +"</td>"+
                                               "<td>"+ value.payment_type +"</td>"+ 
                                               "<td>"+ value.remarks +"</td>"+
                                               "<td><a href='#' class='edit-di-pd'><i class='fa fa-edit ico'></i> / <a href='#' class='delete-di-pd'><i class='fa fa-trash ico'></i></a></td></tr>");
       /* sub_total += (value.amount != '') ? parseFloat(value.amount) : 0;*/
	  // console.log(arr);"<td><input type ='file' name='cheque[]' id ='cheque_select' value= '"+ value.cheque +"'/></td>"+     "<td>"+ value.cheque +"</td>"+
    });
   
    } 
	
	
	
	
	
	
	
	
	
	
	
});