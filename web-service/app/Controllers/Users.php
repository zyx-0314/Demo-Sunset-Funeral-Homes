<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Users extends BaseController
{
    public function showLandingPage(): string
    {
        return view('user/landing');
    }

    public function showMoodBoard(): string
    {
        return view('user/mood_board');
    }

    public function showRoadMap(): string
    {
        return view('user/road_map');
    }
}
