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
        $awal_bulan = Carbon::now()->startOfMonth();
        $akhir_hari_ini = Carbon::now()->endOfDay();

        $userLevel = strtolower((string) auth()->user()->level);
        $queryBase = Transaksi::query()->completed();
        if ($userLevel === 'staff' || $userLevel === 'kasir') {
            $queryBase->whereHas('kasir', function($q) use ($userLevel) {
                $q->where('level', $userLevel);
            });
        }

        // Hitung total jumlah transaksi (hitung item, bukan nominal)
        $transaksi_hari_ini = (clone $queryBase)->whereDate('tanggal', $hari_ini)->count();
        $transaksi_bulan_ini = (clone $queryBase)->whereBetween('tanggal', [$awal_bulan, $akhir_hari_ini])->count();

        // Hitung total nilai penjualan (nominal)
        $nilai_hari_ini = (clone $queryBase)->whereDate('tanggal', $hari_ini)->sum('total');
        $nilai_bulan_ini = (clone $queryBase)->whereBetween('tanggal', [$awal_bulan, $akhir_hari_ini])->sum('total');

        // Chart penjualan 7 hari terakhir
        $chart = (clone $queryBase)->selectRaw('DATE(tanggal) as tanggal, SUM(total) as total')
            ->where('tanggal', '>=', Carbon::now()->subDays(7))
            ->groupBy(DB::raw('DATE(tanggal)'))
            ->orderBy(DB::raw('DATE(tanggal)'))
            ->get();

        $chart_labels = $chart->pluck('tanggal')->map(fn($d) => Carbon::parse($d)->format('d M'));
        $chart_values = $chart->pluck('total');

        // Transaksi terbaru
        $transaksi_terbaru = (clone $queryBase)->with('kasir')->latest('tanggal')->take(5)->get();

        $nilai_hari_ini_warkop = 0;
        $nilai_hari_ini_ranu = 0;
        $nilai_bulan_ini_warkop = 0;
        $nilai_bulan_ini_ranu = 0;

        if ($userLevel === 'admin') {
            $nilai_hari_ini_warkop = Transaksi::query()->completed()->whereDate('tanggal', $hari_ini)->whereHas('kasir', fn($q) => $q->whereIn('level', ['kasir', 'admin']))->sum('total');
            $nilai_hari_ini_ranu = Transaksi::query()->completed()->whereDate('tanggal', $hari_ini)->whereHas('kasir', fn($q) => $q->where('level', 'staff'))->sum('total');
            $nilai_bulan_ini_warkop = Transaksi::query()->completed()->whereBetween('tanggal', [$awal_bulan, $akhir_hari_ini])->whereHas('kasir', fn($q) => $q->whereIn('level', ['kasir', 'admin']))->sum('total');
            $nilai_bulan_ini_ranu = Transaksi::query()->completed()->whereBetween('tanggal', [$awal_bulan, $akhir_hari_ini])->whereHas('kasir', fn($q) => $q->where('level', 'staff'))->sum('total');
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
