<?php
namespace api\modules\v1\models\request\employee;

use Yii;
use api\modules\v1\models\request\Update;
use api\modules\v1\models\request\Error;
use common\models\ActivityFormUser;
use yii\helpers\VarDumper;

class FormsUpdate extends Update
{
    public $status;

    /** @inheritdoc */
    public function rules()
    {
        return [
            ['status', 'required'],
            ['status', 'integer'],
            ['status', 'in', 'range' => $this->getAllowedStatuses()],
        ];
    }

    /**
     * Returns allowed statuses.
     * @return array
     */
    public function getAllowedStatuses()
    {
        /** @var ActivityFormUser $model */
        $model = $this->getModel();
        return $model->getAllowedStatuses();
    }

    /** @inheritdoc */
    public function run()
    {
        if ($this->validate()) {
            $model = $this->getModel();
            $model->attributes = $this->attributes;

            if (!$model->save(false)) {
                $this->setErrorCode(Error::BASE, Error::BASE_UPDATE_REJECTED);
            }
        }

        return $this;
    }
}
;
