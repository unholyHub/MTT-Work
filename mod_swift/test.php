<?php
require_once 'lib/swift_required.php';

$transport = Swift_SmtpTransport::newInstance();

$mailer = Swift_Mailer::newInstance($transport);
$message = Swift_Message::newInstance('Wonderful Subject')
  ->setFrom('alexander@alexander-wollow.org')
  ->setTo('lex.mihaylov@gmail.com')
  ->setContentType('text/html')
  ->setBody('<h1>Test</h1>');

$attachment = Swift_Attachment::newInstance(file_get_contents('../resources/logo.jpg'), 'logo.jpg');  
$message->attach($attachment);
$numSent = $mailer->send($message);
printf("Sent %d messages\n", $numSent);
?>