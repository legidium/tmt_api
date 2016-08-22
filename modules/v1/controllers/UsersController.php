<?php
namespace api\modules\v1\controllers;

use yii;
use yii\data\ActiveDataProvider;
use yii\web\ForbiddenHttpException;
use common\models\User;

/**
 * UsersController Controller
 */
class UsersController extends BaseController
{
    /** @inheritdoc */
    public function verbs()
    {
        return [
            'index' => ['GET'],
            'view'  => ['GET'],
        ];
    }

    /** @inheritdoc */
    public function actions()
    {
        /** @var \common\models\User $user */
        $user = Yii::$app->user->identity;

        if (Yii::$app->user->can('chief')) {
            return [
                'index' => [
                    'class'               => 'api\modules\v1\controllers\actions\common\UsersIndex',
                    'modelClass'          => 'api\modules\v1\models\request\chief\UsersIndex',
                    'prepareDataProvider' => function() use($user) {
                        return new ActiveDataProvider([
                            'query' => User::find()->filterByTeam($user),
                        ]);
                    },
                ],
                'view'  => [
                    'class'          => 'api\modules\v1\controllers\actions\View',
                    'modelClass'     => 'api\modules\v1\models\request\chief\UsersView',
                    'findModel'      => function($id) use($user) {
                        return $this->assertModel(
                            User::find()
                                ->filterByTeam($user)
                                ->byUserId($id)
                                ->one()
                        );
                    }
                ],
            ];
        } elseif (Yii::$app->user->can('employee')) {
            return [
                'index' => [
                    'class'               => 'api\modules\v1\controllers\actions\common\UsersIndex',
                    'modelClass'          => 'api\modules\v1\models\request\employee\UsersIndex',
                    'prepareDataProvider' => function() use($user) {
                        return new ActiveDataProvider([
                            'query' => User::find()->filterByTeam($user),
                        ]);
                    },
                ],
                'view'  => [
                    'class'          => 'api\modules\v1\controllers\actions\View',
                    'modelClass'     => 'api\modules\v1\models\request\employee\UsersView',
                    'findModelClass' => 'common\models\User',
                    'findModel'      => function($id) use($user) {
                        return $this->assertModel(
                            User::find()
                                ->filterByTeam($user)
                                ->byUserId($id)
                                ->one()
                        );
                    }
                ],
            ];
        }

        throw new ForbiddenHttpException(Yii::t('api', 'You are not allowed to perform this action.'));
    }
}
