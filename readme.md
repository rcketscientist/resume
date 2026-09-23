Updating your resume can be a chore, especially if you end up managing multiple formats. This setup lets you focus on the HTML resume and automatically generate the PDF on push. GitHub Actions uses Puppeteer and headless Chromium, so it uses the same browser rendering engine as the web resume.

1. Github webhook calls deploy.php on push.
1. Deploy.php updates your server clone.
1. GitHub Actions commits the generated `resumeMandra.pdf` back to the repository.

# Installation
Local
1. `npm install`

Server

1. The server only needs its existing Git checkout and GitHub webhook.

Github
1. Setup webhook
  * `Payload URL`: "http://your_site/deploy.php"
  * Set a random webhook secret and store the same value in `/var/secure/github-webhook.php`:
    ```php
    <?php
    $githubWebhookSecret = 'yourRandomSecret';
    ?>
    ```
  * `Just the push event`

With this in place the website will be automatically updated when you push. The generated PDF is committed separately by the workflow, and the webhook pulls that commit onto the server. The hook accepts only signed pushes from this repository, deploys the exact `origin/master` state, prevents overlapping pulls, and does not expose command output. No Node.js, Chromium, PHP conversion dependency, or SSH access from GitHub is required.

The deploy hook disables Git's ownership check only for its fixed deployment command, so it continues to work when the nginx container runs Git as a different user than the checkout owner without depending on a server-specific path or global Git configuration. It fetches and resets to `origin/master` explicitly, then logs the deployed commit and PDF metadata for troubleshooting.

# Development
1. `npx gulp watch` (or npm -g gulp) to automatically update css
1. vs code: `live server` extension to live reload
