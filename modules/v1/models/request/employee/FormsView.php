<?php
namespace api\modules\v1\models\request\employee;

use Yii;
use api\modules\v1\models\request\View;

class FormsView extends View
{
    protected function getData(array $filter = [])
    {
        $request = Yii::$app->request;
        $extra = array_filter([
            'user'     => $request->get('user') ? 'user' : false,
            'object'   => $request->get('object') ? 'object' : false,
            'activity' => $request->get('activity') ? 'activity' : false,
        ]);

        return $this->getModel()->toArray([], $extra);
    }
}
