<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{

    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/site.css',
    ];
    public $js = [
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];

    public static function addScript($view, $jsfile) {
        $view->registerJsFile('basic/web'.$jsfile.'?v='.rand(0,1999), [AppAsset::className(), 'depends' => 'app\assets\AppAsset']);
    }

    public static function addCss($view, $cssfile) {
        $view->registerCssFile('basic/web'.$cssfile.'?v='.rand(0,1999), [AppAsset::className(), 'depends'=>'app\assets\AppAsset']);
    }
}
