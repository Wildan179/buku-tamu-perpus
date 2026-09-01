<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Member;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    public function index(Request $request)
    {
        $query = Member::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('member_code', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $members = $query->latest()->paginate(10)->withQueryString();

        return view('admin.anggota.index', compact('members'));
    }

    public function create()
    {
        return view('admin.anggota.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'member_code' => 'required|string|unique:members,member_code',
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|unique:members,email',
            'phone'       => 'nullable|string|max:20',
            'address'     => 'nullable|string',
        ]);

        Member::create($request->all());

        return redirect()->route('admin.anggota.index')->with('success', 'Anggota berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $member = Member::findOrFail($id);
        return view('admin.anggota.edit', compact('member'));
    }

    public function update(Request $request, $id)
    {
        $member = Member::findOrFail($id);

        $request->validate([
            'member_code' => 'required|string|unique:members,member_code,' . $id,
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|unique:members,email,' . $id,
            'phone'       => 'nullable|string|max:20',
            'address'     => 'nullable|string',
        ]);

        $member->update($request->all());

        return redirect()->route('admin.anggota.index')->with('success', 'Data anggota berhasil diperbarui!');
    }

    public function destroy($id)
    {
        Member::findOrFail($id)->delete();
        return back()->with('success', 'Anggota berhasil dihapus!');
    }
}