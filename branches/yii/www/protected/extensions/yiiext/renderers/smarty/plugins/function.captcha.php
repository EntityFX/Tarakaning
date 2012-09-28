<?php

/**
 * Allows to translate strings using Yii::t().
 *
 * Syntax:
 * {t text="text to translate" cat="app"}
 * {t text="text to translate" cat="app" src="en" lang="ru"}
 * {t text="text to translate" cat="app" params=$params}
 *
 * @see Yii::t().
 *
 * @param array $params
 * @param Smarty $smarty
 * @return string
 */
function smarty_function_captcha($params, &$smarty) {
    $parameters = array(
        'buttonType' => isset($params['buttonType']) && $params['buttonType'],
        'buttonOptions' => array(
            'class' => "btn btn-large"
        ),
        'clickableImage' => isset($params['clickableImage']) && (bool)$params['clickableImage'],
        'showRefreshButton' => isset($params['showRefreshButton']) && (bool)$params['showRefreshButton'],
    );
    $widget = Yii::app()->getWidgetFactory()->createWidget(
            $params['owner'], 'CCaptcha', $parameters
    );
    $widget->init();
    $widget->run();
}