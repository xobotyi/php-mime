<?php
declare(strict_types=1);

include_once __DIR__ . './parser/freedesktopParser.php';
include_once __DIR__ . './parser/apacheParser.php';
include_once __DIR__ . './parser/nginxParser.php';
include_once __DIR__ . './parser/mimeDbParser.php';

$freedesktopMimesFile = "https://raw.github.com/minad/mimemagic/master/script/freedesktop.org.xml";
$apacheMimesFile      = "https://svn.apache.org/repos/asf/httpd/httpd/trunk/docs/conf/mime.types";
$nginxMimesFile       = "https://raw.github.com/nginx/nginx/master/conf/mime.types";
$mimeDbMimesFile      = "https://raw.github.com/jshttp/mime-db/master/db.json";

$mimetypesRaw = [];
$mimetypes    = [];
$extensions   = [];

##
#   Fetching DB's
##
$result = freedesktopParseMT($freedesktopMimesFile, $mimetypesRaw);
if (!$result) {
    echo "Failed to process freedesktop mimes db\n\n";
    exit(1);
}
echo "freedesktop mimes db parsed, added {$result['types']} types,  {$result['subtypes']} subtypes, {$result['extensions']} extensions\n\n";

$result = nginxParseMT($nginxMimesFile, $mimetypesRaw);
if (!$result) {
    echo "Failed to process nginx mimes db \n\n";
    exit(1);
}
echo "nginx mimes db parsed, added {$result['types']} types,  {$result['subtypes']} subtypes, {$result['extensions']} extensions\n\n";

$result = apacheParseMT($apacheMimesFile, $mimetypesRaw);
if (!$result) {
    echo "Failed to process apache mimes db\n\n";
    exit(1);
}
echo "apache mimes db parsed, added {$result['types']} types,  {$result['subtypes']} subtypes, {$result['extensions']} extensions\n\n";

$result = mimeDbParseMT($mimeDbMimesFile, $mimetypesRaw);
if (!$result) {
    echo "Failed to process mime-db\n\n";
    exit(1);
}
echo "mime-db parsed, added {$result['types']} types,  {$result['subtypes']} subtypes, {$result['extensions']} extensions\n\n";

##
#   Processing result DB
##
$typesCount      = 0;
$subtypesCount   = 0;
$extensionsCount = 0;

foreach ($mimetypesRaw as $typeRaw => $subtypes) {
    $type = strtolower($typeRaw);

    if (empty($mimetypes[$type])) {
        $typesCount++;
        $mimetypes[$type] = [];
    }

    foreach ($subtypes as $subtypeRaw => $mtExtensions) {
        $subtype = strtolower($subtypeRaw);

        if (empty($mimetypes[$type][$subtype])) {
            $subtypesCount++;
            $mimetypes[$type][$subtype] = [];
        }

        foreach ($mtExtensions as $ext) {
            $ext = strtolower($ext);

            if (!in_array($ext, $mimetypes[$type][$subtype])) {
                $extensionsCount++;
                $mimetypes[$type][$subtype][] = $ext;
            }
        }

        asort($mimetypes[$type][$subtype], SORT_STRING);
        $mimetypes[$type][$subtype] = array_values($mimetypes[$type][$subtype]);
    }
    ksort($mimetypes[$type], SORT_STRING);
}
ksort($mimetypes, SORT_STRING);

foreach ($mimetypes as $type => $subtypes) {
    foreach ($subtypes as $subtype => $mtExtensions) {
        $mt = $type . '/' . $subtype;

        foreach ($mtExtensions as $ext) {
            if (empty($extensions[$ext])) {
                $extensions[$ext] = [];
            }

            if (!in_array($mt, $extensions[$ext])) {
                $extensions[$ext][] = $mt;
            }
        }
    }
}

foreach ($extensions as $extension => $mt) {
    $extensions[$extension] = array_values(array_unique($mt));
    asort($extensions[$extension], SORT_STRING);
}

echo "result DB size: {$typesCount} types,  {$subtypesCount} subtypes, {$extensionsCount} extensions\n\n";


echo "writing result db to '" . __DIR__ . "/mimes.db.json'\n";
file_put_contents("./mimes.db.json", json_encode($mimetypes, JSON_PRETTY_PRINT));

$sourceClass = file_get_contents("./src/MimeType.php");
$sourceClass = preg_replace("~(.*// <-- mimes start --> \\\\)(.*)(// <-- mimes end --> \\\\.*)~si", "$1\n    private static \$mimes = " . exportMimeTypes($mimetypes) . ";\n$3", $sourceClass);
$sourceClass = preg_replace("~(.*// <-- extensions start --> \\\\)(.*)(// <-- extensions end --> \\\\.*)~si", "$1\n    private static \$extensions = " . exportExtensions($extensions) . ";\n$3", $sourceClass);
file_put_contents("./src/MimeType.php", $sourceClass);

exit(0);

function exportMimeTypes(array $mimetypes) :string {
    $result = '';

    foreach ($mimetypes as $type => $subtypes) {
        $result .= "    '{$type}' => [\n";

        foreach ($subtypes as $subtype => $extensions) {
            $result .= "        '{$subtype}' =>  [" . ($extensions ? "'" . implode("','", $extensions) . "'" : '') . "],\n";
        }

        $result .= "    ],\n";
    }

    return "[\n{$result}]";
}

function exportExtensions(array $extensions) :string {
    $result = "";

    foreach ($extensions as $ext => $mimetypes) {
        $result .= "    '{$ext}' =>  ['" . implode("','", $mimetypes) . "'],\n";
    }

    return "[\n{$result}]";
}
