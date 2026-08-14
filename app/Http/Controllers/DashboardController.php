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

        $userLevel = auth()->user()->level;
        $queryBase = Transaksi::query();
        if ($userLevel === 'staff' || $userLevel === 'kasir') {
            $queryBase->whereHas('kasir', function($q) use ($userLevel) {
                $q->where('level', $userLevel);
            });
        }

        // Hitung total jumlah transaksi (hitung item, bukan nominal)
        $transaksi_hari_ini = (clone $queryBase)->whereDate('created_at', $hari_ini)->count();
        $transaksi_bulan_ini = (clone $queryBase)->whereMonth('created_at', $bulan_ini)->count();

        // Hitung total nilai penjualan (nominal)
        $nilai_hari_ini = (clone $queryBase)->whereDate('created_at', $hari_ini)->sum('total');
        $nilai_bulan_ini = (clone $queryBase)->whereMonth('created_at', $bulan_ini)->sum('total');

        // Chart penjualan 7 hari terakhir
        $chart = (clone $queryBase)->selectRaw('DATE(created_at) as tanggal, SUM(total) as total')
            ->where('created_at', '>=', Carbon::now()->subDays(7))
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy(DB::raw('DATE(created_at)'))
            ->get();

        $chart_labels = $chart->pluck('tanggal')->map(fn($d) => Carbon::parse($d)->format('d M'));
        $chart_values = $chart->pluck('total');

        // Transaksi terbaru
        $transaksi_terbaru = (clone $queryBase)->with('kasir')->latest()->take(5)->get();

        $nilai_hari_ini_warkop = 0;
        $nilai_hari_ini_ranu = 0;
        $nilai_bulan_ini_warkop = 0;
        $nilai_bulan_ini_ranu = 0;

        if ($userLevel === 'admin') {
            $nilai_hari_ini_warkop = Transaksi::whereDate('created_at', $hari_ini)->whereHas('kasir', fn($q) => $q->whereIn('level', ['kasir', 'admin']))->sum('total');
            $nilai_hari_ini_ranu = Transaksi::whereDate('created_at', $hari_ini)->whereHas('kasir', fn($q) => $q->where('level', 'staff'))->sum('total');
            $nilai_bulan_ini_warkop = Transaksi::whereMonth('created_at', $bulan_ini)->whereHas('kasir', fn($q) => $q->whereIn('level', ['kasir', 'admin']))->sum('total');
            $nilai_bulan_ini_ranu = Transaksi::whereMonth('created_at', $bulan_ini)->whereHas('kasir', fn($q) => $q->where('level', 'staff'))->sum('total');
        }

        return view('pages.dashboard', compact(
            'transaksi_hari_ini',
            'transaksi_bulan_ini',
            'nilai_hari_ini',
            'nilai_bulan_ini',
            'nilai_hari_ini_warkop',
            'nilai_hari_ini_ranu',
            'nilai_bulan_ini_warkop',
            'nilai_bulan_ini_ranu',
            'chart_labels',
            'chart_values',
            'transaksi_terbaru'
        ));
    }
}
