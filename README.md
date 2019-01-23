<h1 align="center">xobotyi/php-mime-type</h1>
<p align="center">
    <a href="https://packagist.org/packages/xobotyi/php-mime-type"><img src="https://poser.pugx.org/xobotyi/php-mime-type/version?format=flat-square"></a>
    <a href="https://travis-ci.org/xobotyi/php-mime-type"><img src="https://img.shields.io/travis/xobotyi/php-mime-type.svg?style=flat-square"></a>
    <a href="https://app.codacy.com/project/xobotyi/php-mime-type"><img src="https://img.shields.io/codacy/grade/10c5af00007949b280a8d8a06242801a.svg?style=flat-square"></a>
    <a href="https://app.codacy.com/project/xobotyi/php-mime-type"><img src="https://img.shields.io/codacy/coverage/10c5af00007949b280a8d8a06242801a.svg?style=flat-square"></a>
    <a href="https://packagist.org/packages/xobotyi/php-mime-type"><img src="https://poser.pugx.org/xobotyi/php-mime-type/downloads?format=flat-square"></a>
    <a href="https://packagist.org/packages/xobotyi/php-mime-type"><img src="https://poser.pugx.org/xobotyi/php-mime-type/license?format=flat-square"></a>
</p>

A comprehensive MIME-type lib allowing you to get known file extensions by mime-type and vice-versa.  
It uses [mime-db](https://github.com/jshttp/mime-db) underneath as it is most thorough and recent mime-types list, aggregated from [IANA](http://www.iana.org/assignments/media-types/media-types.xhtml), [Apache](http://svn.apache.org/repos/asf/httpd/httpd/trunk/docs/conf/mime.types) and [nginx](http://hg.nginx.org/nginx/raw-file/default/conf/mime.types) 

### Install
```bash
composer require xobotyi/php-mime-type
```

### Usage
```php
<?php

use xobotyi\MimeType;

MimeType::getExtensions('text/plain'); // ['txt', 'text', 'conf', 'def', 'list', 'log', 'in', 'ini']
MimeType::getExtensionMimes('wav'); // ['audio/wav', 'audio/wave', 'audio/x-wav']
```

### API
##### MimeType::isSupported(string $type)
_Description:_ Check whether mime-type is supported.  
_Parameters:_ **$type** - mime-type to check.  
_Return:_ boolean

##### MimeType::getExtensions(string $mime)
_Description:_ Return the mime-type's associated extensions.  
_Return:_ plain array of strings or null if mime-type is unknown 

##### MimeType::getSupportedMimes(string $group = null)
_Description:_ Return the plain list of supported mime-types.  
_Parameters:_ **$group** - group of mime-types to return (group is string before the slash, 4ex: text, video).  
_Return:_ plain array of strings.

##### MimeType::isSupportedExtension(string $extension)
_Description:_ Check whether file extension is supported.  
_Parameters:_ **$extension** - extension to check (without leading dot).  
_Return:_ bool

##### MimeType::getExtensionMimes()
_Description:_ Return known relative mime-types  
_Return:_ Array (null if extension is unknown), even for extensions that associated with a single mime-type.  
This is made for return values monotony, due to some extensions are associated with more than one mime-type.

##### MimeType::getSupportedExtensions()
_Description:_ Return the plain list of supported file extensions.  
_Return:_ plain array of strings or numbers (some file extensions are numbers only so the treated as integer)
