<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {

//         // Trigger lors du refus d'une action, pour mettre à jour les points de l'utilisateur
//         DB::unprepared(
//             "CREATE TRIGGER update_user_points_after_action_refused
//                 AFTER UPDATE ON actions
//                 FOR EACH ROW
//     BEGIN
//     DECLARE points_to_deduct INT;

//     -- Vérifier si le statut est mis à jour vers 'refused'
//     IF NEW.status = 'refused' AND OLD.status != 'refused' THEN
//         -- Récupérer les points associés au type de déchet
//         SELECT COALESCE(points, 0) INTO points_to_deduct
//         FROM trashes
//         WHERE id = NEW.trash_id;

//         -- Calculer les points à déduire
//         SET points_to_deduct = points_to_deduct * NEW.quantity;

//         -- Soustraire les points du total de l'utilisateur
//         UPDATE users
//         SET points = points - points_to_deduct
//         WHERE id = NEW.user_id;
//     END IF;
// END;"
//         );

//         // Trigger lors de la création d'une action pour mettre à jour les points de l'utilisateur
//         DB::unprepared(
//             "CREATE TRIGGER update_user_points_after_action_insert
//             AFTER INSERT ON actions
//             FOR EACH ROW
//             BEGIN
//                 DECLARE trash_points INT DEFAULT 0;
//                 DECLARE action_points INT DEFAULT 0;

//                 -- Get points from trashes table
//                 SELECT COALESCE(points, 0) INTO trash_points
//                 FROM trashes
//                 WHERE id = NEW.trash_id;

//                 -- Calculate total points for the action
//                 SET action_points = trash_points * NEW.quantity;

//                 -- Update user total points
//                 UPDATE users
//                 SET points = points + action_points
//                 WHERE id = NEW.user_id;
//             END;
//         "
//         );

//         // Trigger lors de l'update du nombre de pas journalier pour mettre à jour les points de l'utilisateur
//         DB::unprepared(
//             "CREATE TRIGGER update_user_points_after_steps_insert
//                 AFTER UPDATE ON steps
//                 FOR EACH ROW
//                BEGIN
//     DECLARE point_difference INT;

//     -- Calculer la différence entre l'ancien et le nouveau nombre de points
//     SET point_difference = NEW.points - OLD.points;

//     -- Mettre à jour les points totaux de l'utilisateur dans la table users
//     UPDATE users
//     SET points = points + point_difference
//     WHERE id = NEW.user_id;
// END;"
//         );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop triggers
        // DB::unprepared('DROP TRIGGER IF EXISTS update_user_points_after_action_insert;');
        // DB::unprepared('DROP TRIGGER IF EXISTS update_user_points_after_action_refused;');
        // DB::unprepared('DROP TRIGGER IF EXISTS update_user_points_after_steps_update;');
    }
};
