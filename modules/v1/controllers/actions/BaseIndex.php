<?php
namespace api\modules\v1\controllers\actions;

class BaseIndex extends Base
{
    /**
     * @return mixed
     */
    public function run()
    {
        return $this->process($this->modelClass);
    }
}
