<?php
/**
 * Created by PhpStorm.
 * User: lyx
 * Date: 16/4/18
 * Time: 下午4:08
 */

namespace App\Api\Transformers;

use App\RadioStation;
use League\Fractal\TransformerAbstract;

class RadioStationTransformer extends TransformerAbstract
{
    /**
     * @param RadioStation $radioStation
     * @return array
     */
    public function transform(RadioStation $radioStation)
    {
        $this->timeTransform($radioStation);

        return [
            'id' => $radioStation['id'],
            'name' => $radioStation['name'],
            'content' => $radioStation['content'],
            'author' => $radioStation['author'],
            'time' => $radioStation['time'],
            'unread' => $radioStation['value']
        ];
    }

    /**
     * 把Laravel的carbon时间格式化成标准的
     *
     * @param $radioStation
     * @return mixed
     */
    public function timeTransform($radioStation)
    {
        $radioStation['time'] = $radioStation['created_at']->format('Y-m-d H:i:s');

        return $radioStation;
    }
}