$('#fileUpload').on('submit',(function(e) {
		e.preventDefault();
		var formData = new FormData(this);
		fileUpload(formData);
		return false;
	})
);
// Function fileUpload() is used for submit data to action.php
function fileUpload(formData){
	$.ajax({
		type:'POST',
		url: 'action.php',
		data:formData,
		dataType:"json",
		cache:false,
		contentType: false,
		processData: false,
		success:function(response){
			$(".filesAlert").remove();
			if(response.fileSuccess){
				for(f=0; f<response.fileSuccess.length; f++) {
					$("#response").prepend('<div class="alert alert-success filesAlert"> <strong>Success! </strong> '+response.fileSuccess[f]+'</div>');
				}
			}
			if(response.fileErr){
				for(f=0; f<response.fileErr.length; f++) {
					$("#response").prepend('<div class="alert alert-danger filesAlert"> <strong>Alert! </strong> '+response.fileErr[f]+'</div>');
				}	
			}
		},
		// Add error to console
		error: function(data){
			console.log("error");
			console.log(data);
		}
	});
}// end[fileUpload]
