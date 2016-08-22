<?php
namespace api\modules\v1\models\request\chief;

use yii;
use api\modules\v1\models\request\Index;
use Faker;

class TasksIndex extends Index
{
    protected function getData(array $filter = [])
    {
        $data = [];
        $fields = ['id', 'job_id', 'title', 'comment'];

        foreach ($this->data->getModels() as $model) {
            /** @var \common\models\Task $model */
            $data[] = $model->toArray($fields);
        }

        return $data;

        return [
            [
                'id'         => 1,
                'job_id'     => 1,
                'title'      => 'Исправить выкладку по позиции 3434 бренд Астра',
                'is_photo'   => 1,
                'photos'     => [
                    "http://fakeimg.pl/64x64/",
                    "http://fakeimg.pl/64x64/",
                    "http://fakeimg.pl/64x64/",
                ],
            ],
            [
                'id'         => 2,
                'job_id'     => 1,
                'title'      => 'Исправить выкладку по позиции 3131 бренд Роза',
                'is_photo'   => 1,
                'photos'     => [
                    "http://fakeimg.pl/64x64/",
                    "http://fakeimg.pl/64x64/",
                ],
            ],
            [
                'id'         => 3,
                'job_id'     => 2,
                'title'      => 'Исправить перфоманс',
                'is_photo'   => 1,
                'photos'     => [
                    "http://fakeimg.pl/64x64/",
                ],
            ],
            [
                'id'         => 4,
                'job_id'     => 3,
                'title'      => 'Поправить мебель',
                'is_photo'   => 1,
                'photos'     => [
                    "http://fakeimg.pl/64x64/",
                    "http://fakeimg.pl/64x64/",
                    "http://fakeimg.pl/64x64/",
                    "http://fakeimg.pl/64x64/",
                ],
            ],
        ];
    }
}
