<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\UserDetail;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use App\Models\Course;

class ProfileController extends Controller
{
    /**
     * Display the user's profile.
     */
    public function index(Request $request): View
    {
        $user = $request->user();
        $details = $user->details ?? new UserDetail(['user_id' => $user->id]);
        $images = $details->image ? json_decode($details->image, true) : [];
        $accountage = $user->created_at->diffForHumans();

        return view('profile.index', [
            'user' => $user,
            'details' => $details,
            'images' => $images,
            'accountage' => $accountage,
        ]);
    }

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
    
        // Redirect back to the profile page with the active tab
        return Redirect::route('profile.edit', ['tab' => 'update-profile'])->with('status', 'profile-updated');
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
    
        $details->fill([
            'birth_date' => $data['birth_date'] ?? null,
            'about' => $data['about'] ?? null,
            'phone' => $data['phone'] ?? null,
        ])->save();
    }

    /**
     * Update the user's social media links.
     */
    public function updateSocialMediaLinks(Request $request): RedirectResponse
    {
        $request->validate([
            'social_media' => 'required|json',
        ]);
        
        $user = $request->user();
        $details = $user->details ?? new UserDetail(['user_id' => $user->id]);
        
        // Decode the JSON input
        $socialMedia = json_decode($request->social_media, true);
        
        // Filter out empty values
        $filteredSocialMedia = Arr::where($socialMedia, fn($value) => !empty($value));
        
        // Log the final data to be stored
        Log::info('Final social media links to be stored:', $filteredSocialMedia);
        
        // Update the social media data
        $details->social_media = $filteredSocialMedia;
        $details->save();
        
        // Redirect back to the profile page with the active tab
        return Redirect::route('profile.edit', ['tab' => 'update-link-account'])->with('status', 'social-media-updated');
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

    public function show($username): View
    {
        $user = User::with('details')->where('username', $username)->firstOrFail();
        $details = $user->details ?? new \App\Models\UserDetail(['user_id' => $user->id]);
        $images = $details->image ? json_decode($details->image, true) : [];
        $accountage = $user->created_at->diffForHumans();
    
        $createdCourses = null;
        if (in_array($user->role, ['admin', 'trainer', 'owner'])) {
            $createdCourses = Course::where('author', $user->id)
                ->with(['modules', 'authorUser'])
                ->orderBy('popularity', 'desc')
                ->paginate(10);
        }

        return view('profile.show', [
            'user' => $user,
            'details' => $details,
            'images' => $images,
            'accountage' => $accountage,
            'createdCourses' => $createdCourses,
        ]);
    }

    public function getAllUsers()
    {
        $users = User::select('id', 'name', 'username', 'role', 'created_at')
            ->with('details')
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        $users->getCollection()->transform(function ($user) {
            $avatar = $user->details && $user->details->image 
                ? json_decode($user->details->image, true)['profile'] ?? null 
                : null;
                
            return [
                'id' => $user->id,
                'name' => $user->name,
                'username' => $user->username,
                'role' => $user->role,
                'avatar' => $avatar ? asset('storage/' . $avatar) : asset('storage/profiles/default-profile.png')
            ];
        });

        return response()->json($users);
    }

    public function search(Request $request)
    {
        $query = $request->get('query');
        
        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $users = User::where('name', 'LIKE', "%{$query}%")
            ->orWhere('username', 'LIKE', "%{$query}%")
            ->select('id', 'name', 'username', 'role')
            ->with('details')
            ->limit(10)
            ->get()
            ->map(function ($user) {
                $avatar = $user->details && $user->details->image 
                    ? json_decode($user->details->image, true)['profile'] ?? null 
                    : null;
                    
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'username' => $user->username,
                    'role' => $user->role,
                    'avatar' => $avatar ? asset('storage/' . $avatar) : asset('storage/profiles/default-profile.png')
                ];
            });

        return response()->json($users);
    }
}