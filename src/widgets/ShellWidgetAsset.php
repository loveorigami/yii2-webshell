<?php
namespace lo\wshell\widgets;

/**
 * Class ShellWidgetAsset
 * @package lo\wshell\assets
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 */
class ShellWidgetAsset extends \yii\web\AssetBundle
{
    public $depends = [
        'yii\web\JqueryAsset',
    ];

	/**
	 * @inheritdoc
	 */
	public function init()
	{
        $this->sourcePath = __DIR__ . "/assets";
		$this->js[] = YII_DEBUG ? 'shell-widget.js' : 'shell-widget.min.js';
        parent::init();
    }
}
