<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UploadController extends Controller
{
    /**
     * Upload foto profile user
     * POST /api/upload/profile
     */
    public function uploadProfile(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = auth()->user();

        // Hapus foto lama jika ada
        if ($user->photo && Storage::disk('public')->exists($user->photo)) {
            Storage::disk('public')->delete($user->photo);
        }

        // Upload foto baru
        $file = $request->file('photo');
        $filename = 'profile/' . Str::uuid() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('', $filename, 'public');

        // Update user
        $user->update(['photo' => $path]);

        return response()->json([
            'success' => true,
            'message' => 'Foto profile berhasil diupload',
            'data' => [
                'photo' => $path,
                'url' => Storage::url($path),
            ],
        ]);
    }

    /**
     * Upload bukti pembayaran
     * POST /api/upload/bukti-pembayaran
     */
    public function uploadBuktiPembayaran(Request $request)
    {
        $request->validate([
            'pembayaran_id' => 'required|exists:pembayaran,id',
            'bukti_pembayaran' => 'required|image|mimes:jpeg,png,jpg,pdf|max:5120',
        ]);

        $pembayaran = \App\Models\Pembayaran::where('id', $request->pembayaran_id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        // Hapus bukti lama jika ada
        if ($pembayaran->bukti_pembayaran && Storage::disk('public')->exists($pembayaran->bukti_pembayaran)) {
            Storage::disk('public')->delete($pembayaran->bukti_pembayaran);
        }

        // Upload bukti baru
        $file = $request->file('bukti_pembayaran');
        $filename = 'bukti-pembayaran/' . Str::uuid() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('', $filename, 'public');

        // Update pembayaran
        $pembayaran->update(['bukti_pembayaran' => $path]);

        return response()->json([
            'success' => true,
            'message' => 'Bukti pembayaran berhasil diupload',
            'data' => [
                'bukti_pembayaran' => $path,
                'url' => Storage::url($path),
            ],
        ]);
    }

    /**
     * Upload foto bus
     * POST /api/upload/bus-photo
     */
    public function uploadBusPhoto(Request $request)
    {
        $request->validate([
            'bus_id' => 'required|exists:bus,id',
            'photo' => 'required|image|mimes:jpeg,png,jpg|max:5120',
        ]);

        // Upload foto
        $file = $request->file('photo');
        $filename = 'bus/' . Str::uuid() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('', $filename, 'public');

        // Simpan ke database
        $busPhoto = \App\Models\BusPhoto::create([
            'bus_id' => $request->bus_id,
            'path' => $path,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Foto bus berhasil diupload',
            'data' => [
                'id' => $busPhoto->id,
                'path' => $path,
                'url' => Storage::url($path),
            ],
        ]);
    }

    /**
     * Hapus foto bus
     * DELETE /api/upload/bus-photo/{id}
     */
    public function deleteBusPhoto($id)
    {
        $busPhoto = \App\Models\BusPhoto::findOrFail($id);

        // Hapus file
        if (Storage::disk('public')->exists($busPhoto->path)) {
            Storage::disk('public')->delete($busPhoto->path);
        }

        // Hapus dari database
        $busPhoto->delete();

        return response()->json([
            'success' => true,
            'message' => 'Foto bus berhasil dihapus',
        ]);
    }

    /**
     * Upload foto terminal
     * POST /api/upload/terminal-photo
     */
    public function uploadTerminalPhoto(Request $request)
    {
        $request->validate([
            'terminal_id' => 'required|exists:terminal,id',
            'photo' => 'required|image|mimes:jpeg,png,jpg|max:5120',
        ]);

        // Upload foto
        $file = $request->file('photo');
        $filename = 'terminal/' . Str::uuid() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('', $filename, 'public');

        // Simpan ke database
        $terminalPhoto = \App\Models\TerminalPhoto::create([
            'terminal_id' => $request->terminal_id,
            'path' => $path,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Foto terminal berhasil diupload',
            'data' => [
                'id' => $terminalPhoto->id,
                'path' => $path,
                'url' => Storage::url($path),
            ],
        ]);
    }

    /**
     * Hapus foto terminal
     * DELETE /api/upload/terminal-photo/{id}
     */
    public function deleteTerminalPhoto($id)
    {
        $terminalPhoto = \App\Models\TerminalPhoto::findOrFail($id);

        // Hapus file
        if (Storage::disk('public')->exists($terminalPhoto->path)) {
            Storage::disk('public')->delete($terminalPhoto->path);
        }

        // Hapus dari database
        $terminalPhoto->delete();

        return response()->json([
            'success' => true,
            'message' => 'Foto terminal berhasil dihapus',
        ]);
    }
}
