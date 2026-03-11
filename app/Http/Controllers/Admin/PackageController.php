<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Package;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    public function index()
    {
        $packages = Package::with('features')->latest()->get();
        return view('admin.packages.index', compact('packages'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required',
            'price' => 'required|numeric',
            'features' => 'required|array'
        ]);

        $package = Package::create($request->only('name', 'type', 'price'));

        foreach ($request->features as $featureName) {
            if ($featureName) {
                $package->features()->create(['feature_name' => $featureName]);
            }
        }

        return redirect()->route('admin.packages.index')->with('success', 'Paket berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required',
            'price' => 'required|numeric',
            'features' => 'required|array'
        ]);

        $package = Package::findOrFail($id);
        $package->update($request->only('name', 'type', 'price'));

        // Refresh fitur
        $package->features()->delete();
        foreach ($request->features as $featureName) {
            if ($featureName) {
                $package->features()->create(['feature_name' => $featureName]);
            }
        }

        return redirect()->route('admin.packages.index')->with('success', 'Paket berhasil diperbarui!');
    }

    public function destroy($id)
    {
        Package::findOrFail($id)->delete();
        return redirect()->route('admin.packages.index')->with('success', 'Paket berhasil dihapus!');
    }
}   