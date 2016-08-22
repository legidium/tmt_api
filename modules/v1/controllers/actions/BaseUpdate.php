<?php
namespace api\modules\v1\controllers\actions;

class BaseUpdate extends Base
{
    /**
     * @return mixed
     */
    public function run()
    {
        return $this->process($this->modelClass);
    }
}
