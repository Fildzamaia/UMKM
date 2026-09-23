<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ExpenseController extends Controller
{
    private const MANUAL_TYPES = [
        'LISTRIK',
        'INTERNET',
        'SEWA',
        'ONGKIR',
        'PERAWATAN',
        'LAINNYA',
    ];

    public function index()
    {
        $expenses = DB::table('pengeluaran as e')
            ->join('akun as a', 'e.id_akun', '=', 'a.id_akun')
            ->leftJoin(
                'pembelian_produk as p',
                'e.id_pembelian',
                '=',
                'p.id_pembelian'
            )
            ->select(
                'e.*',
                'a.nama as admin_nama',
                'p.status_pembelian'
            )
            ->orderByDesc('e.tanggal_pengeluaran')
            ->get();

        return view(
            'admin.expenses.index',
            compact('expenses')
        );
    }

    public function create()
    {
        return view('admin.expenses.create');
    }

    public function store(Request $request)
    {
        $validated = $this->validateExpense($request);

        $lastId = DB::table('pengeluaran')
            ->orderByDesc('id_pengeluaran')
            ->value('id_pengeluaran');

        $number = $lastId
            ? ((int) substr($lastId, 3)) + 1
            : 1;

        DB::table('pengeluaran')->insert([
            'id_pengeluaran' =>
                'PNG'.str_pad($number, 4, '0', STR_PAD_LEFT),
            'id_akun' => Auth::id(),
            'id_pembelian' => null,
            'tanggal_pengeluaran' => $validated['tanggal_pengeluaran'],
            'jenis_pengeluaran' => $validated['jenis_pengeluaran'],
            'nominal' => $validated['nominal'],
            'keterangan' => $validated['keterangan'] ?? null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()
            ->route('admin.expenses.index')
            ->with('success', 'Pengeluaran operasional dicatat.');
    }

    public function edit(string $id)
    {
        $expense = DB::table('pengeluaran')
            ->where('id_pengeluaran', $id)
            ->first();

        abort_if(!$expense, 404);

        if ($expense->id_pembelian !== null) {
            return redirect()
                ->route('admin.expenses.index')
                ->withErrors([
                    'pengeluaran' =>
                        'Pengeluaran dari pembelian produk dibuat otomatis dan tidak dapat diedit manual.',
                ]);
        }

        return view(
            'admin.expenses.edit',
            compact('expense')
        );
    }

    public function update(Request $request, string $id)
    {
        $expense = DB::table('pengeluaran')
            ->where('id_pengeluaran', $id)
            ->first();

        abort_if(!$expense, 404);

        if ($expense->id_pembelian !== null) {
            return redirect()
                ->route('admin.expenses.index')
                ->withErrors([
                    'pengeluaran' =>
                        'Pengeluaran dari pembelian produk tidak dapat diedit manual.',
                ]);
        }

        $validated = $this->validateExpense($request);

        DB::table('pengeluaran')
            ->where('id_pengeluaran', $id)
            ->update([
                'tanggal_pengeluaran' =>
                    $validated['tanggal_pengeluaran'],
                'jenis_pengeluaran' =>
                    $validated['jenis_pengeluaran'],
                'nominal' => $validated['nominal'],
                'keterangan' => $validated['keterangan'] ?? null,
                'updated_at' => now(),
            ]);

        return redirect()
            ->route('admin.expenses.index')
            ->with('success', 'Pengeluaran diperbarui.');
    }

    private function validateExpense(Request $request): array
    {
        return $request->validate([
            'tanggal_pengeluaran' => 'required|date',
            'jenis_pengeluaran' =>
                'required|in:'.implode(',', self::MANUAL_TYPES),
            'nominal' => 'required|numeric|min:1',
            'keterangan' => 'nullable|max:1000',
        ]);
    }
}
