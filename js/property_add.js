	var _validFileExtensionsImg = [".jpg", ".jpeg", ".bmp", ".gif", ".png"];    

	
	
	
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

 	//var arr = {};
  	var key = 0;

  /* 	$(window).on('beforeunload', function(){        
        if (!$.isEmptyObject(arr)) {
          return 'When you leave right now, the data will not be saved.';
        }
    }); */
	
	
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
			url : burl + "property/upload_main_img",
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
	
	
	
	
	
	
	
	
	
	
    /** 
     * Btn submit
     */
    $('#btn-submit').click(function(e) {   
        e.preventDefault();
		if( $('#property-title').val() == '' ) {
            alert("Please Enter Property Title");
        }
        else {
           var pr_stat = $('#property-status').val();
		 if( pr_stat == 'Draft' ) {
			 var xx = confirm('This will only be saved as draft. continue');
				if (xx ==true){
					create(pr_stat);
				}
				else{
					//nothing
				}				
		 }
		else if (pr_stat==''){
			pr_stat = 'Pending';
			create (pr_stat);
		}
		 else{
			 create(pr_stat);
		 }
        }
    }); 

	

    function getLastindex() {
        var lastindex = 0;
        $.each(arr, function( i, value ) {
            lastindex = i;
        });    
        return lastindex;
    }
	
	function create(pr_stat){
		
		  $.ajax({
                type: "POST",
                url: burl + "property/create/TRUE", 
                beforeSend : function( xhr ) {
                    $('#btn-submit').html('<i class="fa fa-spinner ico-btn"> Processing...').prop('disabled', true);
                },
                data: { 
                    property_title        	: $('#property-title').val(),
                    tenure             		: $('#tenure').val(),
					location				: $('#location').val(),
                    district             	: $('#district').val(),
                    category         		: $('#category').val(),
                    address          		: $('#address').val(),
                    unit_size             	: $('#unit-size').val(),
                    land_area             	: $('#land-area').val(),
                    no_of_bedrooms          : $('#no-of-bedrooms').val(),
                    property_price          : $('#property-price').val(),
                    price_currency          : $('#price_currency').val(),
                    meta_description        : $('#meta-description').val(),
                    meta_robots_index       : $('#meta-robots_index').val(),
                    meta_robots_follow      : $('#meta-robots_follow').val(),
                    meta_keywords           : $('#meta-keywords').val(),
					main_new_file_name   : $('#main-new-file-name').val(),
                    property_status         : pr_stat,
				},

                success: function(data){  
                  $('#btn-submit').html('<i class="fa fa-save ico-btn"> Save').prop('disabled', false);
                  var result = $.parseJSON(data);
                  if(result['status'] == 'success') {
                    //arr = {};
                    window.location = burl + "property";
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