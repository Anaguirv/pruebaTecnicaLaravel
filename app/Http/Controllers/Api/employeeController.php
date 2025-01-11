<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class employeeController extends Controller
{

    /**
     * Lista todos los empleados.
     * 
     * @return \Illuminate\Http\JsonResponse
    */
    public function index()
    {
        $employees = Employee::paginate(5);

        if ($employees->isEmpty()){
            $data = [
                'message' => 'No se encontraron empleados',
                'status' => 200
            ];
            return response()->json($data, 200);
        }

        $data = [
            'employees' => $employees,
            'status' => 200 
        ];
        return response()->json($data, 200);
    }

    /**
     * Obtiene los detalles de un empleado por ID.
     *
     * @param int $id ID del empleado.
     * @return \Illuminate\Http\JsonResponse Respuesta JSON con los datos del empleado o un error 404.
    */
    public function show($id)
    {
        $employee = Employee::find($id);

        if (!$employee){
            $data = [
                'message' => 'Empleado no encontrado',
                'status' => 404
            ];
            return response()->json($data, 404);
        }

        $data = [
            'employee' => $employee,
            'status' => 200 
        ];
        return response()->json($data, 200);

    }

    /**
     * Crea un nuevo empleado.
     * 
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
    */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'rut'           => 'required|unique:employee|regex:/^\d{7,8}-[kK\d]$/',
            'first_name'    => 'required|max:255',
            'last_name'     => 'required|max:255'
        ]);

        if ($validator->fails()){
            $data = [
                'message' => 'Error en la validación de los datos',
                'errors' => $validator->errors(),
                'status' => 400
            ];
            return response()->json($data, 400);
        }

        $employee = Employee::create([
            'rut'           => $request->rut,
            'first_name'    => $request->first_name,
            'last_name'     => $request->last_name
        ]);

        if (!$employee){
            $data=[
                'message' => 'Error al crear al empleado',
                'status' => 500
            ];
            return response()->json($data, 500);
        }

        $data = [
            'employee' => $employee,
            'status' => 201
        ];
        return response()->json($data, 201);
    }
}
