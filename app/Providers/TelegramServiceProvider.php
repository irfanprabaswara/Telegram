<?php
namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
  class TelegramServiceProvider extends ServiceProvider
  {
    $TOKEN      = "502539981:AAE7FDMraFwOV40U8NNR4MLpIkmnE1J7r84";
    $usernamebot= "@Perfectcode_bot";
    function request_url($method)
    {
    global $TOKEN;
    return "https://api.telegram.org/bot" . $TOKEN . "/". $method;
    }
  }
 ?>
