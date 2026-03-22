<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

/**
 * Controlador de autenticación.
 *
 * Gestiona el registro, inicio de sesión, cierre de sesión
 * y el perfil del usuario incluyendo el cambio de contraseña.
 * La autenticación se realiza por nombre de usuario, no por email.
 */
class AuthController extends Controller
{
    /**
     * Muestra el formulario de inicio de sesión.
     *
     * @return \Illuminate\View\View
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * Procesa el inicio de sesión.
     *
     * Valida las credenciales y redirige al panel admin si es
     * administrador, al resumen del configurador si hay una
     * configuración en progreso, o a la página de inicio.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('username', 'password');
        $remember    = $request->boolean('remember');

        if (!Auth::attempt($credentials, $remember)) {
            return back()
                ->withInput($request->only('username'))
                ->withErrors(['username' => 'Usuario o contraseña incorrectos.']);
        }

        $request->session()->regenerate();

        $destino = Auth::user()->isAdmin() ? route('admin.dashboard') : route('home');

        if ($request->session()->has('configurador')) {
            $destino = route('configurador.resumen');
        }

        return redirect()->intended($destino)
            ->with('success', '¡Bienvenido, ' . Auth::user()->username . '!');
    }

    /**
     * Muestra el formulario de registro.
     *
     * @return \Illuminate\View\View
     */
    public function showRegister()
    {
        return view('auth.register');
    }

    /**
     * Procesa el registro de un nuevo usuario.
     *
     * El nombre de usuario solo puede contener letras, números
     * y guiones bajos, con un mínimo de 3 caracteres.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function register(Request $request)
    {
        $request->validate([
            'username' => [
                'required', 'string', 'min:3', 'max:30',
                'unique:users,username',
                'regex:/^[a-zA-Z0-9_]+$/',
            ],
            'password' => ['required', 'confirmed', Password::min(8)],
        ], [
            'username.regex'  => 'El nombre de usuario solo puede contener letras, números y guiones bajos.',
            'username.unique' => 'Ese nombre de usuario ya está en uso.',
        ]);

        $user = User::create([
            'username' => $request->username,
            'password' => Hash::make($request->password),
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('home')
            ->with('success', '¡Cuenta creada! Bienvenido, ' . $user->username . '.');
    }

    /**
     * Cierra la sesión del usuario actual.
     *
     * Invalida la sesión y regenera el token CSRF.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')
            ->with('success', 'Has cerrado sesión correctamente.');
    }

    /**
     * Muestra la página de perfil del usuario.
     *
     * @return \Illuminate\View\View
     */
    public function showPerfil()
    {
        return view('auth.perfil');
    }

    /**
     * Procesa el cambio de contraseña del usuario.
     *
     * Verifica que la contraseña actual es correcta antes
     * de actualizar a la nueva contraseña cifrada con bcrypt.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function cambiarPassword(Request $request)
    {
        $request->validate([
            'password_actual' => 'required|string',
            'password'        => ['required', 'confirmed', Password::min(8)],
        ], [
            'password_actual.required' => 'La contraseña actual es obligatoria.',
            'password.confirmed'       => 'Las contraseñas no coinciden.',
        ]);

        if (!Hash::check($request->password_actual, Auth::user()->password)) {
            return back()->withErrors(['password_actual' => 'La contraseña actual no es correcta.']);
        }

        Auth::user()->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Contraseña actualizada correctamente.');
    }
}