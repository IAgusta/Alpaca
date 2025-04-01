<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;
use App\Models\UserDetail;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $data = $request->validated();
    
        // Update basic profile information
        $this->updateProfileInfo($user, $data);
    
        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Update the user's profile information.
     */
    private function updateProfileInfo($user, $data)
    {
        $user->fill([
            'name' => $data['name'],
            'email' => $data['email'],
        ]);
    
        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }
    
        $user->save();
    
        $details = $user->details ?? new UserDetail(['user_id' => $user->id]);
    
        // Ensure social_media is properly decoded
        $socialMedia = is_string($data['social_media']) ? json_decode($data['social_media'], true) : $data['social_media'];
        if (empty($socialMedia)) {
            $socialMedia = null; // Set to null instead of an empty array
        } else {
            $socialMedia = json_encode($socialMedia); // Convert back to JSON
        }
    
        $details->fill([
            'birth_date' => $data['birth_date'] ?? null,
            'about' => $data['about'] ?? null,
            'phone' => $data['phone'] ?? null,
            'social_media' => $socialMedia, // Save JSON correctly
        ])->save();
    }

    public function updateProfileImage(Request $request): RedirectResponse
    {
        $request->validate([
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'banner_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);
    
        $user = $request->user();
        $details = $user->details ?? new UserDetail(['user_id' => $user->id]);
        $images = $details->image ? json_decode($details->image, true) : [];
    
        // Define the folder path for the user
        $userFolder = "profiles/{$user->id}";
    
        if ($request->hasFile('profile_image')) {
            // Delete old profile image if it exists
            if (!empty($images['profile'])) {
                Storage::disk('public')->delete($images['profile']);
            }
    
            // Generate a unique filename with the original extension
            $profileImage = $request->file('profile_image');
            $profileImageName = uniqid('profile_', true) . '.' . $profileImage->getClientOriginalExtension();
    
            // Store the new profile image
            $imagePath = $profileImage->storeAs($userFolder, $profileImageName, 'public');
            $images['profile'] = $imagePath;
        } else {
            // Ensure the 'profile' key exists, even if not updated
            $images['profile'] = $images['profile'] ?? null;
        }
    
        if ($request->hasFile('banner_image')) {
            // Delete old banner image if it exists
            if (!empty($images['banner'])) {
                Storage::disk('public')->delete($images['banner']);
            }
    
            // Generate a unique filename with the original extension
            $bannerImage = $request->file('banner_image');
            $bannerImageName = uniqid('banner_', true) . '.' . $bannerImage->getClientOriginalExtension();
    
            // Store the new banner image
            $imagePath = $bannerImage->storeAs($userFolder, $bannerImageName, 'public');
            $images['banner'] = $imagePath;
        } else {
            // Ensure the 'banner' key exists, even if not updated
            $images['banner'] = $images['banner'] ?? null;
        }
    
        // Save the updated images JSON to the database
        $details->image = json_encode($images);
        $details->save();
    
        return Redirect::route('profile.edit')->with('status', 'profile-images-updated');
    }

    /**
     * Soft delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        // Soft delete the user (this keeps the record but sets deleted_at)
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}