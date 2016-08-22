<?php
namespace api\modules\v1\controllers\actions\chief;

use Yii;
use api\modules\v1\controllers\actions\Base;

class FormsView extends Base
{
    /**
     * @param integer $activity_id
     * @param integer $object_id
     * @return array
     * @throws \yii\base\InvalidConfigException
     */
    public function run($activity_id, $object_id)
    {
        $key = [$activity_id, $object_id];

        /* @var $model \yii\db\ActiveRecord */
        $model = $this->findModel(implode(',', $key));

        return $this->process($this->modelClass, [
            'model' => $model,
        ]);
    }
}
