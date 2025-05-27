<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Post;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Check if user is authenticated
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $stats = [
            'total_users' => User::count(),
            'total_posts' => Post::count(),
            'pending_posts' => Post::where('status', 'pending')->count(),
            'approved_posts' => Post::where('status', 'approved')->count(),
            'lost_items' => Post::where('type', 'lost')->where('status', 'approved')->count(),
            'found_items' => Post::where('type', 'found')->where('status', 'approved')->count(),
        ];

        $recent_posts = Post::with('user')
            ->latest()
            ->take(10)
            ->get();

        $recent_users = User::latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recent_posts', 'recent_users'));
    }
}
