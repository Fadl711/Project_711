<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class ArtController extends Controller
{
    public function index(){

            Artisan::call('db:backup');

        return response()->json(['message' => 'تم تنفيذ الأمر بنجاح']);
    }
}
