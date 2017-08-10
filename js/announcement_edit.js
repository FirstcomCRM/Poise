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
	
	updatePDTable(detail_arr);
	
	$('#ico-add').click(function(e) { 
    e.preventDefault();
    if ( $('#path').val() == '') {
      alert("Please Upload File");
    }
    else {
      $.ajax({
          type: "POST",
          url: burl + "announcement/aj_addAnnouncefile", 
          data: { 
            hid_announcement_id   		: $('#hid-announcement-id').val(),
            file_name	   		   : $('#file-name').val(),
            new_file_name		 : $('#new-file-name').val(),
            file_path				: $('#file-path').val(),
            //date_uploaded		: $('#amount').val(),
          },
          success: function(data){ 
            //console.log(data); 
            var result = $.parseJSON(data);
            if(result['status'] == 'success') {
				$('#detail-form').submit();
                updatePDTable(result['announcement_files']);
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
			url : burl + "announcement/upload_main_img",
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
			url : burl + "announcement/upload",
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
	
	
	

	
	
	
	
	
	
	 $('#detail-table').on('click', '.delete-di', function(e) {
        e.preventDefault(); 
        var r = confirm("Are you sure to remove this file?");
        if (r == true) {
          var classname = $(this).closest("tr").attr('class');
          var row = classname.split('-');
          $.ajax({
              type: "POST",
              url: burl + "announcement/aj_deleteAnnouncefile/" + row[1], 
              data: { },
              success: function(data){ 
                console.log(data);
                var result = $.parseJSON(data);
                if(result['status'] == 'success') {
                    $( "#detail-row" ).insertBefore($( "#total-row" ) );
                    updatePDTable(result['announcement_files']);
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
	
	


    /** 
     * Btn submit
     */
    $('#btn-submit').click(function(e) {   
      e.preventDefault(); 
	  //alert(burl + "announcement/edit/" + $('#hid-announcement-id').val());
      if( $('#announcement-body').val() == '' ) {
            alert("Please add announcement detail");
      }
      else {
		 
        $.ajax({
          type: "POST",
          url: burl + "announcement/edit/" + $('#hid-announcement-id').val(), 
          beforeSend : function( xhr ) {
            $('#btn-submit').html('<i class="fa fa-spinner ico-btn"> Processing...').prop('disabled', true);
          },
          data: { 
               announcement_title     : $('#announcement-title').val(),
               announcement_body      : $('#announcement-body').val(),
               announcement_date      : $('#announcement-date').val(),
			   main_new_file_name     : $('#main-new-file-name').val(),
          },
          success: function(data){  
            $('#btn-submit').html('<i class="fa fa-save ico-btn"> Update').prop('disabled', false);
            var result = $.parseJSON(data);
            if(result['status'] == 'success') {
              window.location = burl + "announcement";
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
	
	function updatePDTable(arr) {
    $("#detail-table > tbody:last").children().remove();
    $.each(arr, function( i, value ) {   
        var classname = 'id-' + value.announce_file_id ; 
       $('#detail-table > tbody:last').append("<tr class='"+classname+"'>"+
												   "<td>"+ value.file_name +"</td>"+
												   "<td>"+ value.file_path +"</td>"+
												   "<td> <a href='#' class='delete-di'><i class='fa fa-trash ico'></i></a></td></tr>");
       /* sub_total += (value.amount != '') ? parseFloat(value.amount) : 0;*/
	  // console.log(arr);
    });
   
    } 
	
	
	

});