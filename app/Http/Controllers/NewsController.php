<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Redis;

class NewsController extends Controller
{
    public function index()
    {
        $updates = Redis::zrevrange('github_updates', 0, -1);
        $updates = array_map(function($update) {
            return json_decode($update, true);
        }, $updates);
        
        return view('news', compact('updates'));
    }
}
