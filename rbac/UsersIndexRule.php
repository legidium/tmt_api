<?php
namespace api\rbac;

use Yii;
use yii\rbac\Rule;

class UsersIndexRule extends Rule
{
    /** @var string */
    public $name = 'api\rbac\UsersIndexRule';

    /** @inheritdoc */
    public function execute($user, $item, $params)
    {
        return true;
    }
}
