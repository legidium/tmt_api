<?php
namespace api\modules\v1\models\request\chief;

use yii;
use yii\web\ServerErrorHttpException;
use api\modules\v1\models\request\Create;
use api\modules\v1\models\request\Error;
use common\models\Job;

class JobsCreate extends Create
{
    public $date_start;
    public $date_end;
    public $is_common;
    public $is_photo;
    public $title;
    public $comment;

    public $users;
    public $tasks;

    /** @inheritdoc */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['title'], 'string', 'max' => 255],

            [['date_end'], 'required'],
            [['date_start', 'date_end'], 'integer'],

            [['is_common', 'is_photo'], 'default', 'value' => false],
            [['is_common', 'is_photo'], 'boolean'],

            [['comment'], 'string'],
        ];
    }

    /** @inheritdoc */
    public function run()
    {
        if ($this->validate()) {
            /** @var \common\models\Job $model */
            $model  = $this->getModel();

            $attributes = array_filter($this->attributes, function($item) { return $item !== null; });
            $model->attributes = $attributes;
            $model->status     = Job::STATUS_DRAFT;
            $model->owner_id   = Yii::$app->user->id;

            $success = $model->save();

            if ($success) {
                Yii::$app->response->setStatusCode(201);
            } else {
                $this->setErrorCode(Error::JOBS, Error::JOBS_CREATE_REJECTED);
            }
        }

        return $this;
    }
}
