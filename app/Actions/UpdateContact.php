<?php

namespace App\Actions;

use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;

class UpdateContact
{
    public function execute(Request $request, Contact $contact)
    {
        if (Arr::get($request->email, 'email')) {
            $contact = Contact::query()
                ->where('id', '!=', $contact->id)
                ->whereEmail($request->email)->first();

            if ($contact) {
                throw ValidationException::withMessages(['message' => trans('validation.unique', ['attribute' => trans('contact.props.email')])]);
            }
        }

        $contact->first_name = $request->has('first_name') ? $request->first_name : $contact->first_name;
        $contact->middle_name = $request->has('middle_name') ? $request->middle_name : $contact->middle_name;
        $contact->third_name = $request->has('third_name') ? $request->third_name : $contact->third_name;
        $contact->last_name = $request->has('last_name') ? $request->last_name : $contact->last_name;
        $contact->gender = $request->has('gender') ? $request->gender : $contact->gender;
        $contact->birth_date = $request->has('birth_date') ? $request->birth_date : $contact->birth_date;
        $contact->unique_id_number1 = $request->has('unique_id_number1') ? $request->unique_id_number1 : $contact->unique_id_number1;
        $contact->unique_id_number2 = $request->has('unique_id_number2') ? $request->unique_id_number2 : $contact->unique_id_number2;
        $contact->unique_id_number3 = $request->has('unique_id_number3') ? $request->unique_id_number3 : $contact->unique_id_number3;
        $contact->birth_place = $request->has('birth_place') ? $request->birth_place : $contact->birth_place;
        $contact->nationality = $request->has('nationality') ? $request->nationality : $contact->nationality;
        $contact->mother_tongue = $request->has('mother_tongue') ? $request->mother_tongue : $contact->mother_tongue;
        $contact->contact_number = $request->has('contact_number') ? $request->contact_number : $contact->contact_number;
        $contact->email = $request->has('email') ? $request->email : $contact->email;

        $contact->alternate_records = [
            'contact_number' => $request->has('alternate_records.contact_number') ? $request->input('alternate_records.contact_number') : Arr::get($contact->alternate_records, 'contact_number'),
            'email' => $request->has('alternate_records.email') ? $request->input('alternate_records.email') : Arr::get($contact->alternate_records, 'email'),
        ];

        $contact->address = [
            'present' => $this->getAddress($request, $contact, 'present_address'),
            'permanent' => $this->getAddress($request, $contact, 'permanent_address'),
        ];

        $contact->save();
    }

    private function getAddress(Request $request, Contact $contact, string $type = 'present_address'): array
    {
        $address = [
            'address_line1' => $request->has($type.'.address_line1') ? $request->input($type.'.address_line1') : Arr::get($contact->$type, 'address_line1'),
            'address_line2' => $request->has($type.'.address_line2') ? $request->input($type.'.address_line2') : Arr::get($contact->$type, 'address_line2'),
            'city' => $request->has($type.'.city') ? $request->input($type.'.city') : Arr::get($contact->$type, 'city'),
            'state' => $request->has($type.'.state') ? $request->input($type.'.state') : Arr::get($contact->$type, 'state'),
            'zipcode' => $request->has($type.'.zipcode') ? $request->input($type.'.zipcode') : Arr::get($contact->$type, 'zipcode'),
            'country' => $request->has($type.'.country') ? $request->input($type.'.country') : Arr::get($contact->$type, 'country'),
        ];

        if ($type === 'permanent_address') {
            $address['same_as_present_address'] = $request->has($type.'.same_as_present_address') ? $request->boolean($type.'.same_as_present_address') : (bool) Arr::get($contact->$type, 'same_as_present_address');
        }

        return $address;
    }
}
