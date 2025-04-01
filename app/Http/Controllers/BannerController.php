<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Banner;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class BannerController extends Controller
{
    // Store multiple images
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'images' => 'required|array|max:5', // Max 5 images
            'images.*' => 'image|mimes:jpg,jpeg,png|max:3072', // Each max 3MB
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 422);
        }

        $user = Auth::user();
        $uploadedImages = [];

        foreach ($request->file('images') as $image) {
            $path = $image->store('banners', 'public');
            $banner = Banner::create([
                'user_id' => $user->id,
                'image_path' => $path,
            ]);
            $uploadedImages[] = $banner;
        }

        return response()->json([
            'message' => 'Banners uploaded successfully',
            'data' => $uploadedImages,
        ], 201);
    }

    // Fetch banners of logged-in user
    public function index()
    {
        $banners = Banner::where('user_id', Auth::id())->get();
        return response()->json(['data' => $banners], 200);
    }

    // Delete a banner
    public function destroy($id)
    {
        $banner = Banner::where('id', $id)->where('user_id', Auth::id())->first();

        if (!$banner) {
            return response()->json(['message' => 'Banner not found'], 404);
        }

        Storage::disk('public')->delete($banner->image_path);
        $banner->delete();

        return response()->json(['message' => 'Banner deleted successfully'], 200);
    }

    // Fetch banners by user ID
public function getBannersByUserId($userId)
{
    $banners = Banner::where('user_id', $userId)->get();

    if ($banners->isEmpty()) {
        return response()->json(['message' => 'No banners found for this user'], 404);
    }

    return response()->json([
        'message' => 'Banners retrieved successfully',
        'data' => $banners,
    ], 200);
}

}
