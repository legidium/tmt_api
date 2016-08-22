<?php
namespace api\modules\v1;

use yii;
use yii\base\BootstrapInterface;

class Bootstrap implements BootstrapInterface
{
    /** @inheritdoc */
    public function bootstrap($app)
    {
        if ($app->hasModule('v1') && ($module = $app->getModule('v1')) instanceof Module) {
            Yii::$container->set('api\components\Mailer', $module->mailer);
        }
    }
}
