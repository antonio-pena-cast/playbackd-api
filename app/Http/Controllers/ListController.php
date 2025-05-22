<?php

namespace App\Http\Controllers;

use App\Models\Album;
use Illuminate\Http\Request;

class ListController extends Controller {
    public function getUserAlbum(Request $request) {
        try {
            $user = $request->user();

            if ($user == null) {
                return response()->json(['msg' => 'Not logged in'], 401);
            }

            $album = Album::find($request->id);

            if ($album == null) {
                return response()->json(['msg' => 'Album not found'], 403);
            }

            $entry = null;

            foreach ($album->users as $albumUser) {
                if ($albumUser->pivot->user_id == $user->id) {
                    $entry = $albumUser;
                }
            }

            if ($entry == null) {
                return response()->json(['msg' => null]);
            }

            return response()->json(['msg' => $entry]);
        } catch (\Exception $e) {
            return response()->json(['msg' => $e->getMessage()], 500);
        }
    }

    public function getPlayedAlbums(Request $request) {
        try {
            $user = $request->user();

            if ($user == null) {
                return response()->json(['msg' => 'Not logged in'], 401);
            }

            $albums = [];

            foreach ($user->albums as $album) {
                if ($album->pivot->type == "played") {
                    $albums[] = $album;
                }
            }

            return response()->json(['msg' => $albums]);
        } catch (\Exception $e) {
            return response()->json(['msg' => $e->getMessage()], 500);
        }
    }

    public function getListenList(Request $request) {
        try {
            $user = $request->user();

            if ($user == null) {
                return response()->json(['msg' => 'Not logged in'], 401);
            }

            $albums = [];

            foreach ($user->albums as $album) {
                if ($album->pivot->type == "listenlist") {
                    $albums[] = $album;
                }
            }

            return response()->json(['msg' => $albums]);
        } catch (\Exception $e) {
            return response()->json(['msg' => $e->getMessage()], 500);
        }
    }

    public function setPlayedAlbum(Request $request) {
        try {
            $user = $request->user();

            if ($user == null) {
                return response()->json(['msg' => 'Not logged in'], 401);
            }

            $found = false;

            if (count($user->albums) <= 0) {
                $user->albums()->attach($request->albumId, ['type' => 'played', 'review' => $request->review, 'rating' =>
                    $request->rating, 'date' => $request->date]);
            } else {
                foreach ($user->albums as $album) {
                    if ($album->id == $request->albumId) {
                        $found = true;
                        if ($album->pivot->type == "listenlist") {
                            $album->pivot->type = "played";
                            $album->pivot->review = $request->review;
                            $album->pivot->rating = $request->rating;
                            $album->pivot->date = $request->date;
                            $album->pivot->save();
                        } else {
                            return response()->json(['msg' => 'Album already on played list'], 400);
                        }
                    }
                }
            }

            if (!$found) {
                $user->albums()->attach($request->albumId, ['type' => 'played', 'review' => $request->review, 'rating' =>
                    $request->rating, 'date' => $request->date]);
            }

            return response()->json(['msg' => 'Album added to played list']);
        } catch (\Exception $e) {
            return response()->json(['msg' => $e->getMessage()], 500);
        }
    }

    public function setListenListAlbum(Request $request) {
        try {
            $user = $request->user();

            if ($user == null) {
                return response()->json(['msg' => 'Not logged in'], 401);
            }

            foreach ($user->albums as $album) {
                if ($album->id == $request->albumId) {
                    if ($album->pivot->type == "played") {
                        return response()->json(['msg' => 'Album already on played list'], 400);
                    } else {
                        return response()->json(['msg' => 'Album already on listen list'], 400);
                    }
                }
            }

            $user->albums()->attach($request->albumId, ['type' => 'listenlist']);

            return response()->json(['msg' => 'Album added to listen list']);
        } catch (\Exception $e) {
            return response()->json(['msg' => $e->getMessage()], 500);
        }
    }

    public function updateAlbumRating(Request $request) {
        try {
            $user = $request->user();

            if ($user == null) {
                return response()->json(['msg' => 'Not logged in'], 401);
            }

            foreach ($user->albums as $album) {
                if ($album->id == $request->albumId) {
                    $album->pivot->update(['rating' => $request->rating]);
                }
            }

            return response()->json(['msg' => 'Album rating updated']);
        } catch (\Exception $e) {
            return response()->json(['msg' => $e->getMessage()], 500);
        }
    }

    public function updateAlbumReview(Request $request) {
        try {
            $user = $request->user();

            if ($user == null) {
                return response()->json(['msg' => 'Not logged in'], 401);
            }

            foreach ($user->albums as $album) {
                if ($album->id == $request->albumId) {
                    $album->pivot->update(['review' => $request->review]);
                }
            }

            return response()->json(['msg' => 'Album review updated']);
        } catch (\Exception $e) {
            return response()->json(['msg' => $e->getMessage()], 500);
        }
    }

    public function deletePlayedAlbum(Request $request) {
        try {
            $user = $request->user();
            $albumsLengthStart = $user->albums->count();
            $albumsLengthFinish = $albumsLengthStart;

            if ($user == null) {
                return response()->json(['msg' => 'Not logged in'], 401);
            }

            foreach ($user->albums as $album) {
                if ($album->id == $request->albumId) {
                    if ($album->pivot->type == "played") {
                        $album->pivot->delete();
                    } else {
                        return response()->json(['msg' => 'Album isn\'t on played list'], 400);
                    }
                }
            }

            if ($albumsLengthStart > $albumsLengthFinish) {
                return response()->json(['msg' => 'Album removed from played list']);
            } else {
                return response()->json(['msg' => 'That album wasn\'t on played list']);
            }
        } catch (\Exception $e) {
            return response()->json(['msg' => $e->getMessage()], 500);
        }
    }

    public function deleteListenListAlbum(Request $request) {
        try {
            $user = $request->user();
            $albumsLengthStart = $user->albums->count();
            $albumsLengthFinish = $albumsLengthStart;

            if ($user == null) {
                return response()->json(['msg' => 'Not logged in'], 401);
            }

            foreach ($user->albums as $album) {
                if ($album->id == $request->albumId) {
                    if ($album->pivot->type == "listenlist") {
                        $album->pivot->delete();
                        $albumsLengthFinish--;
                    } else {
                        return response()->json(['msg' => 'Album isn\'t on listen list'], 400);
                    }
                }
            }

            if ($albumsLengthStart > $albumsLengthFinish) {
                return response()->json(['msg' => 'Album removed from listen list']);
            } else {
                return response()->json(['msg' => 'That album wasn\'t on listen list']);
            }
        } catch (\Exception $e) {
            return response()->json(['msg' => $e->getMessage()], 500);
        }
    }
}
