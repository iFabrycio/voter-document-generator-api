<?php

namespace App\Http\Controllers;

use App\Consumers\PanagoraConsumer;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $panagora_api;

    public function __construct(PanagoraConsumer $panagora_api)
    {
        $this->panagora_api = $panagora_api;
    }
}
