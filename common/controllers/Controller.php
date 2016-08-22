<?php

namespace api\common\controllers;

class Controller extends \yii\rest\Controller
{
    /**
    * @inheritdoc
    */
    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [
            'exceptionFilter' => [
                'class' => ErrorToExceptionFilter::className()
            ],
        ]);
    }

    public function actionToken()
    {
        $server = $this->module->getServer();
        $request = $this->module->getRequest();
        $response = $server->handleTokenRequest($request);

        return $response->getParameters();
    }
}
