<?php
require_once 'screenshotmachine.php';
$customer_key = "c71947";
$secret_phrase = ""; //leave secret phrase empty, if not needed

$machine = new ScreenshotMachine($customer_key, $secret_phrase);

//mandatory parameter
$options['url'] = "https://www.google.com";

// all next parameters are optional, see our website screenshot API guide for more details
$options['dimension'] = "1920x1080";  // or "1366xfull" for full length screenshot
$options['device'] = "desktop";
$options['format'] = "jpg";
$options['cacheLimit'] = "0";
$options['delay'] = "200";
$options['zoom'] = "100";

$api_url = $machine->generate_screenshot_api_url($options);

//or save screenshot as an image
$output_file = 'output.png';
file_put_contents($output_file, file_get_contents($api_url));
echo 'Screenshot saved as ' . $output_file . PHP_EOL;