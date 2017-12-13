<?php

require_once __DIR__ . '/vendor/autoload.php';

$http_client = new \LINE\LINEBot\HTTPClient\CurlHTTPClient(getenv('CHANNEL_ACCESS_TOKEN'));
$bot = new \LINE\LINEBot($http_client, array('channnel_secret' => getenv('CHANNEL_SECRET')));

$signature = $_SERVER['HTTP_' . \LINE\LINEBot\Constant\HTTPHeader::LINE_SIGNATURE];

try{
    $events = $bot->parseEventRequest(file_get_contents('php://input'), $signature);
} catch (Exception $ex) {
    error_log(var_export($ex, TRUE));
}

foreach ($events as $event){
  if (!($event instanceof \LINE\LINEBot\Event\MessageEvent)) {
    error_log('Non message event has come');
    continue;
  }
  if (!($event instanceof \LINE\LINEBot\Event\MessageEvent\TextMessage)) {
    error_log('Non text message has come');
    continue;
  }
  
  $bot->replyText($event->getReplyToken(), $event->getText());
}