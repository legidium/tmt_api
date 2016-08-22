<?php
namespace api\modules\v1\models\request\employee;

use common\models\JobUserTask;
use yii;
use api\modules\v1\models\request\Error;
use api\modules\v1\models\request\Update;

class TasksUpdate extends Update
{
    public $status;
    public $photos;

    /** @inheritdoc */
    public function rules()
    {
        return [
            'statusInteger'  => ['status', 'integer'],
            'statusValidate' => ['status', 'validateStatus'],
            'photosValidate' => ['photos', 'validatePhotos'],
        ];
    }

    /**
     * Check whether the task status is valid.
     * @param $attribute
     * @param $params
     */
    public function validateStatus($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $valid = in_array($this->$attribute, JobUserTask::getStatuses());
            if (!$valid) {
                $this->addError($attribute, Yii::t('api', 'Invalid activity status'));
                $this->setErrorCode(Error::JOBS, Error::JOBS_INVALID_STATUS);
            }
        }
    }

    /**
     * Check whether the photos attribute is valid.
     * @param $attribute
     * @param $params
     */
    public function validatePhotos($attribute, $params)
    {
        if (!$this->hasErrors()) {
           if (!is_array($this->$attribute)) {
               $this->addError($attribute, Yii::t('api', 'Invalid json data'));
               $this->setErrorCode(Error::BASE, Error::BASE_INVALID_VALUE);
           }
        }
    }
    
    /** @inheritdoc */
    public function run()
    {
        if ($this->validate()) {
            /** @var yii\db\ActiveRecord $model */
            $model = $this->getModel();
            $model->attributes = $this->attributes;

            if (!$model->save()) {
                $this->setErrorCode(Error::BASE, Error::BASE_UPDATE_REJECTED);
                //var_dump($model->getFirstErrors());
            }
        }

        return $this;
    }
}
