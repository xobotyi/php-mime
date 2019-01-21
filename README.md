<h1 align="center">xobotyi/php-mime-type</h1>
<p align="center">
    <img src="https://poser.pugx.org/xobotyi/php-mime-type/version?format=flat-square">
    <img src="https://img.shields.io/travis/xobotyi/php-mime-type.svg?style=flat-square">
    <img src="https://img.shields.io/codacy/grade/10c5af00007949b280a8d8a06242801a.svg?style=flat-square">
    <img src="https://img.shields.io/codacy/coverage/10c5af00007949b280a8d8a06242801a.svg?style=flat-square">
    <img src="https://poser.pugx.org/xobotyi/php-mime-type/downloads?format=flat-square">
    <img src="https://poser.pugx.org/xobotyi/php-mime-type/license?format=flat-square">
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

MimeType::getInfo('text/plain')['extensions']; // ['txt', 'text', 'conf', 'def', 'list', 'log', 'in', 'ini']
MimeType::getExtensionMimes('wav'); // ['audio/wav', 'audio/wave', 'audio/x-wav']
```

### API
##### MimeType::isSupported()
_Description:_ Check whether mime-type is supported.
_Return:_ boolean

##### MimeType::getInfo()
_Description:_ Return the mime-type information.  
_Return:_ associative array with next structure:  
- `source` - where the mime type is defined. If not set, it's probably a custom media type.
    - `apache` - [Apache common media types](http://svn.apache.org/repos/asf/httpd/httpd/trunk/docs/conf/mime.types)
    - `iana` - [IANA-defined media types](http://www.iana.org/assignments/media-types/media-types.xhtml)
    - `nginx` - [nginx media types](http://hg.nginx.org/nginx/raw-file/default/conf/mime.types)
- `extensions` - known extensions associated with this mime type.
- `compressible` - whether a file of this type can be gzipped.
- `charset` - the default charset associated with this type, if any.

##### MimeType::getSupportedMimes()
_Description:_ Return the plain list of supported mime-types.  
_Return:_ plain array of strings

##### MimeType::isSupportedExtension()
_Description:_ Check whether file extension is supported.  
_Return:_ bool

##### MimeType::getExtensionMimes()
_Description:_ Return known relative mime-types  
_Return:_ Array (null if extension is unknown), even for extensions that associated with a single mime-type.  
This is made for return values monotony, due to some extensions are associated with more than one mime-type.

##### MimeType::getSupportedExtensions()
_Description:_ Return the plain list of supported file extensions.  
_Return:_ plain array of strings ot numbers (some file extensions are numbers only so the treated as integer)
