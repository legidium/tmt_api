<?php
namespace api\modules\v1\models\request;

use yii;

class View extends Base
{
    public $id;
    public $item;
    public $filter;

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

    /**
     * Return the data for the response
     *
     * @param array $filter
     * @return array
     */
    protected function getData(array $filter = [])
    {
        return [];
    }

    /** @inheritdoc */
    protected function response()
    {
        return [
            'default' => [
                'item'  => function($model) {
                    /** @var \api\modules\v1\models\request\View $model */
                    return $model->getItem();
                },
            ]
        ];
    }
}
