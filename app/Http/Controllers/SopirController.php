<?php

namespace App\Http\Controllers;

use App\Models\Sopir;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class SopirController extends Controller
{
    public function index(): View
    {
        $sopir = Sopir::with('user')->paginate(10);
        return view('sopir.index', compact('sopir'));
    }

    public function create(): View
    {
        return view('sopir.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'user_id' => 'required|exists:users,id|unique:sopir,user_id',
            'nik' => 'required|string|max:255|unique:sopir',
            'nomor_sim' => 'required|string|max:255|unique:sopir',
            'alamat' => 'nullable|string',
            'telepon' => 'nullable|string|max:255',
            'tanggal_lahir' => 'required|date',
            'status' => 'required|in:aktif,tidak_aktif',
        ]);

        Sopir::create($request->all());

        return redirect()->route('admin/sopir.index')->with('success', 'Sopir berhasil ditambahkan');
    }

    public function show(Sopir $sopir): View
    {
        $sopir->load('user');
        return view('sopir.show', compact('sopir'));
    }

    public function edit(Sopir $sopir): View
    {
        return view('sopir.edit', compact('sopir'));
    }

    public function update(Request $request, Sopir $sopir): RedirectResponse
    {
        $request->validate([
            'user_id' => 'required|exists:users,id|unique:sopir,user_id,' . $sopir->id,
            'nik' => 'required|string|max:255|unique:sopir,nik,' . $sopir->id,
            'nomor_sim' => 'required|string|max:255|unique:sopir,nomor_sim,' . $sopir->id,
            'alamat' => 'nullable|string',
            'telepon' => 'nullable|string|max:255',
            'tanggal_lahir' => 'required|date',
            'status' => 'required|in:aktif,tidak_aktif',
        ]);

        $sopir->update($request->all());

        return redirect()->route('admin/sopir.index')->with('success', 'Sopir berhasil diperbarui');
    }

    public function destroy(Sopir $sopir): RedirectResponse
    {
        $sopir->delete();

        return redirect()->route('admin/sopir.index')->with('success', 'Sopir berhasil dihapus');
    }

    public function searchUsers(Request $request)
    {
        $query = $request->get('q', '');
        $users = User::where(function ($q) use ($query) {
            if ($query) {
                $q->where('name', 'like', "%{$query}%")
                    ->orWhere('email', 'like', "%{$query}%");
            }
        })
            ->limit($query ? 20 : 10) // Limit lebih sedikit jika default
            ->get(['id', 'name', 'email']);

        return response()->json([
            'results' => $users->map(function ($user) {
                return [
                    'value' => $user->id,
                    'text' => $user->name . ' (' . $user->email . ')'
                ];
            })
        ]);
    }
}
