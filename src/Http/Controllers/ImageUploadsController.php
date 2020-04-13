<?php

namespace Wink\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use JD\Cloudder\Facades\Cloudder;

class ImageUploadsController
{
    /**
     * Upload a new image.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function upload()
    {
        $path = request()->image->store(config('wink.storage_path'), [
                'disk' => config('wink.storage_disk'),
                'visibility' => 'public',
            ]
        );

        $image_url = Storage::disk(config('wink.storage_disk'))->url($path);

        if( env('CLOUDINARY_UPLOAD') ) {
            Cloudder::upload(request()->image->path(), null);
            $image_url = Cloudder::show(Cloudder::getPublicId(), []);
        }

        return response()->json([
            'url' => $image_url
        ]);
    }
}
