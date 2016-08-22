<?php

/*
 * This file is part of the Dektrium project.
 *
 * (c) Dektrium project <http://github.com/dektrium>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

use yii\helpers\Html;

/**
 * @var dektrium\user\models\User  $user
 * @var string $password
 */
?>
<?= Yii::t('api', 'Hello') ?>,
<?= Yii::t('api', 'We have received a request to reset the password for your account on {0}', Yii::$app->name) ?>.
<?= Yii::t('api', 'The new password: ') ?><?= $password ?>
