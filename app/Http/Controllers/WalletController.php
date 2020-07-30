<?php

namespace App\Http\Controllers;

use App\Http\Resources\WalletResource;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    public function __invoke(Request $request)
    {
        return new WalletResource(current_user()->accounts);
    }
}
