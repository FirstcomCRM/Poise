$( document ).ready(function() {

  updateTable(detail_arr);

  $('#ico-add').click(function(e) { 
    e.preventDefault();
    if ( $('#description').val() == '') {
      alert("Please Enter Description");
    }
    else {
      $.ajax({
          type: "POST",
          url: burl + "purchase/aj_addPurchasedetail", 
          data: { 
            hid_purchase_id    : $('#hid-purchase-id').val(),
            no                : $('#no').val(),
            description       : $('#description').val(),
            qty               : $('#qty').val(),
            uom_id            : $('#uom-id').val(),
            amount            : $('#amount').val(),
          },
          success: function(data){ 
            //console.log(data); 
            var result = $.parseJSON(data);
            if(result['status'] == 'success') {
                updateTable(result['purchase_detail']);
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
          url: burl + "purchase/aj_getPurchasedetail/" + row[1], 
          data: { },
          success: function(data){ 
            // console.log(data); 
            var result = $.parseJSON(data);
            if(result['status'] == 'success') {
              $('#no').val(result['no']);
              $('#description').val(result['description']).trigger('autosize.resize');
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
              url: burl + "purchase/aj_deletePurchasedetail/" + row[1], 
              data: { },
              success: function(data){ 
                var result = $.parseJSON(data);
                if(result['status'] == 'success') {
                    $( "#detail-row" ).insertBefore($( "#total-row" ) );
                    updateTable(result['purchase_detail']);
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
                url: burl + "purchase/aj_updatePurchasedetail/" + editkey, 
                data: { 
                  hid_purchase_id  : $('#hid-purchase-id').val(),
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
                      updateTable(result['purchase_detail']);
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
      if( $('#supplier-id').val() == '' ) {
        alert("Please Select Supplier");
      }
      else if( $('#quotation-id').val() == '' ) {
        alert("Please Select Quotation");
      }
      else if( $('#date').val() == '' ) {
        alert("Please Select Date");
      }
      else {
        $.ajax({
          type: "POST",
          url: burl + "purchase/edit/" + $('#hid-purchase-id').val(), 
          beforeSend : function( xhr ) {
            $('#btn-submit').html('<i class="fa fa-spinner ico-btn"> Processing...').prop('disabled', true);
          },
          data: { 
              hid_submitted       : $('#hid-submitted').val(),
              supplier_id         : $('#supplier-id').val(),
              quotation_id        : $('#quotation-id').val(),
              po_no               : $('#po-no').val(),
              date                : $('#date').val(),
              delivery_date       : $('#delivery-date').val(),
              deliver_to          : $('#deliver-to').val(),
              designation         : $('#del-designation').val(), 
              department          : $('#del-department').val(),    
              company             : $('#del-company').val(),
              address             : $('#del-address').val(),
              tel                 : $('#del-tel').val(),   
              fax                 : $('#del-fax').val(),   
              no_locations        : $('#no-locations').val(),
              supplier_qrn        : $('#supplier-qrn').val(),
              terms               : $('#terms').val(),
              sub_total           : $('#sub-total').val(),
              gst                 : $('#gst').val(),
              total               : $('#total').val(),
              purchase_status     : $('#purchase-status').val(),
          },
          success: function(data){  
            $('#btn-submit').html('<i class="fa fa-save ico-btn"> Update').prop('disabled', false);
            var result = $.parseJSON(data);
            if(result['status'] == 'success') {
              window.location = burl + "purchase";
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
        var classname = 'id-' + value.purchase_detail_id ; 
        var unit = (value.uom_id != '') ? $('#uom-id option[value="'+value.uom_id+'"]').text() : '';
        var description = value.description.split('\n').join('<br>').split(' ').join('&nbsp;');
        var sn = (value.no != null) ? value.no : '';
        $('#detail-table > tbody:last').append("<tr class='"+classname+"'>"+
                                               "<td>"+ sn +"</td>"+
                                               "<td>"+ description +"</td>"+
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