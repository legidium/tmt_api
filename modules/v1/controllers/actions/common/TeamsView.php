<?php
namespace api\modules\v1\controllers\actions\common;

use Yii;
use api\modules\v1\controllers\actions\Base;

class TeamsView extends Base
{
    /**
     * @return array
     */
    public function run()
    {
        $model = $this->findModel(null);

        return $this->process($this->modelClass, [
            'model' => $model,
        ]);
    }
}
