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
	
	
	function ValidateMainImage(oInput) {
		if (oInput.type == "file") {
			var sFileName = oInput.value;
			 if (sFileName.length > 0) {
				var blnValid = false;
				for (var j = 0; j < _validFileExtensionsImg.length; j++) {
					var sCurExtension = _validFileExtensionsImg[j];
					if (sFileName.substr(sFileName.length - sCurExtension.length, sCurExtension.length).toLowerCase() == sCurExtension.toLowerCase()) {
						blnValid = true;
						break;
					}
				}
				 
				if (!blnValid) {
					alert("Sorry, " + sFileName + " is invalid, allowed extensions are: " + _validFileExtensionsImg.join(", "));
					oInput.value = "";
					return false;
				}
			}
		}
		return true;
	}


$( document ).ready(function() {

 	var arr = {};
  	var key = 0;

  /* 	$(window).on('beforeunload', function(){        
        if (!$.isEmptyObject(arr)) {
          return 'When you leave right now, the data will not be saved.';
        }
    }); */

	
	$('#ico-add').click(function(e) { 
        e.preventDefault();
        if ( $('#path').val() == '') {
            alert("Please Upload File");
        }
        else {
			//$('#detail-form').submit();
            var formdata = $("#detail-form").serializeObject();
			
            var key = getLastindex();
            arr[++key] = formdata; 
			console.log(formdata);
            updatePDTable();
            resetForm();
        }   
    });
	//$('#formid input').each(function(t)){
	
	$('#img_select').change(function(e){
	$('#detail-form').submit();
	//e.preventDefault();
	var img = $('#img_select').val();
	var news = document.getElementById("img_select").files[0].name;
	//$('#path').val(img);
	$('#path').val(burl + news);
	$('#file-name').val(news);
	//alert(img);
	
	/* var fd = new FormData($('#path').get(0));
	fd.append("CustomField", "This is some extra data");
	console.log(fd); */
	
	});
	$('#detail-form').on('submit', function(e){
		e.preventDefault();
		/* var formdata = $("#img-form").serializeObject();
						
		var key = getLastindex();
		arr[++key] = formdata; 
		console.log(arr); */
	    $.ajax({
			url : burl + "transaction/upload",
			method : "POST",
			data: new FormData(this),
			contentType:false,
			processData:false,
			success: function(data){
				
				//url : burl + "announcement/upload",
				// $('#img_select').val('');  
               // $('#src_img_upload').modal('hide');  
               $('#detail-form').html(data); 
				
				//alert(arr);
				
				
				
				
			}
		})
	});
	
	
	function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $('#main-img-preview').prop('src', e.target.result).show().addClass('selected');
        }

        reader.readAsDataURL(input.files[0]);
    }
}
 
 
	var orig_src = '';
    $('#main-img-preview').click(function(e) {  
        e.preventDefault();
        //tbl.api().ajax.reload();
		
		$('#main_img_select').replaceWith($('#main_img_select').clone(true));
		$('#main-img-preview').not('.selected').hide();
		$('#main-img-preview.selected').prop('src', orig_src).removeClass('selected');
		$('#main-new-file-name').val('');
		$('#main_img_select').val('');
    });

	
	
	
	
	$('#main_img_select').change(function(e){3
		$('#image-form').submit();
	
		readURL(this);
		$('#main-img-preview').show();
	
	
		//e.preventDefault();
		var img = $('#main_img_select').val();
		var news = document.getElementById("main_img_select").files[0].name;
		//$('#path').val(img);
		//$('#path').val(burl + news);
		$('#main-file-name').val(burl + news);
		//alert($('#main-file-path').val());
		//alert(img);
		/* 
		var fd = new FormData($('#path').get(0));
		fd.append("CustomField", "This is some extra data");
		console.log(fd); */
	
	});
	
	
	$('#image-form').on('submit', function(e){
		e.preventDefault();
		/* var formdata = $("#img-form").serializeObject();
						
		var key = getLastindex();
		arr[++key] = formdata; 
		console.log(arr); */
	    $.ajax({
			url : burl + "transaction/upload_main_img",
			method : "POST",
			data: new FormData(this),
			contentType:false,
			processData:false,
			success: function(data){
				
			// /* 	url : burl + "announcement/upload",
				 // $('#main_img_select').val('');  
               // $('#src_img_upload').modal('hide');  
               $('#image-form2').html(data); 
				
				// alert(arr);
				
				
				
				
			}
		})
	});

	
	
	
	
	
	 $('#detail-table').on('click', '.delete-di', function(e) {
        e.preventDefault(); 
        var classname = $(this).closest("tr").attr('class');
        var row = classname.split('-');
        delete arr[row[1]];
        $( "#detail-row" ).insertBefore($( "#total-row" ) );
        updatePDTable();
        resetForm();       
    });
	
	
	
    
    /** 
     * Btn submit
     */
     $('#btn-submit').click(function(e) {   
        e.preventDefault();
		if( $('#prorerty-id').val() == '' ) {
            alert("Please Enter Property Title");
        }
        else {
            $.ajax({
                type: "POST",
                url: burl + "transaction/create/TRUE", 
                beforeSend : function( xhr ) {
                    $('#btn-submit').html('<i class="fa fa-spinner ico-btn"> Processing...').prop('disabled', true);
                },
                data: { 
                    case_no        			: $('#case-no').val(),
                    property_id      		: $('#property-id').val(),
                    amount           		: $('#amount').val(),
                    transact_date       	: $('#transact-date').val(),
                    property_type       	: $('#property-type').val(),
                    address          		: $('#address').val(),
                    price             		: $('#price').val(),
                    gst             		: $('#gst').val(),
                    owner_name          	: $('#owner-name').val(),
                    buyer_name          	: $('#buyer-name').val(),
                    co_broke_agent      	: $('#co-broke-agent').val(),
                    co_broke_agency     	: $('#co-broke-agency').val(),
                    contract_date       	: $('#contract-date').val(),
                    option_date         	: $('#option-date').val(),
                    commission          	: $('#commission').val(),
                    co_broke_commission 	: $('#co-broke-commission').val(),
                    internal_commission 	: $('#internal-commission').val(),
					user_id					: user_id,
					files_info				: arr,
				},

                success: function(data){  
                  $('#btn-submit').html('<i class="fa fa-save ico-btn"> Save').prop('disabled', false);
                  var result = $.parseJSON(data);
                  if(result['status'] == 'success') {
                    //arr = {};
					alert(user_id);
                    window.location = burl + "transaction";
                  }
                  else {
                  	var regex = /(<([^>]+)>)/ig;
                    result['msg'] = result['msg'].replace(regex, "");
                    alert(result['msg']);
					 //window.location.reload();  
					alert(user_id);
                  }
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) { 
                  alert("ERROR!!!");           
                } 
            });   
        }
    }); 
	
		function updatePDTable() {
		$("#detail-table > tbody:last").children().remove();
		$.each(arr, function( i, value ) {   
			var classname = 'id-' + i ; 
			$('#detail-table > tbody:last').append("<tr class='"+classname+"'>"+
												   "<td>"+ value.path +"</td>"+
												   "<td>"+ value.file_path +"</td>"+
												   "<td><a href='#' class='edit-di'><i class='fa fa-edit ico'></i> / <a href='#' class='delete-di'><i class='fa fa-trash ico'></i></a></td></tr>");
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
	
	

});