<?php
declare(strict_types=1);

function fetchFileContents(string $uri, bool $verbose = true) {
    if (!function_exists('curl_init')) {
        if ($verbose) {
            echo "CURL extension has to be enabled in your php.ini\n";
        }
        exit(1);
    }
    if ($verbose) {
        echo "Fetching '{$uri}' ...";
    }

    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL            => $uri,
        CURLOPT_BINARYTRANSFER => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HEADER         => false,
        CURLOPT_FOLLOWLOCATION => true,
    ]);

    $data       = curl_exec($curl);
    $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

    if ($statusCode !== 200) {
        if ($verbose) {
            echo "\rFetching '{$uri}' ... ERROR\n\nUnable to fetch '{$uri}', got non 200 response\n";
        }

        if (!get_cfg_var('curl.cainfo') || !get_cfg_var('openssl.cafile')) {
            echo "Looks like you haven't configured your curl.cacert in php.ini\n\n" .
                 "- Grab the latest cert from https://curl.haxx.se/docs/caextract.html\n" .
                 "- Store it somewhere\n" .
                 "- Put the path to the cacert.pem in `curl.cainfo` setting int your php.ini\n" .
                 "- Tour TLS should work now\n";
        }

        exit(2);
    }

    if ($verbose) {
        echo "\rFetching '{$uri}' ... OK\n";
    }

    return $data;
}

function appendExtensions(array &$mimetypes, string $type, string $subtype, array $extensions, array &$counter) {
    if (empty($mimetypes[$type])) {
        $mimetypes[$type] = [];
        $counter['types']++;
    }

    if (empty($mimetypes[$type][$subtype])) {
        $mimetypes[$type][$subtype] = $extensions;
        $counter['subtypes']++;
        $counter['extensions'] += count($extensions);

        return;
    }

    $new = array_diff($extensions, $mimetypes[$type][$subtype]);
    if (empty($new)) {
        return;
    }

    $mimetypes[$type][$subtype] += $new;
    $counter['extensions']      += count($new);
}
