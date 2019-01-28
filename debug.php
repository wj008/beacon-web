<?php
function out($text, $color = null, $newLine = true)
{
    $styles = array(
        'file' => "\033[0;33m%s\033[0m",
        'sql1' => "\033[0;34m%s\033[0m",
        'sql2' => "\033[0;37m%s\033[0m",
        'error' => "\033[31;31m%s\033[0m",
        'info' => "\033[33;37m%s\033[0m"
    );
    $format = '%s';
    if (isset($styles[$color])) {
        $format = $styles[$color];
    }
    if ($newLine) {
        $format .= PHP_EOL;
    }
    printf($format, $text);
}

function debug($item)
{
    static $tempFile = null;
    if (!is_array($item) || count($item) == 0) {
        return;
    }
    $act = isset($item[2]) ? $item[2] : 'log';
    $file = isset($item[1]) ? $item[1] : null;
    $data = isset($item[0]) ? $item[0] : null;
    if ($file && $tempFile != $file) {
        $tempFile = $file;
        out('------> ' . $file, 'file', true);
    }
    if ($data !== null && is_array($data) && count($data) > 0) {
        if ($act == 'info' && count($data) == 2 && is_string($data[0]) && is_numeric($data[1])) {
            out($data[0] . '    ', 'sql1', false);
            out(intval(floatval($data[1]) * 10000) / 10000, 'sql2', true);
        } else {
            foreach ($data as &$datum) {
                if (is_array($datum)) {
                    $datum = json_encode($datum, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                }
            }
            out(join('    ', $data), $act, true);
        }
    }
}

$server = 'udp://127.0.0.1:1024';
$socket = stream_socket_server($server, $errno, $errstr, STREAM_SERVER_BIND);
echo <<< EOF
  ____
 |  _ \
 | |_) |   ___    __ _    ___    ___    _ __
 |  _ <   / _ \  / _` |  / __|  / _ \  | '_ \ 
 | |_) | |  __/ | (_| | | (__  | (_) | | | | |
 |____/   \___|  \__,_|  \___|  \___/  |_| |_|
=====================debug====================

EOF;
do {
    $msg = stream_socket_recvfrom($socket, 1024 * 20, 0, $peer);
    if (!empty($msg)) {
        if (preg_match('@^[\{\[].*[\}\]]$@', $msg)) {
            $data = json_decode($msg, true);
            debug($data);
        }
    } else {
        usleep(50000);
    }
} while (true);