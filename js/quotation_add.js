$( document ).ready(function() {

 	var arr = {};
  	var key = 0;

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
        $('#supplier-id').val(arr[row[1]].supplier_id);
        $('#supplier-cost').val(arr[row[1]].supplier_cost);
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
        if( $('#client-id').val() == '' ) {
            alert("Please Select Client");
        }

        else if( $('#user-id').val() == '' ) {
            alert("Please Select REP");
        }
        else if ( $.isEmptyObject(arr) ) {
            alert("Please add at lease one detail information.");   
        }
        else {
            $.ajax({
                type: "POST",
                url: burl + "quotation/create/TRUE", 
                beforeSend : function( xhr ) {
                    $('#btn-submit').html('<i class="fa fa-spinner ico-btn"> Processing...').prop('disabled', true);
                },
                data: { 
                    quotation_no        : $('#quotation-no').val(),
                    date              	: $('#date').val(),
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
                    gst		            : $('#gst').val(),
                    total               : $('#total').val(),
                    quotation_status    : $('#quotation-status').val(),
                    job_title           : $('#job-title').val(),
                    terms               : $('#terms').val(),
                    revised             : ($('#revised').prop('checked')==true) ? 1 : 0,
                    commission          : $('#commission').val(),
				    mf                  : $('#mf').val(),
				    detail_info         : arr,
				},

                success: function(data){  
                  $('#btn-submit').html('<i class="fa fa-save ico-btn"> Save').prop('disabled', false);
                  var result = $.parseJSON(data);
                  if(result['status'] == 'success') {
                    arr = {};
                    window.location = burl + "quotation";
                  }
                  else {
                  	var regex = /(<([^>]+)>)/ig;
                    result['msg'] = result['msg'].replace(regex, "");
                    alert(result['msg']);
					 //window.location.reload();  

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
            var supplier = (value.supplier_id != '') ? $('#supplier-id option[value="'+value.supplier_id+'"]').text() : '';
            $('#detail-table > tbody:last').append("<tr class='"+classname+"'>"+
                                                   "<td>"+ value.no +"</td>"+
                                                   "<td>"+ description +"</td>"+
                                                   "<td>"+ supplier +"</td>"+
                                                   "<td>"+ value.supplier_cost +"</td>"+
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