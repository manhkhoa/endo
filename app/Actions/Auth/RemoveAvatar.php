<?php

namespace App\Actions\Auth;

use Illuminate\Http\Request;

class RemoveAvatar
{
    public function execute(Request $request)
    {
        $user = \Auth::user();

        $avatar = $user->getMeta('avatar');
        $avatar = str_replace('/storage/', '', $avatar);

        if (\Storage::disk('public')->exists($avatar)) {
            \Storage::disk('public')->delete($avatar);
        }

        $meta = $user->meta;
        $meta['avatar'] = null;
        $user->meta = $meta;
        $user->save();
    }
}
