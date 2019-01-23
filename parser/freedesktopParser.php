<?php
declare(strict_types=1);

include_once __DIR__ . './parserBase.php';

function freedesktopParseMT(string $uri, array &$mimetypes) :?array {
    $result = [
        'subtypes'   => 0,
        'types'      => 0,
        'extensions' => 0,
    ];

    $data = fetchFileContents($uri);

    if (!$data) {
        echo "'$uri' returned empty response";

        exit(4);
    }

    $data = simplexml_load_string($data);
    foreach ($data as $node) {
        $extensions = [];

        foreach ($node->glob as $glob) {
            $pattern = (string)$glob['pattern'];

            if ($pattern[0] === '*' && $pattern[1] === '.') {
                $extensions[] = substr($pattern, 2);
            }
        }

        if (!$extensions) {
            continue;
        }

        $mt = strtolower((string)$node['type']);

        $delimiterPos = strpos($mt, "/");
        $type         = substr($mt, 0, $delimiterPos);
        $subtype      = substr($mt, $delimiterPos + 1);

        appendExtensions($mimetypes, $type, $subtype, $extensions?:[], $result);

        foreach ($node->alias as $alias) {
            $mt = strtolower((string)$alias['type']);

            $delimiterPos = strpos($mt, "/");
            $type         = substr($mt, 0, $delimiterPos);
            $subtype      = substr($mt, $delimiterPos + 1);

            appendExtensions($mimetypes, $type, $subtype, $extensions?:[], $result);
        }
    }

    return $result;
}
