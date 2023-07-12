<?php

namespace App\Services\Employee;

use App\Models\Employee\Employee;
use Illuminate\Http\Request;

class PhotoService
{
    public function upload(Request $request, Employee $employee)
    {
        $prefix = '';

        request()->validate([
            'image' => 'required|image',
        ]);

        $contact = $employee->contact;

        $photo = $contact->photo;
        $photo = str_replace('/storage/', '', $photo);

        if ($photo && \Storage::disk('public')->exists($photo)) {
            \Storage::disk('public')->delete($photo);
        }

        $image = \Storage::disk('public')->putFile($prefix.'photo', request()->file('image'));

        $contact->photo = '/storage/'.$image;
        $contact->save();
    }

    public function remove(Request $request, Employee $employee)
    {
        $contact = $employee->contact;

        $photo = $contact->photo;
        $photo = str_replace('/storage/', '', $photo);

        if (\Storage::disk('public')->exists($photo)) {
            \Storage::disk('public')->delete($photo);
        }

        $contact->photo = null;
        $contact->save();
    }
}
