<!DOCTYPE html>
<html>
    <head>
        <title>Tarakaning</title>
        <meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
        <link href="/css/ui.custom.css" rel="stylesheet" type="text/css" />
        <link href="/css/bootstrap.css" rel="stylesheet" type="text/css" />
        <link href="/css/bootstrap-responsive.css" rel="stylesheet" type="text/css" />
        
        <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
        <!--[if lt IE 9]>
          <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->

        <script type="text/javascript" src="/js/jquery.min.js"></script>
        <script type="text/javascript" src="/js/operationResult.js"></script>
        <script type="text/javascript" src="/js/j.checkboxes.js"></script>
        <script type="text/javascript" src="/js/bootstrap.min.js"></script>
        {block name=script}{/block}
    </head>
    <body lang="ru" data-offset="50" >
        <div class="navbar navbar-fixed-top">
            {block name=menu}{/block}
        </div>
        <div class="container">
            <div class="row">
                {block name=info}{/block}
            </div>
            <div class="row" id="main_row">
                {block name=body}{/block}
            </div>
            <hr />
            <footer>
                <p>&copy; EntityFX</p>    
            </footer>
        </div>
    </body>
</html>