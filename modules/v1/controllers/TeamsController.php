<?php
namespace api\modules\v1\controllers;

use common\models\Team;
use yii;
use yii\web\ForbiddenHttpException;

/**
 * TeamsController Controller
 */
class TeamsController extends BaseController
{
    /** @inheritdoc */
    public function verbs()
    {
        return [
            'view-own' => ['GET'],
        ];
    }

    /** @inheritdoc */
    public function actions()
    {
        /** @var \common\models\User $user */
        $user = Yii::$app->user->identity;

        if (Yii::$app->user->can('chief')) {
            return [
                'view-own' => [
                    'class'      => 'api\modules\v1\controllers\actions\common\TeamsView',
                    'modelClass' => 'api\modules\v1\models\request\chief\TeamsView',
                    'findModel'  => function() use($user) {
                        return $this->assertModel(
                            Team::find()
                                ->where(['owner_id' => $user->id])
                                ->one()
                        );
                    }
                ],
            ];
        } elseif (Yii::$app->user->can('employee')) {
            return [
                'view-own' => [
                    'class'      => 'api\modules\v1\controllers\actions\common\TeamsView',
                    'modelClass' => 'api\modules\v1\models\request\employee\TeamsView',
                    'findModel'  => function() use($user) {
                        return $this->assertModel(
                            Team::find()
                                ->where(['owner_id' => $user->chief_id])
                                ->one()
                        );
                    }
                ],
            ];
        }

        throw new ForbiddenHttpException(Yii::t('api', 'You are not allowed to perform this action.'));
    }
}
