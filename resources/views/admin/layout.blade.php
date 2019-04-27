<!DOCTYPE html>
<html>
    <head>
        <title>@yield('webpage_title') - Tasks Admin</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="">
        <meta name="author" content="Sinevia Ltd">
        <link rel="shortcut icon" type="image/vnd.microsoft.icon" href="favicon.ico" />
        <link rel="icon" type="image/png" href="favicon.png" />
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" />
        <script src="//code.jquery.com/jquery-3.4.0.min.js"></script>
        <link href="//stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet" />
        <script src="//stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>
    </head>
    <body>
        <!-- START: Main Content -->
        <?php $shared_errors = isset($shared_errors) ? $shared_errors : true; ?>
        <?php if ($shared_errors) { ?>
            @include('tasks::admin.shared-alerts')
        <?php } ?>
            
        <div class="container">
            @yield('webpage_header')
        </div>

        <div class="container">
            @yield('webpage_content')
        </div>
        <!-- END: Main Content -->
    </body>
</html>
