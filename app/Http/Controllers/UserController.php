<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Password;
use Spatie\Permission\Models\Role;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UserExport;

class UserController extends Controller
{
    /* ===================== USERS LIST ===================== */
    public function users(Request $request)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $query = User::query();

        if (!auth()->user()->hasRole('admin')) {
            $query->where('id', auth()->id());
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%");
            });
        }

        $users = $query->latest()->get();
        $total_user = User::count();

        $loginStats = UserLog::select(
                'user_id',
                DB::raw('COUNT(*) as total_logins'),
                DB::raw('MAX(CASE WHEN logout_at IS NULL THEN 1 ELSE 0 END) as has_active_session'),
                DB::raw('MAX(login_at) as last_login_at')
            )
            ->where('action', 'login')
            ->groupBy('user_id')
            ->get()
            ->keyBy('user_id');

        return view('admin.users.users_list', compact('users', 'total_user', 'loginStats'));
    }

    /* ===================== CREATE USER ===================== */
    public function create()
    {
        $roles = Role::pluck('name', 'name')->all();
        return view('admin.users.create', compact('roles'));
    }

    public function users_store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'role'     => 'required'
        ]);

        DB::transaction(function () use ($request) {
            $user = User::create([
                'name'     => $request->name,
                'email'    => $request->email,
                'password' => Hash::make($request->password),
            ]);

            $user->syncRoles($request->role);
        });

        return redirect()->route('users');
    }

    /* ===================== DELETE USER ===================== */
    public function users_delete($user_id)
    {
        $user = User::findOrFail($user_id);

        if ($user->picture && file_exists(public_path('uploads/profile_photos/' . $user->picture))) {
            unlink(public_path('uploads/profile_photos/' . $user->picture));
        }

        $user->delete();

        return response()->json(['success' => true, 'message' => 'User deleted successfully']);
    }

    /* ===================== EDIT USER ===================== */
    public function users_edit($user_id)
    {
        $user  = User::with('roles')->findOrFail($user_id);
        $roles = Role::pluck('name', 'name')->all();

        $html = view('admin.users.partials.user_edit', compact('user', 'roles'))->render();
        return response()->json(['success' => true, 'html' => $html]);
    }

    public function users_update(Request $request, $user_id)
    {
        $user = User::findOrFail($user_id);

        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email,' . $user_id,
            'password' => 'nullable|min:8',
            'role'     => 'required',
        ]);

        $data = [
            'name'  => $request->name,
            'email' => $request->email,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        DB::transaction(function () use ($user, $data, $request) {
            $user->update($data);
            $user->syncRoles($request->role);
        });

        return response()->json(['success' => true, 'message' => 'User updated successfully']);
    }

    /* ===================== PROFILE VIEW ===================== */
    public function profile()
    {
        return view('admin.users.profile');
    }

    /* ===================== PROFILE UPDATE (AJAX) ===================== */
    public function users_profile_update(Request $request, $user_id)
    {
        $user = User::findOrFail($user_id);

        $validator = Validator::make($request->all(), [
            'name'          => 'required|string|min:2|max:255',
            'email'         => 'nullable|email|unique:users,email,' . $user_id,
            'phone'         => 'nullable|string|max:20',
            'department'    => 'nullable|string|max:100',
            'position'      => 'nullable|string|max:100',
            'location'      => 'nullable|string|max:100',
            'bio'           => 'nullable|string|max:500',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors()
            ], 422);
        }

        $data = [
            'name'       => $request->name,
            'phone'      => $request->phone,
            'department' => $request->department,
            'position'   => $request->position,
            'location'   => $request->location,
            'bio'        => $request->bio,
        ];

        if ($request->filled('email')) {
            $data['email'] = $request->email;
        }

        /* === PHOTO UPLOAD === */
        if ($request->hasFile('profile_photo')) {

            if ($user->picture && file_exists(public_path('uploads/profile_photos/' . $user->picture))) {
                unlink(public_path('uploads/profile_photos/' . $user->picture));
            }

            $file = $request->file('profile_photo');
            $filename = 'profile_' . $user_id . '_' . time() . '.' . $file->getClientOriginalExtension();

            $file->move(public_path('uploads/profile_photos'), $filename);
            $data['picture'] = $filename;
        }

        $user->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully'
        ]);
    }

    /* ===================== NAME CHANGE (NORMAL FORM) ===================== */
    public function name_change(Request $request)
    {
        $request->validate([
            'name' => 'required|string|min:2|max:255'
        ]);

        Auth::user()->update([
            'name' => $request->name
        ]);

        return back()->with('success', 'Name updated successfully');
    }

    /* ===================== PASSWORD CHANGE ===================== */
    public function password_change(Request $request)
    {
        $request->validate([
            'old_password' => 'required',
            'new_password' => [
                'required',
                Password::min(8)->letters()->mixedCase()->numbers()->symbols()
            ],
            'confirm_password' => 'required|same:new_password'
        ]);

        if (!Hash::check($request->old_password, Auth::user()->password)) {
            return back()->with('wrong', 'Current password is incorrect');
        }

        Auth::user()->update([
            'password' => Hash::make($request->new_password)
        ]);

        return back()->with('success', 'Password updated successfully');
    }

    /* ===================== PHOTO CHANGE ONLY ===================== */
    public function profile_photo_change(Request $request)
    {
        $request->validate([
            'profile_photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $user = Auth::user();

        if ($user->picture && file_exists(public_path('uploads/profile_photos/' . $user->picture))) {
            unlink(public_path('uploads/profile_photos/' . $user->picture));
        }

        $file = $request->file('profile_photo');
        $filename = 'profile_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('uploads/profile_photos'), $filename);

        $user->update(['picture' => $filename]);

        return back()->with('photo_success', 'Profile photo updated');
    }

    /* ===================== EXPORT USERS ===================== */
    public function export(Request $request)
    {
        $type = $request->type ?? 'xlsx';

        $format = match ($type) {
            'csv'  => \Maatwebsite\Excel\Excel::CSV,
            'xls'  => \Maatwebsite\Excel\Excel::XLS,
            default => \Maatwebsite\Excel\Excel::XLSX,
        };

        return Excel::download(new UserExport, "users.$type", $format);
    }
}
