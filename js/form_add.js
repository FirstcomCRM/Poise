var _validFileExtensions = [".pdf", ".doc", ".docx",".xls",".xlsx",".csv"];   
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
	var key = 0;
	
	
	$(window).on('beforeunload', function(){        
        if (!$.isEmptyObject(arr)) {
          return 'When you leave right now, the data will not be saved.';
        }
    });
	
	/*  if (typeof detail_arr !== 'undefined') {  
        $.each(detail_arr, function( i, value ) {  
            arr[++key] = value;   
        });
        updatePDTable(arr);
        var key = $('#tbl-payment-detail tbody tr').length - 1;
    } */
	 
	$('#ico-add').click(function(e) { 
        e.preventDefault();
        if ( $('#path').val() == '') {
            alert("Please Upload file");
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
			url : burl + "form_agreement/upload",
			method : "POST",
			data: new FormData(this),
			contentType:false,
			processData:false,
			success: function(data){

               $('#detail-form').html(data); 

				
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
        if( $('#form-title').val() == '' ) {
            alert("Please add form title");
        }
        else {
		
            $.ajax({
				
                type: "POST",
                url: burl + "form_agreement/create/TRUE", 
                beforeSend : function( xhr ) {
                    $('#btn-submit').html('<i class="fa fa-spinner ico-btn"> Processing...').prop('disabled', true);
                },
                data: { 
                    hid_submitted       : $('#hid-submitted').val(),
                    form_title  : $('#form-title').val(),
                    category_id   : $('#category-id').val(),
					files_info			: arr,
                },
                success: function(data){  
                  $('#btn-submit').html('<i class="fa fa-save ico-btn"> Save').prop('disabled', false);
                  var result = $.parseJSON(data);
                  if(result['status'] == 'success') {
					  arr = {};
                    window.location = burl + "form_agreement";
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