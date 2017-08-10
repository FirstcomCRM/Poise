$( document ).ready(function() {

 	//var arr = {};
  	var key = 0;

  /* 	$(window).on('beforeunload', function(){        
        if (!$.isEmptyObject(arr)) {
          return 'When you leave right now, the data will not be saved.';
        }
    }); */

    
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