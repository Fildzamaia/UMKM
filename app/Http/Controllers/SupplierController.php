<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SupplierController extends Controller
{
    public function index()
    {
        $suppliers = DB::table('supplier')
            ->orderBy('nama_supplier')
            ->get();

        return view(
            'admin.suppliers.index',
            compact('suppliers')
        );
    }

    public function create()
    {
        return view('admin.suppliers.create');
    }

    public function store(Request $request)
    {
        $validated = $this->validateSupplier($request);

        $lastId = DB::table('supplier')
            ->orderByDesc('id_supplier')
            ->value('id_supplier');

        $number = $lastId
            ? ((int) substr($lastId, 3)) + 1
            : 1;

        DB::table('supplier')->insert([
            'id_supplier' =>
                'SUP'.str_pad($number, 3, '0', STR_PAD_LEFT),
            'nama_supplier' => $validated['nama_supplier'],
            'no_telp' => $validated['no_telp'],
            'alamat' => $validated['alamat'] ?? null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()
            ->route('admin.suppliers.index')
            ->with('success', 'Supplier ditambahkan.');
    }

    public function edit(string $id)
    {
        $supplier = DB::table('supplier')
            ->where('id_supplier', $id)
            ->first();

        abort_if(!$supplier, 404);

        return view(
            'admin.suppliers.edit',
            compact('supplier')
        );
    }

    public function update(Request $request, string $id)
    {
        $validated = $this->validateSupplier($request);

        DB::table('supplier')
            ->where('id_supplier', $id)
            ->update([
                'nama_supplier' => $validated['nama_supplier'],
                'no_telp' => $validated['no_telp'],
                'alamat' => $validated['alamat'] ?? null,
                'updated_at' => now(),
            ]);

        return redirect()
            ->route('admin.suppliers.index')
            ->with('success', 'Supplier diperbarui.');
    }

    private function validateSupplier(Request $request): array
    {
        return $request->validate([
            'nama_supplier' => 'required|max:255',
            'no_telp' => 'required|max:30',
            'alamat' => 'nullable|max:1000',
        ]);
    }
}
