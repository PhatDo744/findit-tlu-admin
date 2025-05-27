<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AccountController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'user'); // Only show mobile app users, not admins

        // Search functionality
        if ($request->has('search') && $request->search) {
            $query->search($request->search);
        }

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->byStatus($request->status);
        }

        $users = $query->latest()->paginate(15);

        return view('admin.users.index', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',

            'status' => 'required|in:active,locked',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user', // Always create as mobile app user
            'status' => $request->status,
            'email_verified_at' => now(),
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Tài khoản người dùng đã được tạo thành công!');
    }

    public function edit(User $user)
    {
        // Only allow editing mobile app users
        if ($user->isAdmin()) {
            return response()->json(['error' => 'Không thể chỉnh sửa tài khoản admin'], 403);
        }

        return response()->json($user);
    }

    public function update(Request $request, User $user)
    {
        // Only allow updating mobile app users
        if ($user->isAdmin()) {
            return redirect()->route('admin.users.index')->with('error', 'Không thể chỉnh sửa tài khoản admin!');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:6',
            'status' => 'required|in:active,locked',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'status' => $request->status,
        ];

        if ($request->password) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')->with('success', 'Tài khoản người dùng đã được cập nhật thành công!');
    }

    public function destroy(User $user)
    {
        // Only allow deleting mobile app users
        if ($user->isAdmin()) {
            return redirect()->route('admin.users.index')->with('error', 'Không thể xóa tài khoản admin!');
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'Tài khoản người dùng đã được xóa thành công!');
    }

    public function toggleStatus(User $user)
    {
        // Only allow toggling status of mobile app users
        if ($user->isAdmin()) {
            return redirect()->route('admin.users.index')->with('error', 'Không thể thay đổi trạng thái tài khoản admin!');
        }

        $user->update([
            'status' => $user->status === 'active' ? 'locked' : 'active'
        ]);

        $message = $user->status === 'active' ? 'Tài khoản đã được mở khóa!' : 'Tài khoản đã được khóa!';

        return redirect()->route('admin.users.index')->with('success', $message);
    }
}
