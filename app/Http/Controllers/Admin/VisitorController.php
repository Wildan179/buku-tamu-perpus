<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Visitor;
use Illuminate\Http\Request;

class VisitorController extends Controller
{
    public function index(Request $request)
    {
        $query = Visitor::query();

        // Filter berdasarkan Nama
        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        // Filter berdasarkan Tanggal Masuk
        if ($request->filled('visit_date')) {
            $query->whereDate('visit_date', $request->visit_date);
        }

        // Filter berdasarkan No. HP
        if ($request->filled('no_hp')) {
            $query->where('no_hp', 'like', '%' . $request->no_hp . '%');
        }

        $visitors = $query->orderByDesc('visit_date')->latest()->paginate(10)->withQueryString();

        return view('admin.tamu.index', compact('visitors'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|string|max:255',
            'no_hp' => 'nullable|string|max:20',
            'purpose' => 'nullable|string|max:255',
            'visit_date' => 'required|date',
        ]);

        Visitor::create($request->all());

        return back()->with('success', 'Data tamu berhasil dicatat!');
    }

    public function destroy($id)
    {
        Visitor::findOrFail($id)->delete();
        return back()->with('success', 'Data tamu berhasil dihapus!');
    }
}