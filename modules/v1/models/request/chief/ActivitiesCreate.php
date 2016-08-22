<?php
namespace api\modules\v1\models\request\chief;

use Yii;
use common\models\Activity;
use common\models\ActivityType;
use common\components\validators\DateOverlapValidator;
use api\modules\v1\models\request\Create;
use api\modules\v1\models\request\Error;

class ActivitiesCreate extends Create
{
    public $type_id;
    public $owner_id;
    public $date_start;
    public $date_end;
    public $title;
    public $comment;

    public $users;

    /** @var  \common\models\ActivityType */
    private $_type;

    /** @inheritdoc */
    public function rules()
    {
        return [
            [['type_id', 'title'], 'required'],
            [['type_id'], 'integer'],
            [['type_id'], 'exist', 'targetClass' => 'common\models\ActivityType', 'targetAttribute' => 'id'],

            [['date_start', 'date_end'], 'required'],
            [['date_start', 'date_end'], 'integer'],
            [['date_start'], 'compare', 'compareAttribute' => 'date_end', 'operator' => '<'],
            [['date_start'], DateOverlapValidator::className(),
                'targetClass'   => Activity::className(),
                'fromAttribute' => 'date_start',
                'toAttribute'   => 'date_end',
            ],

            [['title'], 'string', 'max' => 255],
            [['comment'], 'string'],
            [['users'], 'each', 'rule' => ['integer']],
        ];
    }

    /** @inheritdoc */
    public function run()
    {
        if ($this->validate()) {
            /** @var \common\models\Activity $model */
            $model = $this->getModel();
            $model->attributes = $this->attributes;
            $model->owner_id   = Yii::$app->user->id;
            $model->status     = Activity::STATUS_DRAFT;

            $success = $model->save();
			
            if ($success) {
                Yii::$app->response->setStatusCode(201);
                if ($this->users) {
                    $model->appendUsers($this->users);
                }
            } else {
                $this->setErrorCode(Error::BASE, Error::BASE_CREATE_REJECTED);
            }
        }

        return $this;
    }

    /**
     * @return \common\models\ActivityType
     */
    public function getType()
    {
        if ($this->_type === null) {
            $this->_type = ActivityType::findOne($this->type_id);
        }

        return $this->_type;
    }
}
