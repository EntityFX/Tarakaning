<html>
    <head>
        <title>Ошибка</title>
    </head>
    <style>
    {literal}
    	h1 {
    		color: #a00;	
    	}
    	
    	dt {
    		float: left;
    		margin-right: 15px;
    		font-weight: bold;
    		text-align: right;
    		width: 400px;
    	}
    {/literal}
    </style>
    <body>
        <h1>Ошибка 404</h1>
        <dl>
            <dt>Запрашиваемая вами страница не существует</dt>
            <dd>{$ErrorURL} </dd>            
            <dt>Базовая ссылка</dt>
            <dd>{$BaseURL} </dd>            
        </dl>
    </body>
</html>