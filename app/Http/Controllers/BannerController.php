<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        if (!$user->hasRole("owner")) {
            if ($request->wantsJson()) {
                return response()->json(["message" => "Unauthorized"], 403);
            }
            abort(403);
        }

        $search = $request->get("search");
        $query = Banner::where("owner_id", $user->id);

        if ($search) {
            $query->where("title", "like", "%" . $search . "%");
        }

        $banners = $query->orderBy("order")->paginate(10);

        if ($request->wantsJson()) {
            return response()->json($banners);
        }

        return view("banner.index", compact("banners", "search"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $user = auth()->user();
        if (!$user->hasRole("owner")) {
            if ($request->wantsJson()) {
                return response()->json(["message" => "Unauthorized"], 403);
            }
            abort(403);
        }

        if ($request->wantsJson()) {
            return response()->json(["message" => "Use POST to create"]);
        }

        return view("banner.create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = auth()->user();
        if (!$user->hasRole("owner")) {
            if ($request->wantsJson()) {
                return response()->json(["message" => "Unauthorized"], 403);
            }
            abort(403);
        }

        $request->validate([
            "title" => "required|string|max:255",
            "description" => "nullable|string",
            "image" => "nullable|image|mimes:jpeg,png,jpg,gif|max:2048",
            "order" => "nullable|integer|min:1",
        ]);

        $imagePath = null;
        if ($request->hasFile("image")) {
            $imagePath = $request->file("image")->store("banners", "public");
        }

        $maxOrder = Banner::where("owner_id", $user->id)->max("order") ?? 0;
        $banner = Banner::create([
            "title" => $request->title,
            "description" => $request->description,
            "image" => $imagePath,
            "owner_id" => $user->id,
            "order" => $request->order ?? $maxOrder + 1,
        ]);

        if ($request->wantsJson()) {
            return response()->json($banner, 201);
        }

        return redirect()->route("admin/banner.index")->with("success", "Banner created successfully");
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id)
    {
        $user = auth()->user();
        if (!$user->hasRole("owner")) {
            if ($request->wantsJson()) {
                return response()->json(["message" => "Unauthorized"], 403);
            }
            abort(403);
        }

        $banner = Banner::where("owner_id", $user->id)->findOrFail($id);

        if ($request->wantsJson()) {
            return response()->json($banner);
        }

        return view("banner.show", compact("banner"));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, string $id)
    {
        $user = auth()->user();
        if (!$user->hasRole("owner")) {
            if ($request->wantsJson()) {
                return response()->json(["message" => "Unauthorized"], 403);
            }
            abort(403);
        }

        $banner = Banner::where("owner_id", $user->id)->findOrFail($id);

        if ($request->wantsJson()) {
            return response()->json(["message" => "Use PUT to update"]);
        }

        return view("banner.edit", compact("banner"));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = auth()->user();
        $banner = Banner::findOrFail($id);

        if ($banner->owner_id !== $user->id || !$user->hasRole("owner")) {
            if ($request->wantsJson()) {
                return response()->json(["message" => "Unauthorized"], 403);
            }
            abort(403);
        }

        $request->validate([
            "title" => "required|string|max:255",
            "description" => "nullable|string",
            "image" => "nullable|image|mimes:jpeg,png,jpg,gif|max:2048",
            "order" => "nullable|integer|min:1",
        ]);

        $imagePath = $banner->image;
        if ($request->hasFile("image")) {
            // Delete old image if exists
            if ($banner->image) {
                Storage::disk("public")->delete($banner->image);
            }
            $imagePath = $request->file("image")->store("banners", "public");
        }

        $banner->update([
            "title" => $request->title,
            "description" => $request->description,
            "image" => $imagePath,
            "order" => $request->order ?? $banner->order,
        ]);

        if ($request->wantsJson()) {
            return response()->json($banner);
        }

        return redirect()->route("admin/banner.index")->with("success", "Banner updated successfully");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        $user = auth()->user();
        $banner = Banner::findOrFail($id);

        if ($banner->owner_id !== $user->id || !$user->hasRole("owner")) {
            if ($request->wantsJson()) {
                return response()->json(["message" => "Unauthorized"], 403);
            }
            abort(403);
        }

        // Delete image if exists
        if ($banner->image) {
            Storage::disk("public")->delete($banner->image);
        }

        $banner->delete();

        if ($request->wantsJson()) {
            return response()->json(["message" => "Banner deleted"]);
        }

        return redirect()->route("admin/banner.index")->with("success", "Banner deleted successfully");
    }

    public function order(Request $request, Banner $banner, $direction)
    {
        $user = auth()->user();
        if (!$user->hasRole("owner") || $banner->owner_id !== $user->id) {
            abort(403);
        }

        if ($direction === "up") {
            $previous = Banner::where("owner_id", $user->id)->where("order", "<", $banner->order)->orderBy("order", "desc")->first();
            if ($previous) {
                $temp = $banner->order;
                $banner->order = $previous->order;
                $previous->order = $temp;
                $banner->save();
                $previous->save();
            }
        } elseif ($direction === "down") {
            $next = Banner::where("owner_id", $user->id)->where("order", ">", $banner->order)->orderBy("order", "asc")->first();
            if ($next) {
                $temp = $banner->order;
                $banner->order = $next->order;
                $next->order = $temp;
                $banner->save();
                $next->save();
            }
        }

        return redirect()->route("admin/banner.index")->with("success", "Urutan banner berhasil diubah");
    }

    public function publicIndex(Request $request)
    {
        $banners = Banner::orderBy("order")
            ->get()
            ->map(function ($banner) {
                // Convert stored image path to full URL for public API consumers
                if ($banner->image) {
                    $banner->image = asset("storage/" . $banner->image);
                } else {
                    $banner->image = null;
                }
                return $banner;
            });

        return response()->json($banners);
    }

    /**
     * Public show - return single banner by id (public)
     */
    public function publicShow(Request $request, $id)
    {
        $banner = Banner::find($id);

        if (!$banner) {
            return response()->json(["message" => "Banner not found"], 404);
        }

        // Convert stored image path to full URL for public API consumers
        if ($banner->image) {
            $banner->image = asset("storage/" . $banner->image);
        } else {
            $banner->image = null;
        }

        return response()->json($banner);
    }
}
