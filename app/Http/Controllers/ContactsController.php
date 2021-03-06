<?php

namespace App\Http\Controllers;

use App\Contact;
use App\CustomAttribute;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class ContactsController extends Controller
{
    /**
     * Return the contacts view with the current team's contacts
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $contacts = Auth::user()->team->contacts;
        return view('contacts.index', compact('contacts'));
    }

    /**
     * Return the import view
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function import()
    {
        return view('contacts.import');
    }

    /**
     * Get columns from CSV and return them so they can be mapped
     * @param Request $request
     * @return mixed
     */
    public function upload(Request $request)
    {
        $csv = array_map('str_getcsv', file($request->file));

        $contact = new Contact();

        $result['csvColumns'] = $csv[0];
        $result['availableColumns'] = array_merge(
            $contact->getFillable(),
            CustomAttribute::pluck('key')->unique()->toArray());
        $result['map'] = array_intersect($csv[0], $contact->getFillable());

        return $result;
    }

    /**
     * Store the contacts and related custom attributes to database
     * @param Request $request
     * @return int
     */
    public function store(Request $request)
    {
        $rows = array_map('str_getcsv', file($request->file));
        $mappedFields = (array) json_decode(request('fields'));
        $columns = $rows[0];
        array_shift($rows);
        foreach ($rows as $row) {
            $currentColumns = $columns;
            $contact = new Contact();
            $contact->name = $row[array_search('name', $mappedFields)];
            Arr::pull($currentColumns, array_search('name', $mappedFields));
            $contact->phone = $row[array_search('phone', $mappedFields)];
            Arr::pull($currentColumns, array_search('phone', $mappedFields));
            $contact->email = $row[array_search('email', $mappedFields)];
            Arr::pull($currentColumns, array_search('email', $mappedFields));
            $contact->sticky_phone_number_id = $row[array_search('sticky_phone_number_id', $mappedFields)];
            Arr::pull($currentColumns, array_search('sticky_phone_number_id', $mappedFields));
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
