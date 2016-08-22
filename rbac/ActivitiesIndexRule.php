<?php
namespace api\rbac;

use Yii;
use yii\rbac\Rule;

class ActivitiesIndexRule extends Rule
{
    /** @var string */
    public $name = 'api\rbac\ActivitiesIndexRule';

    /** @inheritdoc */
    public function execute($user, $item, $params)
    {
        return true;
    }
}
