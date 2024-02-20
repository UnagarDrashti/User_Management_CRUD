<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class UserContoller extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $users = User::where('role', '!=', 'admin')->get();
            return DataTables::of($users)
                    ->addIndexColumn()
                    ->addColumn('profile_img', function ($row) {
                        if ($row->profile_img) {
                            $imageUrl = asset('storage/profile-images/' . $row->profile_img);
                            return '<img src="' . $imageUrl . '" border="0" width="100px" height="auto" class="img-rounded" align="center" />';
                        } else {
                            return '<img src="' . asset('assets/img/default-user-image.png') . '" border="0" width="50px" height="auto" class="img-rounded" align="center" />';
                        }
                    })
                    ->addColumn('action', function($row){
                            $btn = '<a href="javascript:void(0)" id="show-user-info" data-url="'.route('user.show', $row->id).'" class="btn btn-success btn-sm"><i class="fa fa-eye"></i></a>';
                            $btn .= '<a href="'.route('user.edit', $row->id).'" title="Edit User" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i></a>';
                            $btn .= '<form style="display:inline-block" class="form-group" action="'. route('user.destroy', $row->id) .'" method="POST">
                                    ' . csrf_field() . '
                                    ' . method_field('DELETE') . '
                                    <button type="submit" class="btn btn-sm btn-danger")"><i class="fa fa-trash"></i></button>
                                </form>';
                            return $btn;
                    })
                    ->rawColumns(['profile_img','action'])
                    ->make(true);
        }
        return view('admin.user.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.user.create');
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'phone' => 'required',
            'gender' => 'required|in:male,female',
            'profile_img' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $profileImagePath = '';
        if ($request->hasFile('profile_img')) {
            $profileImagePath = $request->file('profile_img')->store('public/profile-images');
            $profile_image = basename($profileImagePath);
        }

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->gender = $request->gender;
        $user->profile_img = $profile_image;
        $user->save();

        return redirect()->route('user.index')->with('success', 'User created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $user = User::find($id);

        if ($user->profile_img) {
            $user->profile_img_url = Storage::url('profile-images/' . $user->profile_img);
        } else {
            $user->profile_img_url = asset('assets/img/default-user-image.png');
        }

        return response()->json($user);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        return view('admin.user.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'required',
            'profile_img' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'gender' => 'required|in:male,female',
        ]);

        if ($request->hasFile('profile_img')) {
            if ($request->hasFile('profile_img')) {
                $profileImagePath = $request->file('profile_img')->store('public/profile-images');
                $profile_image = basename($profileImagePath);
            }

            $user->update([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'phone' => $validatedData['phone'],
                'gender' => $validatedData['gender'],
                'profile_img' => $profile_image,
            ]);
        } else {
            $user->update([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'phone' => $validatedData['phone'],
                'gender' => $validatedData['gender'],
            ]);
        }

        return redirect()->route('user.index')->with('success', 'User updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        try {
            $user->delete();
            return redirect()->route('user.index')->with('success', 'User deleted successfully');
        } catch (\Exception $e) {
            die;
            return redirect()->route('user.index')->with('error', $e->getMessage());
        }
    }
}
