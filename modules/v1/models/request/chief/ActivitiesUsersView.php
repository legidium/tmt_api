<?php
namespace api\modules\v1\models\request\chief;

use Yii;
use api\modules\v1\models\request\View;

class ActivitiesUsersView extends View
{
    /** @inheritdoc */
    protected function getData(array $filter = [])
    {
        $request = Yii::$app->request;
        $fields = [];
        $extra = array_filter([
            'user'     => $request->get('user') ? 'user' : false,
            'object'   => $request->get('object') ? 'object' : false,
            'forms'    => $request->get('forms') ? 'forms' : false,
        ]);
        $model = $this->getModel();
        
        return $model->toArray($fields, $extra);
    }
}
