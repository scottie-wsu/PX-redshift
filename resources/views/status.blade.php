<!doctype html>
<html lang="en">
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
        <title>Check Status - Redshift</title>
        <style>
            body, html {
                height: 100%;
            }
        </style>
    </head>
    <body>
        <!--Navbar-->
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <a class="navbar-brand" href="#">Redshift</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                        <a class="nav-link" href="/history">History</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/logout">Logout</a>
                    </li>
                <ul>
            </div>
        </nav>
        <!--Main container -->
        <div class="container d-flex h-100">
            <div class="row align-self-center w-100">
                <div class="col-sm-12 mx-auto" id = "checking-col">
                    <h2 align=center>Checking...</h2>
                </div>
                <div class="col-lg-12 mx-auto" id = "processed-col" style = "display: none;">
                    <h2>Nothing processing in background, all requests processed! <img src={{ asset('images/bootstrap-icons-1.0.0/emoji-laughing.svg') }} alt="" width="64" height="64" title="Bootstrap"></h2>
                </div>
                <div class="col-lg-12 mx-auto" id = "processing-col" style = "display: none;">
                    <h2><img src={{ asset('images/bootstrap-icons-1.0.0/patch-exclamation.svg') }} alt="" width="32" height="32" title="Bootstrap"> Still processing, please wait...<span align=right>[<span id = "processed-count">10</span>/<span id = "total-count">30</span>]</span></h2>
                    <div class="progress">
                        <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" id = "status-progress-bar" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width: 1%"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Optional JavaScript -->
        <!-- jQuery first, then Popper.js, then Bootstrap JS -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="../../app/Http/Controllers/poll-status.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    </body>
</html>