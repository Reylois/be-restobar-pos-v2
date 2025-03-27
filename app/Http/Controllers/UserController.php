<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
    * Display a listing of the resource.
    */
    public function index(Request $request)
    {
        // Use the paginate method to paginate users, with a default of 10 users per page
        $users = User::paginate(10);

        // Return the paginated response
        return response()->json($users);
    }
}