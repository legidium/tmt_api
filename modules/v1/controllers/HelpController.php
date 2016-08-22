<?php
namespace api\modules\v1\controllers;

use yii;
use yii\web\Response;

/**
 * HelpController Controller
 */
class HelpController extends BaseController
{
    public $api         = 'api_v1.txt';
    public $apiList     = 'api_v1_list.txt';
    public $apiStatuses = 'api_statuses.txt';

    /** @inheritdoc */
    public function init()
    {
        $response = Yii::$app->response;
        $response->format = Response::FORMAT_RAW;
        $response->getHeaders()->set('Content-Type', 'text/plain; charset=utf-8');
    }

    /** @inheritdoc */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        unset($behaviors['contentNegotiator']);
        unset($behaviors['authenticator']);
        unset($behaviors['access']);

        return $behaviors;
    }

    public function actionApi()
    {
        return $this->renderFile(Yii::getAlias('@api/doc/' . $this->api));
    }

    public function actionApiList()
    {
        return $this->renderFile(Yii::getAlias('@api/doc/' . $this->apiList));
    }

    public function actionApiStatuses()
    {
        return $this->renderFile(Yii::getAlias('@api/doc/' . $this->apiStatuses));
    }
}
