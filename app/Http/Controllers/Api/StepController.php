<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Step;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class StepController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'steps' => 'required|integer',
        ]);

        $user = User::find(Auth::user()->id);
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $points = $this->convertStepsToPoints($request->steps);

        // Vérifier s'il y a déjà des pas enregistrés pour aujourd'hui
        $existingStep = Step::where('user_id', $user->id)
            ->whereDate('created_at', now()->format('Y-m-d'))
            ->first();

        if ($existingStep) {
            // Si le nombre de pas récupéré de request est inférieur au nombre de pas récupéré par existingStep, on ne fait pas de mise à jour
            if ($request->steps > $existingStep->steps) {
                // Mettre à jour les pas et les points existants
                $existingStep->steps = $request->steps;
                $existingStep->points = $points;

                $existingStep->save();
            }
        } else {
            // Créer un nouveau record pour aujourd'hui
            Step::create([
                'user_id' => $user->id,
                'steps' => $request->steps,
                'points' => $points,
            ]);
        }
        // Attendre 2 secondes
        // sleep(2);
        $total_points = User::where('id', Auth::user()->id)->value('points');

        return response()->json(['message' => 'Points sauvegardés', 'points' => $total_points], 201);
    }
    
    private function convertStepsToPoints($steps)
    {
        // Conversion des pas en points, par exemple, 100 pas = 1 point
        return intval($steps / 100);
    }

    public function index()
    {
        return Step::all();
    }

    public function show($id)
    {
        $step = Step::find($id);
        if (!$step) {
            return response()->json(['error' => 'Step not found'], 404);
        }

        return response()->json($step);
    }

    public function update(Request $request, $id)
    {
        $step = Step::find($id);
        if (!$step) {
            return response()->json(['error' => 'Step not found'], 404);
        }

        $step->steps = $request->steps;
        $step->points =  $this->convertStepsToPoints($request->steps);
        $step->save();

        return response()->json($step);
    }

    public function destroy($id)
    {
        $step = Step::find($id);
        if (!$step) {
            return response()->json(['error' => 'Step not found'], 404);
        }

        $step->delete();

        return response()->json(['message' => 'Step deleted']);
    }



    /**
     * Récupérer les pas de l'utilisateur pour chaque jour de la semaine
     */
    public function getWeeklySteps()
    {
        $user = User::find(Auth::user()->id);
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $stepsData = [];
        $daysOfWeek = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'];

        try {
            foreach ($daysOfWeek as $index => $day) {
                $date = now()->startOfWeek()->addDays($index);
                $steps = Step::where('user_id', $user->id)
                    ->whereDate('created_at', $date->format('Y-m-d'))
                    ->first();

                $stepsData[] = [
                    'day' => $day,
                    'steps' => $steps ? $steps->steps : 0
                ];
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }

        return response()->json(['weekSteps' => $stepsData]);
    }
}
