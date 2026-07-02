<?php
namespace App\Services;
use App\Models\User;
use App\Models\TutorProfile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AccountService
{
    /**
     * Create a staff account (ADMIN or TUTOR) with a secure auto-generated
     * password. Returns the plain password ONCE so it can be shown/emailed.
     * The user must change it on first login.
     */
    public static function createStaff(array $data, string $role): array
    {
        // Generate a strong, readable temporary password.
        $plainPassword = self::generatePassword();

        $user = User::create([
            'name'                 => $data['name'],
            'email'                => $data['email'],
            'work_email'           => $data['work_email'] ?? $data['email'],
            'phone'                => $data['phone'] ?? null,
            'password'             => Hash::make($plainPassword),
            'role'                 => $role,
            'must_change_password' => true,
            'is_active'            => true,
        ]);

        // Tutors get a profile record for their public-facing info.
        if ($role === 'TUTOR') {
            TutorProfile::create([
                'user_id'    => $user->id,
                'bio'        => $data['bio'] ?? null,
                'phone'      => $data['phone'] ?? null,
                'work_email' => $data['work_email'] ?? $data['email'],
                'specialties'=> $data['specialties'] ?? [],
            ]);
        }

        return ['user' => $user, 'password' => $plainPassword];
    }

    /**
     * Strong temporary password: 4 letters + 4 digits + symbol, shuffled.
     * Avoids ambiguous characters (0/O, 1/l) for readability when handed over.
     */
    public static function generatePassword(): string
    {
        $letters = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz';
        $digits  = '23456789';
        $symbols = '!@#$%&*';

        $pw  = '';
        for ($i = 0; $i < 4; $i++) $pw .= $letters[random_int(0, strlen($letters) - 1)];
        for ($i = 0; $i < 4; $i++) $pw .= $digits[random_int(0, strlen($digits) - 1)];
        $pw .= $symbols[random_int(0, strlen($symbols) - 1)];

        return str_shuffle($pw);
    }
}
