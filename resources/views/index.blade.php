<?php
//isikan token dan nama botmu yang di dapat dari bapak bot :
$TOKEN      = "502539981:AAE7FDMraFwOV40U8NNR4MLpIkmnE1J7r84";
$usernamebot= "@Perfectcode_bot"; // sesuaikan besar kecilnya, bermanfaat nanti jika bot dimasukkan grup.
// aktifkan ini jika perlu debugging
$debug = false;

// fungsi untuk mengirim/meminta/memerintahkan sesuatu ke bot
function request_url($method)
{
global $TOKEN;
return "https://api.telegram.org/bot" . $TOKEN . "/". $method;
}

// fungsi untuk meminta pesan
// bagian ebook di sesi Meminta Pesan, polling: getUpdates
function get_updates($offset)
{
$url = request_url("getUpdates")."?offset=".$offset;
    $resp = file_get_contents($url);
    $result = json_decode($resp, true);
    if ($result["ok"]==1)
        return $result["result"];
    return array();
}
// fungsi untuk mebalas pesan,
// bagian ebook Mengirim Pesan menggunakan Metode sendMessage
function send_reply($chatid, $msgid, $text)
{
global $debug;
$data = array(
    'chat_id' => $chatid,
    'text'  => $text,
    'reply_to_message_id' => $msgid   // <---- biar ada reply nya balasannya, opsional, bisa dihapus baris ini
);
// use key 'http' even if you send the request to https://...
$options = array(
    'http' => array(
        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
        'method'  => 'POST',
        'content' => http_build_query($data),
    ),
);
$context  = stream_context_create($options);
$result = file_get_contents(request_url('sendMessage'), false, $context);
if ($debug)
    print_r($result);
}

// fungsi mengolahan pesan, menyiapkan pesan untuk dikirimkan
function create_response($text, $message)
{
global $usernamebot;
// inisiasi variable hasil yang mana merupakan hasil olahan pesan
$hasil = '';
$fromid = $message["from"]["id"]; // variable penampung id user
$chatid = $message["chat"]["id"]; // variable penampung id chat
$pesanid= $message['message_id']; // variable penampung id message
// variable penampung username nya user
isset($message["from"]["username"])
    ? $chatuser = $message["from"]["username"]
    : $chatuser = '';

// variable penampung nama user
isset($message["from"]["last_name"])
    ? $namakedua = $message["from"]["last_name"]
    : $namakedua = '';
$namauser = $message["from"]["first_name"]. ' ' .$namakedua;
// ini saya pergunakan untuk menghapus kelebihan pesan spasi yang dikirim ke bot.
$textur = preg_replace('/\s\s+/', ' ', $text);
// memecah pesan dalam 2 blok array, kita ambil yang array pertama saja
$command = explode(' ',$textur,2); //
// identifikasi perintah (yakni kata pertama, atau array pertamanya)
switch ($command[0]) {
    // jika ada pesan /id, bot akan membalas dengan menyebutkan idnya user
    case '/id':
    case '/id'.$usernamebot : //dipakai jika di grup yang haru ditambahkan @usernamebot
        $hasil = "$namauser, ID kamu adalah $fromid";
        break;

    // jika ada permintaan waktu
    case '/time':
    case '/time'.$usernamebot :
        $hasil  = "$namauser, waktu lokal bot sekarang adalah :\n";
        $hasil .= date("d M Y")."\nPukul ".date("H:i:s");
        break;
    // balasan default jika pesan tidak di definisikan
    default:
        $hasil = 'Terimakasih, pesan telah kami terima.';
        break;
}
return $hasil;
}
?>
