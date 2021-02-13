<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class InvitationController extends Controller
{
    public function create()
    {
        return response()->json(['message' => 'invitation created']);
    }

    public function show()
    {
        return response()->json(['message' => 'invitation showed']);
    }
}
