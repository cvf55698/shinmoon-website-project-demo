<html>
    <head>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" >
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" ></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" ></script>
        </style>
    </head>

    <body>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <a class="navbar-brand" href="">PHP Enviroment Requirements</a>
 
        </nav>
        <div id="error-block" class="container-fluid" style="position:relative;top:30px;">
            <?php
                foreach($error_arr as $error){
                    echo "<div class='alert alert-danger' role='alert'>$error</div>";
                }
            ?>
        </div>
    </body>
</html>