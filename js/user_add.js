	var _validFileExtensions = [".pdf", ".doc", ".docx",".xls",".xlsx",".csv"];
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

	function clear_img(){
		
		$('#main_img_select').replaceWith($('#main_img_select').clone(true));
		$('#main-img-preview').not('.selected').hide();
		$('#main-img-preview.selected').prop('src', orig_src).removeClass('selected');
		$('#main-new-file-name').val('');
		$('#main_img_select').val('');
		$('#img-new-file-name').val('');
		$('#cv-new-file-name').val('');
		resetForm();
		
		
	}
$( document ).ready(function() {
    

    var arr = {};
	var key = 0;
	/* $(window).on('beforeunload', function(){        
        if (!$.isEmptyObject(arr)) {
          return 'When you leave right now, the data will not be saved.';
        }
    }); */
	
	/*  if (typeof detail_arr !== 'undefined') {  
        $.each(detail_arr, function( i, value ) {  
            arr[++key] = value;   
        });
        updatePDTable(arr);
        var key = $('#tbl-payment-detail tbody tr').length - 1;
    } */
	 
	
	
	$('#img_select').change(function(e){
	
	//var ext = document.getElementById("img_select").files[1].name;
	$('#detail-form').submit();
	//e.preventDefault();
	var img = $('#img_select').val();
	var news = document.getElementById("img_select").files[0].name;
	//$('#path').val(img);
	$('#path').val(burl + news);
	$('#file-name').val(news);

	
	});
	$('#detail-form').on('submit', function(e){
		e.preventDefault();
	
	    $.ajax({
			url : burl + "user/upload",
			method : "POST",
			data: new FormData(this),
			contentType:false,
			processData:false,
			success: function(data){

               $('#detail-form').html(data); 

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
		$('#img-new-file-name').val('');
		$('#cv-new-file-name').val('');
    });

	
	
	$('#img-new-file-name').change(function(e){
		//alert(zzzzz);
		vals = $('#img-new-file-name').val();
		$('#img-uploaded').val(vals);
		
	});
	$('#main_img_select').change(function(e){3
		if($('#main_img_select').val()!=''){
			$('#image-form').submit();
		}
		readURL(this);
		$('#main-img-preview').show();

		//e.preventDefault();
		//var img = $('#main_img_select').val();
		//var news = document.getElementById("main_img_select").files[0].name;
		//$('#path').val(img);
		//$('#path').val(burl + news);
		//$('#main-file-name').val(burl + news);

	
	});
	
	$('#main_cv_select').change(function(e){3
		if($('#main_cv_select').val()!=''){
			$('#cv-form').submit();
		}

	
	});
	
	
	
	
	
	$('#image-form').on('submit', function(e){
		e.preventDefault();
		//if($('#main_img_select').val()!='')
			$.ajax({
				url : burl + "user/upload_main_img",
				method : "POST",
				data: new FormData(this),
				contentType:false,
				processData:false,
				success: function(data){
					
				// /* 	url : burl + "announcement/upload",
					 // $('#main_img_select').val('');  
				   // $('#src_img_upload').modal('hide');  
				   $('#image-form2').html(data); 
				//alert();
				}
			})
		//}
		//else{
			
		//}
	});

	
	$('#cv-form').on('submit', function(e){
		e.preventDefault();
		//if($('#main_img_select').val()!='')
			$.ajax({
				url : burl + "user/upload",
				method : "POST",
				data: new FormData(this),
				contentType:false,
				processData:false,
				success: function(data){
					
				// /* 	url : burl + "announcement/upload",
					 // $('#main_img_select').val('');  
				   // $('#src_img_upload').modal('hide');  
				   $('#cv-form2').html(data); 
				//alert();
				}
			})
		//}
		//else{
			
		//}
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

});