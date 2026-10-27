<?php
// Renders every page in both languages and asserts clean, well-formed output.
$root = dirname(__DIR__);
if (($argv[1] ?? '') === '--render') {
  ini_set('session.save_path', sys_get_temp_dir());
  error_reporting(E_ALL);
  ini_set('display_errors', 'stderr');
  $_SERVER['SCRIPT_FILENAME'] = $root . '/index.php';
  $_SERVER['REQUEST_URI'] = '/' . $argv[2] . '.html';
  $_SERVER['HTTP_HOST'] = 'localhost';
  if ($argv[3] !== '-') $_SERVER['HTTP_ACCEPT_LANGUAGE'] = $argv[3];
  $_GET = ['page' => $argv[2]];
  session_id(bin2hex(random_bytes(8)));
  include $root . '/index.php';
  echo ob_get_clean();
  exit(0);
}

function render(string $page, string $accept): array {
  $cmd = [PHP_BINARY, __FILE__, '--render', $page, $accept];
  $p = proc_open($cmd, [1 => ['pipe', 'w'], 2 => ['pipe', 'w']], $pipes);
  $out = stream_get_contents($pipes[1]);
  $err = trim(stream_get_contents($pipes[2]));
  fclose($pipes[1]); fclose($pipes[2]);
  $code = proc_close($p);
  return [$code, $out, $err];
}

function check(string $page, string $accept): array {
  [$code, $html, $err] = render($page, $accept);
  $fail = [];
  if ($code !== 0) $fail[] = "exit code $code";
  if ($err !== '') $fail[] = "php: " . strtok($err, "\n");
  if (trim($html) === '') return [$fail, $html];
  if (preg_match('~<\?(?!xml)~', $html)) $fail[] = 'raw PHP leaked';
  if (strpos($html, '</html>') === false) $fail[] = 'no closing html';
  $xml = preg_replace('~<!DOCTYPE.*?>~s', '', $html);
  $xml = str_replace(['&copy;', '&nbsp;'], ['&#169;', '&#160;'], $xml);
  libxml_use_internal_errors(true);
  $doc = new DOMDocument();
  if (!$doc->loadXML($xml)) {
    foreach (array_slice(libxml_get_errors(), 0, 2) as $e) $fail[] = 'xml: ' . trim($e->message) . ' @' . $e->line;
  } else {
    $ids = [];
    foreach ($doc->getElementsByTagName('*') as $el) {
      if ($el->hasAttribute('id')) {
        $id = $el->getAttribute('id');
        if (isset($ids[$id])) $fail[] = "duplicate id=$id";
        $ids[$id] = true;
      }
      if ($el->tagName === 'img' && !$el->hasAttribute('alt')) $fail[] = 'img without alt @' . $el->getLineNo();
    }
  }
  libxml_clear_errors();
  return [$fail, $html];
}

$cases = [];
foreach (glob($root . '/inc/*.php') as $f) {
  $page = basename($f, '.php');
  foreach (['cs', 'en', '-'] as $accept) $cases[] = [$page, $accept];
}
$cases[] = ['../../etc/passwd', '-'];

$failed = 0;
foreach ($cases as [$page, $accept]) {
  [$fail, $html] = check($page, $accept);
  if ($page === '../../etc/passwd' && strpos($html, 'Welcome on my website') === false) $fail[] = 'traversal not redirected to default page';
  if ($fail) {
    $failed++;
    echo 'FAIL ' . $page . '/lang=' . $accept . ': ' . implode('; ', $fail) . "\n";
  }
}
echo $failed ? $failed . ' of ' . count($cases) . " checks failed\n" : count($cases) . " renders clean\n";
exit($failed ? 1 : 0);
