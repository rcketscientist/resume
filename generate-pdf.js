const puppeteer = require("puppeteer");

const [, , url = "http://resume.anthonymandra.com", output = "resumeMandra.pdf"] =
  process.argv;

async function generatePdf() {
  const browser = await puppeteer.launch({ args: ["--no-sandbox"] });

  try {
    const page = await browser.newPage();
    await page.goto(url, { waitUntil: "networkidle0" });
    await page.evaluate(() => document.fonts.ready);
    await page.pdf({
      path: output,
      format: "Letter",
      printBackground: true,
      preferCSSPageSize: true,
    });
  } finally {
    await browser.close();
  }
}

generatePdf().catch((error) => {
  console.error(error);
  process.exitCode = 1;
});
