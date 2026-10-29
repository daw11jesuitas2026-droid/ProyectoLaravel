<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

// Controlador de la API para las direcciones (relación 1:1 con User).
class AddressController extends Controller
{
    // POST /api/address
    // Crea la dirección del usuario identificado por su email.
    public function store(Request $request)
    {
        // Validamos por separado el email y los datos de la dirección
        $emailValidator = $this->validateEmail($request);
        $addressValidator = $this->validateAddress($request);

        // Si falla cualquiera de las dos validaciones, devolvemos los errores de ambas
        if ($emailValidator->fails() || $addressValidator->fails()) {
            return response()->json([
                'message' => 'Failed',
                'email'   => $emailValidator->messages(),
                'address' => $addressValidator->messages(),
            ], 400);
        }

        // Buscamos el usuario por su email (sabemos que existe gracias a la validación)
        $user = User::where('email', $request->get('email'))->firstOrFail();

        // Relación 1:1: si el usuario ya tiene dirección, no creamos otra
        if ($user->address) {
            return response()->json([
                'message' => 'Failed, UserAddress already exists',
                'data' => null
            ], 400);
        }

        $address = new Address($addressValidator->validate());

        if($user->address()->save($address)){
            return response()->json(['message' => 'Address saved', 'data' => $address], 200);
        }
        return response()->json(['message' => 'Failed', 'data' => null], 400);
    }

    // GET /api/address/{address}
    // Muestra una dirección. Laravel la busca por el id de la URL (route model binding).
    public function show(Address $address)
    {
        return response()->json(['message' => '', 'data' => $address], 200);
    }

    // GET /api/address/{address}/user
    // Devuelve el usuario al que pertenece la dirección (relación belongsTo).
    public function show_user(Address $address)
    {
        return response()->json(['message' => '', 'data' => $address->user], 200);
    }

    // Valida que llegue un email correcto y que pertenezca a un usuario registrado
    private function validateEmail(Request $request)
    {
        return Validator::make($request->all(), [
            'email' => 'required|string|email|exists:users,email',
        ]);
    }

    // Valida los campos propios de la dirección
    private function validateAddress(Request $request)
    {
        return Validator::make($request->all(), [
            'country' => 'required|string|max:255',
            'zipcode' => 'required|string|max:20',
        ]);
    }
}
