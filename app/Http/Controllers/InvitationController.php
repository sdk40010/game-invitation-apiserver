<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Invitation\StoreRequest;
use App\Models\Invitation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class InvitationController extends Controller
{
    public function store(StoreRequest $request)
    {
        $validated = $request->validated();
        Log::debug($validated);
        $invitation = new Invitation($validated);
        $invitation->user()->associate(Auth::user());
        $invitation->save();
        return response()->json([
            'message' => 'new invitation created',
            'redirectTo' => '/invitations'.'/'.$invitation->id
        ]);
    }

    public function show(Invitation $invitation)
    {
        $invitation->load('user');
        return response()->json($invitation);
    }
}
