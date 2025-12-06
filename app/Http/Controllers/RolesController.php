<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;

class RolesController extends Controller
{
    
    public function index()
    {
        return Role::all();
    }

   
    public function create()
    {
        //
    }

    
    public function store(Request $request)
    {
        return Role::create($request->all());
    }

    
    public function show($id)
    {
        //
    }

    
    public function edit($id)
    {
        //
    }

   
    public function update(Request $request, $id)
    {
        $roles = Role::findOrFail($id);
        $roles->nombre = $request->nombre;
        $roles->update();

        return $roles;
    }

    
    public function destroy($id)
    {
        $cliente = Role::findOrFail($id);
        $cliente->delete();
    }
}
