<?php
namespace api\modules\v1\models\request;

use yii;

class Create extends Base
{
    public $item;
    public $filter;

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

    /**
     * @return array|null
     */
    public function getItem()
    {
        if ($this->item === null) {
            $this->item = $this->getData($this->getFilter());
        }

        return $this->item;
    }

    /**
     * @param array $item
     */
    public function setItem(array $item)
    {
        $this->item = $item;
    }

    /**
     * @return array|null
     */
    public function getFilter()
    {
        if ($this->filter === null) {
            $this->filter = [];
        }

        return $this->filter;
    }

    /**
     * @param array $filter
     */
    public function setFilter(array $filter)
    {
        $this->filter = $filter;
    }

    /** @inheritdoc */
    protected function getData(array $filter = [])
    {
        /** @var \common\models\Activity $model */
        $model = $this->getModel();

        return $model && $model->id ? ['id' => $model->id] : null;
    }

    /** @inheritdoc */
    protected function response()
    {
        return [
            'default' => [
                'item'  => function(Create $model) { return $model->getItem(); },
            ]
        ];
    }
}
