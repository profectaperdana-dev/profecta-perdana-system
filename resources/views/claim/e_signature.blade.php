<html>
<head>
    <title>Laravel Signature Pad Tutorial Example - ItSolutionStuff.com </title>
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/css/bootstrap.css">
  
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script> 
    <link type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/south-street/jquery-ui.css" rel="stylesheet"> 
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <script src="{{asset('js/jquery.ui.touch-punch.min.js')}}"></script>

    <script type="text/javascript" src="http://keith-wood.name/js/jquery.signature.js"></script>
  
    <link rel="stylesheet" type="text/css" href="http://keith-wood.name/css/jquery.signature.css">
  
    <style>
        .kbw-signature { width: 100%; height: 500px;}
        #sig canvas{
            width: 100% !important;
            height: auto;
        }
    </style>
  
</head>
<body class="bg-dark">
<div class="container-fluid">
   <div class="row justify-content-center">
       <div class="col-md-11 mt-2">
           <div class="card">
               <div class="card-body">
                    <form method="POST" action="">
                        @csrf
                        <div class="col-12 form-group">
                            <label class="" for="">Signature:</label>
                            <br/>
                            <div id="sig" ></div>
                            <br/>
                            <textarea id="signature64" name="signed" style="display: none"></textarea>
                        </div>
                        <div class="col-md-12 form-group">
                        <button id="clear" class="btn btn-danger">Clear Signature</button>
                        <button class="btn btn-success">Save</button>
                        </div>
                        <br/>
                    </form>
               </div>
           </div>
       </div>
   </div>
</div>
<script type="text/javascript">
    var sig = $('#sig').signature({syncField: '#signature64', syncFormat: 'PNG'});
    $('#clear').click(function(e) {
        e.preventDefault();
        sig.signature('clear');
        $("#signature64").val('');
    });
</script>
</body>
</html>