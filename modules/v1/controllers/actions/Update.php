<?php
namespace api\modules\v1\controllers\actions;

class Update extends Base
{
    /**
     * @param $id
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     */
    public function run($id)
    {
        /* @var $model \yii\db\ActiveRecord */
        $model = $this->findModel($id);
        $model->setScenario($this->scenario);

        return $this->process($this->modelClass, [
            'model' => $model,
        ]);
    }
}
