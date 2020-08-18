<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ContactsTest extends TestCase
{

    protected $user;

    protected function auth()
    {
        $this->user = User::first();

        Auth::login($this->user);
    }

    /**
     * An authenticated user can access the contacts page
     */
    public function testAuthenticatedUserCanAccessContactsPage()
    {
        $this->auth();

        $response = $this->get('/contacts');

        $response->assertStatus(200);
    }


    /**
     * An authenticated user can access import contacts page
     *
     * @return void
     */
    public function testAuthUserCanAccessImportContactsPage()
    {
        $this->auth();

        $response = $this->get('/contacts/import');

        $response->assertStatus(200);
        $response->assertSee('Import Contacts');
    }

    /**
     * A guest can't access import contacts page
     *
     * @return void
     */
    public function testGuestDoesNotHaveAccessToImportContactsPage()
    {
        $response = $this->get('/contacts/import');

        $response->assertStatus(302);
    }

    /**
     * A basic unit test example.
     *
     * @return void
     * @throws \Throwable
     */
    public function testUpload()
    {
        $this->auth();

        Storage::fake('uploads');

        $header = 'name,phone,email,sticky_phone_number_id,company';
        $row1 = 'Carlos Chavez,3312551255,carlos@example.com,234,Carlos LLC';
        $row2 = 'Montse Garcia,3331063106,montse@example.com,298,Montse Inc.';

        $content = implode("\n", [$header, $row1, $row2]);

        $request = [
            'file' =>
                UploadedFile::
                fake()->
                createWithContent(
                    'contacts.csv',
                    $content
                )
        ];

        $response = $this->json('POST', '/contacts/upload', $request);

        $response->assertStatus(200);

        $this->assertArrayHasKey('csvColumns', $response->decodeResponseJson());
    }


}
