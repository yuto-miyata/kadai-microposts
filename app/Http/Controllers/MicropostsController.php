<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class MicropostsController extends Controller
{
    public function index()
    {
         $data = [];
        if (\Auth::check()) {
            $user = \Auth::user();
            $microposts = $user->feed_microposts()->orderBy('created_at', 'desc')->paginate(10);
            //$favposts = $user->feed_favposts()->orderBy('created_at', 'desc')->paginate(10);

            $data = [
                'user' => $user,
                'microposts' => $microposts,
                //'favposts' => $favposts,
            ];
            $data += $this->counts($user);
            return view('users.show', $data);
        }else {
            return view('welcome');
        }
        return view('welcome', $data);
        
        /*$data = [];
        if (\Auth::check()){
            $user = \Auth::user();
            $microposts = $user->microposts()->orderBy('created_at', 'desc')->paginate(10);
            
            $data = [
                'user' => $user,
                'microposts' => $microposts,
            ];
            $data += $this->counts($user);
            return view('users.show', $data);
        }else {
            return view('welcome');
        }*/
    }
    
    public function store(Request $request)
    {
        $this->validate($request, [
            'content' => 'required|max:255',
            ]);
            
            $request->user()->microposts()->create([
                'content' => $request->content,
            ]);
            
            return redirect('/');
    }
    
    public function destroy($id)
    {
        $micropost = \App\Micropost::find($id);
        
        if (\Auth::user()->id === $micropost->user_id) {
            $micropost->delete();
        }
        
        return redirect()->back();
    }
    
    public function fav($userId)
    {
    $exist = $this->is_fav($userId);
    //$its_me = $this->id == $userId;
    if ($exist) {
        return false;
    } else {
        $this->favposts()->attach($userId);
        return true;
        }
    }

    public function unfav($userId)
    {
    $exist = $this->is_fav($userId);
    //$its_me = $this->id == $userId;

    if ($exist) {
        $this->favposts()->detach($userId);
        return true;
    } else {
        return false;
        }
    }
}
