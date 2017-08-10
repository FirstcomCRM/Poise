/*---LEFT BAR ACCORDION----*/
$(function() {
   /*  $('#nav-accordion').dcAccordion({
        eventType: 'click',
        autoClose: true,
        saveState: true,
        disableLink: true,
        speed: 'slow',
        showCount: false,
        autoExpand: true,
        //cookie: 'dcjq-accordion-1',
        classExpand: 'dcjq-current-parent'
    }); */

    jQuery('#sidebar .sub-menu > a').click(function () {
        var o = ($(this).offset());
        diff = 250 - o.top;
        if(diff>0)
            $("#sidebar").scrollTo("-="+Math.abs(diff),500);
        else
            $("#sidebar").scrollTo("+="+Math.abs(diff),500);
    });

    $('.fa-bars').click(function () {
        if ($('#sidebar > ul').is(":visible") === true) {
            $('#main-content').css({
                'margin-left': '0px'
            });
            $('#sidebar').css({
                'margin-left': '-210px'
            });
            $('#sidebar > ul').hide();
            $("#container").addClass("sidebar-closed");
        } else {
            $('#main-content').css({
                'margin-left': '210px'
            });
            $('#sidebar > ul').show();
            $('#sidebar').css({
                'margin-left': '0'
            });
            $("#container").removeClass("sidebar-closed");
        }
    });

    function responsiveView() {
        var wSize = $(window).width();
        if (wSize <= 768) {
            $('#container').addClass('sidebar-close');
            $('#sidebar > ul').hide();
        }

        if (wSize > 768) {
            $('#container').removeClass('sidebar-close');
            $('#sidebar > ul').show();
        }
    }
    $(window).on('load', responsiveView);
    $(window).on('resize', responsiveView);

    // custom scrollbar
  //  $("#sidebar").niceScroll({styler:"fb",cursorcolor:"#555299", cursorwidth: '3', cursorborderradius: '10px', background: '#404040', spacebarenabled:false, cursorborder: ''});

    //$("html").niceScroll({styler:"fb",cursorcolor:"#555299", cursorwidth: '14', cursorborderradius: '10px', background: '#404040', spacebarenabled:false,  cursorborder: '', zindex: '1000'});

    // widget tools
    jQuery('.panel .tools .fa-chevron-down').click(function () {
        var el = jQuery(this).parents(".panel").children(".panel-body");
        if (jQuery(this).hasClass("fa-chevron-down")) {
            jQuery(this).removeClass("fa-chevron-down").addClass("fa-chevron-up");
            el.slideUp(200);
        } else {
            jQuery(this).removeClass("fa-chevron-up").addClass("fa-chevron-down");
            el.slideDown(200);
        }
    });

    jQuery('.panel .tools .fa-times').click(function () {
        jQuery(this).parents(".panel").parent().remove();
    });


    //Tool tips
    $('.tooltips').tooltip();

    //Popovers
  //  $('.popovers').popover();

    // Custom bar chart
    if ($(".custom-bar-chart")) {
        $(".bar").each(function () {
            var i = $(this).find(".value").html();
            $(this).find(".value").html("");
            $(this).find(".value").animate({
                height: i
            }, 2000)
        })
    }


    //Custom Script

    $('.go-top').click(function(){
        $('html, body').animate({scrollTop : 0}, 500);
        return false;
    });


    $.fn.serializeObject = function() {
        var o = {};
        var a = this.serializeArray();
        $.each(a, function() {
            if (o[this.name] !== undefined) {
                if (!o[this.name].push) {
                    o[this.name] = [o[this.name]];
                }
                o[this.name].push(this.value || '');
            } else {
                o[this.name] = this.value || '';
            }
        });
        return o;
    };

    $.fn.dataTableExt.oApi.fnStandingRedraw = function(oSettings) {
        //redraw to account for filtering and sorting
        // concept here is that (for client side) there is a row got inserted at the end (for an add)
        // or when a record was modified it could be in the middle of the table
        // that is probably not supposed to be there - due to filtering / sorting
        // so we need to re process filtering and sorting
        // BUT - if it is server side - then this should be handled by the server - so skip this step
        if(oSettings.oFeatures.bServerSide === false){
            var before = oSettings._iDisplayStart;
            oSettings.oApi._fnReDraw(oSettings);
            //iDisplayStart has been reset to zero - so lets change it back
            oSettings._iDisplayStart = before;
            oSettings.oApi._fnCalculateEnd(oSettings);
        }
          
        //draw the 'current' page
        oSettings.oApi._fnDraw(oSettings);
    };

    /* Quck Modal Hide */
    $('#quickModal').on('hidden.bs.modal', function () {
        $('#quick-form').each(function(){
            this.reset();
        });
        $('#quick-form input, #quick-form select').each(function() {
            if($(this).parent().parent().hasClass('has-error')) {
                $(this).parent().parent().removeClass('has-error');
            }
        });
        $('.err-display').html('');
        $('.err-display').hide();     
    });

    $("body").tooltip({ selector: '[data-toggle="tooltip"]' });


});

String.prototype.capitalize = function() {
    return this.charAt(0).toUpperCase() + this.slice(1);
}

function validateForm(formname) {
    if(!$('#'+ formname)[0].checkValidity()) {
        var message = '';
        $("#" +formname+ " input, #" +formname+ " select").each(function() {
          if($(this).prop('required')) {
            if( $(this).val() == '' ) {
              $(this).parent().parent().addClass('has-error');
              message += "Please " + $(this).attr('placeholder').capitalize() + "<br/>";
            }
            else {
              $(this).parent().parent().removeClass('has-error');
            }
          }
        }); 
        $('.err-display').html(message);
        $('.err-display').show(); 
        return false;
    }
    else {
        $("#" +formname+ " input, #" +formname+ " select").each(function() {
            if($(this).parent().parent().hasClass('has-error')) {
                $(this).parent().parent().removeClass('has-error');
            }
        });
        $('.err-display').html('');
        $('.err-display').hide(); 

        return true;
    }   
}

function emptyForm(formname) {
    $("#" + formname).each(function(){
        this.reset();
    });
    $('.err-display').html('');
    $('.err-display').hide(); 
}

function getIdfromURL(url) {
    var returl = url.split("/");
    return returl[returl.length-1];
}

function quickAjaxsubmit(url, values, tbl) {
    $.ajax({
        type :  "POST",
        url  :  burl + url,
        data :  values,
        success: function(data){  
            //alert(data);
            var result = $.parseJSON(data);
            if(result['status'] == 'success') {
                //tbl.api().ajax.reload();
                tbl.fnStandingRedraw();
                emptyForm('quick-form');
                $("#quickModal").modal('hide');
                $(".success-alert-area").empty().append("<div class='alert alert-success success-display'><a href='#' class='close' data-dismiss='alert'>&times;</a>"+result['msg']+"</div>");
            }
            else {
              $('.err-display').html(result['msg']);
              $('.err-display').show();
            }
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) { 
            alert("ERROR!!!");     
            alert(errorThrown);      
        } 
    });
}

function quickSummarysubmit(url, values, tbl, modal, form) {
    $.ajax({
        type :  "POST",
        url  :  burl + url,
        data :  values,
        success: function(data){  
            var result = $.parseJSON(data);
            if(result['status'] == 'success') {
                tbl.fnStandingRedraw();
                $("#"+modal).modal('hide');
                $(".success-alert-area").empty().append("<div class='alert alert-success success-display'><a href='#' class='close' data-dismiss='alert'>&times;</a>"+result['msg']+"</div>");
            }
            else {
              $('.err-display').html(result['msg']);
              $('.err-display').show();
            }
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) { 
            alert("ERROR!!!");     
            alert(errorThrown);      
        } 
    });
}


function getEditvalues(url) {
    $.ajax({
        type :  "POST",
        url  :  burl + url,
        //async : false,
        data :  {},
        success: function(data){ 
            var result = $.parseJSON(data);
            if(result['status'] == 'success') {
                $.each(result, function( index, value ) {
                    if ($("[name='"+index+"']").length) {
                        if($("[name='"+index+"']").is("input")) {
                            if( $("[name='"+index+"']").attr('type') == 'text' ) {
                                $("input[name='"+index+"']").val(value);  
								$("img[name='"+index+"']").prop('src', value).show();								
                            }
							else if( $("[name='"+index+"']").attr('type') == 'hidden' ) {
								  $("input[name='"+index+"']").val(value);  
								$("img[name='"+index+"']").prop('src', value).show();			
							}
                        }
                        else if ($("[name='"+index+"']").is("textarea")) {
                            $("textarea[name='"+index+"']").val(value);   
                        }
                        else if ($("[name='"+index+"']").is("select")) {
                            $("select[name='"+index+"']").val(value);   
                        }
						else if ($("[name='"+index+"']").is("img")) {
							$("img[name='"+index+"']").prop('src', value).show();         
                        
							/* if(index == 'user_img'){
								$('#img-new-file-name').val(value);
							} */
						}
						else if ($("[name='"+index+"']").is("a")) {
							alert();
                            $("a[name='"+index+"']").attr('href')=value;   
                        }
                    }
                });
            }
            else {
               alert(result['msg']);
            }
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) { 
            alert("ERROR!!!");     
            alert(errorThrown);      
        } 
    }); 
}

function deleteAjax(url, tbl) {
    $.ajax({
        type :  "POST",
        url  :  burl + url,
        data :  {},
        success: function(data){ 
            var result = $.parseJSON(data);
            if(result['status'] == 'success') {
                $(".success-alert-area").empty().append("<div class='alert alert-success success-display'><a href='#' class='close' data-dismiss='alert'>&times;</a>"+result['msg']+"</div>");
                //tbl.api().ajax.reload();  
                tbl.fnStandingRedraw();
            }
            else {
                if( "msg" in result ) {
                    alert(result['msg']);
                }
                else {
                    alert('Something Wrong');    
                }
            }
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) { 
            alert("ERROR!!!");     
            alert(errorThrown);      
        } 
    });    
}


function myFixed(value, places) {
    //Check Numeric or Not
    if( $.isNumeric( value ) ) {
        //Check full number or Decimal number
        if(value % 1 != 0 || value.toString().indexOf('.') != -1) {
            var valSplit = value.toString().split(".");
            var decVal = valSplit[1];
            var decLen = decVal.toString().length; 
            if(places > decLen) { 
                var result = value;
                for (i = 0; i < places - decLen; i++) { 
                    result +='0';
                }   
            }
            else if(places < decLen){
                var result = valSplit[0] + '.' + decVal.toString().slice(0, places);
            }
            else {
                var result = value;
            }
        }
        else {
            var result = value + '.';
            for (i = 0; i < places; i++) { 
                result +='0';
            }
        }
        return result;
    }
    else {
        return value;
    }
}