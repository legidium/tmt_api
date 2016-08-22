<?php
namespace api\modules\v1\controllers\actions\chief;

use Yii;
use api\modules\v1\controllers\actions\Base;

class FormsUpdate extends Base
{
    /**
     * @param integer $activity_id
     * @param integer $object_id
     * @return array
     * @throws \yii\base\InvalidConfigException
     */
    public function run($activity_id, $user_id)
    {
        $key = [$activity_id, $user_id];

        /* @var $model \yii\db\ActiveRecord */
        $model = $this->findModel(implode(',', $key));
        $model->setScenario($this->scenario);

        return $this->process($this->modelClass, [
            'model' => $model,
        ]);
    }
}
