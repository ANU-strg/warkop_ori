<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Table;
use Illuminate\Http\Request;

class TableController extends Controller
{
    public function index()
    {
        $tables = Table::orderByRaw('CAST(table_number AS UNSIGNED)')->paginate(10);
        return view('admin.tables.index', compact('tables'));
    }

    public function create()
    {
        return view('admin.tables.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'table_number' => 'required|numeric|min:1|unique:tables,table_number',
        ], [
            'table_number.numeric' => 'Table number must be a number only.',
            'table_number.min' => 'Table number must be at least 1.',
            'table_number.unique' => 'Table number already exists. Please use a different number.',
        ]);

        Table::create([
            'table_number' => $request->table_number,
        ]);

        return redirect()->route('admin.tables.index')
            ->with('success', 'Table created successfully!');
    }

    public function show(Table $table)
    {
        return view('admin.tables.show', compact('table'));
    }

    public function edit(Table $table)
    {
        return view('admin.tables.edit', compact('table'));
    }

    public function update(Request $request, Table $table)
    {
        $request->validate([
            'table_number' => 'required|numeric|min:1|unique:tables,table_number,' . $table->id,
        ], [
            'table_number.numeric' => 'Table number must be a number only.',
            'table_number.min' => 'Table number must be at least 1.',
            'table_number.unique' => 'Table number already exists. Please use a different number.',
        ]);

        $table->update([
            'table_number' => $request->table_number,
        ]);

        return redirect()->route('admin.tables.index')
            ->with('success', 'Table updated successfully!');
    }

    public function destroy(Table $table)
    {
        $table->delete();

        return redirect()->route('admin.tables.index')
            ->with('success', 'Table deleted successfully!');
    }
}
