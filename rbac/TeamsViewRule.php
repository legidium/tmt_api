<?php
namespace api\rbac;

use Yii;
use yii\rbac\Rule;

class TeamsViewRule extends Rule
{
    /** @var string */
    public $name = 'api\rbac\TeamsViewRule';

    /** @inheritdoc */
    public function execute($user, $item, $params)
    {
        if ($item->name == 'teams.view-own') {}
        return true;
    }
}
