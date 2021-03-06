<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUser;
use App\Http\Requests\UpdateUserImage;
use App\User;
use Intervention\Image\Facades\Image;
use Storage;

class ProfileController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display the specified resource.
     *
     * @param  User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        $posts = $user->posts()->latest()->limit(10)->get();

        return view('profile.show', compact('posts'), compact('user'));
    }

    /**
     * Display the specified resource.
     *
     * @param  User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        return response()->json($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateUser  $request
     * @param  User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUser $request, User $user)
    {
        $user->name = $request->input('name');
        $user->surname = $request->input('surname');
        $user->phone = $request->input('phone');
        $user->birthday = $request->input('birthday');
        $user->sex = $request->input('sex');
        $user->bio = $request->input('bio');

        $user->save();

        return json_encode($user);
    }

    /**
     * Update the user's profile image.
     *
     * @param  UpdateUserImage  $request
     * @param  User  $user
     * @return \Illuminate\Http\Response
     */
    public function updateImage(UpdateUserImage $request, User $user)
    {
        // Verify if a file is present and upload it in case
        $path = $user->image_url;

        if ($request->hasFile('user-image')) {
            if ($request->file('user-image')->isValid()) {
                
                if ($path != 'profile-images/user.svg'){
                    Storage::delete($path);
                }

                $resize = Image::make($request->file('user-image'))->fit(800)->encode('jpg');
                $hash = md5($resize->__toString().$user->id);

                $path = 'profile-images/'.$hash.'.jpg';

                Storage::put($path, $resize->__toString());

                $user->image_url = $path;
                $user->save();

                $request->session()->flash('message', 'Immagine del profilo aggiornata.');
                $request->session()->flash('type', 'success');
            }
        }

        return back();
    }
}
