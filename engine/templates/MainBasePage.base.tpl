<!DOCTYPE html>
<html>
    <head>
        <title>Tarakaning</title>
        <meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
            <link href="/css/bootstrap.css" rel="stylesheet" type="text/css" />
            <link href="/css/bootstrap-responsive.css" rel="stylesheet" type="text/css" />
            <link href="/css/ui.custom.css" rel="stylesheet" type="text/css" />
            <script type="text/javascript" src="/js/jquery.min.js"></script>
            <script type="text/javascript" src="/js/operationResult.js"></script>
            <script type="text/javascript" src="/js/j.checkboxes.js"></script>
            <script type="text/javascript" src="/js/bootstrap.min.js"></script>
            {block name=script}{/block}
    </head>
    <body lang="ru">
        <div class="navbar navbar-fixed-top">
            {block name=menu}{/block}
        </div>
        <div class="container-fluid">
            <div class="row-fluid">
                {block name=info}{/block}
            </div>
            <div class="row-fluid">
                {block name=body}{/block}
            </div>
            <hr />
            <footer>
                <p>&copy; EntityFX</p>    
            </footer>
        </div>
    </body>
</html>