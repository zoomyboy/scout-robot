<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Zoomyboy\Fileupload\Image as UploadImage;
use Intervention\Image\Facades\Image;
use Storage;

class FileController extends Controller
{
	public function store(Request $request) {
		if($request->file('files')->isValid()) {
			$file = $request->file('files')->store('logo');
			$image = UploadImage::create(['filename' => $file]);
			$image->save();

			return response()->json($image->toArray(), 200);
		} else {
			return response()->json(['error' => 'File is invalid'], 400);
		}
	}

	public function display($path) {
		if (Storage::exists($path)) {
			return Image::make(Storage::get($path))->response();
		} else {
			throw new \NotFoundException();
		}
	}

	public function show(UploadImage $image) {
		if (Storage::exists($image->filename)) {
			return Image::make(Storage::get($image->filename))->response();
		} else {
			throw new \NotFoundException();
		}
	}

	public function destroy(UploadImage $file) {
		$file->delete();
		return response()->json([]);
	}

}
