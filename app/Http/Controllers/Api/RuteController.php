<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Rute;
use Illuminate\Http\Request;

class RuteController extends Controller
{
    public function index(Request $request)
    {
        $query = Rute::query();

        // Conditional eager loading based on 'include' query parameter
        if ($request->has("include")) {
            $includes = explode(",", $request->include);
            // Filter to only allowed relations for security
            $allowed = ["asalTerminal", "tujuanTerminal"];
            $validIncludes = array_intersect($includes, $allowed);
            if (!empty($validIncludes)) {
                $query->with($validIncludes);
            }
        }

        // Filter berdasarkan asal terminal
        if ($request->has("asal")) {
            $query->whereHas("asalTerminal", function ($q) use ($request) {
                $q->where("nama_terminal", "like", "%" . $request->asal . "%")->orWhere("nama_kota", "like", "%" . $request->asal . "%");
            });
        }

        // Filter berdasarkan tujuan terminal
        if ($request->has("tujuan")) {
            $query->whereHas("tujuanTerminal", function ($q) use ($request) {
                $q->where("nama_terminal", "like", "%" . $request->tujuan . "%")->orWhere("nama_kota", "like", "%" . $request->tujuan . "%");
            });
        }

        $perPage = $request->get("per_page", 10);
        $rute = $query->paginate($perPage);
        return response()->json($rute);
    }

    public function store(Request $request)
    {
        $request->validate([
            "asal_terminal_id" => "required|exists:terminal,id",
            "tujuan_terminal_id" => "required|exists:terminal,id",
        ]);
        $rute = Rute::create($request->all());
        return response()->json($rute, 201);
    }

    public function show(Request $request, $id)
    {
        $query = Rute::query();

        // Conditional eager loading based on 'include' query parameter
        if ($request->has("include")) {
            $includes = explode(",", $request->include);
            // Filter to only allowed relations for security
            $allowed = ["asalTerminal", "tujuanTerminal"];
            $validIncludes = array_intersect($includes, $allowed);
            if (!empty($validIncludes)) {
                $query->with($validIncludes);
            }
        }

        $rute = $query->findOrFail($id);
        return response()->json($rute);
    }

    public function update(Request $request, $id)
    {
        $rute = Rute::findOrFail($id);
        $rute->update($request->all());
        return response()->json($rute);
    }

    public function destroy($id)
    {
        $rute = Rute::findOrFail($id);
        $rute->delete();
        return response()->json(["message" => "Rute dihapus"]);
    }
}
