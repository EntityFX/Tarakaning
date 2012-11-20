<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');
// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
    'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
    'name' => 'EntityFX',
    'language' => 'ru',
    // preloading 'log' component
    'preload' => array('log'),
    // autoloading model and component classes
    'import' => array(
        'application.models.*',
        'application.components.*',
        'application.components.ioc.*',
        'application.components.captcha.*',
        'application.components.widgets.*',
        'application.components.common.*',
        'application.components.common.database.tables.*',
        'application.components.common.database.views.*',
        'application.components.common.database.storedProcedures.*',
        'application.components.common.enum.*',
        'application.components.common.helpers.*',
        'application.components.services.*',
        'application.components.services.user.*',
        'application.components.services.profile.*',
        'application.components.services.project.*',
        'application.components.services.project.enum.*',
        'application.components.services.locale.*',
        'application.components.services.subscribe.*',
        'application.components.services.subscribe.enum.*',
        'application.components.services.request.*',
        'application.components.services.item.*',
        'application.components.services.item.enum.*',
        'application.components.services.comment.*',
        'application.controllers.*',
        'application.vendors.phemto.Phemto'
    ),
    'modules' => array(
    // uncomment the following to enable the Gii tool
    /*
      'gii'=>array(
      'class'=>'system.gii.GiiModule',
      'password'=>'Enter Your Password Here',
      // If removed, Gii defaults to localhost only. Edit carefully to taste.
      'ipFilters'=>array('127.0.0.1','::1'),
      ),
     */
    ),
    // application components
    'components' => array(
        'user' => array(
            // enable cookie-based authentication
            'allowAutoLogin' => true
        ),
        // uncomment the following to enable URLs in path-format

        'urlManager' => array(
            'urlFormat' => 'path',
            'rules' => array(
                '/' => 'site/index',
                'login' => 'auth/login',
                'logout' => 'auth/logout',
                'registration' => 'auth/register',
                'projects' => 'project/index',
                '<controller:\w+>/<id:\d+>' => '<controller>/view',
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
            ),
            'showScriptName' => false
        ),
        'db' => array(
            'connectionString' => 'mysql:host=127.0.0.1;dbname=Tarakaning',
            'emulatePrepare' => true,
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8',
            'enableProfiling' => true
        ),
        'errorHandler' => array(
            // use 'site/error' action to display errors
            'errorAction' => 'site/error',
        ),
        'log' => array(
            'class' => 'CLogRouter',
            'routes' => array(
                array(
                    'class' => 'CFileLogRoute',
                    'levels' => 'error, warning',
                ),
                // uncomment the following to show log messages on web pages

                array(
                    'class' => 'CWebLogRoute',
                    'levels' => 'error, warning'
                ),
                array(
                    'class' => 'CWebLogRoute',
                    'levels' => 'profile',
                    'categories' => 'system.db.CDbCommand.*'
                ),
            ),
        ),
        'viewRenderer' => array(
            'class' => 'application.extensions.yiiext.renderers.twig.ETwigViewRenderer',
            // All parameters below are optional, change them to your needs
            'fileExtension' => '.twig',
            'options' => array(
                'autoescape' => false,
            ),
            'extensions' => array(
            //'My_Twig_Extension',
            ),
            'globals' => array(
                'html' => 'CHtml',
                'yii' => 'YiiBase'
            ),
            'functions' => array(
                'rot13' => 'str_rot13',
            ),
            'filters' => array(
                'jencode' => 'CJSON::encode',
            ),
        // Change template syntax to Smarty-like (not recommended)
        /* 'lexerOptions' => array(
          'tag_comment' => array('{*', '*}'),
          'tag_block' => array('{', '}'),
          'tag_variable' => array('{$', '}')
          ) */
        )
    ),
    // application-level parameters that can be accessed
    // using Yii::app()->params['paramName']
    'params' => array(
        // this is used in contact page
        'adminEmail' => 'webmaster@example.com',
    ),
);