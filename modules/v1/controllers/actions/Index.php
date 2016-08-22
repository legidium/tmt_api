<?php
namespace api\modules\v1\controllers\actions;

use Yii;
use yii\base\InvalidConfigException;
use yii\data\ActiveDataProvider;

class Index extends Base
{
    public $allowedFilterParams = [];
    public $defaultFilterParams = [];

    /** @var callable */
    public $prepareDataProvider;

    /**
     * Prepares the data provider that should return the requested collection of the models.
     * @return ActiveDataProvider
     * @throws InvalidConfigException
     */
    protected function prepareDataProvider()
    {
        if ($this->prepareDataProvider !== null) {
            return call_user_func($this->prepareDataProvider, $this);
        }

        if ($this->findModelClass === null) {
            throw new InvalidConfigException(get_class($this) . '::$findModelClass must be set.');
        }

        /* @var $findModelClass \yii\db\BaseActiveRecord */
        $findModelClass = $this->findModelClass;

        return new ActiveDataProvider([
            'query' => $findModelClass::find(),
        ]);
    }

    /**
     * Return request filter params
     *
     * @return array
     */
    public function getFilterParams()
    {
        $params = array_intersect_key(
            Yii::$app->request->getQueryParams(),
            array_flip($this->allowedFilterParams)
        );

        return array_merge($this->defaultFilterParams, $params);
    }

    /**
     * Run the configured request model processing and return the response data
     *
     * @return mixed
     */
    public function run()
    {
        $data = $this->prepareDataProvider();

        return $this->process($this->modelClass, [
            'data' => $data
        ]);
    }
}
