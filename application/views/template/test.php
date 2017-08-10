<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>NGEE BENG TRADING</title>
    <!-- Bootstrap Core CSS -->
    <link href="<?= base_url(); ?>css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="<?= base_url(); ?>css/style.css" rel="stylesheet">
    <script src="<?= base_url(); ?>js/jquery-1.11.1.js"></script>
    <!-- Bootstrap Core JavaScript -->
    <script src="<?= base_url(); ?>js/bootstrap.min.js"></script>
    <!-- Metis Menu Plugin JavaScript -->
    <!-- Custom Theme JavaScript -->
    

    <style>
    .page-break {
        page-break-after: always;
    } 
    </style>
    <script>
        $(function(){ 
            var totalHeight = 0;
            var header = '<div class="header">HEADER</div>';
            var footer = '<div class="footer">FOOTER</div>';
            var count = $('.child').length;
            $( ".child" ).each(function( index ) {

                if(index == 0) {
                    $( this ).before(header);   
                }
                
                totalHeight = totalHeight + $( this ).height();

                if( totalHeight >= 300 ) {
                    $( this ).before(footer+header);
                    //$( this ).prepend("<br/> FOOTER </br>");
                    totalHeight = 0;
                }

                if(index+1 == count) {
                    $( this ).after(footer);   
                }

                //console.log( index + ": " + $( this ).text() );
            });

            $('.footer').addClass('page-break');
        });
    </script>
</head>

<body>

    <div id="wrapper">

       <div class="main-container">
            <div class="child div-1" style="height:250px"> Div 1</div>
            <div class="child div-2" style="height:100px"> Div 2</div>
            <div class="child div-3" style="height:400px"> Div 3</div>
            <div class="child div-4" style="height:100px"> Div 4</div>
            <div class="child div-5" style="height:100px"> Div 5</div>
            <div class="child div-6" style="height:100px"> Div 6</div>
            <div class="child div-7" style="height:100px"> Div 7</div>
            <div class="child div-8" style="height:100px"> Div 8</div>
            <div class="child div-9" style="height:100px"> Div 9</div>
            <div class="child div-10" style="height:100px"> Div 10</div>
       </div>
    </div>
    

</body>

</html>
