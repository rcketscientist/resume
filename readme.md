# Installation
Local
1. `npm install`

Server

1. Sign-up: https://www.convertapi.com/
1. /var/secure/convertapiKey.php

```php
<?php
$convertapiKey = 'yourSecretKey';
?>
```

Github
1. Setup webhook
  * `Payload URL`: "http://your_site/deploy.php"
  * No need for a secret
  * `Just the push event`

With this in place the website will be automatically updated when you push and converted to a pdf.

# Development
1. `npx gulp watch` (or npm -g gulp) to automatically update css
1. vs code: `live server` extension to live reload
