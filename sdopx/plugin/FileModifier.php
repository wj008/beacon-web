<?php


namespace sdopx\plugin;


use beacon\core\Util;


class FileModifier
{
    /**
     * 生成文件路径
     * @param $data
     * @return string
     */
    public static function render($data): string
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
            if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif'])) {
                $html[] = '<a href="' . $item['url'] . '" target="_blank" class="show-img-item"><img src="' . $item['url'] . '"/></a>';
            } else {
                $html[] = '<a href="' . $item['url'] . '" target="_blank" class="show-file-item">' . $item['name'] . '</a>';
            }
        }
        return join('', $html);
    }
}