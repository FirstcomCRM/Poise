$( document ).ready(function() {
	/** Add Item **/
    $('#productionoption-add').click(function(e) { 
        e.preventDefault();
        if ( $('#s-name').val() == '') {
            alert("Please Key in Name");
        }
        else {
        	$.ajax({
                type: "POST",
                url: burl + "production/addProductionoption", 
                data: { 
                    production_id       : $('#hid-production-id').val(),
                    name                : $('#s-name').val(),
                    description         : $('#s-description').val(),
                },
                success: function(data){  
                  var result = $.parseJSON(data);
                  if(result['status'] == 'success') {
                   	var classname = 'di-'+ result['production_option_id'];
                   	var sn = $('#productionoption-table tr').length - 1;
            		$('.productionoption-add').before("<tr class='"+ classname +"'>"+
                                    "<td>"+ sn +"</td>"+
                                    "<td>"+ $('#s-name').val()  +"</td>"+
                                    "<td>"+ $('#s-description').val()  +"</td>"+                                   
                                    "<td><a href='#' class='edit-di'><i class='fa fa-edit ico'></i></a> / " +
                                        "<a href='#' class='delete-di'><i class='fa fa-trash-o ico'></i></a></td>"+
                                    "</tr>");
		            $("#productionoption-form").each(function(){
                        this.reset();
                    });
                    $('#remark').trigger('autosize.resize');
                  }
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) { 
                  alert("ERROR!!!");           
                } 
            }); 
        }   
    });

	/** Edit Item **/
	$('#productionoption-table').on('click', '.edit-di', function(e) {
        e.preventDefault(); 
        var classname = $(this).closest("tr").attr('class');
        var row = classname.split('-');
        $.ajax({
            type: "POST",
            url: burl + "production/aj_getProductionoption/" + row[1], 
            data: { },
            success: function(data){  
                var result = $.parseJSON(data);
                if(result['status'] == 'success') {
                    $('#hid-edit-id').val(row[1]);
                    $('#s-name').val(result['productionoption']['name']);
                    $('#s-description').val(result['productionoption']['description']);
                    $('#cp-update').show();
                    $('#productionoption-add').hide(); 
                    $(".productionoption-add").insertAfter( $( ".di-" + row[1] ) );   
                }
                else {
                    alert('Something wrong with data.');
                }
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) { 
              alert("ERROR!!!");        
            } 
        });          
    });

	/** Add Item **/
    $('#productionoption-cancel').click(function(e) { 
        e.preventDefault();    
        $("#productionoption-form").each(function(){
            this.reset();
        });
        $('#cp-update').hide();
        $('#productionoption-add').show(); 
        $( ".productionoption-add" ).appendTo( "tfoot" );
    });

    /** Update Item **/ 
    $('#productionoption-update').click(function(e) { 
        e.preventDefault();
        if ( $('#s-name').val() == '') {
            alert("Please Key in Name");
        }
        else { 
            var editkey = $("#hid-edit-id").val(); 
            $.ajax({
                type: "POST",
                url: burl + "production/updateProductionoption/" + editkey, 
                data: { 
                    production_id   : $('#hid-production-id').val(),
                    name            : $('#s-name').val(),
                    description     : $('#s-description').val(),
                },
                success: function(data){  
                  var result = $.parseJSON(data);
                  if(result['status'] == 'success') {
            		var classname = 'di-'+editkey;
            		var sn = $('.di-'+editkey+'  td:first-child').text();
            		$('.di-'+editkey).replaceWith("<tr class='"+ classname +"'>"+
                                    "<td>"+ sn +"</td>"+
                                    "<td>"+ $('#s-name').val()  +"</td>"+
                                    "<td>"+ $('#s-description').val()  +"</td>"+                                 
                                    "<td><a href='#' class='edit-di'><i class='fa fa-edit ico'></i></a> / " +
                                        "<a href='#' class='delete-di'><i class='fa fa-trash-o ico'></i></a></td>"+
                                    "</tr>");
		            $("#productionoption-form").each(function(){
                        this.reset();
                    });
                    $('#cp-update').hide();
                    $('#productionoption-add').show(); 
                    $( ".productionoption-add" ).appendTo( "tfoot" );
                  }
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) { 
                  alert("ERROR!!!");           
                } 
            }); 
        }   
    });

	/** 
	 * Delete Item
	 */
	$('#productionoption-table').on('click', '.delete-di', function(e) {
        e.preventDefault(); 
            var classname = $(this).closest("tr").attr('class');
            var row = classname.split('-');
            var r = confirm("Are u sure to remove this productionoption!");
            if (r == true) {
                $.ajax({
                    type: "POST",
                    url: burl + "production/removeProductionoption/" + row[1], 
                    data: { },
                    success: function(data){  
                        var result = $.parseJSON(data);
                        if(result['status'] == 'success') {
                            $(".di-"+row[1]).remove();
                            $('#cp-update').hide();
                            $('#productionoption-add').show(); 
                            resetForm(); 
                            $( ".productionoption-add" ).appendTo( "tfoot" ); 
                        }
                        else {
                            alert('Something wrong with data.');
                        }
                    },
                    error: function(XMLHttpRequest, textStatus, errorThrown) { 
                      alert("ERROR!!!");        
                    } 
                });        
            }            
    }); 


	/** 
     * Update Submit
     */
    $('#btn-submit').click(function(e) {   
        if ( $('#name').val() == '') {
            alert("Please Key in Client Name");
        }
        else {
            $.ajax({
                type: "POST",
                url: burl + "production/edit/" + $('#hid-production-id').val(), 
                data: { 
                    name                :   $('#name').val(),
                    description         :   $('#description').val(),
                },
                success: function(data){  
                  var result = $.parseJSON(data);
                  if(result['status'] == 'success') {
                    window.location = burl + "production";
                  }
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) { 
                  alert("ERROR!!!");           
                } 
            });   
        }
    }); 
        
});