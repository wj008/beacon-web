<?php


namespace sdopx\plugin;


use beacon\core\Util;
use sdopx\lib\Raw;


class ImageModifier
{
    /**
     * 生成文件路径
     * @param $data
     * @return Raw
     */
    public static function render($data): Raw
    {
        if (is_string($data) && Util::isJson($data)) {
            $data = json_decode($data, true);
        }
        if (!is_array($data)) {
            if (is_string($data) && !empty($data)) {
                $data = [$data];
            } else {
                $data = [];
            }
        }
        $html = [];
        foreach ($data as $item) {
            if ($item === null || $item === '' || $item === 'null') {
                continue;
            }
            if (is_string($item)) {
                $item = ['url' => $item, 'name' => basename($item)];
            }
            if (empty($item['name']) && !empty($item['localname'])) {
                $item['name'] = $item['localname'];
            }
            if (empty($item['name']) && !empty($item['url'])) {
                $item['name'] = basename($item['url']);
            }
            $ext = strtolower(pathinfo($item['url'], PATHINFO_EXTENSION));
            if (empty($ext)||in_array($ext, ['jpg', 'jpeg', 'png', 'gif'])) {
                $html[] = '<li><a href="javascript:;" class="image-show" img-url="' . $item['url'] . '"><img  src="' . $item['url'] . '?imageView2/1/w/200/h/200/q/75"/></a></li>';
            }
        }
        return new Raw('<ul class="image-group">' . join('', $html) . '</ul>');
    }
}