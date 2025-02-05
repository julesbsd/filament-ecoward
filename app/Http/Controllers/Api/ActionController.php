<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Action;
use App\Models\Challenge;
use App\Models\Trash;
use App\Models\User;
use App\Models\UserChallenge;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ActionController extends Controller
{
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'challengeId' => 'required|int', // Récupération du challenge directement
            'trashId' => 'required|int',
            'quantity' => 'required|int|min:1|max:20',
            'imageTop' => 'required|string',
            'imageBottom' => 'required|string',
            'latitude' => 'required',
            'longitude' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->errors()], 401);
        }

        try {
            // Upload des images
            $imageTopName = 'actionTop_' . uniqid() . '.png';
            $imageBottomName = 'actionBottom_' . uniqid() . '.png';

            if ($request->has('imageTop')) {
                $imageTopData = $request->imageTop;
                Storage::disk('public')->put($imageTopName, base64_decode($imageTopData));
            }

            if ($request->has('imageBottom')) {
                $imageBottomData = $request->imageBottom;
                Storage::disk('public')->put($imageBottomName, base64_decode($imageBottomData));
            }

            // Création de l'action
            $action = Action::create([
                'user_id' => auth()->user()->id,
                'trash_id' => $request->trashId,
                'action_type_id' => 1,
                'challenge_id' => $request->challengeId,
                'status' => 'pending',
                'description' => 'Action écologique réalisée',
                'quantity' => $request->quantity,
                'image_action' => $imageTopName,
                'image_throw' => $imageBottomName,
                'location' => $request->latitude . ',' . $request->longitude,
            ]);

            // Récupération des données nécessaires
            $user = User::find(auth()->user()->id);
            $challenge = Challenge::find($request->challengeId);
            $userChallenge = UserChallenge::where('user_id', $user->id)
                ->where('challenge_id', $challenge->id)
                ->first();

            if (!$userChallenge) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User is not enrolled in this challenge.'
                ], 404);
            }

            // Mise à jour de la progression
            $userChallenge->progress += $action->quantity;
            $baseGoal = $challenge->base_goal + ($userChallenge->current_level - 1) * $challenge->increment_goal;
            $levelUp = false;

            // Vérification du passage au niveau suivant
            if ($userChallenge->progress >= $baseGoal) {
                $levelUp = true; // Indicateur pour savoir si l'utilisateur monte de niveau
                $userChallenge->current_level += 1;
                $userChallenge->progress -= $baseGoal;
            }

            $userChallenge->save();

            // Ajouter des points uniquement si l'utilisateur a monté de niveau
            if ($levelUp) {
                $user->points += $challenge->points;
                $user->save();
            }

            Log::info("Action créée avec succès pour l'utilisateur : {$user->id}, niveau atteint : {$userChallenge->current_level}");
        } catch (\Exception $e) {
            Log::error('Erreur lors de la création de l\'action : ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 401);
        }

        return response()->json([
            'status' => "success",
            "message" => "Action created",
            "points" => $user->points,
            "progress" => $userChallenge->progress,
            "level" => $userChallenge->current_level
        ], 200);
    }





    public function testChallenge(Request $request)
    {
        try {
            $user = auth()->user();
            $challengeId = $request->challengeId;
            $userChallenge = $user->challenges()->where('challenge_id', $challengeId)->first();

            if (!$userChallenge) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Challenge not found for user'
                ], 404);
            }
        } catch (\Exception $e) {
            Log::error('Error creating action: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 401);
        } catch (\Exception $e) {
            Log::error('Error creating action: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 401);
        }
    }
}
