<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UserRequest;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class UserController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request): View
    {
        $this->authorize('viewAny',User::class);

        $search=$request->input('search');
        $role=$request->input('role');
        $status=$request->input('status');

        $users=User::query()
            ->when($search,function($query) use($search){
                $query->where(function($query) use($search){
                    $query->where('name','like',"%{$search}%")
                        ->orWhere('email','like',"%{$search}%");
                });
            })
            ->when($role,function($query) use($role){
                $query->where('role',$role);
            })
            ->when($status,function($query) use($status){
                $query->where('status',$status);
            })
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.users.index',compact(
            'users',
            'search',
            'role',
            'status'
        ));
    }

    public function create(): View
    {
        $this->authorize('create',User::class);

        return view('admin.users.create');
    }

    public function store(UserRequest $request): RedirectResponse
    {
        $this->authorize('create',User::class);

        $validated=$request->validated();

        $user=User::create([
            'name'=>$validated['name'],
            'slug'=>$this->generateUniqueSlug($validated['name']),
            'email'=>$validated['email'],
            'password'=>$validated['password'],
            'role'=>$validated['role'],
            'status'=>$validated['status'],
        ]);

        return redirect()
            ->route('admin.users.show',$user)
            ->with('success',"User {$user->name} created successfully.");
    }

    public function show(User $user): View
    {
        $this->authorize('view',$user);

        return view('admin.users.show',compact('user'));
    }

    public function edit(User $user): View
    {
        $this->authorize('update',$user);

        return view('admin.users.edit',compact('user'));
    }

    public function update(UserRequest $request,User $user): RedirectResponse
    {
        $this->authorize('update',$user);

        $validated=$request->validated();

        $data=[
            'name'=>$validated['name'],
            'slug'=>$this->generateUniqueSlug(
                $validated['name'],
                $user->id
            ),
            'email'=>$validated['email'],
            'role'=>$validated['role'],
            'status'=>$validated['status'],
        ];

        if(!empty($validated['password'])){
            $data['password']=$validated['password'];
        }

        $user->update($data);

        return redirect()
            ->route('admin.users.show',$user)
            ->with('success',"User {$user->name} updated successfully.");
    }

    public function destroy(User $user): RedirectResponse
    {
        $this->authorize('delete',$user);

        $name=$user->name;

        $user->delete();

        return redirect()
            ->route('admin.users.index')
            ->with('success',"User {$name} moved to trash.");
    }

    public function trash(): View
    {
        $this->authorize('viewAny',User::class);

        $users=User::onlyTrashed()
            ->latest('deleted_at')
            ->paginate(20)
            ->withQueryString();

        return view('admin.users.trash',compact('users'));
    }

    public function restore(string $user): RedirectResponse
    {
        $user=User::withTrashed()
            ->where('slug',$user)
            ->firstOrFail();

        $this->authorize('restore',$user);

        $user->restore();

        return redirect()
            ->route('admin.users.trash')
            ->with('success',"User {$user->name} restored successfully.");
    }

    public function forceDelete(string $user): RedirectResponse
    {
        $user=User::withTrashed()
            ->where('slug',$user)
            ->firstOrFail();

        $this->authorize('forceDelete',$user);

        $name=$user->name;

        $user->forceDelete();

        return redirect()
            ->route('admin.users.trash')
            ->with('success',"User {$name} permanently deleted.");
    }

    private function generateUniqueSlug(
        string $name,
        ?int $ignoreId=null
    ): string {
        $slug=Str::slug($name);

        if($slug===''){
            $slug='user';
        }

        $original=$slug;
        $counter=1;

        while(
            User::where('slug',$slug)
                ->when(
                    $ignoreId,
                    fn($query)=>$query->where('id','!=',$ignoreId)
                )
                ->exists()
        ){
            $slug=$original.'-'.$counter++;
        }

        return $slug;
    }
}