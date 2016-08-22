<?php
namespace api\modules\v1\models\request;

use yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use api\modules\v1\models\request\Error;
use api\modules\v1\traits\ModuleTrait;

/**
 * Base Model
 */
class Base extends Model
{
    use ModuleTrait;

    protected $error_base;
    protected $error_code;
    protected $error_message;

    /** @var  yii\db\ActiveRecord */
    protected $_model;

    /** @var  mixed */
    private $_data;

    /**
     * @return yii\db\ActiveRecord
     */
    public function getModel()
    {
        return $this->_model;
    }

    /**
     * @param yii\db\ActiveRecord $model
     */
    public function setModel($model)
    {
        $this->_model = $model;
    }

    /**
     * Returns the data for the response
     *
     * @param array $filter
     * @return array
     */
    protected function getData(array $filter = [])
    {
        return $this->_data ? $this->_data : [];
    }

    /**
     * @param $data
     */
    protected function setData($data)
    {
        $this->_data = $data;
    }

    /**
     * @return boolean
     */
    public function isSuccess()
    {
        return !$this->hasErrors() && !$this->hasErrorCode();
    }

    /**
     * @return boolean
     */
    public function hasErrorCode()
    {
        return ($this->error_base !== null && $this->error_code !== null);
    }

    /**
     * Compares the model's current error code with the specified one
     *
     * @param $base
     * @param int $code
     * @return bool
     */
    public function checkErrorCode($base, $code = 0)
    {
        return $this->getErrorCode() === ($base + $code);
    }

    /**
     * Set current error code
     *
     * @param integer $base
     * @param integer $code
     */
    public function setErrorCode($base, $code = 0)
    {
        $this->error_base = $base;
        $this->error_code = $code;
        $this->error_message = null;
    }

    /**
     * Return current error code
     *
     * @return integer
     */
    public function getErrorCode()
    {
        return $this->hasErrorCode() ? $this->error_base + $this->error_code : 0;
    }

    /**
     * @return string
     */
    public function getErrorMessage()
    {
        if ($this->error_message === null) {
            $this->error_message = $this->hasErrorCode() ? Error::message($this->error_base, $this->error_code) : '';
        }

        return $this->error_message;
    }

    /**
     * @param string $message
     */
    public function setErrorMessage($message)
    {
        $this->error_message = $message;
    }

    /** @inheritdoc */
    public function fields()
    {
        $fields['success'] = function($model) { /** @var Base $model */ return (int)$model->isSuccess(); };

        if ($this->isSuccess()) {
            $response = $this->response();
            $response = isset($response[$this->scenario]) ? $response[$this->scenario] : [];
        } else {
            $response = $this->responseError();
        }

        return ArrayHelper::merge($fields, $response);
    }

    /** @inheritdoc */
    public function afterValidate()
    {
        parent::afterValidate();
        if (!$this->isSuccess() && !$this->hasErrorCode()) {
            $this->setErrorCode(Error::BASE, Error::BASE_BAD_REQUEST);
        }
    }

    /**
     * Perform the model's main actions
     *
     * @return mixed
     */
    public function run()
    {
        if ($this->validate()) {
            // Do some actions here...
        }

        return $this;
    }

    /**
     * Array for fields method, where key is scenario name
     * and value is array with rules for fields method
     *
     * @see Model::fields()
     * @return array
     */
    protected function response()
    {
        return ['default' => []];
    }

    /**
     * Return fields for error response.
     *
     * @see Model::fields()
     * @return array
     */
    protected function responseError()
    {
        return [
            'error_code'    => function(Base $model) { return $model->getErrorCode(); },
            'error_message' => function(Base $model) { return $model->getErrorMessage(); },
            'error_log'     => function(Base $model) { return $model->hasErrors() ? $model->getFirstErrors() : null; },
        ];
    }
}
