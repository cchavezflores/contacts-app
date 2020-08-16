<?php

namespace App\Http\Controllers;

use App\Contact;
use App\CustomAttribute;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class ContactsController extends Controller
{
    public function index()
    {
        $contacts = Auth::user()->team->contacts;
        return view('contacts.index', compact('contacts'));
    }

    public function import()
    {
        return view('contacts.import');
    }

    public function upload(Request $request)
    {
        $csv = array_map('str_getcsv', file($request->file));

        $contact = new Contact();

        $result['csvColumns'] = $csv[0];
        $result['availableColumns'] = array_merge($contact->getFillable(), CustomAttribute::pluck('key')->toArray());
        $result['map'] = array_intersect($csv[0], $contact->getFillable());

        return $result;
    }

    public function store(Request $request)
    {
        $rows = array_map('str_getcsv', file($request->file));
        $columns = $rows[0];
        array_shift($rows);

        foreach ($rows as $row) {
            $currentColumns = $columns;

            $contact = new Contact();
            $contact->name = $row[array_search('name', $currentColumns)];
            Arr::pull($currentColumns, array_search('name', $currentColumns));
            $contact->phone = $row[array_search('phone', $currentColumns)];
            Arr::pull($currentColumns, array_search('phone', $currentColumns));
            $contact->email = $row[array_search('email', $currentColumns)];
            Arr::pull($currentColumns, array_search('email', $currentColumns));
            $contact->sticky_phone_number_id = $row[array_search('sticky_phone_number_id', $currentColumns)];
            Arr::pull($currentColumns, array_search('sticky_phone_number_id', $currentColumns));
            $contact->team_id = Auth::user()->team->id;
            $contact->save();

            foreach ($currentColumns as $index => $column) {
                CustomAttribute::create([
                    'contact_id' => $contact->id,
                    'key' => $column,
                    'value' => $row[$index]
                ]);
            }
        }
        return count($rows);
    }
}
