<?php
const SECRET_FILE = '/var/secure/github-webhook.php';
const REPOSITORY_NAME = 'rcketscientist/resume';

function respond($statusCode) {
	http_response_code($statusCode);
	exit;
}

function loadWebhookSecret() {
	if (is_file(SECRET_FILE)) {
		require SECRET_FILE;
		if (isset($githubWebhookSecret) && $githubWebhookSecret !== '') {
			return $githubWebhookSecret;
		}
	}

	return null;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
	respond(405);
}

$event = isset($_SERVER['HTTP_X_GITHUB_EVENT']) ? $_SERVER['HTTP_X_GITHUB_EVENT'] : '';
$signature = isset($_SERVER['HTTP_X_HUB_SIGNATURE_256']) ? $_SERVER['HTTP_X_HUB_SIGNATURE_256'] : '';
$body = file_get_contents('php://input');

if ($event !== 'push' || strpos($signature, 'sha256=') !== 0) {
	respond(400);
}

$webhookSecret = loadWebhookSecret();
if ($webhookSecret === null) {
	respond(500);
}

$expectedSignature = 'sha256=' . hash_hmac('sha256', $body, $webhookSecret);
if (!hash_equals($expectedSignature, $signature)) {
	respond(401);
}

$payload = json_decode($body, true);
if (!is_array($payload) && strpos(isset($_SERVER['CONTENT_TYPE']) ? $_SERVER['CONTENT_TYPE'] : '', 'application/x-www-form-urlencoded') === 0) {
	$form = array();
	parse_str($body, $form);
	if (isset($form['payload'])) {
		$payload = json_decode($form['payload'], true);
	}
}

if (!is_array($payload)) {
	error_log('Resume deployment ignored: invalid GitHub push payload');
	respond(400);
}

if (!isset($payload['repository']['full_name']) || $payload['repository']['full_name'] !== REPOSITORY_NAME) {
	$ref = isset($payload['ref']) ? $payload['ref'] : 'missing';
	$repositoryName = isset($payload['repository']['full_name']) ? $payload['repository']['full_name'] : 'missing';
	error_log('Resume deployment ignored: repository ' . $repositoryName . ', ref ' . $ref);
	respond(202);
}

$lock = fopen(sys_get_temp_dir() . '/resume-deploy.lock', 'c');
if ($lock === false || !flock($lock, LOCK_EX | LOCK_NB)) {
	if (is_resource($lock)) {
		fclose($lock);
	}
	respond(409);
}

$repository = __DIR__;
$repositoryArgument = escapeshellarg($repository);
$git = 'git -c safe.directory=* -C ' . $repositoryArgument;
$command = $git . ' fetch --prune origin master && ' . $git . ' reset --hard origin/master 2>&1';
$output = array();
$exitCode = 0;
exec($command, $output, $exitCode);
flock($lock, LOCK_UN);
fclose($lock);

if ($exitCode !== 0) {
	error_log('Resume deployment failed: git fetch/reset exited with code ' . $exitCode . ': ' . implode("\n", $output));
	respond(500);
}

$head = trim(shell_exec($git . ' rev-parse HEAD 2>&1'));
$pdf = $repository . '/resumeMandra.pdf';
$pdfDetails = is_file($pdf) ? filesize($pdf) . ' bytes, ' . date('c', filemtime($pdf)) : 'missing';
error_log('Resume deployment succeeded: ' . $repository . ' at ' . $head . '; resumeMandra.pdf ' . $pdfDetails);

respond(204);
?>
