<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Plant;

class PlantController extends Controller
{
    public function destroy(Plant $plant)
    {
        $plant->delete();
        return redirect()->route('editor.plants.index')->with('success', 'Tanaman berhasil dihapus!');
    }
}
