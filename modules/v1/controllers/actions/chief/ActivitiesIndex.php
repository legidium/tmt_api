<?php
namespace api\modules\v1\controllers\actions\chief;

use Yii;
use api\modules\v1\controllers\actions\Index;

class ActivitiesIndex extends Index
{
    public $allowedFilterParams = ['date_start', 'date_end'];

    /**
     * @return mixed
     */
    public function run()
    {
        $model = Yii::$app->user->identity;
        $filter = $this->getFilterParams();
        $data = $this->prepareDataProvider();

        return $this->process($this->modelClass, [
            'model'  => $model,
            'filter' => $filter,
            'data'   => $data,
        ]);
    }
}
