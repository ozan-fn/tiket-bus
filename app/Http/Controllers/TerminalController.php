<?php

namespace App\Http\Controllers;

use App\Models\Terminal;
use App\Models\TerminalPhoto;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Spatie\QueryBuilder\QueryBuilder;

class TerminalController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->input("search");
        $dateFrom = $request->input("date_from");
        $dateTo = $request->input("date_to");

        $terminals = QueryBuilder::for(Terminal::with("photos"))
            ->where(function ($q) use ($search) {
                if ($search) {
                    $q->where("nama_terminal", "like", "%{$search}%")
                        ->orWhere("nama_kota", "like", "%{$search}%")
                        ->orWhere("alamat", "like", "%{$search}%");
                }
            })
            ->when($dateFrom, function ($query) use ($dateFrom) {
                return $query->whereDate("created_at", ">=", $dateFrom);
            })
            ->when($dateTo, function ($query) use ($dateTo) {
                return $query->whereDate("created_at", "<=", $dateTo);
            })
            ->allowedSorts(["nama_terminal", "nama_kota", "created_at"])
            ->defaultSort("-created_at")
            ->paginate(10)
            ->withQueryString();

        $sort = $request->input("sort", "-created_at");
        $order = strpos($sort, "-") === 0 ? "desc" : "asc";
        $sortField = ltrim($sort, "-");

        return view("terminal.index", compact("terminals", "search", "sort", "order", "sortField", "dateFrom", "dateTo"));
    }

    public function create(): View
    {
        return view("terminal.create");
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            "nama_terminal" => "required|string|max:255|unique:terminal",
            "nama_kota" => "required|string|max:255",
            "alamat" => "nullable|string",
            "foto" => "array",
            "foto.*" => "image|mimes:jpeg,png,jpg,gif|max:2048",
        ]);

        $terminal = Terminal::create($request->only(["nama_terminal", "nama_kota", "alamat"]));

        if ($request->hasFile("foto")) {
            foreach ($request->file("foto") as $file) {
                $path = $file->store("terminal_foto", "public");
                TerminalPhoto::create([
                    "terminal_id" => $terminal->id,
                    "path" => $path,
                ]);
            }
        }

        return redirect()->route("admin/terminal.index")->with("success", "Terminal berhasil ditambahkan");
    }

    public function show(Terminal $terminal): View
    {
        $terminal->load("photos");
        return view("terminal.show", compact("terminal"));
    }

    public function edit(Terminal $terminal): View
    {
        $terminal->load("photos");
        return view("terminal.edit", compact("terminal"));
    }

    public function update(Request $request, Terminal $terminal): RedirectResponse
    {
        $request->validate([
            "nama_terminal" => "required|string|max:255|unique:terminal,nama_terminal," . $terminal->id,
            "nama_kota" => "required|string|max:255",
            "alamat" => "nullable|string",
            "foto" => "array",
            "foto.*" => "image|mimes:jpeg,png,jpg,gif|max:2048",
        ]);

        $terminal->update($request->only(["nama_terminal", "nama_kota", "alamat"]));

        if ($request->hasFile("foto")) {
            // Upload foto baru (foto lama tetap ada)
            foreach ($request->file("foto") as $file) {
                $path = $file->store("terminal_foto", "public");
                TerminalPhoto::create([
                    "terminal_id" => $terminal->id,
                    "path" => $path,
                ]);
            }
        }

        return redirect()->route("admin/terminal.index")->with("success", "Terminal berhasil diperbarui");
    }

    public function destroy(Terminal $terminal): RedirectResponse
    {
        // Hapus foto
        foreach ($terminal->photos as $photo) {
            Storage::disk("public")->delete($photo->path);
            $photo->delete();
        }

        $terminal->delete();

        return redirect()->route("admin/terminal.index")->with("success", "Terminal berhasil dihapus");
    }

    public function destroyPhoto(TerminalPhoto $terminalPhoto)
    {
        try {
            // Hapus file jika ada
            if ($terminalPhoto->path && Storage::disk("public")->exists($terminalPhoto->path)) {
                Storage::disk("public")->delete($terminalPhoto->path);
            }

            // Hapus dari database
            $terminalPhoto->delete();

            return response()->json([
                "success" => true,
                "message" => "Foto berhasil dihapus",
            ]);
        } catch (\Exception $e) {
            return response()->json(
                [
                    "success" => false,
                    "message" => "Gagal menghapus foto: " . $e->getMessage(),
                ],
                500,
            );
        }
    }
}
