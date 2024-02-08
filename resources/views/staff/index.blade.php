@extends('layouts.main')

@section('page_title', 'staff')
@section('staffs', 'active-link')
@section('content')

<div class="flex justify-between mb-3">
    <div class=" flex">
        <label for="search" class="search-input">
            <input type="text" class="input-search" placeholder="Search">
            <i class="fal fa-search"></i>
        </label>
    </div>
    <div class="flex justify-end flex-col">
        <button type="button" class="add-btn ">
            Add New
        </button>

    </div>
</div>
<div class="block rounded-xl">
    <div class="overflow-x-auto">
        <!-- <div class="inline-block min-w-full py-2 sm:px-6 lg:px-8"> -->
        <div class="overflow-hidden ">
            <table class="min-w-full primary-table rounded-xl text-center text-sm font-light ">
                <thead class="border-b font-medium ">
                    <tr>
                        <th scope="col" class=" px-6 py-4 ">
                            #
                        </th>
                        <th scope="col" class=" px-6 py-4 ">
                            Name
                        </th>
                        <th scope="col" class=" px-6 py-4 ">
                            Phone Number
                        </th>

                        <th scope="col" class=" px-6 py-4 ">
                            Address
                        </th>
                        <th scope="col" class=" px-6 py-4 ">
                            Department
                        </th>
                        <th scope="col" class="px-6 py-4">

                        </th>
                    </tr>
                </thead>
                <tbody>

                    <!-- looping start -->
                    <tr class="bg-white rounded-lg overflow-hidden shadow-lg">
                        <td class=" px-6 py-4 font-medium ">
                            1
                        </td>
                        <td class="whitespace-nowrap px-6 py-4 ">
                            Mg Mg
                        </td>
                        <td class="whitespace-nowrap px-6 py-4 ">
                            09960327201
                        </td>
                        <td class=" px-6 py-4 ">
                            Lorem ipsum dolor sit amet consectetur adipisicing elit. Nobis voluptatem incidunt
                            repellendus
                        </td>
                        <td class="whitespace-nowrap px-6 py-4 ">
                            Kitchen
                        </td>
                        <td class="whitespace-nowrap px-6 py-4">
                            <button id="edit-btn" class="pr-1">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </td>
                    </tr>
                    <tr class="">
                        <td class=" py-2 "></td>
                    </tr>
                    <!-- looping end -->

                    <tr class="bg-white rounded-lg overflow-hidden shadow-lg">
                        <td class=" px-6 py-4 font-medium ">
                            1
                        </td>
                        <td class="whitespace-nowrap px-6 py-4 ">
                            Mg Mg
                        </td>
                        <td class="whitespace-nowrap px-6 py-4 ">
                            09960327201
                        </td>
                        <td class=" px-6 py-4 ">
                            Lorem ipsum dolor sit amet consectetur adipisicing elit. Nobis voluptatem incidunt
                            repellendus
                        </td>
                        <td class="whitespace-nowrap px-6 py-4 ">
                            Kitchen
                        </td>
                        <td class="whitespace-nowrap px-6 py-4">
                            <button id="edit-btn" class="pr-1">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </td>
                    </tr>
                    <tr class="">
                        <td class=" py-2 ">

                        </td>

                    </tr>
                    <tr class="bg-white rounded-lg overflow-hidden shadow-lg">
                        <td class=" px-6 py-4 font-medium ">
                            1
                        </td>
                        <td class="whitespace-nowrap px-6 py-4 ">
                            Mg Mg
                        </td>
                        <td class="whitespace-nowrap px-6 py-4 ">
                            09960327201
                        </td>
                        <td class=" px-6 py-4 ">
                            Lorem ipsum dolor sit amet consectetur adipisicing elit. Nobis voluptatem incidunt
                            repellendus
                        </td>
                        <td class="whitespace-nowrap px-6 py-4 ">
                            Kitchen
                        </td>
                        <td class="whitespace-nowrap px-6 py-4">
                            <button id="edit-btn" class="pr-1">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </td>
                    </tr>
                    <tr class="">
                        <td class=" py-2 ">

                        </td>

                    </tr>

                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection