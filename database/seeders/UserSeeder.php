<?php

namespace Database\Seeders;

use App\Enums\FieldOfStudy;
use App\Enums\Role;
use App\Enums\Status;
use App\Models\Club;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/** Comptes de démonstration : mot de passe « password » pour tous. */
class UserSeeder extends Seeder
{
    public function run(): void
    {
        $ais = Club::where('slug', 'ais')->firstOrFail();
        $hackademy = Club::where('slug', 'hackademy')->firstOrFail();

        $accounts = [
            ['bde@ensaj.test', 'BDE', 'Admin', Role::Bde, Status::Approved, null, null],
            ['etudiant@ensaj.test', 'Étudiant', 'Test', Role::Student, Status::Approved, FieldOfStudy::Gi, null],
            ['attente@ensaj.test', 'En', 'Attente', Role::Student, Status::Pending, FieldOfStudy::Cp1, null],
            ['refuse@ensaj.test', 'Refusé', 'Test', Role::Student, Status::Rejected, FieldOfStudy::Cp2, 'Informations incomplètes.'],
            ['membre.ais@ensaj.test', 'Membre', 'AIS', Role::ClubMember, Status::Approved, FieldOfStudy::Isic, null],
            ['membre.hackademy@ensaj.test', 'Membre', 'Hackademy', Role::ClubMember, Status::Approved, FieldOfStudy::Iite, null],
        ];

        foreach ($accounts as [$email, $first, $last, $role, $status, $field, $reason]) {
            $user = User::firstOrNew(['email' => $email]);
            $user->first_name = $first;
            $user->last_name = $last;
            $user->password = Hash::make('password');
            $user->field_of_study = $field ?? FieldOfStudy::Gi;
            $user->role = $role;
            $user->status = $status;
            $user->rejection_reason = $reason;
            $user->validated_by = null;
            $user->validated_at = null;
            $user->assignRole($role, match ($email) {
                'membre.ais@ensaj.test' => $ais->id,
                'membre.hackademy@ensaj.test' => $hackademy->id,
                default => null,
            });
        }

        // Les comptes approuvés sont validés par le BDE, quel que soit son identifiant.
        $bdeId = User::where('email', 'bde@ensaj.test')->value('id');
        foreach ($accounts as [$email, , , , $status]) {
            if ($status !== Status::Approved) {
                continue;
            }
            $approved = User::where('email', $email)->firstOrFail();
            $approved->validated_by = $bdeId;
            $approved->validated_at = now();
            $approved->save();
        }
    }
}
