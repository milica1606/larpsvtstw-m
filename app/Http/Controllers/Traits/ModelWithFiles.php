<?php
namespace App\Http\Controllers\Traits;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

trait ModelWithFiles {
    // public $fileFields = ["fileField" => ["filePath" => "users", "fileDriver" => 'public', "fileRequiredForField" => true]];

    protected function uploadFiles(Request $request, $files = [], $existing = null /* an existing record */) {
        if (!count($files)) {
            $files = $this->fileFields;
        }
        $result = [];
        foreach($files as $fF => $fileopts) {
            if ($request->hasFile($fF)) {
                $file = $request->file($fF); //file from request for name="$fF"
                $ext = $file->getClientOriginalExtension();
                if (isset($fileopts['filePath'])) {
                    $path = $fileopts['filePath'];
                } else {
                    $path = '';
                }
                if (isset($fileopts['fileDriver'])) {
                    $disk = $fileopts['fileDriver'];
                }
                $public = false;
                if (isset($fileopts['public'])) {
                    $public = $fileopts['public'];
                } else if (isset($disk)) {
                    $public = (config("filesystems.disks.$disk.visibility") == 'public');
                }
                if ($file->isValid()) { // there were no problems uploading the file - it should be stored then
                    $name = Str::random(10);
                    if (!isset($disk)) { // filePath is the absolute path in this case!?
                        $url = Storage::putFileAs($path, $file, "$name.$ext");
                    } else {
                        if ($public) { // use disk driver - and filePath is then relative to driver folder
                            $url = config("filesystems.disks.$disk.url") . '/';
                        } else { // use root from disk driver
                            $url = config("filesystems.disks.$disk.root") . '/'; // $url = '' ?
                        }
                        $url .= $file->storeAs($path, "$name.$ext", $disk); // it could be also with Storage
                    }
                    if (!is_null($existing)) { // clean an old file
                        if (isset($disk) && $public) {
                            $img = \str_replace(config("filesystems.disks.$disk.url") . '/', '', $existing->{$fF});
                            if (Storage::disk($disk)->exists($img)) { // remove the old one
                                Storage::disk($disk)->delete($img);
                            }
                        } else {
                            $img = $existing->{$fF};
                            if (Storage::exists($img)) {
                                Storage::delete($img);
                            }
                        }
                    }
                    $result[$fF] =  $url;
                } else {
                    $result[$fF] = "File is not valid!";
                }
            } else {
                $result[$fF] = "There is no file!";
            }
        }
        return $result;
    }
}
