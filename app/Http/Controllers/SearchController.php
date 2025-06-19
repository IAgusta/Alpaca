<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class SearchController extends Controller
{
    public function globalSearch(Request $request): JsonResponse
    {
        $query = $request->input('query', '');
        
        if (strlen($query) < 2) {
            return response()->json([
                'users' => [],
                'courses' => []
            ]);
        }

        $users = User::where('name', 'LIKE', "%{$query}%")
            ->orWhere('username', 'LIKE', "%{$query}%")
            ->with('details')
            ->limit(5)
            ->get()
            ->map(function ($user) {
                $avatar = $user->details && $user->details->image 
                    ? json_decode($user->details->image, true)['profile'] ?? null 
                    : null;

                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'username' => $user->username,
                    'avatar' => $avatar ? asset('storage/' . $avatar) : asset('storage/profiles/default-profile.png')
                ];
            });

        $courses = Course::where(function($q) use ($query) {
            $q->where('name', 'like', '%' . $query . '%')
            ->orWhere('description', 'like', '%' . $query . '%')
            ->orWhere('theme', 'like', '%' . $query . '%')
            ->orWhereHas('authorUser', function($q) use ($query) {
                $q->where('name', 'like', '%' . $query . '%');
            });
        })
        ->when(Auth::check() && Auth::user()->role === 'user', function ($query) {
            $query->whereRaw("LOWER(name) NOT LIKE '%test%'");
        })
        ->limit(5)
        ->get()
        ->map(function ($course) {
            return [
                'id' => $course->id,
                'name' => $course->display_name,
                'url' => route('user.course.detail', ['slug' => $course->slug, 'courseId' => $course->id])
            ];
        });

        return response()->json([
            'users' => $users,
            'courses' => $courses
        ]);
    }
}