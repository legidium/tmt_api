<?php
namespace api\modules\v1\controllers\actions;

use Yii;

class CreateRelated extends Create
{
    /** @inheritdoc */
    public function run()
    {
        /* @var $model \yii\db\ActiveRecord */
        $model = $this->findModel(Yii::$app->request->get('id'));
        $model->setScenario($this->scenario);

        return $this->process($this->modelClass, [
            'model' => $model
        ]);
    }
}
