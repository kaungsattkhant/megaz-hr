<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Repositories\Journal\JournalRepositoryInterface;
use Illuminate\Http\Request;

class JournalAPIController extends Controller
{
    //
    protected $journalRepo;

    public function __construct(JournalRepositoryInterface $journalRepo)
    {
        $this->journalRepo = $journalRepo;
    }

    public function listAllJournals(Request $request)
    {
        $this->journalRepo->listAllData($request);
    }

    public function createJournal(Request $request)
    {
        $this->journalRepo->createData($request);
    }
}
