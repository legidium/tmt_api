<?php
namespace api\modules\v1\models\request\chief;

use Yii;
use api\modules\v1\models\request\Error;
use api\modules\v1\models\request\Update;

class TasksUpdate extends Update
{
    public $title;
    public $comment;
    public $photos;

    /** @inheritdoc */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['title'], 'string', 'max' => 255],
            [['comment'], 'string'],
            [['photos'], 'each', 'rule' => ['string']],
        ];
    }

    /** @inheritdoc */
    public function run()
    {
        if ($this->validate()) {
            /** @var \common\models\Task $model */
            $model = $this->getModel();

            $attributes = array_filter($this->attributes, function($item) { return !is_null($item); });
            $model->attributes = $attributes;

            if (!$model->save()) {
                $this->setErrorCode(Error::BASE, Error::BASE_UPDATE_REJECTED);
                //var_dump($model->getFirstErrors());
            }
        }

        return $this;
    }
}
