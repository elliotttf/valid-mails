<?php

require_once 'smtp_validateEmail.class.php';

// You should change this to a valid email address before
// running the script. Some mail servers will deny fake
// mail addresses from connecting to their server.
$sender = 'you@example.com';
$SMTP_Validator = new SMTP_validateEmail();
$SMTP_Validator->debug = FALSE;
$emails = array();

if (!isset($argv[1]) || !file_exists($argv[1])) {
  echo "Invalid or missing input file. ./valid-mails.php PATH/TO/INPUT/file.csv" . PHP_EOL;
  exit(1);
}

$in = fopen($argv[1], 'r');
while ($row = fgetcsv($in)) {
  $emails[] = $row[0];
}
fclose($in);

$results = $SMTP_Validator->validate($emails, $sender);
$count = 0;

if (isset($argv[2])) {
  $out = fopen($argv[2], 'w');
}
foreach ($results as $email => $result) {
  if (!$result) {
    $count++;
    if ($out) {
      fwrite($out, $email . PHP_EOL);
    }
    echo "$email is not valid." . PHP_EOL;
  }
}
if ($out) {
  fclose($out);
}

echo PHP_EOL . PHP_EOL;
echo "Found $count invalid email addresses." . PHP_EOL;

