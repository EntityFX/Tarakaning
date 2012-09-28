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
    $widget = Yii::app()->getWidgetFactory()->createWidget(
            $params['owner'],
            $params['name'],
            !isset($params['params']) ? array() : $params['params']
    );
    $widget->init();
    $widget->run();
}