<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller {
    public function getUser(Request $request) {
        try {
            $user = $request->user();

            if ($user == null) {
                return response()->json(['msg' => 'Not logged in'], 401);
            }

            return response()->json(['msg' => $user]);
        } catch (\Exception $e) {
            return response()->json(['msg' => $e->getMessage()], 500);
        }
    }

    public function getUserById(Request $request) {
        try {
            $user = User::find($request->id);

            if ($user == null) {
                return response()->json(['msg' => 'User not found'], 401);
            }

            return response()->json(['msg' => $user]);
        } catch (\Exception $e) {
            return response()->json(['msg' => $e->getMessage()], 500);
        }
    }

    public function getUserReviews(Request $request) {
        try {
            $user = User::find($request->id);

            if ($user == null) {
                return response()->json(['msg' => 'User not found'], 401);
            }

            $reviews = [];

            foreach ($user->albums as $album) {
                if ($album->pivot->review != null || $album->pivot->review != "") {
                    $reviews[] = $album;
                }
            }

            if (count($reviews) == 0) {
                return response()->json(['msg' => "This user doesn't have any reviews yet"]);
            }

            return response()->json(['msg' => $reviews]);
        } catch (\Exception $e) {
            return response()->json(['msg' => $e->getMessage()], 500);
        }
    }

    public function updateUser(Request $request) {
        try {
            $user = $request->user();

            if ($user == null) {
                return response()->json(['msg' => 'Not logged in'], 401);
            }

            $user->update($request->all());

            return response()->json(['msg' => "User updated successfully"]);
        } catch (\Exception $e) {
            return response()->json(['msg' => $e->getMessage()], 500);
        }
    }
}
