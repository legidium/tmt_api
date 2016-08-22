<?php
namespace api\modules\v1\models\request\chief;

use yii;
use common\models\Form;
use common\models\Activity;
use common\models\address\Object;
use common\models\ActivityForm;
use api\modules\v1\models\request\Update;
use api\modules\v1\models\request\Error;

class FormsUpdate extends Update
{
    public $values;

    private $_form;
    private $_fields;

    /** @inheritdoc */
    public function rules()
    {
        return [
            [['values'], 'required'],
            [['values'], 'validateValues'],
        ];
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
            /** @var \common\models\ActivityForm $model */
            $model = $this->getModel();
            $model->setFormData($this->_fields);

            if (!$model->save(false)) {
                $this->setErrorCode(Error::BASE, Error::BASE_UPDATE_REJECTED);
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
            /** @var \common\models\ActivityForm $model */
            $model = $this->getModel();
            $this->_form = $model->form;
        }

        return $this->_form;
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
