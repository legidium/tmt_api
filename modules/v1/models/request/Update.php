<?php
namespace api\modules\v1\models\request;

use yii;

class Update extends Base
{
    /** @var  yii\db\ActiveRecord */
    protected $_model;

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
}
