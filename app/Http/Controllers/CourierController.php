<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\Courier;

class CourierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($request)
    {
        $query = DB::table('couriers');

        if($request->filled('search')){
            $search = $request->search;
            $query->where('name', 'like', '%' . $search . '%');
        }

        if($request->sort_by === 'created_at'){
            $query->orderBy('created_at', 'desc');
        }else{
            $query->orderBy('name', 'asc');
        }

        $courierData = $query->paginate(10);

        return view('courier.index', ['couriers' => $courierData]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validateInput = $request->validate([
            'nama' => 'required',
            'no_telp' => 'required|min:13',
            'password' => 'required|min:8'
        ]);
        $validateInput['password'] = Hash::make($request['password']);
        Courier::create($validateInput);
        return redirect()->route('courier.index')->with('success', 'Data kurir telah ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $courier = Courier::findOrFail($id);
        return view('courier.show', ['courier' => $courier]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validate = $request->validate([
            'nama' => 'required',
            'no_telp' => 'required|min:13',
        ]);
        $userCourier = Courier::findOrFail($id);
        $userCourier->update($validate);
        return redirect()->route('courier.index')->with('success', 'Data Kurir diperbaharui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Courier $courier)
    {
        $courier->delete();
        return redirect()->route('courier.index')->with('success', 'Data Kurir telah dihapus');
    }
}
