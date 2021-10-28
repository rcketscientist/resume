Updating your resume can be a chore, especially if you end up managing multiple formats.  This setup will allow you to simply focus on your html resume and automatically convert it to pdf on push.  How does it work?

1. Github webhook calls deploy.php on push.
1. Deploy.php updates your server clone.
1. Deploy.php uses https://www.convertapi.com/ to convert your hosted resume to pdf and download it locally.

Please note that the conversion is not terribly robust.  Changes, especially to the graph, may corrupt the conversion.  It took quite some time to fine-tune the behavior.  Be sure to confirm the pdf after major changes.

# Installation
Local
1. `npm install`

Server

1. Sign-up: https://www.convertapi.com/
1. /var/secure/convertapi.php

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
