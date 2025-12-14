<?php

namespace App\Data;

class SidebarMenuData
{
    public static function getMenus(): array
    {
        return [
            "dashboard" => [
                "label" => "Dashboard",
                "route" => "dashboard",
                "icon" => "layout-dashboard",
                "roles" => ["owner", "agent"],
            ],
            "data_setup" => [
                "label" => "Data Setup",
                "type" => "section",
                "roles" => ["owner", "agent"],
            ],
            "bus" => [
                "label" => "Bus",
                "route" => "admin/bus.index",
                "icon" => "bus",
                "roles" => ["owner"],
            ],
            "fasilitas" => [
                "label" => "Fasilitas",
                "route" => "admin/fasilitas.index",
                "icon" => "sparkles",
                "roles" => ["owner"],
            ],
            "kelas_bus" => [
                "label" => "Kelas Bus",
                "route" => "admin/kelas-bus.index",
                "icon" => "layers",
                "roles" => ["owner"],
            ],
            "sopir" => [
                "label" => "Sopir",
                "route" => "admin/sopir.index",
                "icon" => "user-round",
                "roles" => ["owner"],
            ],
            "rute_jadwal" => [
                "label" => "Rute & Jadwal",
                "type" => "section",
                "roles" => ["owner", "agent"],
            ],
            "terminal" => [
                "label" => "Terminal",
                "route" => "admin/terminal.index",
                "icon" => "building-2",
                "roles" => ["owner"],
            ],
            "rute" => [
                "label" => "Rute",
                "route" => "admin/rute.index",
                "icon" => "route",
                "roles" => ["owner"],
            ],
            "jadwal" => [
                "label" => "Jadwal",
                "route" => "admin/jadwal.index",
                "icon" => "calendar",
                "roles" => ["owner", "agent"],
            ],
            "pricing" => [
                "label" => "Pricing",
                "type" => "section",
                "roles" => ["owner", "agent"],
            ],
            "harga_tiket" => [
                "label" => "Harga Tiket",
                "route" => "admin/jadwal-kelas-bus.index",
                "icon" => "tag",
                "roles" => ["owner", "agent"],
            ],
            "transaksi" => [
                "label" => "Transaksi",
                "type" => "section",
                "roles" => ["owner", "agent"],
            ],
            "history_pemesanan" => [
                "label" => "History Pemesanan",
                "route" => "admin/history-pemesanan",
                "icon" => "clipboard-list",
                "roles" => ["owner", "agent"],
            ],
            "pembayaran_manual" => [
                "label" => "Pembayaran Manual",
                "route" => "admin.pembayaran-manual",
                "icon" => "wallet",
                "roles" => ["owner", "agent"],
            ],
            "laporan" => [
                "label" => "Laporan",
                "type" => "section",
                "roles" => ["owner"],
            ],
            "analytics" => [
                "label" => "Analytics",
                "route" => "admin/laporan.index",
                "icon" => "bar-chart-3",
                "roles" => ["owner"],
            ],
            "laporan_tiket" => [
                "label" => "Laporan Tiket",
                "route" => "admin/laporan.tiket",
                "icon" => "ticket",
                "roles" => ["owner"],
            ],
            "laporan_pendapatan" => [
                "label" => "Laporan Pendapatan",
                "route" => "admin/laporan.pendapatan",
                "icon" => "dollar-sign",
                "roles" => ["owner"],
            ],
            "laporan_penumpang" => [
                "label" => "Laporan Penumpang",
                "route" => "admin/laporan.penumpang",
                "icon" => "users",
                "roles" => ["owner"],
            ],
        ];
    }

    public static function getFilteredMenus(string $role = "agent"): array
    {
        $menus = self::getMenus();

        return array_filter($menus, function ($menu) use ($role) {
            return in_array($role, $menu["roles"]);
        });
    }
}
