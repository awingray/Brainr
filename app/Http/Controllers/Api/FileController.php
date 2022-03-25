<?php

namespace Brainr\Http\Controllers\Api;

use Brainr\Http\Controllers\Controller;
use Brainr\ProfileFile;
use Brainr\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    /**
     * FileController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    /**
     * Displays all files owned by the profile
     *
     * @param Profile $profile
     * @return mixed
     */
    public function index(Profile $profile)
    {
        return $profile->files;
    }

    public function store(Profile $profile, Request $request)
    {

        $validator = Validator::make($request->all(), [
            'files.*' => 'mimes:pdf,xlx,csv,png,jpg,jpeg,gif,doc,docx'
        ]);
        if($validator->fails()){
            return response([
                'errors' => $validator->errors(),
                'type' => 'danger'
            ], 500);
        }

        $response = [
            'status'=>0,
        ];
        if($request->files){
            $files = [];
            foreach ($request->file('files') as $file) {
                $path = $file->storeAs('public', "profile/".$profile->id."/".$file->getClientOriginalName());
                $files[] = ['path'=>$path,'profile_id'=>$profile->id,'filename'=>$file->getClientOriginalName(),'filesize'=> $this->formatBytes($file->getSize())];
            }

            if(count($files) > 0){
                ProfileFile::insert($files);
                $response = [
                    'status'=>0,
                    'files' => ProfileFile::where('profile_id',$profile->id)->orderBy('id','desc')->get()
                ];
            }

        }

        return response($response, 200);
    }

    public function update(Profile $profile, ProfileFile $file)
    {
        //
    }

    public function delete(Profile $profile, ProfileFile $file)
    {
        Storage::delete($file->path);
        $file->delete();
        return response(null, 204);
    }

    private function formatBytes($size, $precision = 2)
    {
        if ($size > 0) {
            $size = (int) $size;
            $base = log($size) / log(1024);
            $suffixes = array(' bytes', ' KB', ' MB', ' GB', ' TB');

            return round(pow(1024, $base - floor($base)), $precision) . $suffixes[floor($base)];
        } else {
            return $size;
        }
    }

}
