<?php
namespace api\modules\v1;

use Yii;

class Module extends \yii\base\Module
{
    const VERSION = '1.0.0';

    /** @var array Mailer configuration */
    public $mailer = [];
}
