<?php
$content = file_get_contents("php://input");
$content = '{"update_id":534691120,"message":{"message_id":264,"from":{"id":227853458,"is_bot":false,"first_name":"Simo","username":"docsimo85","language_code":"it-IT"},"chat":{"id":227853458,"first_name":"Simo","username":"docsimo85","type":"private"},"date":1517305698,"text":"!network"}}';
$update = json_decode($content, true);
if(!$update){
    exit;
}
$message = isset($update['message']) ? $update['message'] : "";
$json = file_get_contents('https://chainz.cryptoid.info/ufo/api.dws?q=getdifficulty');
$json2 = file_get_contents('https://chainz.cryptoid.info/ufo/api.dws?q=getblockcount');
$json3 = file_get_contents('https://chainz.cryptoid.info/ufo/api.dws?q=ticker.usd');
$json4 = file_get_contents('https://chainz.cryptoid.info/ufo/api.dws?q=circulating');
$json5 = file_get_contents('https://chainz.cryptoid.info/ufo/api.dws?q=ticker.btc');
$json6 = file_get_contents('https://api.coinmarketcap.com/v1/ticker/ufo-coin/');
var_dump($json6[id]);die;
$messageId = isset($message['message_id']) ? $message['message_id'] : "";
$chatId = isset($message['chat']['id']) ? $message['chat']['id'] : "";
$firstname = isset($message['chat']['first_name']) ? $message['chat']['first_name'] : "";
$lastname = isset($message['chat']['last_name']) ? $message['chat']['last_name'] : "";
$username = isset($message['chat']['username']) ? $message['chat']['username'] : "";
$gruppo = $message['chat']['type'];
$date = isset($message['date']) ? $message['date'] : "";
$text = isset($message['text']) ? $message['text'] : "";
$text = trim($text);
//$text = strtolower($text);
if($text == '!network'){
    header("Content-Type: application/json");
    $parameters = array('chat_id' => $chatId,
        "text" =>
            '🌎 UFO Coin Real Time Info: 🌎'.chr(10).chr(10).
            'Current diff: '.json_decode($json,true).chr(10).
            'Current block: '.json_decode($json2,true).chr(10).
            'Rank on CMC: '.json_decode($json6["id"],true).chr(10).
            //'Current USD Value: '.json_decode($json3,true).chr(10).
            'Circulating Coins: '.number_format(json_decode($json4,true)).chr(10).chr(10).
            '✅For info on this bot type !help'
    );
    $parameters["method"] = "sendMessage";
//$parameters["reply_markup"] = '{ "keyboard": [["uno", "due"], ["tre", "quattro"], ["cinque"]], "resize_keyboard": true, "one_time_keyboard": false}';
}
else if (strpos($text,'!wallet') === 0) {
    if ($gruppo == 'private') {
        $wallet = explode(" ",$text);
        $urlcheckwallet = 'https://chainz.cryptoid.info/ufo/api.dws?q=getbalance&a='.$wallet[1];
        $richlist = 'https://chainz.cryptoid.info/ufo/api.dws?q=richrank&a='.$wallet[1];
        header("Content-Type: application/json");
        $parameters = array('chat_id' => $chatId, "text" =>
//        '💰 UFO Coin Value 💰'.chr(10).chr(10).
//        'Current USD Value: '.number_format(json_decode($json3,true),5).chr(10).
//        'Current BTC Value: '.number_format(json_decode($json5,true),5).chr(10).chr(10).
//        '✅For info on this bot type !help'
            '💰 Current wallet balance: '. number_format(file_get_contents($urlcheckwallet),6,',','.').chr(10).
            '📉 Rich rank: '. number_format(file_get_contents($richlist),0,',','.').chr(10).chr(10).
            '✅For info on this bot type !help'
        );
        $parameters["method"] = "sendMessage";
    }
    else if ($gruppo == 'group') {
        header("Content-Type: application/json");
        $parameters = array('chat_id' => $chatId, "text" =>
        '⛔ The !wallet command is avaible only in private chat to protect your privacy. ⛔'.chr(10).chr(10).
        'Please tap on this bot and start a private chat to use !wallet command.'.chr(10).chr(10).
        '✅For info on this bot type !help'
        );
        $parameters["method"] = "sendMessage";
    }
}
else if($text == '!price'){
    header("Content-Type: application/json");
    $parameters = array('chat_id' => $chatId, "text" =>
        '💰 UFO Coin Value 💰'.chr(10).chr(10).
        'Current USD Value: '.number_format(json_decode($json3,true),5).chr(10).
        'Current BTC Value: '.number_format(json_decode($json5,true),8).chr(10).chr(10).
        '✅For info on this bot type !help'
    );
    $parameters["method"] = "sendMessage";
}
else if($text == '!help'){
    header("Content-Type: application/json");
    $parameters = array('chat_id' => $chatId, "text" =>
        '🕳 UFO Coin Info Bot 🕳'.chr(10).
        'developed with ❤ by @docsimo85'.chr(10).chr(10).
        '-COMMANDS-'.chr(10).
        '!network - bot will reply with real time info about UFO Coin diff and block.'.chr(10).
        '!price - bot will reply with USD and BTC current value.'.chr(10).
        '!help - show this instructions.'.chr(10).chr(10).
        '-PRIVATE CHAT COMMANDS-'.chr(10).
        '!wallet yourufowalletaddresshere - bot will reply with your current wallet balance and rich rank'.chr(10).chr(10).
        'This bot does not require to be admin and it can be added in group'.chr(10).chr(10).
        '✅ If you find it useful donations are welcome :) UFO Address: BwJvr6HVnnsHRK7PArc72yrLXYEe52yAYp');
    $parameters["method"] = "sendMessage";
}
;
echo json_encode($parameters);
