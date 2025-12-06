<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{

    public function validarCredenciales(Request $request)
    {
        $alias = $request->alias;
        $password = $request->password;
    
        $user = User::where('email', $alias)
                    ->where('activo', 1)
                    ->first();
    
        if (!$user) {
            return response()->json(['error' => 'Usuario no encontrado'], 404);
        }
    
        // Verificamos si llega correctamente la contraseña y si hace match
        if (Hash::check($password, $user->password)) {
            return response()->json(['message' => 'Login exitoso', 'user' => $user]);
        } else {
            return response()->json([
                'error' => 'Credenciales inválidas',
                'input_password' => $password,
                'stored_password' => $user->password,
                'hash_check' => Hash::check($password, $user->password)
            ], 401);
        }
    }
    


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return User::all();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validar los datos de entrada (sin 'activo' ya que se asignará automáticamente)
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users,email',
            'password' => 'required|string',
            'role_id' => 'required|integer',
        ]);
    
        // Obtener los datos de la solicitud
        $data = $request->all();
    
        // Asignar el valor predeterminado de 'activo' a 1
        $data['activo'] = 1;
    
        // Hashear la contraseña antes de guardarla
        $data['password'] = Hash::make($request->password);
    
        // Crear y devolver el usuario
        $user = User::create($data);
    
        return response()->json($user, 201);  // Retornar el usuario creado con código 201
    }
    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
{
    $user = User::find($id);

    if (!$user) {
        return response()->json(['error' => 'Usuario no encontrado'], 404);
    }

    // Validar los datos de entrada
    $validator = Validator::make($request->all(), [
        'name' => 'required|string',
        'email' => 'required|string|email|unique:users,email,' . $id, // evitar duplicado excepto el actual
        'password' => 'nullable|string',
        'role_id' => 'required|integer',
    ]);

    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 422);
    }

    // Actualizar campos básicos
    $user->name = $request->name;
    $user->email = $request->email;
    $user->role_id = $request->role_id;

    // Actualizar contraseña solo si se envía una nueva
    if ($request->filled('password')) {
        $user->password = Hash::make($request->password);
    }

    // Forzar siempre el campo activo en 1
    $user->activo = 1;

    $user->save();

    return response()->json(['message' => 'Usuario actualizado correctamente', 'usuario' => $user], 200);
}

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $usuario = User::findOrFail($id);
        $usuario->delete();
    }

    public function obtenerRoles(){
        $roles = Role::all();
        return response()->json($roles);
    }
}
