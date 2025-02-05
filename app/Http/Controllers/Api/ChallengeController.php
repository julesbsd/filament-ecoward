<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Challenge;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class ChallengeController extends Controller
{
    public function index()
    {
        $user = auth()->user(); // Récupération de l'utilisateur connecté

        // Récupération des défis avec progression de l'utilisateur
        $challenges = Challenge::all()->filter(function ($challenge) {
            return $challenge->is_active;
        })->map(function ($challenge) use ($user) {
            $userChallenge = $user->challenges()->where('challenge_id', $challenge->id)->first();

            $currentLevel = $userChallenge->pivot->current_level ?? 1;
            $currentProgress = $userChallenge->pivot->progress ?? 0;
            $currentGoal = $challenge->base_goal + ($challenge->increment_goal * ($currentLevel - 1));

            return [
                'id' => $challenge->id,
                'name' => $challenge->name,
                'description' => $challenge->description,
                'trash_id' => $challenge->trash_id,
                'type_id' => $challenge->type_id,
                'is_active' => $challenge->is_active,
                'progress' => [
                    'level' => $currentLevel,
                    'current' => $currentProgress,
                    'goal' => $currentGoal,
                    'text' => "$currentProgress/$currentGoal",
                ],
            ];
        });

        return response()->json(['challenges' => $challenges], 200);
    }
}
