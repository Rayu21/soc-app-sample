<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use App\Models\Follow;
use Illuminate\Http\Request;
use App\Events\OurExampleEvent;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\View;
use Intervention\Image\ImageManager;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;



class UserController extends Controller
{
    public function createuser(Request $request){

        $newuser = $request->validate([

            'username' => ['required', 'max:10', 'min:5', Rule::unique('users', 'username')],
            'email' => ['required', 'email', Rule::unique('users', 'email')],
            'password' => ['required', 'min:8', 'confirmed']

        ]);

        $newuser['password'] = bcrypt($newuser['password']);

        $user = User::create($newuser);
        auth()->login($user);
        return redirect('/')->with('success', 'Welcome New User!');
    }




    public function login(Request $request){
        $Incommingdata = $request->validate([
            'loginusername' => 'required',
            'loginpassword' =>'required'
        ]);

        if (auth()->attempt(['username' => $Incommingdata['loginusername'], 'password' => $Incommingdata['loginpassword']])){
            $request->session()->regenerate();
            return redirect('/')->with('success', 'Welcome ' . auth()->user()->username. '!');
        }else{
            return redirect('/')->with('failure', 'Invalid Credentials');
        }


    }

    public function logout(){
        auth()->logout();
        return redirect('/')->with('success', 'Successfully logged out!');
    }


    public function correctHomepage()
    {
        if (auth()->check()) {
            $user = User::find(auth()->id());
            $posts = $user->feedPosts()->latest()->get();
            return view('homepage-feed', compact('posts'));
        } else {
            return view('homepage');
        }
    }


///Profile///

    private function getSharedData($user){
        $currentlyFollowing = 0;

        if(auth()->check()){
            $currentlyFollowing = Follow::where([['user_id', '=', auth()->user()->id],['followeduser', '=', $user->id]])->count();

        }

        View::share('sharedData', ['currentlyFollowing' => $currentlyFollowing, 'avatar' => $user->avatar, 'username' => $user->username, 'postCount' => $user->posts()->count(), 'followingCount' => $user->followingTheseuser()->count(), 'followerCount' => $user->followers()->count()]);
    }




    public function myProfile(User $user){
        $this->getSharedData($user);
        return view('profile-user', ['posts' => $user->posts()->latest()->paginate(5)]);
    }


    public function profileRaw(User $user) {
        return response()->json(['theHTML' => view('profile-user-raw', ['posts' => $user->posts()->latest()->paginate(5)])->render(), 'docTitle' => $user->username . "'s Profile"]);
    }


    public function myfollowers(User $user){
        $this->getSharedData($user);
        return view('profile-followers', ['followers' => $user->followers()->latest()->get()]);
    }

    public function profileFollowersRaw(User $user) {
        return response()->json(['theHTML' => view('profile-followers-raw', ['followers' => $user->followers()->latest()->get()])->render(), 'docTitle' => $user->username . "'s Followers"]);
    }


    public function myFollowing(User $user){
        $this->getSharedData($user);
        return view('profile-following', ['following' => $user->followingTheseuser()->latest()->get()]);
    }

    public function profileFollowingRaw(User $user) {
        return response()->json(['theHTML' => view('profile-following-raw', ['following' => $user->followingTheseuser()->latest()->get()])->render(), 'docTitle' => 'Who ' . $user->username . " Follows"]);
    }






    public function showAvatarForm(){
        return view('avatar-form');
    }


    public function storeAvatar(Request $request){
        $request->validate([
            'avatar' => 'required|image|min:15|max:4000'
        ]);

        $user = User::find(auth()->id());
        $filename = $user->id . '-' . uniqid() . '.jpg';

        $manager = new ImageManager(new Driver());
        $image = $manager->read($request->file("avatar"));
        $imgData = $image->cover(120, 120)->toJpeg();
        Storage::put("public/avatar/" .$filename, $imgData );

        $oldAvatar = $user->avatar;
        $user->update(['avatar' => $filename]);

        if ($oldAvatar != "/fallback-avatar.jpg"){
            Storage::delete(str_replace("/storage/","public/" ,$oldAvatar));
        }

        return back()->with('success', 'Congrats on the new avatar.');
    }

}
