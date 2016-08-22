<?php
namespace api\modules\v1\controllers;

use Yii;

class LocationController extends BaseController
{
    /** @inheritdoc */
    public function verbs()
    {
        return [
            'update' => ['POST'],
        ];
    }

    /**
     * @return mixed
     */
    public function actionUpdate()
    {
        return $this->process('api\modules\v1\models\request\common\LocationUpdate', [
            'model' => Yii::$app->user->identity,
        ]);
    }
}
