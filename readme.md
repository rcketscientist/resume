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
  * No need for a secret
  * `Just the push event`

With this in place the website will be automatically updated when you push. The generated PDF is committed separately by the workflow, and the webhook pulls that commit onto the server. No Node.js, Chromium, PHP conversion dependency, or SSH access from GitHub is required.

# Development
1. `npx gulp watch` (or npm -g gulp) to automatically update css
1. vs code: `live server` extension to live reload
