<?php
namespace api\modules\v1\controllers\actions;

use Yii;

class IndexRelated extends Index
{
    /** @inheritdoc */
    public function run()
    {
        $id = Yii::$app->request->get('id');
        $model = $this->findModel($id);
        $data = $this->prepareDataProvider();

        return $this->process($this->modelClass, [
            'model' => $model,
            'data' => $data,
        ]);
    }
}
