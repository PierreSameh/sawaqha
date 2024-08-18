<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Str;

class DownloadLinkController extends Controller
{
    // Method to create an invitation code for a user
    public function createInvitationCode(Request $request)
    {
        $user = $request->user(); // Assuming the user is authenticated

        // Check if the user already has an invitation code
        if ($user->invitation_code) {
            return response()->json([
                'message' => 'Invitation code already exists',
                'invitation_code' => $user->invitation_code
            ]);
        }

        // Generate a unique invitation code
        $invitationCode = Str::random(8) . '_' . $user->id;

        // Save the code to the user's invitation_code attribute
        $user->invitation_code = $invitationCode;
        $user->save();

        return response()->json([
            'message' => 'Invitation code created successfully',
            'invitation_code' => $invitationCode
        ]);
    }

    // Method to enter an invitation code
    public function enterInvitationCode(Request $request)
    {
        $invitationCode = $request->input('invitation_code');
        $user = $request->user(); // Assuming the user is authenticated

        // Check if the invitation code exists
        $invitingUser = User::where('invitation_code', $invitationCode)->first();

        if (!$invitingUser) {
            return response()->json([
                'message' => 'Invalid invitation code'
            ], 400);
        }

        // Add 10 coins to the user's balance (entered user)
        $user->balance += 10;
        $user->save();

        // Add 30 coins to the inviting user's balance
        $invitingUser->balance += 30;
        $invitingUser->save();

        return response()->json([
            'message' => 'Invitation code applied successfully',
            'user_balance' => $user->balance,
            'inviting_user_balance' => $invitingUser->balance
        ]);
    }
}
