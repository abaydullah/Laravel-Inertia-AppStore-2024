<?php

namespace App\Http\Controllers\Admin;

use Abaydullah\ApkParser\Parser;
use App\Http\Controllers\Controller;
use App\Models\App;
use App\Models\Version;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Pion\Laravel\ChunkUpload\Exceptions\UploadMissingFileException;
use Pion\Laravel\ChunkUpload\Handler\ContentRangeUploadHandler;
use Pion\Laravel\ChunkUpload\Receiver\FileReceiver;

class VersionController extends Controller
{
    public function store($app, Request $request)
    {

        $receiver = new FileReceiver(UploadedFile::fake()->createWithContent('file', $request->getContent()), $request, ContentRangeUploadHandler::class);

        if ($receiver->isUploaded() === false) {
            throw new UploadMissingFileException();
        }
        $save = $receiver->receive();

        if ($save->isFinished()) {
            $this->storeFile($app, $save->getFile(), $request);

        }
        $save->handler();


    }

    public function update($app, Request $request, Version $version)
    {

        $receiver = new FileReceiver(UploadedFile::fake()->createWithContent('file', $request->getContent()), $request, ContentRangeUploadHandler::class);

        if ($receiver->isUploaded() === false) {
            throw new UploadMissingFileException();
        }
        $save = $receiver->receive();

        if ($save->isFinished()) {
            $this->updateFile($app,$version, $save->getFile(), $request);

        }
        $save->handler();

        return back()->with('success', 'Version Uploaded Successfully');
    }

    public function destroy($app, Version $version)
    {
        if(Storage::disk('public')->exists($version->file)) {
            Storage::disk('public')->delete($version->file);
        }
        $version->delete();
        return back()->with('success', 'Version Deleted Successfully');
    }

    public function updateVersion($app, Request $request, Version $version)
    {
        $version = $version->update([

            'version' => $request->version,
            'version_code' => $request->version_code,
            'screen_dpi' => $request->screen_dpi,
            'sha1' => sha1_file($request->sha1),
            'sha256' => hash('sha256', $request->sha256),
            'md5' => hash('md5', $request->md5),
            'minimum_android' => $request->minimum_android,
            'minimum_android_level' => $request->minimum_android_level,
            'target_android' => $request->target_android,
            'target_android_level' => $request->target_android_level,
            'architecture' => $request->architecture,
            'signature' => $request->signature,
            'date' => now()->format('Y-m-d H:i:s'),
            'user_id' => auth()->id(),
        ]);
        return back()->with('success', 'Version Updated Successfully');
    }
    protected function storeFile($app, UploadedFile $file, Request $request)
    {
        $app = App::find($app);
        $apk = new Parser($file);
        $manifest = $apk->getManifest();
        if ($manifest->getVersionName()) {
            $ext = 'apk';

            $size = $file->getSize();
            $manifest = $apk->getManifest();
            $permissions = $manifest->getPermissions();
            $package_name = $manifest->getPackageName();
            $version_name = $manifest->getVersionName();
            $version_code = $manifest->getVersionCode();
            $minimum_android =$manifest->getMinSdk()->platform;
            $minimum_android_level = $manifest->getMinSdkLevel();
            $target_android = $manifest->getTargetSdk()->platform;
            $target_android_level = $manifest->getTargetSdkLevel();
            $file_name = $version_name. '-' . $version_code . '.' . $ext;

            $version = $app->versions()->create([
                'file' => $file->storeAs('apps/' .$app->app_id, $file_name, 'public'),
                'version' => $version_name,
                'version_code' => $version_code,
                'screen_dpi' => 'nodpi',
                'sha1' => sha1_file($file),
                'sha256' => hash('sha256', $file_name),
                'md5' => hash('md5', $file_name),
                'minimum_android' => $minimum_android,
                'minimum_android_level' => $minimum_android_level,
                'target_android' => $target_android,
                'target_android_level' => $target_android_level,
                'architecture' => '',
                'signature' => '',
                'file_size' => $size,
                'file_type' => $ext,
                'date' => now()->format('Y-m-d H:i:s'),
                'user_id' => auth()->id(),
            ]);
        } else{
            throw new UploadMissingFileException();

        }




    }
    protected function updateFile($app,Version $version, UploadedFile $file, Request $request): void
    {
        $app = App::find($app);
//        $version = Version::find($version);
        $apk = new Parser($file);
        $manifest = $apk->getManifest();
        if ($manifest->getVersionName()) {
            $ext = 'apk';
            $apk = new Parser($file);
            $size = $file->getSize();
            $manifest = $apk->getManifest();
//            $permissions = $manifest->getPermissions();
//            $package_name = $manifest->getPackageName();
            $version_name = $manifest->getVersionName();
            $version_code = $manifest->getVersionCode();
            $minimum_android =$manifest->getMinSdk()->platform;
            $minimum_android_level = $manifest->getMinSdkLevel();
            $target_android = $manifest->getTargetSdk()->platform;
            $target_android_level = $manifest->getTargetSdkLevel();
            $file_name = $version_name. '-' . $version_code . '.' . $ext;

            $version = $version->update([
                'file' => $file->storeAs('apps/' .$app->app_id, $file_name, 'public'),
                'version' => $version_name,
                'version_code' => $version_code,
                'screen_dpi' => 'nodpi',
                'sha1' => sha1_file($file),
                'sha256' => hash('sha256', $file_name),
                'md5' => hash('md5', $file_name),
                'minimum_android' => $minimum_android,
                'minimum_android_level' => $minimum_android_level,
                'target_android' => $target_android,
                'target_android_level' => $target_android_level,
                'architecture' => '',
                'signature' => '',
                'file_size' => $size,
                'file_type' => $ext,
                'date' => now()->format('Y-m-d H:i:s'),
                'user_id' => auth()->id(),
            ]);
        } else{
            throw new UploadMissingFileException();

        }




    }
}
