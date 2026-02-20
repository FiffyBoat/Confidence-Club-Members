<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\CollectorAssignment;
use Illuminate\Support\Facades\Hash;

class CollectorController extends Controller
{
    public function index()
    {
        $collectors = User::where('role','collector')->latest()->paginate(15);
        return view('collectors.index', compact('collectors'));
    }

    public function create()
    {
        return view('collectors.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'=>'required|string|max:150',
            'email'=>'required|email|unique:users,email',
            'phone'=>'required|string|max:20',
            'password'=>'required|string|min:6'
        ]);

        User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'phone'=>$request->phone,
            'role'=>'collector',
            'password'=>Hash::make($request->password)
        ]);

        return redirect()->route('collectors.index')->with('success','Collector Added');
    }

    public function edit(User $collector)
    {
        return view('collectors.edit', compact('collector'));
    }

    public function update(Request $request, User $collector)
    {
        $request->validate([
            'name'=>'required|string|max:150',
            'email'=>'required|email|unique:users,email,'.$collector->id,
            'phone'=>'required|string|max:20',
            'password'=>'nullable|string|min:6'
        ]);

        $data = $request->only(['name','email','phone']);
        if($request->password){
            $data['password'] = Hash::make($request->password);
        }

        $collector->update($data);
        return redirect()->route('collectors.index')->with('success','Collector Updated');
    }

    public function destroy(User $collector)
    {
        $collector->delete();
        return redirect()->route('collectors.index')->with('success','Collector Deleted');
    }
}
