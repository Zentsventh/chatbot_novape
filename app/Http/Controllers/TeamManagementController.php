<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class TeamManagementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $tenantId = $request->user()->tenant_id;
        
        $team = User::where('tenant_id', $tenantId)
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('settings.team', compact('team'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Solo los administradores pueden crear nuevos trabajadores
        if ($request->user()->role !== 'tenant_admin' && $request->user()->role !== 'super_admin') {
            abort(403, 'No tienes permisos para crear usuarios.');
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'string', 'in:tenant_admin,tenant_supervisor,tenant_agent'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'tenant_id' => $request->user()->tenant_id,
            'role' => $request->role,
        ]);

        return redirect()->route('settings.team.index')
            ->with('success', 'Usuario creado correctamente. Ahora puede iniciar sesión.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, User $user)
    {
        // Solo los administradores pueden eliminar trabajadores
        if ($request->user()->role !== 'tenant_admin' && $request->user()->role !== 'super_admin') {
            abort(403, 'No tienes permisos para eliminar usuarios.');
        }
        // Verificar que el usuario pertenece al mismo tenant
        if ($user->tenant_id !== $request->user()->tenant_id) {
            abort(403);
        }

        // Evitar que el usuario se elimine a sí mismo
        if ($user->id === $request->user()->id) {
            return redirect()->back()->with('error', 'No puedes eliminarte a ti mismo.');
        }

        $user->delete();

        return redirect()->route('settings.team.index')
            ->with('success', 'Usuario eliminado correctamente.');
    }
}
