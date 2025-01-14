<?php

namespace App\Repositories\Contact;

use App\Models\Contact;
use Illuminate\Http\Request;


class ContactRepository implements ContactRepositoryInterface
{
  public function contactList(Request $request)
  {
    return Contact::orderBy('id', 'desc')->get();
  }

  public function getContactById($id)
  {
    return Contact::find($id);
  }

  public function updateOrCreate($request)
  {
    return Contact::updateOrCreate(
      ['id' => $request->id],
      [
        'phone_number' => $request->phone_number
      ]
    );
  }

  public function delete($id)
  {
    $data =  Contact::find($id);
    $data->delete();
    return $data;
  }
}
