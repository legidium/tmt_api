<?php
namespace api\rbac;

use Yii;
use yii\rbac\Rule;
use common\models\Activity;

class ActivitiesCreateRule extends Rule
{
    /** @var string */
    public $name = 'api\rbac\ActivitiesCreateRule';

    /** @inheritdoc */
    public function execute($user, $item, $params)
    {
        $allow = false;

        if (Yii::$app->user->can('chief')) {
            $allow = true;
        }

        return $allow;
    }
}
