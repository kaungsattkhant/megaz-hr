<?php

namespace App\Repositories\Contact;

use Illuminate\Http\Request;

interface ContactRepositoryInterface
{
  public function contactList(Request $request);

  public function getContactById($id);

  public function updateOrCreate($request);

  public function delete($id);
}
