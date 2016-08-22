<?php
namespace api\modules\v1\models\request;

use yii;

class Index extends Base
{
    public $items;
    public $model;
    /** @var  \yii\data\BaseDataProvider */
    public $data;

    private $_filter;

    public function getItems()
    {
        if ($this->items === null) {
            $this->items = $this->getData($this->getFilter());
        }

        return $this->items;
    }

    public function setItems(array $items)
    {
        $this->items = $items;
    }

    public function getFilter()
    {
        if ($this->_filter === null) {
            $this->_filter = [];
        }

        return $this->_filter;
    }

    public function setFilter(array $filter)
    {
        $this->_filter = $filter;
    }

    /** @inheritdoc */
    protected function response()
    {
        return [
            'default' => [
                'filter' => function($model) { /** @var Index $model */ return $model->getFilter(); },
                'items'  => function($model) { /** @var Index $model */ return $model->getItems(); },
            ]
        ];
    }
}
