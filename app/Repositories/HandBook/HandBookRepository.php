<?php

namespace App\Repositories\HandBook;

use App\Models\HandBook;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\HandBookResource;


class HandBookRepository implements HandBookInterface
{
  public function getHandBookList($request)
  {
    $handBooks = HandBook::query()->orderBy('id', 'desc');

    return (isset(request()->per_page) || isset(request()->page))
      ? $handBooks->paginate(config('common.list_count'))
      : $handBooks->get();
  }

  public function getHandBooks(){
    $handBooks = HandBook::all();
    return HandBookResource::collection($handBooks);
  }

  public function updateOrCreateHandBook($data)
  {
    DB::beginTransaction();
    try {
      if (isset($data['image'])) {
        $imageData = $data['image'];
        $extension = $imageData->getClientOriginalExtension();
        $hashedName = md5(uniqid() . microtime()) . '.' . $extension;
        $data['image_path'] = $imageData->storeAs('handBookImgs', $hashedName, 'public');
        $data['image_url'] = Storage::url($data['image_path']);
      }
      $data['id'] = $data['id'] ?? null;
      $handBook =  HandBook::updateOrCreate(
        ['id' => $data['id']],
        $data
      );
      DB::commit();
      return $handBook;
    } catch (\Exception $e) {
      DB::rollback();
      ResponseMessage($e->getMessage(), 402);
      throw $e;
    }
  }

  public function getHandBookById($id)
  {
    $handBook = HandBook::find($id);
    if (!$handBook) {
      ResponseMessage('Handbook not found', 404);
    }
    ResponseData($handBook);
  }

  public function deleteHandBook($id)
  {
    DB::beginTransaction();
    try {
      $handBook = HandBook::find($id);
      if (!$handBook) {
        ResponseMessage('Handbook not found', 404);
      }
      if ($handBook->image_path) {
        Storage::disk('public')->delete($handBook->image_path);
      }
      $handBook->delete();
      DB::commit();
      ResponseMessage('Handbook deleted successfully');
    } catch (\Exception $e) {
      DB::rollback();
      ResponseMessage($e->getMessage(), 402);
      throw $e;
    }
  }
}
