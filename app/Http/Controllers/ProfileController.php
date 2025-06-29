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
use App\Models\robot;
use App\Models\robotDetail;

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
        return Redirect::route('profile.edit', ['tab' => 'update-profile'])->with('status', 'User Information Updated Successfully');
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
        
        // Build full URLs from usernames
        $prefixes = [
            'facebook' => 'https://facebook.com/',
            'instagram' => 'https://instagram.com/',
            'x' => 'https://x.com/',
            'linkedin' => 'https://linkedin.com/in/',
            'youtube' => 'https://youtube.com/',
            'github' => 'https://github.com/',
        ];

        $finalUrls = [];
        foreach ($socialMedia as $platform => $value) {
            $value = trim($value);
            if ($value === '') continue;

            if ($platform === 'youtube') {
                if (strpos($value, '@') !== 0) $value = '@' . ltrim($value, '@');
                $finalUrls[$platform] = $prefixes[$platform] . $value;
            } elseif ($platform === 'facebook' && (str_starts_with($value, 'profile.php?id=') || str_starts_with($value, 'pages/'))) {
                $finalUrls[$platform] = $prefixes[$platform] . $value;
            } elseif ($platform === 'linkedin') {
                $value = preg_replace('~^(https?://)?(www\.)?linkedin\.com/in/~', '', $value);
                $finalUrls[$platform] = $prefixes[$platform] . $value;
            } else {
                $value = ltrim($value, '@');
                $finalUrls[$platform] = $prefixes[$platform] . $value;
            }
        }

        // Log the final data to be stored
        Log::info('Final social media links to be stored:', $finalUrls);

        // Update the social media data
        $details->social_media = $finalUrls;
        $details->save();
        
        // Redirect back to the profile page with the active tab
        return Redirect::route('profile.edit', ['tab' => 'update-link-account'])->with('status', 'Social media links updated successfully.');
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
    
        return Redirect::route('profile.edit')->with('status', 'User profiles images updated successfully.');
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
            $createdCourses = Course::where('author', $user->id)->where('name', 'not like', '%test%')
                ->with(['modules', 'authorUser'])
                ->orderBy('popularity', 'desc')
                ->paginate(10)
                ->withQueryString()
                ->appends(['tab' => 'courses']);
        }

        $robot = null;
        $userRobot = robot::where('user_id', $user->id)->first();
        if ($userRobot) {
            $robotDetails = robotDetail::where('robot_id', $userRobot->id)->first();
            if ($robotDetails) {
                $robot = $robotDetails;
                $robot->robot = $userRobot; // Add the robot model for updated_at timestamp
            }
        }

        return view('profile.show', [
            'user' => $user,
            'details' => $details,
            'images' => $images,
            'accountage' => $accountage,
            'createdCourses' => $createdCourses,
            'robot' => $robot,
        ]);
    }

    public function getAllUsers(Request $request)
    {
        $query = User::select('id', 'name', 'username', 'role', 'created_at')
            ->with('details');
    
        // Apply role filter if specified
        if ($request->role) {
            $query->where('role', $request->role);
        }
    
        // Apply name sorting
        $direction = $request->get('sort', 'asc');
        $query->orderBy('name', $direction);
    
        $users = $query->paginate(12);
    
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
    
        $userQuery = User::where('name', 'LIKE', "%{$query}%")
            ->orWhere('username', 'LIKE', "%{$query}%")
            ->select('id', 'name', 'username', 'role')
            ->with('details');
    
        // Apply role filter if specified
        if ($request->role) {
            $userQuery->where('role', $request->role);
        }
    
        // Apply name sorting
        $direction = $request->get('sort', 'asc');
        $userQuery->orderBy('name', $direction);
    
        $users = $userQuery->limit(10)
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

    public function section(Request $request, string $section)
    {
        $validSections = [
            'update-profile' => 'profile.partials.update-profile-information-form',
            'update-profile-pictures' => 'profile.partials.update-profile-pictures',
            'update-link-account' => 'profile.partials.social-media-profile-information-modal',
            'update-password' => 'profile.partials.update-password-form',
            'delete-account' => 'profile.partials.delete-user-form'
        ];

        if (!array_key_exists($section, $validSections)) {
            abort(404);
        }

        return view($validSections[$section], [
            'user' => $request->user()
        ]);
    }
}