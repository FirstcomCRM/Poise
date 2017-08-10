$( document ).ready(function() {
	//var arr = {};
  	var key = 0;
	//var burl;
  updateTable(arr);

	$('#ico-add').click(function(e) { 
    e.preventDefault();
    if ( $('#c-contact').val() == '') {
      alert("Please Enter Contact Name");
    }
    else {
      $.ajax({
          type: "POST",
          url: burl + "client/aj_addClientdetail", 
          data: { 
            hid_client_id  	: $('#hid-client-id').val(),
          	contact		    : $('#c-contact').val(),
            designation     : $('#c-designation').val(),
            department     	: $('#c-department').val(),
            phone           : $('#c-phone').val(),
            address         : $('#c-address').val(),
          },
          success: function(data){ 
            //console.log(data); 
            var result = $.parseJSON(data);
            if(result['status'] == 'success') {
					console.log(data);
            		//var formdata = $("#detail-form").serializeObject(); 
					//var key = getLastindex();
					//arr[++key] = formdata;
					updateTable(result['client_detail']);
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
          url: burl + "client/aj_getClientdetail/" + row[1], 
          data: { },
          success: function(data){ 
            console.log(data); 
            var result = $.parseJSON(data);
            if(result['status'] == 'success') {
				//alert(burl + "client/aj_getClientdetail/" + row[1]);
              //$('#no').val(result['no']);
             // $('#cont').val(result['description']).trigger('autosize.resize');
              $('#hid-client-id').val(result['client_id']);
              $('#c-contact').val(result['name']);
              $('#c-designation').val(result['designation']);
              $('#c-department').val(result['department']);
              $('#c-phone').val(result['phone']);
              $('#c-address').val(result['address']);
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
	            url: burl + "client/aj_deleteClientdetail/" + row[1], 
				data: { },
	            success: function(data){ 
                console.log(data);
	              var result = $.parseJSON(data);
				  var links = burl + "client/aj_deleteClientdetail/" + row[1];
				  //alert(links);
	              if(result['status'] == 'success') {
						$( "#detail-row" ).insertBefore($( "#total-row" ) );
	              		updateTable(result['client_detail']);
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
        if ( $('#c-contact').val() == '') {
		  alert("Please Enter Contact Name");
		}
        else { 
            var editkey = $("#hid-edit-id").val();  
            $.ajax({
                type: "POST",
                url: burl + "client/aj_updateClientdetail/" + editkey, 
                data: { 
                  hid_client_id  	: $('#hid-client-id').val(),
                  contact		    : $('#c-contact').val(),
                  designation       : $('#c-designation').val(),
                  department     	: $('#c-department').val(),
                  phone             : $('#c-phone').val(),
                  address           : $('#c-address').val(),
                },
                success: function(data){ 
                   console.log(data); 
                  var result = $.parseJSON(data);
                  if(result['status'] == 'success') {
                      $( "#detail-row" ).insertBefore($( "#total-row" ) );
                  		updateTable(result['client_detail']);
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
      //  $( "#detail-row" ).insertBefore($( "#total-row" ) );
    });


    /** 
     * Btn submit
     */
    $('#btn-submit').click(function(e) {   
      e.preventDefault();
     /* if( $('#company').val() == '' ) {
        alert("Please Insert Company");
      }*/
      
      //}
      //else {
        $.ajax({
          type: "POST",
          url: burl + "client/edit_contact/" + $('#hid-client-id').val() + '/TRUE', 
          beforeSend : function( xhr ) {
            $('#btn-submit').html('<i class="fa fa-spinner ico-btn"> Processing...').prop('disabled', true);
          },
          data: { 
              client_id           : $('#client-id').val(),
              company             : $('#company').val(),
			  department             : $('#department').val(),
              contact			: $('#contact').val(),	
			  phone                : $('#phone').val(),
			  address_1         : $('#address-1').val(),
              address_2          : $('#address-2').val(),
              postal_code             : $('#postal-code').val(),
              email      : $('#email').val(),
              remark       : $('#remark').val(),   
          },
          success: function(data){  
            $('#btn-submit').html('<i class="fa fa-save ico-btn"> Update').prop('disabled', false);
            var result = $.parseJSON(data);
            if(result['status'] == 'success') {
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
      //}
    }); 

	function updateTable(arr) {
    $("#detail-table > tbody:last").children().remove();
    var no = 1;
     $.each(arr, function( i, value ) {   
            var classname = 'id-' + value.client_detail_id ; 
            //var unit = (value.uom_id != '') ? $('#uom-id option[value="'+value.uom_id+'"]').text() : '';
           // var description = value.description.split('\n').join('<br>').split(' ').join('&nbsp;');
            //var supplier = (value.supplier_id != '') ? $('#supplier-id option[value="'+value.supplier_id+'"]').text() : '';
            $('#detail-table > tbody:last').append("<tr class='"+classname+"'>"+
                                                   "<td>"+ value.name +"</td>"+
                                                   "<td>"+ value.designation +"</td>"+
                                                   "<td>"+ value.department +"</td>"+
                                                   "<td>"+ value.phone +"</td>"+
                                                   "<td>"+ value.address +"</td>"+
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