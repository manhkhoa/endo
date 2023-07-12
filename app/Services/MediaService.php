<?php

namespace App\Services;

use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class MediaService
{
    public function store(Request $request)
    {
        $prefix = '';

        $this->validateToken($request);
        $this->validateFileSize($request);
        $this->validateFileExtension($request);
        $this->validateFileLimit($request);
        $this->validateFileDuplication($request);

        return Media::forceCreate([
            'token' => $request->token,
            'name' => $request->file('file')->store($prefix.'media/'.$request->module),
            'collection' => 'default',
            'file_name' => $request->file('file')->getClientOriginalName(),
            'user_id' => auth()->id(),
            'model_type' => Str::studly($request->module),
            'meta' => [
                'mime' => $request->file('file')->getClientMimeType(),
                'size' => $request->file('file')->getSize(),
                'is_temp_deleted' => false,
                'hash' => $request->hash,
            ],
        ]);
    }

    public function delete(Request $request, Media $media)
    {
        if (! auth()->user()->hasRole('admin') && $media->user_id != auth()->id()) {
            throw ValidationException::withMessages(['message' => trans('user.errors.permission_denied')]);
        }

        if (! $media->status) {
            if (\Storage::exists($media->name)) {
                \Storage::delete($media->name);
            }
            $media->delete();
        }

        if ($media->status) {
            $meta = $media->meta;
            $meta['is_temp_deleted'] = true;
            $meta['delete_hash'] = $request->query('hash');
            $media->meta = $meta;
            $media->save();
        }
    }

    private function validateToken(Request $request)
    {
        $existingToken = Media::query()
            ->whereToken($request->token)
            ->where('model_type', '!=', $request->module)
            ->count();

        if ($existingToken) {
            throw ValidationException::withMessages(['message' => trans('general.errors.invalid_action')]);
        }
    }

    private function validateFileSize(Request $request)
    {
        $maxFileSize = 5;
        $size = $request->file('file')->getSize();

        if ($size > $maxFileSize * 1024 * 1024) {
            throw ValidationException::withMessages(['message' => trans('general.errors.file_too_large')]);
        }
    }

    private function validateFileExtension(Request $request)
    {
        $allowedFileExtensions = ['jpg', 'jpeg', 'png', 'txt', 'doc', 'docx', 'xls', 'xlsx'];
        $extension = $request->file('file')->getClientOriginalExtension();

        if (! in_array($extension, $allowedFileExtensions)) {
            throw ValidationException::withMessages(['message' => trans('general.errors.file_not_supported')]);
        }
    }

    private function validateFileLimit(Request $request)
    {
        $maxNoOfFiles = 5;

        $existingMedia = Media::query()
            ->whereToken($request->token)
            ->whereModelType($request->module)
            // ->where('meta->hash', $request->hash) // Commented to fix file limit check whatever be the hash
            ->where('meta->is_temp_deleted', false)
            ->count();

        if ($existingMedia >= $maxNoOfFiles) {
            throw ValidationException::withMessages(['message' => trans('general.errors.file_upload_limit_exceeded')]);
        }
    }

    private function validateFileDuplication(Request $request)
    {
        $duplicateMedia = Media::query()
            ->whereToken($request->token)
            ->whereModelType($request->module)
            ->where(function ($q) use ($request) {
                $q->whereStatus(1)
                ->orWhere(function ($q) use ($request) {
                    $q->whereStatus(0)->where('meta->hash', $request->hash);
                });
            })
            ->whereFileName($request->file('file')->getClientOriginalName())
            ->count();

        if ($duplicateMedia) {
            throw ValidationException::withMessages(['message' => trans('general.errors.file_with_same_name_uploaded')]);
        }
    }
}
