<?php
namespace api\modules\v1\models\request\chief;

use Yii;
use api\modules\v1\models\request\Create;
use api\modules\v1\models\request\Error;

class JobsTasksCreate extends Create
{
    public $title;
    public $comment;
    public $photos;
    public $users;

    public $id;

    /** @inheritdoc */
    public function rules()
    {
        return [
            ['title', 'required'],
            ['title', 'string', 'max' => 255],
            ['comment', 'string'],
            ['photos', 'validatePhotos'],
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
            /** @var \common\models\Job $model */
            $model = $this->getModel();

            $this->id = $model->appendTask($this->attributes);
            $success = $this->id !== false;

            if ($success) {
                Yii::$app->response->setStatusCode(201);
            } else {
                $this->setErrorCode(Error::JOBS, Error::JOBS_TASKS_CREATE_REJECTED);
            }
        }

        return $this;
    }

    /** @inheritdoc */
    protected function response()
    {
        return [
            'default' => [
                'item'  => 'id',
            ]
        ];
    }
}
