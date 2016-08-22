<?php
namespace api\modules\v1\models\request\chief;

use yii;
use common\models\Form;
use common\models\Activity;
use common\models\address\Object;
use common\models\ActivityForm;
use api\modules\v1\models\request\Create;
use api\modules\v1\models\request\Error;

class FormsCreate extends Create
{
    public $activity_id;
    public $object_id;
    public $values;

    private $_form;
    private $_fields;

    /** @inheritdoc */
    public function rules()
    {
        return [
            [['activity_id', 'object_id'], 'required'],
            [['activity_id', 'object_id'], 'integer'],
            [['activity_id'], 'exist', 'targetClass' => Activity::className(), 'targetAttribute' => 'id'],
            [['object_id'], 'exist', 'targetClass' => Object::className(), 'targetAttribute' => 'id'],
            [['activity_id', 'object_id'], 'unique',
                'targetClass' => ActivityForm::className(),
                'targetAttribute' => ['activity_id' , 'object_id']
            ],
            [['object_id'], 'validateObject'],

            [['values'], 'required'],
            [['values'], 'validateValues'],
        ];
    }

    /** Check whether the object is belongs to the activity owner.
     *
     * @param $attribute
     * @param $params
     */
    public function validateObject($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $object = Activity::find()
                ->innerJoin('user', 'user.id = activity.owner_id')
                ->innerJoin('user_object', 'user_object.user_id = user.id')
                ->where([
                    'activity.id' => $this->activity_id,
                    'user_object.object_id' => $this->object_id
                ]);

            if (!$object->exists()) {
                $this->addError($attribute, Yii::t('api', 'This value cannot be used'));
                $this->setErrorCode(Error::BASE, Error::BASE_BAD_REQUEST);
            }
        }
    }

    /**
     * @param $attribute
     * @param $params
     */
    public function validateValues($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $data = $this->$attribute;

            if (is_array($data)) {
                $form = $this->getForm();
                if (!$form->validateFormFields($data, $this->_fields)) {
                    $this->addError($attribute, Yii::t('api', 'Form fields contain errors'));
                    $this->setErrorCode(Error::FORMS, Error::FORMS_INVALID_FIELDS);
                }
            } else {
                $this->addError($attribute, Yii::t('api', 'Invalid JSON data'));
                $this->setErrorCode(Error::BASE, Error::BASE_BAD_REQUEST);
            }
        }
    }

    /** @inheritdoc */
    public function run()
    {
        if ($this->validate()) {
            $model = ActivityForm::create([
                'activity_id' => $this->activity_id,
                'object_id'   => $this->object_id,
                'status'      => ActivityForm::STATUS_DRAFT,
                'values'      => $this->_fields,
            ]);

            $this->setModel($model);
            $success = $model->save(false);

            if ($success) {
                Yii::$app->response->setStatusCode(201);
            } else {
                $this->setErrorCode(Error::BASE, Error::BASE_CREATE_REJECTED);
            }
        }

        return $this;
    }

    /**
     * @return \common\models\Form
     */
    protected function getForm()
    {
        if ($this->_form === null) {
            $this->_form = Form::find()
                ->innerJoinWith('activities')
                ->innerJoinWith('activityTypes')
                ->where('activity_type.id = activity.type_id')
                ->andwhere(['activity.id' => $this->activity_id])
                ->one();
        }

        return $this->_form;
    }

    /** @inheritdoc */
    public function getData(array $filter = [])
    {
        /** @var ActivityForm $model */
        $model = $this->getModel();

        return $model->toArray(['activity_id', 'object_id']);
    }

    /** @inheritdoc */
    public function responseError()
    {
        $response = parent::responseError();
        if ($this->checkErrorCode(Error::FORMS, Error::FORMS_INVALID_FIELDS)) {
            $response['error_items'] = function($model) {
                return array_filter($model->_fields, function($item) {
                    return isset($item['error']);
                });
            };
        }

        return $response;
    }
}
