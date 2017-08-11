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
    

    var arr = {};
	var arr_pd = {};
    var key = 0; 

    if (typeof detail_arr !== 'undefined') {  
        $.each(detail_arr, function( i, value ) {  
            arr[++key] = value;   
        });
        updateTable(arr);
        var key = $('#tbl-detail tbody tr').length - 1;
    }
	
	else if (typeof payment_detail_arr !== 'undefined') {  
        $.each(payment_detail_arr, function( i, value ) {  
            arr[++key] = value;   
        });
        updatePDTable(arr);
        var key = $('#tbl-payment-detail tbody tr').length - 1;
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
			console.log(formdata);
            updateTable();
            resetForm();
        }   
    });
    
	$('#ico-add-pd').click(function(e) { 
        e.preventDefault();
        if ( $('#payment-date').val() == '') {
            alert("Please Enter Payment Date");
        }
        else {
            var formdata_pd = $("#detail-form-pd").serializeObject(); 
            var key_pd = getLastindexPD();
            arr_pd[++key_pd] = formdata_pd; 
			console.log(formdata_pd);
            updatePDTable();
            resetFormPD();
        }   
    });
	
	
	
	$('#cheque_select').change(function(e){
	
	//var ext = document.getElementById("img_select").files[1].name;
	$('#detail-form-pd').submit();
	//e.preventDefault();
	var img = $('#cheque_select').val();
	var news = document.getElementById("cheque_select").files[0].name;
	//$('#path').val(img);
	$('#path').val(burl + news);
	$('#file-name').val(news);
	//alert(ext);
	
	/* var fd = new FormData($('#path').get(0));
	fd.append("CustomField", "This is some extra data");
	console.log(fd); */
	
	});
	$('#detail-form-pd').on('submit', function(e){
		e.preventDefault();
		/* var formdata = $("#img-form").serializeObject();
						
		var key = getLastindex();
		arr[++key] = formdata; 
		console.log(arr); */
	    $.ajax({
			url : burl + "invoice/upload_cheque",
			method : "POST",
			data: new FormData(this),
			contentType:false,
			processData:false,
			success: function(data){
				
				//url : burl + "announcement/upload",
				// $('#img_select').val('');  
               // $('#src_img_upload').modal('hide');  
               $('#detail-form-pd').html(data); 
				
				//alert(arr);
				
				
				
				
			}
		})
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
	
	 $('#detail-table-pd').on('click', '.edit-di-pd', function(e) {
        e.preventDefault(); 
        var classname = $(this).closest("tr").attr('class');
        var row = classname.split('-');
        $('#hid-edit-id').val(row[1]);
        $('#payment-date').val(arr[row[1]].payment_date);
        $('#payment-amount').val(arr[row[1]].payment_amount);
        $('#payment-type').val(arr[row[1]].payment_type);
        $('#remarks').val(arr[row[1]].remarks);
        $('#detail-add-pd').hide();
        $('#detail-update-pd').show();  
        $("#detail-row-pd").insertAfter( $( ".id-" + row[1] ) );       
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
        if( $('#quotation-id').val() == '' ) {
            alert("Please Select Quotation");
        }
        else if( $('#invoice-date').val() == '' ) {
            alert("Please Select Date");
        }
        else if ( $.isEmptyObject(arr) ) {
            alert("Please add at lease one detail information.");   
        }
        else {
            $.ajax({
                type: "POST",
                url: burl + "invoice/create", 
                beforeSend : function( xhr ) {
                    $('#btn-submit').html('<i class="fa fa-spinner ico-btn"> Processing...').prop('disabled', true);
                },
                data: { 
                    hid_submitted       		: $('#hid-submitted').val(),
                    invoice_no          		: $('#invoice-no').val(),
                    invoice_date        		: $('#invoice-date').val(),
                    transact_id      			: $('#transact-id').val(),
                    entry_no       				: $('#entry-no').val(),   
                    deliver_to         			: $('#deliver-to').val(),
                    address           			: $('#address').val(),
                    attention           		: $('#attention').val(),
                    custmomer_no        		: $('#customer-no').val(),
                    sub_total           		: $('#sub-total').val(),
                    gst           				: $('#gst').val(),
					add_gst           			: $('#add-gst').val(),
                    discount           			: $('#discount').val(),
                    total           			: $('#total').val(),
                    associate      				: $('#associate').val(),
                    detail_info         		: arr,
                    payment_detail_info         : arr_pd,
                },
                success: function(data){  
                  $('#btn-submit').html('<i class="fa fa-save ico-btn"> Save').prop('disabled', false);
                  var result = $.parseJSON(data);
                  if(result['status'] == 'success') {
                    arr = {};
					arr_pd = {};
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

    function updateTable() {
        $("#detail-table > tbody:last").children().remove();
        var no = 1; var sub_total = 0;
        $.each(arr, function( i, value ) {   
            var classname = 'id-' + i ; 
            var unit = (value.uom_id != '') ? $('#uom-id option[value="'+value.uom_id+'"]').text() : '';
            var description = value.description.split('\n').join('<br>').split(' ').join('&nbsp;');
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
	
	function updatePDTable() {
    $("#payment-detail-table > tbody:last").children().remove();
    $.each(arr_pd, function( i, value ) {   
        var classname = 'id-' + i ; 
        $('#payment-detail-table > tbody:last').append("<tr class='"+classname+"'>"+
                                               "<td>"+ value.payment_date +"</td>"+
                                               "<td>"+ value.bank_name +"</td>"+
                                               "<td>"+ value.payment_amount +"</td>"+
                                               "<td>"+ value.payment_type +"</td>"+
                                               "<td>"+ value.path +"</td>"+
                                               "<td>"+ value.remarks +"</td>"+
                                               "<td><a href='#' class='edit-di-pd'><i class='fa fa-edit ico'></i> / <a href='#' class='delete-di-pd'><i class='fa fa-trash ico'></i></a></td></tr>");
       /* sub_total += (value.amount != '') ? parseFloat(value.amount) : 0;*/
	  // console.log(arr);
    });
   
    } 
	
	
	
	
	

    function getLastindex() {
        var lastindex = 0;
        $.each(arr, function( i, value ) {
            lastindex = i;
        });    
        return lastindex;
    }
	
	function getLastindexPD() {
        var lastindex_pd = 0;
        $.each(arr_pd, function( i, value ) {
            lastindex_pd = i;
        });    
        return lastindex_pd;
    }

});