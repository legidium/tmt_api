<?php
namespace api\modules\v1\models\request\chief;

use Yii;
use api\modules\v1\models\request\Create;
use api\modules\v1\models\request\Error;

class ActivitiesUsersCreate extends Create
{
    /** @var array */
    public $users;
    public $items;

    /** @inheritdoc */
    public function rules()
    {
        return [
            ['users', 'required'],
            ['users', 'each', 'rule' => ['integer']],
        ];
    }
    
    /** @inheritdoc */
    public function run()
    {
        if ($this->validate()) {
            /** @var \common\models\Activity $model */
            $model = $this->getModel();
            
            $this->items = $model->appendUsers($this->users);
            $success = $this->items !== false;

            if ($success) {
                Yii::$app->response->setStatusCode(201);
            } else {
                $this->setErrorCode(Error::ACTIVITIES, Error::ACTIVITIES_USERS_CREATE_REJECTED);
            }
        }

        return $this;
    }

    /** @inheritdoc */
    protected function response()
    {
        return ['default' => ['items']];
    }
}
