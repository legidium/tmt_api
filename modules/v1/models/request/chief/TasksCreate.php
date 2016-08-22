<?php
namespace api\modules\v1\models\request\chief;

use yii;
use api\modules\v1\models\request\Create;
use api\modules\v1\models\request\Error;
use common\models\Task;

class TasksCreate extends Create
{
    public $job_id;
    public $title;
    public $comment;
    public $photos;

    public $id;

    /** @inheritdoc */
    public function rules()
    {
        return [
            [['job_id', 'title'], 'required'],
            [['title'], 'string', 'max' => 255],
            [['comment'], 'string'],
            [['photos'], 'validatePhotos'],
        ];
    }

    /**
     * @param $attribute
     * @param $params
     */
    public function validatePhotos($attribute, $params)
    {

    }

    /** @inheritdoc */
    public function run()
    {
        if ($this->validate()) {
            /** @var \common\models\Task $model */
            $model = $this->getModel();

            $attributes = array_filter($this->attributes, function($item) { return $item !== null; });
            $model->attributes = $attributes;
            $success = $model->save();

            if ($success) {
                Yii::$app->response->setStatusCode(201);
            } else {
                $this->setErrorCode(Error::TASKS, Error::TASKS_CREATE_REJECTED);
            }
        }

        return $this;
    }
}
