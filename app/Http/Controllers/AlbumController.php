<?php

namespace App\Http\Controllers;

use App\Models\Album;
use Illuminate\Http\Request;

class AlbumController extends Controller {
    public function getAlbums(Request $request) {
        try {
            $albums = Album::all();

            if ($albums == null) {
                return response()->json(['msg' => 'Error trying to fetch de albums'], 404);
            }

            return response()->json(['msg' => $albums]);
        }catch (\Exception $e) {
            return response()->json(['msg' => $e->getMessage()], 500);
        }
    }

    public function getAlbum(Request $request) {
        try {
            $album = Album::find($request->id);

            if ($album == null) {
                return response()->json(['msg' => 'Album not found'], 404);
            }

            return response()->json(['msg' => $album]);
        } catch (\Exception $e) {
            return response()->json(['msg' => $e->getMessage()], 500);
        }
    }

    public function getAlbumReviews(Request $request) {
        try {
            $album = Album::find($request->id);

            if ($album == null) {
                return response()->json(['msg' => 'Album not found'], 404);
            }

            $reviews = [];

            foreach ($album->users as $user) {
                if ($user->pivot->review != null || $user->pivot->review != "") {
                    $reviews[] = $user;
                }
            }

            if (count($reviews) == 0) {
                return response()->json(['msg' => []]);
            }

            return response()->json(['msg' => $reviews]);
        } catch (\Exception $e) {
            return response()->json(['msg' => $e->getMessage()], 500);
        }
    }
}
