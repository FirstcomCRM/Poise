$( document ).ready(function() {

    var arr = {};
    var key = 0;

    $(window).on('beforeunload', function(){        
        if (!$.isEmptyObject(arr)) {
          return 'When you leave right now, the data will not be saved.';
        }
    });

    $('#productionoption-add').click(function(e) { 
        e.preventDefault();
        if ( $('#s-name').val() == '') {
            alert("Please Enter Option Name");
        }
        else {
            var formdata = $("#productionoption-form").serializeObject(); 
            var key = getLastindex();
            arr[++key] = formdata;
            updateTable();
            resetForm();
        }   
    });
    
    /** Edit Site **/
    $('#productionoption-table').on('click', '.edit-di', function(e) {
        e.preventDefault(); 
        var classname = $(this).closest("tr").attr('class');
        var row = classname.split('-');
        $('#hid-edit-id').val(row[1]);
        $('#s-name').val(arr[row[1]].name);
        $('#s-description').val(arr[row[1]].description);
        // $('#qty').val(arr[row[1]].qty);
        $('#productionoption-add').hide();
        $('#cp-update').show();  
        $(".productionoption-add").insertAfter( $( ".id-" + row[1] ) );  
    });

    /** Delete Site **/
    $('#productionoption-table').on('click', '.delete-di', function(e) {
        e.preventDefault(); 
        var classname = $(this).closest("tr").attr('class');
        var row = classname.split('-');
        delete arr[row[1]];
        $( ".productionoption-add" ).appendTo( "tfoot" );
        updateTable();
        resetForm();       
    });

    /** Add Item **/
    $('#productionoption-update').click(function(e) { 
        e.preventDefault();
        if ( $('#s-name').val() == '') {
            alert("Please Enter name");
        }
        else { 
            var formdata = $("#productionoption-form").serializeObject(); 
            var editkey = $("#hid-edit-id").val();  
            arr[editkey] = formdata;
            $( ".productionoption-add" ).appendTo( "tfoot" );
            updateTable();
            resetForm();
        }   
    });

    /** Add Item **/
    $('#productionoption-cancel').click(function(e) { 
        e.preventDefault();    
        resetForm();
        $( ".productionoption-add" ).appendTo( "tfoot" );
    });


    /** 
     * Btn submit
     */
    $('#btn-submit').click(function(e) {   
        e.preventDefault();
        if( $('#name').val() == '' ) {
            alert("Please Enter Production Name");
        }
        // else if ( $.isEmptyObject(arr) ) {
        //     alert("Please add at lease one production option information.");   
        // }
        else {
            $.ajax({
                type: "POST",
                url: burl + "production/create/1", 
                beforeSend : function( xhr ) {
                    $('#btn-submit').html('<i class="fa fa-spinner ico-btn"> Processing...').prop('disabled', true);
                },
                data: { 
                    name                   : $('#name').val(),
                    description            : $('#description').val(),
                    productionoption_info  : arr,
                },
                success: function(data){  
                  $('#btn-submit').html('<i class="fa fa-save ico-btn"> Save').prop('disabled', false);
                  var result = $.parseJSON(data);
                  if(result['status'] == 'success') {
                    arr = {};
                    window.location = burl + "production";
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

    function updateTable() {
        $("#productionoption-table > tbody:last").children().remove();
        var no = 1; var sub_total = 0;
        $.each(arr, function( i, value ) {   
            var classname = 'id-' + i ;
            $('#productionoption-table > tbody:last').append("<tr class='"+classname+"'>"+
                                                   "<td>"+ no++ +"</td>"+
                                                   "<td>"+ value.name +"</td>"+
                                                   "<td>"+ value.description +"</td>"+
                                                   "<td><a href='#' class='edit-di'><i class='fa fa-edit ico'></i> / <a href='#' class='delete-di'><i class='fa fa-trash ico'></i></a></td></tr>");
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