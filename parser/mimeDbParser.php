<?php
declare(strict_types=1);

include_once __DIR__ . './parserBase.php';

function mimeDbParseMT(string $uri, array &$mimetypes) :?array {
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

    $data = json_decode($data, true);

    foreach ($data as $mt => $mtData) {
        $delimiterPos = strpos($mt, "/");
        $type         = strtolower(substr($mt, 0, $delimiterPos));
        $subtype      = strtolower(substr($mt, $delimiterPos + 1));

        appendExtensions($mimetypes, $type, $subtype, ($mtData['extensions'] ?? []) ?: [], $result);
    }

    return $result;
}
