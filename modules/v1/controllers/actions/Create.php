<?php
namespace api\modules\v1\controllers\actions;

use yii\base\Model;

class Create extends BaseCreate
{
    /**
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     */
    public function run()
    {
        /* @var $model \yii\db\ActiveRecord */
        $model = new $this->findModelClass([
            'scenario' => $this->scenario,
        ]);

        return $this->process($this->modelClass, [
            'model' => $model
        ]);
    }
}
