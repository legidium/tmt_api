<?php
namespace api\modules\v1\models\request\chief;

use yii;
use common\models\Activity;
use common\components\validators\DateOverlapValidator;
use api\modules\v1\models\request\Update;
use api\modules\v1\models\request\Error;

class ActivitiesUpdate extends Update
{
    public $status;
    public $date_start;
    public $date_end;
    public $title;
    public $comment;

    /** @inheritdoc */
    public function scenarios()
    {
        /** @var \common\models\Activity $model */
        $model = $this->getModel();
        $attributes = $model->status == Activity::STATUS_DRAFT
            ? ['status', 'title', 'comment', 'date_start', 'date_end']
            : ['status'];

        return [self::SCENARIO_DEFAULT => $attributes];
    }

    /** @inheritdoc */
    public function rules()
    {
        return [
            'titleString'       => ['title', 'string', 'max' => 255],
            'commentString'     => ['comment', 'string'],
            'statusInteger'     => ['status', 'integer', 'skipOnEmpty' => false],
            'statusValidate'    => ['status', 'validateStatus'],
            
            'dateStartInteger'  => ['date_start', 'integer'],
            'dateEndInteger'    => ['date_end', 'integer'],
            'dateStartCompare'  => ['date_start', 'compare', 'compareAttribute' => 'date_end', 'operator' => '<'],
            'dateStartOverlap'  => ['date_start', DateOverlapValidator::className(),
                'targetClass'   => Activity::className(),
                'fromAttribute' => 'date_start',
                'toAttribute'   => 'date_end',
                'filter'        => ['!=', 'id', $this->model->id],
            ],
        ];
    }

    /**
     * Check whether the activity status is valid.
     *
     * @param $attribute
     * @param $params
     */
    public function validateStatus($attribute, $params)
    {
        if (!$this->hasErrors()) {
            if (!in_array($this->$attribute, Activity::getStatuses())) {
                $this->addError($attribute, Yii::t('api', 'Invalid activity status'));
                $this->setErrorCode(Error::ACTIVITIES, Error::ACTIVITIES_INVALID_STATUS);
            }
        }
    }

    /** @inheritdoc */
    public function run()
    {
        if ($this->validate()) {
            /** @var \common\models\Activity $model */
            $model = $this->getModel();
            $attributes = array_filter($this->attributes, function($item) { return $item !== null; });
            $model->attributes = $attributes;

            if (!$model->save()) {
                $this->setErrorCode(Error::BASE, Error::BASE_UPDATE_REJECTED);
            }
        }

        return $this;
    }
}
