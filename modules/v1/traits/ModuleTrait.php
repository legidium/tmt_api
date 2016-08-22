<?php
namespace api\modules\v1\traits;

use yii;

trait ModuleTrait
{
    /**
     * @return Module
     */
    public function getModule()
    {
        return \Yii::$app->getModule('v1');
    }
}
