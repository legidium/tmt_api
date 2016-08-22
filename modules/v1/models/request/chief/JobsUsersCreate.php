<?php
namespace api\modules\v1\models\request\chief;

use Yii;
use common\models\User;
use api\modules\v1\models\request\Error;
use api\modules\v1\models\request\Create;

class JobsUsersCreate extends Create
{
    /** @var integer[] */
    public $users;

    /** @var array */
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
            /** @var \common\models\Job $job */
            $job = $this->getModel();
            $success = false;

            // Проверяем, что у задания есть задачи.
            if ($job->getTasks()->count()) {
                $users = User::find()
                    ->where(['id' => $this->users, 'chief_id' => $job->owner_id])
                    ->all();

                $appended = [];
                foreach ($users as $user) {
                    /** @var $user \common\models\User */
                    if ($job->appendUser($user)) {
                        $appended[] = $user->id;
                    }
                }
                $this->items = $appended;
            }

            if ($success) {
                Yii::$app->response->setStatusCode(201);
            } else {
                $this->setErrorCode(Error::JOBS, Error::JOBS_USERS_CREATE_REJECTED);
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
