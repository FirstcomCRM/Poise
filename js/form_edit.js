
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


	
	
	updatePDTable(detail_arr);
	
	
	
	$('#ico-add').click(function(e) { 
    e.preventDefault();
    if ( $('#path').val() == '') {
      alert("Please Add a File");
    }
    else {
      $.ajax({
          type: "POST",
          url: burl + "form_agreement/aj_addFormfile", 
          data: { 
            hid_form_id   		: $('#hid-form-id').val(),
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
                updatePDTable(result['form_files']);
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
              url: burl + "form_agreement/aj_deleteFormfile/" + row[1], 
              data: { },
              success: function(data){ 
                console.log(data);
                var result = $.parseJSON(data);
                if(result['status'] == 'success') {
                    $( "#detail-row" ).insertBefore($( "#total-row" ) );
                    updatePDTable(result['form_files']);
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
		if( $('#form-title').val() == '' ) {
            alert("Please Upload File");
        }
        else {
            $.ajax({
                type: "POST",
                url: burl + "form_agreement/edit/"+$('#hid-form-id').val() +"/TRUE", 
                beforeSend : function( xhr ) {
                    $('#btn-submit').html('<i class="fa fa-spinner ico-btn"> Processing...').prop('disabled', true);
                },
                data: { 
                  form_title  : $('#form-title').val(),
                  category_id   : $('#category-id').val(),
				},

                success: function(data){  
                  $('#btn-submit').html('<i class="fa fa-save ico-btn"> Save').prop('disabled', false);
                  var result = $.parseJSON(data);
                  if(result['status'] == 'success') {
                    //arr = {};
                    window.location = burl + "form_agreement";
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
	
	function updatePDTable(arr) {
    $("#detail-table > tbody:last").children().remove();
    $.each(arr, function( i, value ) {   
        var classname = 'id-' + value.form_file_id ; 
       $('#detail-table > tbody:last').append("<tr class='"+classname+"'>"+
												   "<td>"+ value.file_name +"</td>"+
												   "<td>"+ value.file_path +"</td>"+
												   "<td> <a href='#' class='delete-di'><i class='fa fa-trash ico'></i></a></td></tr>");
       /* sub_total += (value.amount != '') ? parseFloat(value.amount) : 0;*/
	  // console.log(arr);
    });
   
    } 

});