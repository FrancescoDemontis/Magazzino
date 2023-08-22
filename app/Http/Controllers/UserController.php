<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
class UserController extends Controller
{

    public function index()
    {
        //qui il metoodo all() v aa prendere tutti gli utenti 
       $users = User::all(); 
          
       // Return Json Response
       return response()->json([
            'results' => $users
       ],200);
    }

 public function registrazione(Request $request)
 {
    //$request validate serve per validare appunto i campi che corrispondano al database
     $request->validate([
        'name' => 'required|string|max:255',
        'cognome' => 'required|string|max:255',
         'email' => 'required|string|email|unique:users|max:255',
         'password' => 'required|string|min:6',
     ]);
        //$user assegnamo a un nuvo oggetto User dove istanziamo i dati seguenti e poi facciamo l areturn che salverà i dati e la reposne ci farà vedere che la registrazione è avvenuta 
     $user = new User([
        'name' => $request->name,
        'cognome' => $request->cognome,
         'email' => $request->email,
         'password' => bcrypt($request->password),
         'role' => 'user',
         
     ]);

     $user->save();

     return response()->json(['message' => 'Registrazione avvenuta con successo'], 201);
 }


 
 public function login(Request $request)
 {

    
     $request->validate([
         'email' => 'required|string|email',
         'password' => 'required|string',
     ]);
     //$request only stiamo dicendo ch ei campi sono unicamente i seguenti 
     $credentials = $request->only('email', 'password');
     //effettuiamo un controllo che ci permette di avere il token di accesso se le credenziali sono corrette e corrispondono ai dati che ci sono nel db
     if (Auth::attempt($credentials)) {
         $user = Auth::user();
         $token = $user->createToken('AuthToken')->plainTextToken;
         // Restituise l'utente autenticato come risposta
         return response([  'token' => $token], 200);
     } else {
         // Restituisce una risposta di errore se l'utente non è autenticato
         return response()->json(['message' => 'Credenziali non valide'], 401);
     }
 
 
    }

    public function auth()
    {
       // Auth::user() per ottenere l'utente autenticato corrente
        $user = Auth::user();
        
        if ($user) {
            // Restituise l'utente autenticato come risposta JSON
            return response()->json(['user' => $user], 200);
        } else {
            // Restituisce una risposta di errore se l'utente non è autenticato
            return response()->json(['message' => 'Utente non autenticato'], 401);
        }
    }
    




}







