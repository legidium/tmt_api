<?php
namespace api\modules\v1\controllers\actions;

use yii\base\Model;

class BaseCreate extends Base
{
    /**
     * @var string the scenario to be assigned to the new model before it is validated and saved.
     */
    public $scenario = Model::SCENARIO_DEFAULT;

    /**
     * @return mixed
     */
    public function run()
    {
        return $this->process($this->modelClass);
    }
}
