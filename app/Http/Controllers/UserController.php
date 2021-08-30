<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Log;
use Validator;

class UserController extends Controller
{
    
    public function __construct()
    {
        $this->middleware('auth');
    }
   
    public function index()
    {
        $datos = User::where('deleted','0')->orderBy('id','desc')->paginate(5);
        return view('home',compact('datos'));
    }

   
    public function create()
    {
        //
    }

    
    public function store(Request $request)
    {

        // Log::info($request->all());
        $this->data = $request->except(['_token']);

        $rules = [
            'name'              => 'required',
            'last_name'              => 'required',
            'telephone'              => 'required|numeric',
            'email'                 => 'required|string|email|max:255|unique:users',            
            'password'                 => 'required',            
        ];

        $validator = Validator::make($this->data, $rules);

    //    Log::info($validator->fails());

        if ($validator->fails()) {
            $errors = [];
            foreach ($this->data as $key => $value) {
                if (!empty($validator->errors()->first($key))) {
                    array_push($errors, array($key => $validator->errors()->first($key)));
                }
            }

            return response()->json(array(
                'created' => false,
                'errors'  => $errors
            ), 200);
        }

        

        

        if (!empty($this->data['hidden_id']) && is_numeric($this->data['hidden_id'])) {

            $datosActualizar=$this->data;
            unset($datosActualizar['hidden_id']);
            User::where('id', '=', $this->data['hidden_id'])->update($datosActualizar);

        } else {
            User::create($this->data);
        }


        return response()->json(array(
            'created' => true,
            'message'  => 'Usuario guardado correctamente'
        ), 200);
    }

    
    public function show($id)
    {
        return User::find($id);
    }

    
    // public function update(Request $request, $id)
    // {
    //     //
    // }

    
    public function destroy($id)
    {
        try {
            User::where('id', '=', $id)->update(['deleted'=>'1']);  

            return response()->json(array(
                'deleted' => true,
            ), 200);
        } catch (\Exception $e) {
            Log::critical("Error al eliminar usuario. Detalles del error: {$e->getCode()},{$e->getLine()},{$e->getMessage()}");
            return response()->json(array(
                'deleted' => false,
                'details' => 'Error Inesperado: por favor contactarse con el administrador del sitio',
            ), 200);
        }
    }
}
