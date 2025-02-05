<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\DB;
use App\Models\Role;
use App\Models\Step;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Laravel\Sanctum\HasApiTokens;

class UserController extends Controller
{
    use HasApiTokens;

    public function index()
    {
        return UserResource::collection(User::with(['role', 'actions.trash'])->get());
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:5',
        ], [
            'password' => 'Le mot de passe doit contenir au moins 8 caractères',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }

        try {
            $credentials = $request->only('email', 'password');

            if (!Auth::attempt($credentials)) {
                return response()->json(['error' => 'Les identifiants sont incorrects'], 401);
            }
            $steps = Step::where('user_id', Auth::user()->id)
                ->whereDate('created_at', now()->format('Y-m-d'))
                ->first();

            $user = User::with('role')
            ->with('actions')
            ->with('actions.trash')
            ->find(Auth::user()->id);

            $user->steps = $steps ? $steps->steps : 0;

            $token = $user->createToken('auth_token')->plainTextToken;

            // Calculer le total des points des déchets de l'utilisateur
            // $userPoints = $this->getUserPoints(Auth::user()->id);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }
        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => new UserResource($user),
        ], 200);
    }

    public function register(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:5',
        ], [
            'email' => 'Un compte existe déjà avec cet email',
            'password' => 'Le mot de passe doit contenir au moins 8 caractères, une lettre majuscule, une lettre minuscule et un chiffre',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }

        $password = bcrypt($request['password']);

        $role = Role::where('name', 'user')->first();
        $user = User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => $password,
            'role_id' => 1,
        ]);


        return response()->json(['message' => 'User created'], 201);
    }

    public function update(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . Auth::id(),
            'profileImage' => 'nullable',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }
        // Récupérer l'utilisateur authentifié
        $user = Auth::user();

        // Mise à jour des informations
        $user->name = $request->name;
        $user->email = $request->email;
        if ($request->has('profileImage')) {
            $imageData = $request->profileImage;
            $imageName = 'profile_' . uniqid() . '.png';
            Storage::disk('public')->put($imageName, base64_decode($imageData));
            $user->profile_photo_path = $imageName;
        }
        $user->save();
        $userPoints = $this->getUserPoints(Auth::user()->id);


        return response()->json([
            'user' => new UserResource($user),
            'points' => $userPoints,
            'message' => 'Informations utilisateur mises à jour avec succès'
        ], 200, [],);
    }

    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            // 'password' => 'required|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/',
            'password' => 'required|min:5',
        ], [
            'password' => 'Le mot de passe doit contenir au moins 8 caractères, une lettre majuscule, une lettre minuscule et un chiffre',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['error' => 'Aucun utilisateur trouvé'], 401);
        }

        $password = bcrypt($request['password']);
        $user->password = $password;
        $user->save();

        return response()->json(['message' => 'Password reset'], 200);
    }

    public function me()
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $step = Step::where('user_id', Auth::user()->id)
            ->whereDate('created_at', now()->format('Y-m-d'))
            ->first();

        return response()->json([
            'user' => new UserResource(Auth::user()),
            'step' => $step ? $step->steps : 0,
        ], 200);
    }

    public function autologin()
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        // $userPoints = $this->getUserPoints(Auth::user()->id);

        $step = Step::where('user_id', Auth::user()->id)
            ->whereDate('created_at', now()->format('Y-m-d'))
            ->first();

        $user = Auth::user();
        $user->steps = $step ? $step->steps : 0;
        return response()->json([
            'user' => new UserResource($user),
            // 'steps' => $step ? $step->steps : 0,
            // 'points' => $userPoints,
        ], 200);
    }


    public function logout()
    {

        try {
            auth()->user()->tokens()->delete();
            return response()->json(['message' => 'logout'], 200);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }

    public function getUserPoints($id)
    {
        $totalTrashPoints = DB::table('users')
            ->leftJoin('actions', function ($join) {
                $join->on('users.id', '=', 'actions.user_id')
                    ->where('actions.status', '=', 'accepted');
            })
            ->leftJoin('trashes', 'actions.trash_id', '=', 'trashes.id')
            ->where('users.id', $id)
            ->select(DB::raw('COALESCE(SUM(trashes.points * actions.quantity), 0) as total_trash_points'))
            ->groupBy('users.id')
            ->first()
            ->total_trash_points;

        // Calculer le total des points de la table step de l'utilisateur
        $totalStepPoints = DB::table('steps')
            ->where('user_id', $id)
            ->select(DB::raw('COALESCE(SUM(points), 0) as total_step_points'))
            ->first()
            ->total_step_points;

        // Additionner les deux totaux de points
        $totalPoints = $totalTrashPoints + $totalStepPoints;
        return $totalPoints;
    }

    public function testbase64Image()
    {
        $user = User::find(1);
        return response()->json(['profile_photo_url' => $user->profile_photo_path]);
    }
}
