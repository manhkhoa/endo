<?php

namespace App\Actions\Auth;

use Illuminate\Http\Request;

class UploadAvatar
{
    public function execute(Request $request)
    {
        $prefix = '';

        request()->validate([
            'image' => 'required|image',
        ]);

        $user = \Auth::user();

        $avatar = $user->getMeta('avatar');
        $avatar = str_replace('/storage/', '', $avatar);

        if ($avatar && \Storage::disk('public')->exists($avatar)) {
            \Storage::disk('public')->delete($avatar);
        }

        $image = \Storage::disk('public')->putFile($prefix.'avatar', request()->file('image'));

        $meta = $user->meta;
        $meta['avatar'] = '/storage/'.$image;
        $user->meta = $meta;
        $user->save();
    }
}
