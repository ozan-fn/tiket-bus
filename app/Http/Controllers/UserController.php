<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use App\Models\User;
use App\Models\Sopir;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index(Request $request)
    {
        $query = User::with("roles");
        $roles = Role::all();

        if ($search = $request->input("search")) {
            $query->where(function ($q) use ($search) {
                $q->where("name", "like", "%{$search}%")->orWhere("email", "like", "%{$search}%");
            });
        }

        if ($role = $request->input("role")) {
            $query->whereHas("roles", function ($q) use ($role) {
                $q->where("name", $role);
            });
        }

        // Handle sorting
        $sort = $request->input("sort", "name");
        $direction = str_starts_with($sort, "-") ? "desc" : "asc";
        $sortField = ltrim($sort, "-");

        if ($sortField === "role") {
            // Special handling for role sorting
            $query->join("model_has_roles", "users.id", "=", "model_has_roles.model_id")->join("roles", "model_has_roles.role_id", "=", "roles.id")->orderBy("roles.name", $direction)->select("users.*");
        } else {
            $query->orderBy($sortField, $direction);
        }

        $users = $query->paginate(10);

        return view("user.index", compact("users", "roles"));
    }

    public function create()
    {
        $roles = Role::all();
        return view("user.create", compact("roles"));
    }

    public function store(Request $request)
    {
        $request->validate([
            "name" => "required|string|max:255",
            "email" => "required|email|max:255|unique:users",
            "password" => "required|string|min:8|confirmed",
            "role" => "required|exists:roles,name",
            "nik" => "required_if:role,driver|string",
            "nomor_sim" => "required_if:role,driver|string",
            "alamat" => "nullable|string",
            "telepon" => "nullable|string",
            "tanggal_lahir" => "required_if:role,driver|date",
            "status" => "required_if:role,driver|in:aktif,tidak_aktif",
        ]);

        DB::transaction(function () use ($request) {
            $user = User::create([
                "name" => $request->name,
                "email" => $request->email,
                "password" => bcrypt($request->password),
            ]);

            $user->assignRole($request->role);

            if ($request->role === "driver") {
                Sopir::create([
                    "user_id" => $user->id,
                    "nik" => $request->nik,
                    "nomor_sim" => $request->nomor_sim,
                    "alamat" => $request->alamat,
                    "telepon" => $request->telepon,
                    "tanggal_lahir" => $request->tanggal_lahir,
                    "status" => $request->status,
                ]);
            }
        });

        return redirect()->route("admin/user.index")->with("success", "User berhasil ditambahkan.");
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        $sopir = $user->sopirs()->first(); // Assuming one sopir per user
        return view("user.edit", compact("user", "roles", "sopir"));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            "name" => "required|string|max:255",
            "email" => "required|email|max:255|unique:users,email," . $user->id,
            "password" => "nullable|string|min:8|confirmed",
            "role" => "required|exists:roles,name",
            "nik" => "required_if:role,driver|string",
            "nomor_sim" => "required_if:role,driver|string",
            "alamat" => "nullable|string",
            "telepon" => "nullable|string",
            "tanggal_lahir" => "required_if:role,driver|date",
            "status" => "required_if:role,driver|in:aktif,tidak_aktif",
        ]);

        DB::transaction(function () use ($request, $user) {
            $user->update([
                "name" => $request->name,
                "email" => $request->email,
                "password" => $request->password ? bcrypt($request->password) : $user->password,
            ]);

            $user->syncRoles([$request->role]);

            if ($request->role === "driver") {
                Sopir::updateOrCreate(
                    ["user_id" => $user->id],
                    [
                        "nik" => $request->nik,
                        "nomor_sim" => $request->nomor_sim,
                        "alamat" => $request->alamat,
                        "telepon" => $request->telepon,
                        "tanggal_lahir" => $request->tanggal_lahir,
                        "status" => $request->status,
                    ],
                );
            }
        });

        return redirect()->route("admin/user.index")->with("success", "User berhasil diperbarui.");
    }

    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route("admin/user.index")->with("success", "User berhasil dihapus.");
    }
}
