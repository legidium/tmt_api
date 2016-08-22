<?php
namespace api\modules\v1\controllers\actions;

class View extends Base
{
    /**
     * @param $id
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     */
    public function run($id)
    {
        $model = $this->findModel($id);

        return $this->process($this->modelClass, [
            'model' => $model,
        ]);
    }
}
