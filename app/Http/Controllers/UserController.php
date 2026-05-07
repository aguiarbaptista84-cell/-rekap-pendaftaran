<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::orderBy('role')->orderBy('munisipiu')->orderBy('name')->get();
        return view('users.index', compact('users'));
    }

    public function create()
    {
        $munisipiuList = User::$munisipiuList;
        $roleLabels    = User::$roleLabels;
        return view('users.create', compact('munisipiuList', 'roleLabels'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'      => 'required|string|max:100',
            'email'     => 'required|email|unique:users,email',
            'password'  => 'required|string|min:6|confirmed',
            'role'      => 'required|in:super_admin,diretor,user',
            'munisipiu' => 'nullable|string|max:50',
            'aktif'     => 'boolean',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['aktif']    = $request->boolean('aktif', true);

        if ($validated['role'] !== 'user') {
            $validated['munisipiu'] = null;
        }

        User::create($validated);

        return redirect()->route('users.index')
            ->with('sukses', 'Utilizadór ' . $validated['name'] . ' kria ho susesu.');
    }

    public function edit(User $user)
    {
        $munisipiuList = User::$munisipiuList;
        $roleLabels    = User::$roleLabels;
        return view('users.edit', compact('user', 'munisipiuList', 'roleLabels'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name'      => 'required|string|max:100',
            'email'     => 'required|email|unique:users,email,' . $user->id,
            'password'  => 'nullable|string|min:6|confirmed',
            'role'      => 'required|in:super_admin,diretor,user',
            'munisipiu' => 'nullable|string|max:50',
            'aktif'     => 'boolean',
        ]);

        if (empty($validated['password'])) {
            unset($validated['password']);
        } else {
            $validated['password'] = Hash::make($validated['password']);
        }

        $validated['aktif'] = $request->boolean('aktif', false);

        if ($validated['role'] !== 'user') {
            $validated['munisipiu'] = null;
        }

        $user->update($validated);

        return redirect()->route('users.index')
            ->with('sukses', 'Utilizadór ' . $user->name . ' atualiza ho susesu.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'La bele hamoos konta rasik.');
        }
        $user->delete();
        return redirect()->route('users.index')
            ->with('sukses', 'Utilizadór hamoos ho susesu.');
    }

    public function toggleAktif(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'La bele desativu konta rasik.');
        }
        $user->update(['aktif' => !$user->aktif]);
        $status = $user->aktif ? 'ativu' : 'la ativu';
        return back()->with('sukses', 'Konta ' . $user->name . ' agora ' . $status . '.');
    }
}
