<?php

namespace App\Http\Controllers\Api\V1;


class HomeController extends  BaseController
{
    public function index()
    {
        return $this->response->array([
            'message' => 'success',
            'status_code' => 200
        ]);
    }
}
