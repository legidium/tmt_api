<?php
namespace api\modules\v1\models\request\chief;

use Yii;
use api\modules\v1\models\request\Update;
use common\models\ActivityUser;
use api\modules\v1\models\request\Error;

class ActivitiesUsersUpdate extends Update
{
    public $status;
    public $reject_type;
    public $comment;

    /** @inheritdoc */
    public function rules()
    {
        return [
            ['status', 'integer'],
            ['status', 'in', 'range' => ActivityUser::getStatuses()],

            ['reject_type', 'required', 'when' => function($model) {
                return $model->status == ActivityUser::STATUS_REJECTED;
            }],
            ['reject_type', 'integer'],
            ['reject_type', 'in', 'range' => ActivityUser::getRejectTypes()],

            ['comment', 'string'],
        ];
    }

    /** @inheritdoc */
    public function run()
    {
        if ($this->validate()) {
            /** @var \common\models\ActivityUser $model */
            $model = $this->getModel();

            $attributes = array_filter($this->attributes, function($item) { return $item !== null; });
            $model->attributes = $attributes;

            if (!$model->save()) {
                $this->setErrorCode(Error::ACTIVITIES, Error::ACTIVITIES_USERS_CREATE_REJECTED);
            }
        }

        return $this;
    }
}
