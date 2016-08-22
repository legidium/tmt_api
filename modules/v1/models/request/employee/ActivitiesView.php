<?php
namespace api\modules\v1\models\request\employee;

use Yii;
use yii\helpers\ArrayHelper;
use common\models\ActivityUser;
use api\modules\v1\models\request\View;

class ActivitiesView extends View
{
    /** @inheritdoc */
    protected function getData(array $filter = [])
    {
        $request = Yii::$app->request;
        $filter['owner']   = $request->get('owner');
        $filter['forms']   = $request->get('forms');
        $filter['objects'] = $request->get('objects');

        $model = $this->getModel();
        $fields = $model->fields();
        $extra  = [];

        // Show owner
        if (isset($filter['owner']) && $filter['owner']) {
            $extra = array_merge($extra, [
                'owner' => function(ActivityUser $model) {
                    return $model->activity->owner->toArray(['id', 'name', 'title']);
                }
            ]);
        }

        // Show objects
        if (isset($filter['objects']) && $filter['objects']) {
            $extra = array_merge($extra, [
                'objects' => function(ActivityUser $model) {
                    $objects = $model->getObjects()
                        ->joinWith('formStatuses fs')
                        ->where([
                            'fs.activity_id' => $model->activity_id,
                            'fs.user_id'     => $model->user_id
                        ])
                        ->all();

                    return ArrayHelper::toArray($objects, [
                        'common\models\address\Object' => [
                            'id'      => 'id',
                            'title'   => 'name',
                            'address' => 'address'
                        ]
                    ]);
                }
            ]);
        }

        // Show forms
        if (isset($filter['forms']) && $filter['forms']) {
            $extra = array_merge($extra, [
                'forms' => function(ActivityUser $model) {
                    $forms = $model->getForms()
                        ->with(['form', 'object'])
                        ->all();

                    return ArrayHelper::toArray($forms, [
                        'common\models\ActivityFormUser' => [
                            'object_id',
                            'status',
                            'object' => function($model) { return $model->form->getFormObject(); },
                            'values' => function($model) { return $model->form->getFormValues(); },
                            'schema' => function($model) { return $model->form->getFormSchema(); }
                        ]
                    ]);
                }
            ]);
        }

        return ArrayHelper::toArray($model, [
            'common\models\ActivityUser' => array_merge($fields, $extra),
        ]);
    }
}
