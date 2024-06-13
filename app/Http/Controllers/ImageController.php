<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Helpers\ImageService;
use App\Http\Requests\StoreImageRequest;
use App\Models\Image;
use App\Models\Room;
use App\Repositories\ImageRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log; // Import Log facade

class ImageController extends Controller
{
    private $imageRepository;

    public function __construct(ImageRepository $imageRepository)
    {
        $this->imageRepository = $imageRepository;
    }

    public function store(StoreImageRequest $request, Room $room)
    {
        $path = public_path('img/room/' . $room->number);
        $file = $request->file('image');

        $lastFileName = $this->imageRepository->uploadImage($path, $file);

        // Create image record in the database
        $image = Image::create([
            'room_id' => $room->id,
            'url' => $lastFileName,
        ]);

        // Log event for image upload
        Log::info('Image uploaded', [
            'room_id' => $room->id,
            'image_url' => $lastFileName,
            'user_id' => auth()->id(), // Assuming you have authentication set up
        ]);

        return redirect()->route('room.show', ['room' => $room->id])->with('success', 'Image upload successfully!');
    }

    public function destroy(Image $image)
    {
        $path = public_path('img/room/' . $image->room->number . '/' . $image->url);

        // Delete image file if exists
        if (file_exists($path)) {
            unlink($path);
        }

        // Delete image record from database
        $image->delete();

        // Log event for image deletion
        Log::info('Image deleted', [
            'image_id' => $image->id,
            'image_url' => $image->url,
            'room_id' => $image->room_id,
            'user_id' => auth()->id(), // Assuming you have authentication set up
        ]);

        return redirect()->back()->with('success', 'Image ' . $image->url . ' has been deleted!');
    }
}
