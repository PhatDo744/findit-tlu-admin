<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Category;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $query = Post::with(['user', 'category']);

        // Search functionality
        if ($request->has('search') && $request->search) {
            $query->search($request->search);
        }

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->byStatus($request->status);
        }

        // Filter by type
        if ($request->has('type') && $request->type) {
            $query->byType($request->type);
        }

        // Filter by category
        if ($request->category) {
            $query->where('category_id', $request->category);
        }

        // Filter by date range
        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $posts = $query->latest()->paginate(15);

        // Get categories for dropdown
        $categories = Category::active()->ordered()->get();
        return view('admin.posts.index', compact('posts', 'categories'));
    }

    public function show(Post $post)
    {
        $post->load('user');
        return view('admin.posts.show', compact('post'));
    }

    public function approve(Post $post)
    {
        $post->update(['status' => 'approved']);

        return response()->json([
            'success' => true,
            'message' => 'Bài đăng đã được duyệt thành công!'
        ]);
    }

    public function reject(Request $request, Post $post)
    {
        $request->validate([
            'reason' => 'nullable|string|max:500'
        ]);

        $post->update([
            'status' => 'rejected',
            'rejection_reason' => $request->reason
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Bài đăng đã bị từ chối!'
        ]);
    }

    public function markReturned(Post $post)
    {
        $post->update(['status' => 'returned']);

        return response()->json([
            'success' => true,
            'message' => 'Bài đăng đã được đánh dấu là đã trả/tìm thấy!'
        ]);
    }

    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:approve,reject,delete',
            'post_ids' => 'required|array',
            'post_ids.*' => 'exists:posts,id'
        ]);

        $posts = Post::whereIn('id', $request->post_ids);

        switch ($request->action) {
            case 'approve':
                $posts->update(['status' => 'approved']);
                $message = 'Các bài đăng đã được duyệt thành công!';
                break;
            case 'reject':
                $posts->update(['status' => 'rejected']);
                $message = 'Các bài đăng đã bị từ chối!';
                break;
            case 'delete':
                $posts->delete();
                $message = 'Các bài đăng đã được xóa!';
                break;
        }

        return redirect()->route('admin.posts.index')->with('success', $message);
    }
}
