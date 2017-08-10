$( document ).ready(function() {
    

    var arr = {};   
    var key = 0; 

    if (typeof detail_arr !== 'undefined') {  
        $.each(detail_arr, function( i, value ) {  
            arr[++key] = value;   
        });
        updateTable(arr);
        var key = $('#tbl-detail tbody tr').length - 1;
    }


    $(window).on('beforeunload', function(){        
        if (!$.isEmptyObject(arr)) {
          return 'When you leave right now, the data will not be saved.';
        }
    });

    $('#ico-add').click(function(e) { 
        e.preventDefault();
        if ( $('#description').val() == '') {
            alert("Please Enter Description");
        }
        else {
            var formdata = $("#detail-form").serializeObject(); 
            var key = getLastindex();
            arr[++key] = formdata; 
            updateTable();
            resetForm();
        }   
    });
    
    /** Edit Site **/
    $('#detail-table').on('click', '.edit-di', function(e) {
        e.preventDefault(); 
        var classname = $(this).closest("tr").attr('class');
        var row = classname.split('-');
        $('#hid-edit-id').val(row[1]);
        $('#no').val(arr[row[1]].no);
        $('#description').val(arr[row[1]].description.split('<br>').join('\n').split('&nbsp;').join(' ')).trigger('autosize.resize');
        $('#qty').val(arr[row[1]].qty);
        $('#uom-id').val(arr[row[1]].uom_id);
        $('#amount').val(arr[row[1]].amount);
        $('#detail-add').hide();
        $('#detail-update').show();  
        $("#detail-row").insertAfter( $( ".id-" + row[1] ) );      
    });

    /** Delete Site **/
    $('#detail-table').on('click', '.delete-di', function(e) {
        e.preventDefault(); 
        var classname = $(this).closest("tr").attr('class');
        var row = classname.split('-');
        delete arr[row[1]];
        $( "#detail-row" ).insertBefore($( "#total-row" ) );
        updateTable();
        resetForm();       
    });


    /** Add Item **/
    $('#ico-update').click(function(e) { 
        e.preventDefault();
        if ( $('#description').val() == '') {
            alert("Please Enter Description");
        }
        else { 
            var formdata = $("#detail-form").serializeObject(); 
            var editkey = $("#hid-edit-id").val();  
            arr[editkey] = formdata;
            $( "#detail-row" ).insertBefore($( "#total-row" ) );
            updateTable();
            resetForm();
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
                url: burl + "purchase/create", 
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
                    detail_info         : arr,
                },
                success: function(data){  
                  $('#btn-submit').html('<i class="fa fa-save ico-btn"> Save').prop('disabled', false);
                  var result = $.parseJSON(data);
                  if(result['status'] == 'success') {
                    arr = {};
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

    function updateTable() {
        $("#detail-table > tbody:last").children().remove();
        var no = 1; var sub_total = 0;
        $.each(arr, function( i, value ) {   
            var classname = 'id-' + i ; 
            var unit = (value.uom_id != '') ? $('#uom-id option[value="'+value.uom_id+'"]').text() : '';
            var description = value.description.split('\n').join('<br>').split(' ').join('&nbsp;');
            $('#detail-table > tbody:last').append("<tr class='"+classname+"'>"+
                                                   "<td>"+ value.no +"</td>"+
                                                   "<td>"+ description +"</td>"+
                                                   "<td>"+ value.qty +"</td>"+
                                                   "<td>"+ unit +"</td>"+
                                                   "<td>"+ value.amount +"</td>"+
                                                   "<td><a href='#' class='edit-di'><i class='fa fa-edit ico'></i> / <a href='#' class='delete-di'><i class='fa fa-trash ico'></i></a></td></tr>");
            sub_total += (value.amount != '') ? parseFloat(value.amount) : 0;
        });
        $('#sub-total').val(myFixed(sub_total,2));
        calculateGST();
    } 

    function getLastindex() {
        var lastindex = 0;
        $.each(arr, function( i, value ) {
            lastindex = i;
        });    
        return lastindex;
    }

});