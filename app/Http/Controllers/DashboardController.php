<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $hari_ini = Carbon::today();
        $bulan_ini = Carbon::now()->month;

        // Hitung total jumlah transaksi (hitung item, bukan nominal)
        $transaksi_hari_ini = Transaksi::whereDate('created_at', $hari_ini)->count();
        $transaksi_bulan_ini = Transaksi::whereMonth('created_at', $bulan_ini)->count();

        // Hitung total nilai penjualan (nominal)
        $nilai_hari_ini = Transaksi::whereDate('created_at', $hari_ini)->sum('total');
        $nilai_bulan_ini = Transaksi::whereMonth('created_at', $bulan_ini)->sum('total');

        // Chart penjualan 7 hari terakhir
        $chart = Transaksi::selectRaw('DATE(created_at) as tanggal, SUM(total) as total')
            ->where('created_at', '>=', Carbon::now()->subDays(7))
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy(DB::raw('DATE(created_at)'))
            ->get();

        $chart_labels = $chart->pluck('tanggal')->map(fn($d) => Carbon::parse($d)->format('d M'));
        $chart_values = $chart->pluck('total');

        // Transaksi terbaru
        $transaksi_terbaru = Transaksi::with('kasir')->latest()->take(5)->get();

        return view('pages.dashboard', compact(
            'transaksi_hari_ini',
            'transaksi_bulan_ini',
            'nilai_hari_ini',
            'nilai_bulan_ini',
            'chart_labels',
            'chart_values',
            'transaksi_terbaru'
        ));
    }
}
