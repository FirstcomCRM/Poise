/*
 * jQuery File Upload Plugin JS Example
 * https://github.com/blueimp/jQuery-File-Upload
 *
 * Copyright 2010, Sebastian Tschan
 * https://blueimp.net
 *
 * Licensed under the MIT license:
 * https://opensource.org/licenses/MIT
 */

/* global $, window */
var arr={};
var key =0;

/* var files = new Array();
		$("#fileupload td p.name a").each(function() {
			var name = $(this).attr("href");
			files.push(name);
			console.log(files);
		}); */
   // alert(files.join('\n'));
$(function () {
    'use strict';

    // Initialize the jQuery File Upload widget:
    $('#fileupload').fileupload({
        // Uncomment the following to send cross-domain cookies:
        //xhrFields: {withCredentials: true},
        url: '../server/php/'
    }).on('fileuploadsubmit', function (e, data) {
		 // data.formData = data.context.find(':input').serializeArray();
	/* 	data.formData = data.context.find('#form-files').serializeArray();*/
//var	formData = $('#file-form').serializeObject();
	//	var key = getLastindex();
		//arr[++key] =formData; 
		//console.log(formData); 
	/* 	var filesList = $('input[type="file"]').prop('files');
		console.log(filesList);
		$('#fileupload').fileupload('add', {files: filesList});	 */
	});
 
	
	/*  $('#fileupload').fileupload({
        // Uncomment the following to send cross-domain cookies:
        //xhrFields: {withCredentials: true},
        url: '../server/php/'
    }); */
	
	
	 
	
	
    // Enable iframe cross-domain access via redirect option:
    $('#fileupload').fileupload(
        'option',
        'redirect',
        window.location.href.replace(
            /\/[^\/]*$/,
            '/cors/result.html?%s'
        )
    );
	
/* 	$('#fileupload').bind('fileuploadcompleted', function (e, data) {
     var filess= data.result.files[0];
        var filenam = filess.name;
      // alert(filenam);
	 var key = getLastindex();
						arr[++key] = filess;
						console.log(arr);
	  
}); */
	
    if (window.location.hostname === 'blueimp.github.io') {
        // Demo settings:
        $('#fileupload').fileupload('option', {
            url: '//jquery-file-upload.appspot.com/',
            // Enable image resizing, except for Android and Opera,
            // which actually support image resizing, but fail to
            // send Blob objects via XHR requests:
            disableImageResize: /Android(?!.*Chrome)|Opera/
                .test(window.navigator.userAgent),
            maxFileSize: 999000,
            acceptFileTypes: /(\.|\/)(gif|jpe?g|png)$/i
        });
        // Upload server status check for browsers with CORS support:
        if ($.support.cors) {
            $.ajax({
                url: '//jquery-file-upload.appspot.com/',
                type: 'HEAD'
            }).fail(function () {
                $('<div class="alert alert-danger"/>')
                    .text('Upload server currently unavailable - ' +
                            new Date())
                    .appendTo('#fileupload');
            });
        }
		
		
    } else {
		
		
		
		
        // Load existing files:
        $('#fileupload').addClass('fileupload-processing');
        $.ajax({
            // Uncomment the following to send cross-domain cookies:
            //xhrFields: {withCredentials: true},
            url: $('#fileupload').fileupload('option', 'url'),
            dataType: 'json',
            context: $('#fileupload')[0]
        }).always(function () {
            $(this).removeClass('fileupload-processing');
        }).done(function (result) {
            $(this).fileupload('option', 'done')
                .call(this, $.Event('done'), {result: result});
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
	
	
	