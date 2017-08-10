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
        if ( $('#c-contact').val() == '') {
		  alert("Please Enter Contact Name");
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
		//$('#c-contact').val(arr[row[1]].c_contact);
        $('#c-contact').val(arr[row[1]].c_contact);
        $('#c-designation').val(arr[row[1]].c_designation);
        $('#c-department').val(arr[row[1]].c_department);
        $('#c-phone').val(arr[row[1]].c_phone);
        $('#c-address').val(arr[row[1]].c_address);

       
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
        else if( $('#date').val() == '' ) {
        	alert("Please Select Date");
        }
        else if( $('#user-id').val() == '' ) {
            alert("Please Select REP");
        }
       /*  else if ( $.isEmptyObject(arr) ) {
            alert("Please add at lease one detail information.");   
        } */
        else {
            $.ajax({
                type: "POST",
                url: burl + "client/create/TRUE", 
                beforeSend : function( xhr ) {
                    $('#btn-submit').html('<i class="fa fa-spinner ico-btn"> Processing...').prop('disabled', true);
                },
                data: { 
                    client_id           : $('#client-id').val(),
                    company             : $('#company').val(),
                    department          : $('#department').val(),
				    contact				: $('#contact').val(),	
				    phone               : $('#phone').val(),
				    address_1         	: $('#address-1').val(),
				    address_2          	: $('#address-2').val(),
				    postal_code         : $('#postal-code').val(),
				    email      			: $('#email').val(),
				    remark       		: $('#remark').val(),   
					detail_info         : arr,
				},
                success: function(data){  
                  $('#btn-submit').html('<i class="fa fa-save ico-btn"> Save').prop('disabled', false);
                  var result = $.parseJSON(data);
                  if(result['status'] == 'success') {
                    arr = {};
                    window.location = burl + "client";
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

	/* function updateTable() {
        $("#detail-table > tbody:last").children().remove();
        var no = 1;
        $.each(arr, function( i, value ) {   
            var classname = 'id-' + i ; 
            //var unit = (value.uom_id != '') ? $('#uom-id option[value="'+value.uom_id+'"]').text() : '';
           // var description = value.description.split('\n').join('<br>').split(' ').join('&nbsp;');
            //var supplier = (value.supplier_id != '') ? $('#supplier-id option[value="'+value.supplier_id+'"]').text() : '';
            $('#detail-table > tbody:last').append("<tr class='"+classname+"'>"+
                                                   "<td>"+ value.no +"</td>"+
                                                   "<td>"+ value.contact +"</td>"+
                                                   "<td>"+ value.designation +"</td>"+
                                                   "<td>"+ value.department +"</td>"+
                                                   "<td>"+ value.phone +"</td>"+
                                                   "<td><a href='#' class='edit-di'><i class='fa fa-edit ico'></i> / <a href='#' class='delete-di'><i class='fa fa-trash ico'></i></a></td></tr>");
            //sub_total += (value.amount != '') ? parseFloat(value.amount) : 0;
        });
        //$('#sub-total').val(myFixed(sub_total,2));
        //calculateGST();
    } */ 
	
	function updateTable() {
    $("#detail-table > tbody:last").children().remove();
    var no = 1;
     $.each(arr, function( i, value ) {   
            var classname = 'id-' + i ; 
            //var unit = (value.uom_id != '') ? $('#uom-id option[value="'+value.uom_id+'"]').text() : '';
           // var description = value.description.split('\n').join('<br>').split(' ').join('&nbsp;');
            //var supplier = (value.supplier_id != '') ? $('#supplier-id option[value="'+value.supplier_id+'"]').text() : '';
            $('#detail-table > tbody:last').append("<tr class='"+classname+"'>"+
                                                   "<td>"+ value.c_contact +"</td>"+
                                                   "<td>"+ value.c_designation +"</td>"+
                                                   "<td>"+ value.c_department +"</td>"+
                                                   "<td>"+ value.c_phone +"</td>"+
                                                   "<td>"+ value.c_address +"</td>"+
                                                   "<td><a href='#' class='edit-di'><i class='fa fa-edit ico'></i> / <a href='#' class='delete-di'><i class='fa fa-trash ico'></i></a></td></tr>");
            //sub_total += (value.amount != '') ? parseFloat(value.amount) : 0;
        });
    //$('#sub-total').val(myFixed(sub_total,2));
   // calculateGST();
    } 
	
	
	

    function getLastindex() {
        var lastindex = 0;
        $.each(arr, function( i, value ) {
            lastindex = i;
        });    
        return lastindex;
    }

});