<?php

namespace App\Repositories\SignedContract;

use App\Models\SignedContract;

class SignedContractRepository implements SignedContractRepositoryInterface
{
    public function list($request)
    {
        $query = SignedContract::with(['contract', 'staff', 'confirmedBy', 'cancelledBy'])->orderBy('id', 'DESC');

        if ($request->has('search')) {
            $searchTerm = $request->input('search');
            $query->where(function ($query) use ($searchTerm) {
                $query->where('signed_ducument', 'LIKE', '%' . $searchTerm . '%');
            });
        }

        if ($request->has('per_page') || $request->has('page')) {
            return $query->paginate(config('common.list_count'));
        }

        return $query->get();
    }

    public function updateOrCreate($request)
    {
        $data = $request->all();

        if (!isset($data['id'])) {
            $data['id'] = null;
        }

        return SignedContract::updateOrCreate(
            ['id' => $data['id']],
            $data
        );
    }

    public function detail($signedContract)
    {
        $signedContract->load(['contract', 'staff', 'confirmedBy', 'cancelledBy']);
        return $signedContract;
    }
}
