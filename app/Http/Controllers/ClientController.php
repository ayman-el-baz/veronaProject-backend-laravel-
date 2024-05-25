<?php

namespace App\Http\Controllers;

use App\Mail\ForgetPassword;
use App\Models\Clients;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;


class ClientController extends Controller
{
  public function index()
  {
    $Client = Clients::all();
    if (is_null($Client)) {
      return response()->json('Aucun client trouvé', 404);
    }
    return response()->json($Client);

  }
  public function store(Request $request)
  {
    $request->validate([
      'nom' => 'required',
      'prenom' => 'required',
      'email' => 'required|email|unique:clients',
      'password' => 'required',
      'nationalite' => 'required',
      'photo' => 'required|image',
    ]);

    $client = new Clients();
    $client->nom = $request->nom;
    $client->prenom = $request->prenom;
    $client->email = $request->email;
    $client->password = bcrypt($request->password);
    $client->telephone = $request->telephone;
    $client->sexe = $request->sexe;
    $client->nationalite = $request->nationalite;
    $client->role = 'user';
    $client->adresse = $request->adresse;
    $client->commentaire = $request->commentaire;
    $client->ville = $request->ville;

    if ($request->hasFile('photo')) {
      $imageName = time() . '.' . $request->file('photo')->getClientOriginalExtension();
      $request->file('photo')->move(public_path('client/'), $imageName);
      $client->photo = $imageName;
    }

    $client->save();

    return response()->json(['message' => 'Client has been added', 'data' => $client]);
  }
  public function show($id)
  {
    $client = Clients::find($id);
    if (is_null($client)) {
      return response()->json('client not found', 404);
    }
    return response()->json($client);
  }
  public function update(Request $request, $id)
  {
      $client = Clients::find($id);
  
      if (!$client) {
          return response()->json(["error" => "Client not found"], 404);
      }
  
      // Mettre à jour les champs sauf la photo
      $client->nom = $request->input('nom', $client->nom);
      $client->prenom = $request->input('prenom', $client->prenom);
      $client->email = $request->input('email', $client->email);
      $client->telephone = $request->input('telephone', $client->telephone);
      $client->sexe = $request->input('sexe', $client->sexe);
      $client->nationalite = $request->input('nationalite', $client->nationalite);
      $client->role = $request->input('role', $client->role);
      $client->adresse = $request->input('adresse', $client->adresse);
      $client->commentaire = $request->input('commentaire', $client->commentaire);
      $client->ville = $request->input('ville', $client->ville);
  
      $client->save();
  
      return response()->json($client, 200);
  }
  public function uploadPhoto(Request $request, $id)
  {
    $client = Clients::find($id);

    if (!$client) {
      return response()->json(["error" => "Client not found"], 404);
    }

    if ($request->hasFile('photo')) {
      if ($client->photo) {
        $oldImagePath = public_path('client/') . $client->photo;
        if (file_exists($oldImagePath)) {
          unlink($oldImagePath);
        }
      }

      $imageName = time() . '.' . $request->file('photo')->getClientOriginalExtension();
      $request->file('photo')->move(public_path('client/'), $imageName);
      $client->photo = $imageName;
      $client->save();

      return response()->json(["message" => "Photo updated successfully", "photoUrl" => $imageName]);
    } else {
      return response()->json(["error" => "No photo uploaded"], 400);
    }
  }

  public function delete($id)
  {
    $client = Clients::find($id);
    $client->delete();
    return response()->json("client supprimé avec succé", 204);
  }
  public function login(Request $request)
  {
    $request->only([
      "email" => "required|email",
      "password" => "required"
    ]);

    $user = Clients::where("email", $request->email)->first();

    if (!$user || $request->password != $user->password) {
      return response()->json(["erreur!" => "Invalid password or email!"], 400);
    }

    return response()->json(["message" => "Login validated", "user" => $user], 200);
  }
  public function logout(Request $request)
  {
    $request->user()->token()->revoke();
    return response()->json(['message' => 'Successfully logged out'], 200);
  }
  public function ForgetPassword(Request $request)
  {
    $userData = $request->validate([
      'email' => 'required|email'
    ]);

    $code = mt_rand(100000, 999999);

    $client = Clients::where('email', $userData['email'])->first();

    if (!$client) {
      return response()->json(["error" => "This email does not exist"], 404);
    }

    $client->forgetPassNum = $code;
    $client->save();

    // Send forget password email
    try {
      Mail::to($userData['email'])->send(new ForgetPassword($code));
    } catch (\Exception $e) {
      return response()->json(["error" => "Failed to send email"], 500);
    }

    return response()->json(["message" => "Success"]);
  }

}
