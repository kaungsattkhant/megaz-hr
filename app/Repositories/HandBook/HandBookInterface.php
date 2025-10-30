<?php

namespace App\Repositories\HandBook;

interface HandBookInterface
{
  public function getHandBookList($request);

  public function updateOrCreateHandBook($data);

  public function getHandBookById($id);

  public function deleteHandBook($id);

  public function getHandBooks();
}
