<?php
namespace api\modules\v1\models\request\chief;

use yii;
use api\modules\v1\models\request\Create;
use api\modules\v1\models\request\Error;

class TasksPhotosCreate extends Create
{
    /** @var array */
    public $items;

    /** @inheritdoc */
    public function rules()
    {
        return [
            ['items', 'required'],
            ['items', 'validateItems'],
        ];
    }

    /**
     * @param $attribute
     * @param $params
     */
    public function validateItems($attribute, $params)
    {
        if (!$this->hasErrors()) {
            if (!is_array($this->$attribute)) {
                $this->addError($attribute, Yii::t('api', 'The `{attribute}` field must be an array', ['attribute' => $attribute]));
                $this->setErrorCode(Error::BASE, Error::BASE_INVALID_VALUE);
            }
        }
    }

    /** @inheritdoc */
    public function run()
    {
        if ($this->validate()) {
            /** @var \common\models\Task $model */
            $model = $this->getModel();
            $model->appendPhotos($this->items, false);

            $success = $model->save();

            if ($success) {
                Yii::$app->response->setStatusCode(201);
            } else {
                $this->setErrorCode(Error::TASKS, Error::TASKS_PHOTOS_APPEND_REJECTED);
            }
        }

        return $this;
    }

    /** @inheritdoc */
    protected function response()
    {
        return [
            'default' => [
                'items' => function($model) { return $this->model->getTaskPhotos(); },
            ]
        ];
    }
}
