<?php
namespace api\modules\v1\models\request\common;

use yii;
use api\modules\v1\models\request\Update;
use api\modules\v1\models\request\Error;

class LocationUpdate extends Update
{
    /** @var double */
    public $lat;

    /** @var double */
    public $long;

    /**
     * Define rules for validation
     */
    public function rules()
    {
        return [
            [['lat', 'long'], 'required', 'message' =>  Yii::t('api', 'The field is required')],
            [['lat', 'long'], 'string',
                'max' => 20,
                'message' =>  Yii::t('api', 'The field value must be a string'),
                'tooLong' =>  Yii::t('api', 'The field value cannot be longer than 20 characters')
            ],
            [['lat', 'long'], 'trim'],
            [['lat', 'long'], 'validateLocationValue'],
        ];
    }

    public function validateLocationValue($attribute, $params)
    {
        if (!$this->hasErrors()) {
            if (!is_numeric($this->$attribute)) {
                $this->addError($attribute, Yii::t('api', 'The field must contain a numeric value'));
                $this->setErrorCode(Error::BASE, Error::BASE_INVALID_VALUE);
            }
        }
    }

    /** @inheritdoc */
    public function run()
    {
        if ($this->validate()) {
            /** @var \common\models\User $user */
            $user = $this->getModel();

            if ($user) {
                $user->updateLocation($this->lat, $this->long);
            } else {
                $this->setErrorCode(Error::BASE, Error::BASE_INTERNAL_FAILURE);
            }
        }

        return $this;
    }
}
