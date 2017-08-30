
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../../../favicon.ico">

    <title>URL Extractor - Amit Merchant</title>

    <!-- Bootstrap core CSS -->
    <link href="../css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="../css/starter-template.css" rel="stylesheet">
</head>

<body>

<nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top">
    <a class="navbar-brand" href="#">URL Extractor</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarsExampleDefault">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item active">
                <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
            </li>
        </ul>
    </div>
</nav>

<div class="container">

    <div class="starter-template">
        <div class="form-group">
            <h1>URL Extractor</h1>
            <div class="form-group">
                <form id="urlextractform" class="form-horizontal" role="form" method="POST">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <textarea class="form-control" id="extractor" name="extractor" rows="3"></textarea>
                </form>
            </div>
        </div>
        <div style="clear:both;"></div>
        <div class="form-group">
            <form id="url_info" role="form" method="POST">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div id="divUrlFetchInfo"></div>
                <div style="clear:both;"></div>
                <div>
                    <button type="button" id="submit_url" class="btn btn-success hidden">Save</button>
                </div>
            </form>
        </div>
        <div style="clear:both;"></div>
        <div class="form-group text-left">
            <h3>URLs stored in the database</h3>

            @foreach ($allUrls as $url)
                <p><a href="javascript:void(0);" onclick="fetchUrlInfo('{{ $url->url }}')">{{ $url->title }}</a></p>
            @endforeach

        </div>
    </div>

</div><!-- /.container -->


<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="../js/jquery-3.2.1.min.js"></script>
<script src="../js/popper.min.js"></script>
<script src="../js/bootstrap.min.js"></script>
<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
<script src="../js/ie10-viewport-bug-workaround.js"></script>
<script src="../js/custom.js"></script>
</body>
</html>
