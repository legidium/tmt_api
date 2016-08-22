<?php
namespace api\modules\v1\models\request\chief;

use Yii;
use common\models\Job;
use api\modules\v1\models\request\Update;
use api\modules\v1\models\request\Error;

class JobsUpdate extends Update
{
    public $status;
    public $date_start;
    public $date_end;
    public $is_common;
    public $is_photo;
    public $title;
    public $comment;

    /** @inheritdoc */
    public function rules()
    {
        return [
            ['status', 'integer'],
            ['status', 'in', 'range' => Job::getStatuses()],

            [['date_start', 'date_end'], 'integer'],

            [['is_common', 'is_photo'], 'boolean'],
            ['title', 'string', 'max' => 255],
            ['comment', 'string'],
        ];
    }

    /** @inheritdoc */
    public function run()
    {
        if ($this->validate()) {
            /** @var \common\models\Job $model */
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
