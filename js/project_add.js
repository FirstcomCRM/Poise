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
		if( $('#description').val() == '' ) {
            alert("Please Enter Description");
        }
        else {
            $.ajax({
                type: "POST",
                url: burl + "project/create/TRUE", 
                beforeSend : function( xhr ) {
                    $('#btn-submit').html('<i class="fa fa-spinner ico-btn"> Processing...').prop('disabled', true);
                },
                data: { 
                    project_code        	: $('#project-code').val(),
                    description             : $('#description').val(),
                    project_ic           	: $('#project-ic').val(),
                    date             		: $('#date').val(),
                    category         		: $('#category').val(),
                    project_type          	: $('#project-type').val(),
                    status             		: $('#status').val(),
				},

                success: function(data){  
                  $('#btn-submit').html('<i class="fa fa-save ico-btn"> Save').prop('disabled', false);
                  var result = $.parseJSON(data);
                  if(result['status'] == 'success') {
                    //arr = {};
                    window.location = burl + "project";
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

	

    function getLastindex() {
        var lastindex = 0;
        $.each(arr, function( i, value ) {
            lastindex = i;
        });    
        return lastindex;
    }

});