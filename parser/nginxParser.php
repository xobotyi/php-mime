<?php
declare(strict_types=1);

include_once __DIR__ . './parserBase.php';

function nginxParseMT(string $uri, array &$mimetypes) :?array {
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

    preg_match_all('/^\s*([\w-]+\/[\w+.-]+)((?:\s+[\w-]+)*);\s*$/m', $data, $matches, PREG_PATTERN_ORDER);

    foreach ($matches[1] as $key => $mt) {
        $delimiterPos = strpos($mt, "/");
        $type         = strtolower(substr($mt, 0, $delimiterPos));
        $subtype      = strtolower(substr($mt, $delimiterPos + 1));

        $extensions = array_filter(preg_split("/\s+/", strtolower($matches[2][$key] ?? '')), function ($item) { return !!$item; });

        appendExtensions($mimetypes, $type, $subtype, $extensions?:[], $result);
    }

    return $result;
}
