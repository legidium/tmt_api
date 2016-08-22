<?php
namespace api\modules\v1\controllers;

use yii;
use yii\data\ActiveDataProvider;
use common\models\Standarts;

/**
 * StandardsController Controller
 */
class StandardsController extends BaseController
{
    /** @inheritdoc */
    public function verbs()
    {
        return [
            'index'  => ['GET'],
            'view'   => ['GET'],
        ];
    }

    /** @inheritdoc */
    public function actions()
    {
        return [
            'index' => [
                'class'      => 'api\modules\v1\controllers\actions\Index',
                'modelClass' => 'api\modules\v1\models\request\common\StandardsIndex',
                'prepareDataProvider' => function() {
                    return new ActiveDataProvider([
                        'query' => Standarts::find()
                            ->where(['or', ['parent_id' => null], ['parent_id' => 0]])
                    ]);
                },
            ],
            'view'  => [
                'class'      => 'api\modules\v1\controllers\actions\View',
                'modelClass' => 'api\modules\v1\models\request\common\StandardsView',
                'findModelClass' => 'common\models\Standarts',
            ],
        ];
    }
}
