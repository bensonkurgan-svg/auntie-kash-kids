<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class PasswordController extends Controller
{
    public function showChange()
    {
        return view('auth.change-password');
    }

    public function change(Request $request)
    {
        $data = $request->validate([
            'current_password' => ['required'],
            'password' => ['required','confirmed', Password::min(8)->letters()->numbers()],
        ]);

        $user = Auth::user();

        if (! Hash::check($data['current_password'], $user->password)) {
            return back()->withErrors(['current_password' => 'Your current password is incorrect.']);
        }

        // Prevent reusing the same password
        if (Hash::check($data['password'], $user->password)) {
            return back()->withErrors(['password' => 'Please choose a password different from your current one.']);
        }

        $user->update([
            'password' => Hash::make($data['password']),
            'must_change_password' => false,
        ]);

        return redirect($user->dashboardRoute())->with('success', 'Password updated successfully. Welcome aboard!');
    }
}
