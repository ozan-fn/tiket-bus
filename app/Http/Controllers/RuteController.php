<?php

namespace App\Http\Controllers;

use App\Models\Rute;
use App\Models\Terminal;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Spatie\QueryBuilder\QueryBuilder;

class RuteController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->input("search");
        $dateFrom = $request->input("date_from");
        $dateTo = $request->input("date_to");

        $rutes = QueryBuilder::for(Rute::with("asalTerminal", "tujuanTerminal"))
            ->where(function ($q) use ($search) {
                if ($search) {
                    $q->whereHas("asalTerminal", function ($q2) use ($search) {
                        $q2->where("nama_terminal", "like", "%{$search}%")->orWhere("nama_kota", "like", "%{$search}%");
                    })->orWhereHas("tujuanTerminal", function ($q2) use ($search) {
                        $q2->where("nama_terminal", "like", "%{$search}%")->orWhere("nama_kota", "like", "%{$search}%");
                    });
                }
            })
            ->when($dateFrom, function ($query) use ($dateFrom) {
                return $query->whereDate("created_at", ">=", $dateFrom);
            })
            ->when($dateTo, function ($query) use ($dateTo) {
                return $query->whereDate("created_at", "<=", $dateTo);
            })
            ->allowedSorts(["asal_terminal_id", "tujuan_terminal_id", "created_at"])
            ->defaultSort("-created_at")
            ->paginate(10)
            ->withQueryString();

        $sort = $request->input("sort", "-created_at");
        $order = strpos($sort, "-") === 0 ? "desc" : "asc";
        $sortField = ltrim($sort, "-");

        return view("rute.index", compact("rutes", "search", "sort", "order", "sortField", "dateFrom", "dateTo"));
    }

    public function create(): View
    {
        $terminals = Terminal::all();
        return view("rute.create", compact("terminals"));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            "asal_terminal_id" => "required|exists:terminal,id",
            "tujuan_terminal_id" => "required|exists:terminal,id|different:asal_terminal_id",
        ]);

        Rute::create($request->only(["asal_terminal_id", "tujuan_terminal_id"]));

        return redirect()->route("admin/rute.index")->with("success", "Rute berhasil ditambahkan");
    }

    public function show(Rute $rute): View
    {
        $rute->load("asalTerminal", "tujuanTerminal");
        return view("rute.show", compact("rute"));
    }

    public function edit(Rute $rute): View
    {
        $terminals = Terminal::all();
        return view("rute.edit", compact("rute", "terminals"));
    }

    public function update(Request $request, Rute $rute): RedirectResponse
    {
        $request->validate([
            "asal_terminal_id" => "required|exists:terminal,id",
            "tujuan_terminal_id" => "required|exists:terminal,id|different:asal_terminal_id",
        ]);

        $rute->update($request->only(["asal_terminal_id", "tujuan_terminal_id"]));

        return redirect()->route("admin/rute.index")->with("success", "Rute berhasil diperbarui");
    }

    public function destroy(Rute $rute): RedirectResponse
    {
        $rute->delete();

        return redirect()->route("admin/rute.index")->with("success", "Rute berhasil dihapus");
    }
}
