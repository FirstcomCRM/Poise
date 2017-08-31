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
					$('#path').val('');
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

    /** 
     * Btn submit
     */
    $('#btn-submit').click(function(e) {
	  var password = $('#password').val();
	  var level = $('#level').val();
      e.preventDefault(); 
	  //alert(burl + "announcement/edit/" + $('#hid-announcement-id').val());
      if($('#password').val() != ''){
		  var password = password;
	  
	  }
	  else if($('#level').val() != ''){
		  var level = level;
	  }
	  
	  
	  
	  if( $('#username').val() == '' ) {
            alert("Please enter username");
      }
      else {
		 
        $.ajax({
          type: "POST",
          url: burl + "user/edit/" + $('#hid-user-id').val()+ '/TRUE',
          beforeSend : function( xhr ) {
            $('#btn-submit').html('<i class="fa fa-spinner ico-btn"> Processing...').prop('disabled', true);
          },
          data: { 
               name     				: $('#name').val(),
               username      			: $('#username').val(),
               password      			: password,
               role_id      			: $('#role-id').val(),
               team_id      			: $('#team-id').val(),
               level      				: $('#level').val(),
               user_belong_to   		: $('#user-belong-to').val(),
               email      				: $('#email').val(),
               contact      			: $('#contact').val(),
               nric      				: $('#nric').val(),
               cea_no      				: $('#cea-no').val(),
               commission      			: $('#commission').val(),
               co_broke_commission  	: $('#co-broke-commission').val(),
			   internal_commission		: $('#internal-commission').val(),
			   user_img					: $('#img-new-file-name').val(),
			   cv						: $('#cv-new-file-name').val(),
			   
			   //main_new_file_name     : $('#main-new-file-name').val(),
          },
          success: function(data){  
            $('#btn-submit').html('<i class="fa fa-save ico-btn"> Update').prop('disabled', false);
            var result = $.parseJSON(data);
            if(result['status'] == 'success') {
              window.location = burl + "user";
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
	

	

});