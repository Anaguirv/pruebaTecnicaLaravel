<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;

class EmployeeController extends Controller
{
    public function index()
    {
        // Consumir la API
        dump("Antes de la API");
        
        $response = Http::get('http://localhost:8000/api/employee');

        if ($response->successful()) {
            $employees = $response->json();

            // Pasar los datos a la vista Blade
            return view('views.index', compact('employees'));
        }

        return view('index')->withErrors('Error al obtener los datos de la API.');
    }
}
