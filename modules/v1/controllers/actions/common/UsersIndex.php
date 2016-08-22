<?php
namespace api\modules\v1\controllers\actions\common;

use Yii;
use api\modules\v1\controllers\actions\Index;

class UsersIndex extends Index
{
    /**
     * @return array
     * @throws \yii\base\InvalidConfigException
     */
    public function run()
    {
        $model = Yii::$app->user->identity;
        $data = $this->prepareDataProvider();

        return $this->process($this->modelClass, [
            'model' => $model,
            'data'  => $data,
        ]);
    }
}
