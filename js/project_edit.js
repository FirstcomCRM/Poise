$( document ).ready(function() {

 

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
          url: burl + "project/edit/" + $('#hid-project-id').val() + '/TRUE', 
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
            $('#btn-submit').html('<i class="fa fa-save ico-btn"> Update').prop('disabled', false);
            var result = $.parseJSON(data);
            if(result['status'] == 'success') {
              window.location = burl + "project";
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