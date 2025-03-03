<?php

namespace App\Repositories\Package;

use App\Models\AccessoryPackage;
use App\Models\Menu;
use App\Models\MenuPackage;
use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PackageRepository implements PackageRepositoryInterface
{
    public function listAllData(Request $request)
    {
        // if(isset($request->perPage))
        $validateDate = $request->date ?? CurrentDate();
        $packages = Package::with(['menuPackages.menu'])->where('from_date', '<=', $validateDate)
            ->orderBy('created_at', 'desc')
            ->when($request->has('search'), function ($q) use ($request) {
                $q->where('name', 'LIKE', '%' . $request->search . '%')
                    ->orWhere('price', 'LIKE', '%' . $request->search . '%');
            })
            ->where('to_date', '>=', $validateDate)
            ->with([
                'menuPackages.menu.prices',
                'menuPackages.menu.menuServiceDiscounts' => function ($query) use ($validateDate) {
                    $query->where('from_date', '<=', $validateDate)
                        ->where('to_date', '>=', $validateDate)
                        ->first();
                }
            ])
            ->paginate(config('common.list_count'));

        ResponseData($packages);
    }

    public function detailPackage(int $id)
    {
        $package = Package::where('id', $id)->with('menuPackages.menu.prices')->first();
        ResponseData($package);
    }

    public function createData(array $data)
    {
        DB::beginTransaction();
        try {
            $data['created_by'] = UserData()->id;
            // $isValid = false;
            // if(isset($data['roomIds'])){
            //     $roomIds = json_decode($data['roomIds']);
            //     $isValid = $this->validatePackingDates($roomIds, $data['from_date'], $data['to_date']);
            // }
            // if ($isValid) {
            //     ResponseMessage('Package dates overlap with existing packages for the specified rooms.', 422);
            // }
            $imageData = $data['image'];
            $extension = $imageData->getClientOriginalExtension();
            $hashedName = md5(uniqid() . microtime()) . '.' . $extension;
            $data['image_path'] = $imageData->storeAs('images/package_images', $hashedName, 'public');
            $data['image_url'] = Storage::url($data['image_path']);

            $data['session'] = $data['pay_session'] + $data['free_session'];
            $data['package_discount'] = 0;
            $sessionPrice = $data['pay_session'] * $data['session_price'];
            $package = Package::create($data);
            $menuPrice = 0;
            $accessoryPrice=0;

            if (isset($data['menuIds'])) {
                $menuIds = json_decode($data['menuIds']);
                foreach ($menuIds as $menu) {
                    $foodMenu = Menu::find($menu->menu_id);
                    $menuPrice += $foodMenu->price->price * $menu->quantity;
                    $menu_package = MenuPackage::create([
                        'menu_id' => $menu->menu_id,
                        'quantity' => $menu->quantity,
                        'package_id' => $package->id
                    ]);
                }
            }
            if (isset($data['accessories'])) {
                $accessories = json_decode($data['accessories'], true); // Decode as an associative array

                if (empty($accessories)) {
                    // Accessories are empty or null
                    return response()->json(['message' => 'No accessories provided'], 400);
                }
                foreach ($accessories as $accessory) {
                    $foodMenu = Menu::find($accessory->accessory_id);
                    $accessoryPrice += $accessory->accessory_price->price * $accessory->quantity;
                    $accessoryPackage = AccessoryPackage::create([
                        'accessory_id' => $accessory->accessory_id,
                        'quantity' => $accessory->quantity,
                        'package_id' => $package->id
                    ]);
                }
            }
            $package_original_price = $menuPrice + $sessionPrice+ $accessoryPrice;
            if ($package_original_price > $data['price']) {
                $data['package_discount'] = $package_original_price - $data['price'];
                $package->package_discount = $data['package_discount'];
                $package->save();
            } else {
                ResponseMessage('Package original price  shoud be more than the package price', 422);
            }
            if(isset($data['room_ids']) && is_array($data['room_ids']){
                $roomIds=$data['room_ids'];
                $package->rooms()->sync($roomIds);
            //     $rooms = json_decode($data['roomIds']);
            //     foreach($rooms as $room)
            //     {
            //         $package->rooms()->attach($room);
            //     }
            }else{
                ResponseMessage('Room Ids must be array format', 422);
            }
            DB::commit();
            ResponseData($package);
        } catch (\Exception $e) {
            DB::rollBack();
            ResponseMessage($e->getMessage(), 500);
            throw $e;
        }
    }


    // public function validatePackingDates(array $roomIds, $fromDate, $toDate)
    // {
    //     foreach ($roomIds as $roomId) {
    //         $overlappingPacking = Package::whereHas('rooms', function ($query) use ($roomId) {
    //             $query->where('entities.id', $roomId);
    //         })
    //             ->where(function ($query) use ($fromDate, $toDate) {
    //                 $query->where('from_date', '<=', $toDate)
    //                     ->where('to_date', '>=', $fromDate);
    //             })->first();

    //         // dd($overlappingPacking);
    //         if ($overlappingPacking == null) {
    //             return false;
    //         }
    //     }

    //     return true;
    // }


    public function editData(int $id, array $data)
    {
        DB::beginTransaction();
        try {
            if (isset($data['image'])) {
                $imageData = $data['image'];
                $extension = $imageData->getClientOriginalExtension();
                $hashedName = md5(uniqid() . microtime()) . '.' . $extension;
                $data['image_path'] = $imageData->storeAs('images', $hashedName, 'public');
                $data['image_url'] = Storage::url($data['image_path']);
            }
            $sessionPrice = $data['pay_session'] * $data['session_price'];
            $package = Package::find($id);
            $package->update($data);
            $menuPrice = 0;

            if (isset($data['menuIds'])) {
                $menuIds = json_decode($data['menuIds']);
                MenuPackage::where('package_id', $package->id)->delete();
                foreach ($menuIds as $menu) {
                    $foodMenu = Menu::find($menu->menu_id);
                    $menuPrice += $foodMenu->price->price;
                    MenuPackage::create([
                        'menu_id' => $menu->menu_id,
                        'quantity' => $menu->quantity,
                        'package_id' => $package->id
                    ]);
                }
            }

            $package_original_price = $sessionPrice + $menuPrice;
            if ($package_original_price > $data['price']) {
                $data['package_discount'] = $package_original_price - $data['price'];
                $package->package_discount = $data['package_discount'];
                $package->save();
            } else {
                ResponseMessage('Invalid package', 422);
            }
            DB::commit();
            ResponseMessage($package);
        } catch (\Exception $e) {
            DB::rollBack();
            ResponseMessage($e->getMessage());
            throw $e;
        }
    }

    public function deleteData(int $id)
    {
        DB::beginTransaction();
        try {
            $package = Package::find($id);
            $package->delete();
            DB::commit();
            ResponseMessage('Package deleted');
        } catch (\Exception $e) {
            DB::rollBack();
            ResponseMessage($e->getMessage());
            throw $e;
        }
    }


    // user app

    public function listAllPackage(Request $request)
    {
        $validateDate = $request->date ?? CurrentDate();
        $packages = Package::with([
            'menuPackages.menu.prices',
            'menuPackages.menu.menuServiceDiscounts' => function ($query) use ($validateDate) {
                $query->where('from_date', '<=', $validateDate)
                    ->where('to_date', '>=', $validateDate);
            }
        ])
            ->paginate(config('common.list_count'));

        ResponseData($packages);
    }
}
