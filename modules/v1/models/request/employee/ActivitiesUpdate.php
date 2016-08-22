<?php
namespace api\modules\v1\models\request\employee;

use yii;
use api\modules\v1\models\request\Update;
use api\modules\v1\models\request\Error;
use common\models\ActivityUser;

class ActivitiesUpdate extends Update
{
    public $status;
    public $reject_type;
    public $user_comment;

    /** @inheritdoc */
    public function rules()
    {
        return [
            'statusInteger'      => ['status',       'integer'],
            'statusValidate'     => ['status',       'validateStatus'],

            'rejectTypeRequired' => ['reject_type',  'required', 'when' => function($model) {
                return $model->status == ActivityUser::STATUS_REJECTED;
            }],
            'rejectTypeInteger'  => ['reject_type',  'integer'],
            'rejectTypeValidate' => ['reject_type',  'validateRejectType'],

            'userCommentString'  => ['user_comment', 'string'],
        ];
    }

    /**
     * Check whether the activity status is valid.
     * @param $attribute
     * @param $params
     */
    public function validateStatus($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $valid = in_array($this->$attribute, ActivityUser::getStatuses());

            if (!$valid) {
                $this->addError($attribute, Yii::t('api', 'Invalid activity status'));
                $this->setErrorCode(Error::ACTIVITIES, Error::ACTIVITIES_INVALID_STATUS);
            }
        }
    }

    /**
     * Check whether the reject type is valid.
     * @param $attribute
     * @param $params
     */
    public function validateRejectType($attribute, $params)
    {
        if (!$this->hasErrors()) {
            if (!in_array($this->$attribute, ActivityUser::getRejectTypes())) {
                $this->addError($attribute, Yii::t('api', 'Invalid reject type'));
                $this->setErrorCode(Error::ACTIVITIES, Error::ACTIVITIES_INVALID_REJECT_TYPE);
            }
        }
    }

    /** @inheritdoc */
    public function run()
    {
        if ($this->validate()) {
            /** @var ActivityUser $model */
            $model = $this->getModel();
            $model->attributes = $this->attributes;

            if (!$model->save()) {
                $this->setErrorCode(Error::BASE, Error::BASE_UPDATE_REJECTED);
            }
        }

        return $this;
    }
}
