$( document ).ready(function() {

  updateTable(arr);

	$('#ico-add').click(function(e) { 
    e.preventDefault();
    if ( $('#description').val() == '') {
      alert("Please Enter Description");
    }
    else {
      $.ajax({
          type: "POST",
          url: burl + "quotation/aj_addQuotationdetail", 
          data: { 
            hid_quotation_id  : $('#hid-quotation-id').val(),
            no                : $('#no').val(),
          	description		    : $('#description').val(),
            supplier_id       : $('#supplier-id').val(),
            supplier_cost     : $('#supplier-cost').val(),
            discount     : $('#discount').val(),
            qty               : $('#qty').val(),
            uom_id            : $('#uom-id').val(),
            amount            : $('#amount').val(),
          },
          success: function(data){ 
            //console.log(data); 
            var result = $.parseJSON(data);
            if(result['status'] == 'success') {
            		updateTable(result['quotation_detail']);
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
    
    /** Edit Site **/
    $('#detail-table').on('click', '.edit-di', function(e) {
        e.preventDefault(); 
        var classname = $(this).closest("tr").attr('class');
        var row = classname.split('-');
        $('#hid-edit-id').val(row[1]);
        $.ajax({
          type: "POST",
          url: burl + "quotation/aj_getQuotationdetail/" + row[1], 
          data: { },
          success: function(data){ 
            // console.log(data); 
            var result = $.parseJSON(data);
            if(result['status'] == 'success') {
              $('#no').val(result['no']);
              $('#description').val(result['description']).trigger('autosize.resize');
              $('#supplier-id').val(result['supplier_id']);
              $('#supplier-cost').val(result['supplier_cost']);
			  $('#discount').val(result['discount']);
              $('#qty').val(result['qty']);
              $('#uom-id').val(result['uom_id']);
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

    /** Delete Site **/
    $('#detail-table').on('click', '.delete-di', function(e) {
        e.preventDefault(); 
        var r = confirm("Are you sure to remove this record!");
	      if (r == true) {
	        var classname = $(this).closest("tr").attr('class');
	        var row = classname.split('-');
	        $.ajax({
	            type: "POST",
	            url: burl + "quotation/aj_deleteQuotationdetail/" + row[1], 
	            data: { },
	            success: function(data){ 
                console.log(data);
	              var result = $.parseJSON(data);
	              if(result['status'] == 'success') {
                    $( "#detail-row" ).insertBefore($( "#total-row" ) );
	              		updateTable(result['quotation_detail']);
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
                url: burl + "quotation/aj_updateQuotationdetail/" + editkey, 
                data: { 
                  hid_quotation_id  : $('#hid-quotation-id').val(),
                	no                : $('#no').val(),
                  description       : $('#description').val(),
                  supplier_id       : $('#supplier-id').val(),
                  supplier_cost     : $('#supplier-cost').val(),
                  discount     		: $('#discount').val(),
                  qty               : $('#qty').val(),
                  uom_id            : $('#uom-id').val(),
                  amount            : $('#amount').val(),
                },
                success: function(data){ 
                  // console.log(data); 
                  var result = $.parseJSON(data);
                  if(result['status'] == 'success') {
                      $( "#detail-row" ).insertBefore($( "#total-row" ) );
                  		updateTable(result['quotation_detail']);
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

    /** Add Item **/
    $('#ico-cancel').click(function(e) { 
        e.preventDefault();    
        resetForm();
        $( "#detail-row" ).insertBefore($( "#total-row" ) );
    });


    /** 
     * Btn submit
     */
    $('#btn-submit').click(function(e) {   
      e.preventDefault();
      if( $('#client-id').val() == '' ) {
        alert("Please Select Client");
      }
      else if( $('#date').val() == '' ) {
        alert("Please Select Date");
      }
      else if( $('#user-id').val() == '' ) {
        alert("Please Select REP");
      }
      else {
        $.ajax({
          type: "POST",
          url: burl + "quotation/edit/" + $('#hid-quotation-id').val() + '/TRUE', 
          beforeSend : function( xhr ) {
            $('#btn-submit').html('<i class="fa fa-spinner ico-btn"> Processing...').prop('disabled', true);
          },
          data: { 
              quotation_no        : $('#quotation-no').val(),
              date                : $('#date').val(),
              client_id           : $('#client-id').val(),
              contact             : $('#contact').val(),
              designation         : $('#designation').val(),
              department          : $('#department').val(),
              address             : $('#address').val(),
              sale_person_id      : $('#user-id').val(),
              delivery_date       : $('#delivery-date').val(),   
              po_no               : $('#po-no').val(),
              st_oic              : $('#st-oic').val(),
              invoice_date        : $('#invoice-date').val(),
              invoice_no          : $('#invoice-no').val(),
              tel_no              : $('#tel-no').val(),
              sub_total           : $('#sub-total').val(),
              discount            : $('#discount').val(),
              gst                 : $('#gst').val(),
              total               : $('#total').val(),
              quotation_status    : $('#quotation-status').val(),
              job_title           : $('#job-title').val(),
              terms               : $('#terms').val(),
              revised             : ($('#revised').prop('checked')==true) ? 1 : 0,
              commission          : $('#commission').val(),
              mf                  : $('#mf').val(),
          },
          success: function(data){  
            $('#btn-submit').html('<i class="fa fa-save ico-btn"> Update').prop('disabled', false);
            var result = $.parseJSON(data);
            if(result['status'] == 'success') {
              window.location = burl + "quotation";
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
        var classname = 'id-' + value.quotation_detail_id ; 
        var unit = (value.uom_id != '') ? $('#uom-id option[value="'+value.uom_id+'"]').text() : '';
        var supplier = (value.supplier_id != '') ? $('#supplier-id option[value="'+value.supplier_id+'"]').text() : '';
        var description = value.description.split('\n').join('<br>').split(' ').join('&nbsp;');
        var sn = (value.no != null) ? value.no : '';
        $('#detail-table > tbody:last').append("<tr class='"+classname+"'>"+
                                               "<td>"+ sn +"</td>"+
                                               "<td>"+ description +"</td>"+
                                               "<td>"+ supplier +"</td>"+
                                               "<td>"+ value.supplier_cost +"</td>"+
                                               "<td>"+ value.qty +"</td>"+
                                               "<td>"+ unit+"</td>"+
                                               "<td>"+ value.amount +"</td>"+
                                               "<td><a href='#' class='edit-di'><i class='fa fa-edit ico'></i> / <a href='#' class='delete-di'><i class='fa fa-trash ico'></i></a></td></tr>");
        sub_total += (value.amount != '') ? parseFloat(value.amount) : 0;
    });
    $('#sub-total').val(myFixed(sub_total,2));
    calculateGST();
    } 
});