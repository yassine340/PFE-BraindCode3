<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use App\Notifications\WorkerAccountCreated;
use Illuminate\Support\Str;


class StartupController extends Controller
{
    /**
     * Display a listing of the workers for the authenticated startup.
     *
     * @return \Illuminate\Http\Response
     */
    public function listWorkers()
    {
        // Get the authenticated startup
        $startup = Auth::user();
        
        // Check if the authenticated user is a startup
        if ($startup->role !== 'startup') {
            return response()->json(['message' => 'Unauthorized. Only startups can view workers.'], 403);
        }
        
        // Get all workers associated with this startup
        $workers = User::where('role', 'worker')
                      ->where('startup_id', $startup->id)
                      ->get();
        
        return response()->json(['workers' => $workers]);
    }

    /**
     * Add a new worker for the authenticated startup.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addWorker(Request $request)
{
    // Get the authenticated startup
    $startup = Auth::user();
    
    // Check if the authenticated user is a startup
    if ($startup->role !== 'startup') {
        return response()->json(['message' => 'Unauthorized. Only startups can add workers.'], 403);
    }
    
    // Validate the request data
    $validator = Validator::make($request->all(), [
        'first_name' => 'required|string|max:255',
        'last_name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'phone' => 'nullable|string|max:20',
    ]);
    
    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 422);
    }
    
    // Generate random password
    $password = Str::random(10);
    
    // Create the worker
    $worker = User::create([
        'first_name' => $request->first_name,
        'last_name' => $request->last_name,
        'email' => $request->email,
        'password' => Hash::make($password),
        'phone' => $request->phone,
        'role' => 'worker',
        'startup_id' => $startup->id,
        'email_verified_at' => now(),
    ]);
    
    // Send email with password
    $worker->notify(new WorkerAccountCreated($password));
    
    return response()->json([
        'message' => 'Worker added successfully. Login credentials sent via email.',
        'worker' => $worker
    ], 201);
}

    /**
     * Display the specified worker.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showWorker($id)
    {
        // Get the authenticated startup
        $startup = Auth::user();
        
        // Check if the authenticated user is a startup
        if ($startup->role !== 'startup') {
            return response()->json(['message' => 'Unauthorized. Only startups can view worker details.'], 403);
        }
        
        // Get the worker and ensure they belong to this startup
        $worker = User::where('id', $id)
                     ->where('role', 'worker')
                     ->where('startup_id', $startup->id)
                     ->first();
        
        if (!$worker) {
            return response()->json(['message' => 'Worker not found or not associated with your startup.'], 404);
        }
        
        return response()->json(['worker' => $worker]);
    }

    /**
     * Update the specified worker.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateWorker(Request $request, $id)
    {
        // Get the authenticated startup
        $startup = Auth::user();
        
        // Check if the authenticated user is a startup
        if ($startup->role !== 'startup') {
            return response()->json(['message' => 'Unauthorized. Only startups can update workers.'], 403);
        }
        
        // Get the worker and ensure they belong to this startup
        $worker = User::where('id', $id)
                     ->where('role', 'worker')
                     ->where('startup_id', $startup->id)
                     ->first();
        
        if (!$worker) {
            return response()->json(['message' => 'Worker not found or not associated with your startup.'], 404);
        }
        
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'first_name' => 'sometimes|required|string|max:255',
            'last_name' => 'sometimes|required|string|max:255',
            'email' => [
                'sometimes',
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($worker->id),
            ],
            'password' => 'sometimes|required|string|min:8',
            'phone' => 'nullable|string|max:20',
        ]);
        
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        
        
        // Update worker information
        if ($request->has('first_name')) {
            $worker->first_name = $request->first_name;
        }
        
        if ($request->has('last_name')) {
            $worker->last_name = $request->last_name;
        }
        
        if ($request->has('email')) {
            $worker->email = $request->email;
        }
        
        if ($request->has('password')) {
            $worker->password = Hash::make($request->password);
        }
        
        if ($request->has('phone')) {
            $worker->phone = $request->phone;
        }
        
        $worker->save();
        
        return response()->json([
            'message' => 'Worker updated successfully',
            'worker' => $worker
        ]);
    }

    /**
     * Remove the specified worker.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteWorker($id)
    {
        // Get the authenticated startup
        $startup = Auth::user();
        
        // Check if the authenticated user is a startup
        if ($startup->role !== 'startup') {
            return response()->json(['message' => 'Unauthorized. Only startups can delete workers.'], 403);
        }
        
        // Get the worker and ensure they belong to this startup
        $worker = User::where('id', $id)
                     ->where('role', 'worker')
                     ->where('startup_id', $startup->id)
                     ->first();
        
        if (!$worker) {
            return response()->json(['message' => 'Worker not found or not associated with your startup.'], 404);
        }
        
        // Delete the worker
        $worker->delete();
        
        return response()->json(['message' => 'Worker deleted successfully']);
    }

    /**
     * Display a listing of the groups for the authenticated startup.
     *
     * @return \Illuminate\Http\Response
     */
    public function listGroups()
{
    // Get the authenticated startup
    $startup = Auth::user();
    
    // Check if the authenticated user is a startup
    if ($startup->role !== 'startup') {
        return response()->json(['message' => 'Unauthorized. Only startups can view groups.'], 403);
    }
    
    // Make sure to get the groups with their related workers
    $groups = Group::where('startup_id', $startup->id)
                   ->with('workers')
                   ->get();
    
    // Ensure we're returning the data in the format expected by the frontend
    return response()->json(['groups' => $groups]);
}

    /**
     * Store a newly created group in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    // Update the createGroup method to handle image uploads
public function createGroup(Request $request)
{
    // Get the authenticated startup
    $startup = Auth::user();
    
    // Check if the authenticated user is a startup
    if ($startup->role !== 'startup') {
        return response()->json(['message' => 'Unauthorized. Only startups can create groups.'], 403);
    }
    
    // Validate the request data
    $validator = Validator::make($request->all(), [
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Add image validation
    ]);
    
    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 422);
    }
    
    // Handle image upload if provided
    $imagePath = null;
    if ($request->hasFile('image')) {
        $imagePath = $request->file('image')->store('group_images', 'public');
    }
    
    // Create the group
    $group = Group::create([
        'name' => $request->name,
        'description' => $request->description,
        'startup_id' => $startup->id,
        'image_path' => $imagePath,
    ]);
    
    return response()->json([
        'message' => 'Group created successfully',
        'group' => $group
    ], 201);
}

// Also update the updateGroup method to handle image updates
public function updateGroup(Request $request, $id)
{
    // Get the authenticated startup
    $startup = Auth::user();
    
    // Check if the authenticated user is a startup
    if ($startup->role !== 'startup') {
        return response()->json(['message' => 'Unauthorized. Only startups can update groups.'], 403);
    }
    
    // Get the group and ensure it belongs to this startup
    $group = Group::where('id', $id)
                 ->where('startup_id', $startup->id)
                 ->first();
    
    if (!$group) {
        return response()->json(['message' => 'Group not found or not associated with your startup.'], 404);
    }
    
    // Validate the request data
    $validator = Validator::make($request->all(), [
        'name' => 'sometimes|required|string|max:255',
        'description' => 'nullable|string',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Add image validation
    ]);
    
    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 422);
    }
    
    // Update group information
    if ($request->has('name')) {
        $group->name = $request->name;
    }
    
    if ($request->has('description')) {
        $group->description = $request->description;
    }
    
    // Handle image upload if a new image is provided
    if ($request->hasFile('image')) {
        // Delete the old image if it exists
        if ($group->image_path) {
            Storage::disk('public')->delete($group->image_path);
        }
        
        // Store the new image
        $group->image_path = $request->file('image')->store('group_images', 'public');
    }
    
    $group->save();
    
    return response()->json([
        'message' => 'Group updated successfully',
        'group' => $group
    ]);
}

    /**
     * Display the specified group.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showGroup($id)
    {
        // Get the authenticated startup
        $startup = Auth::user();
        
        // Check if the authenticated user is a startup
        if ($startup->role !== 'startup') {
            return response()->json(['message' => 'Unauthorized. Only startups can view group details.'], 403);
        }
        
        // Get the group and ensure it belongs to this startup
        $group = Group::where('id', $id)
                     ->where('startup_id', $startup->id)
                     ->with('workers')
                     ->first();
        
        if (!$group) {
            return response()->json(['message' => 'Group not found or not associated with your startup.'], 404);
        }
        
        return response()->json(['group' => $group]);
    }

    /**
     * Remove the specified group from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteGroup($id)
    {
        // Get the authenticated startup
        $startup = Auth::user();
        
        // Check if the authenticated user is a startup
        if ($startup->role !== 'startup') {
            return response()->json(['message' => 'Unauthorized. Only startups can delete groups.'], 403);
        }
        
        // Get the group and ensure it belongs to this startup
        $group = Group::where('id', $id)
                     ->where('startup_id', $startup->id)
                     ->first();
        
        if (!$group) {
            return response()->json(['message' => 'Group not found or not associated with your startup.'], 404);
        }
        
        // Delete the group (this will also delete the group_worker records due to cascade)
        $group->delete();
        
        return response()->json(['message' => 'Group deleted successfully']);
    }

    /**
     * Add a worker to a group.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addWorkerToGroup(Request $request)
    {
        // Get the authenticated startup
        $startup = Auth::user();
        
        // Check if the authenticated user is a startup
        if ($startup->role !== 'startup') {
            return response()->json(['message' => 'Unauthorized. Only startups can add workers to groups.'], 403);
        }
        
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'group_id' => 'required|exists:groups,id',
            'worker_id' => 'required|exists:users,id',
        ]);
        
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        
        // Get the group and ensure it belongs to this startup
        $group = Group::where('id', $request->group_id)
                     ->where('startup_id', $startup->id)
                     ->first();
        
        if (!$group) {
            return response()->json(['message' => 'Group not found or not associated with your startup.'], 404);
        }
        
        // Get the worker and ensure they belong to this startup
        $worker = User::where('id', $request->worker_id)
                     ->where('role', 'worker')
                     ->where('startup_id', $startup->id)
                     ->first();
        
        if (!$worker) {
            return response()->json(['message' => 'Worker not found or not associated with your startup.'], 404);
        }
        
        // Check if worker is already in the group
        if ($group->workers()->where('users.id', $worker->id)->exists()) {
            return response()->json(['message' => 'Worker is already in this group.'], 422);
        }
        
        // Add worker to the group
        $group->workers()->attach($worker->id);
        
        return response()->json([
            'message' => 'Worker added to group successfully',
            'group' => $group->load('workers')
        ]);
    }

    /**
     * Remove a worker from a group.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function removeWorkerFromGroup(Request $request)
    {
        // Get the authenticated startup
        $startup = Auth::user();
        
        // Check if the authenticated user is a startup
        if ($startup->role !== 'startup') {
            return response()->json(['message' => 'Unauthorized. Only startups can remove workers from groups.'], 403);
        }
        
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'group_id' => 'required|exists:groups,id',
            'worker_id' => 'required|exists:users,id',
        ]);
        
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        
        // Get the group and ensure it belongs to this startup
        $group = Group::where('id', $request->group_id)
                     ->where('startup_id', $startup->id)
                     ->first();
        
        if (!$group) {
            return response()->json(['message' => 'Group not found or not associated with your startup.'], 404);
        }
        
        // Get the worker and ensure they belong to this startup
        $worker = User::where('id', $request->worker_id)
                     ->where('role', 'worker')
                     ->where('startup_id', $startup->id)
                     ->first();
        
        if (!$worker) {
            return response()->json(['message' => 'Worker not found or not associated with your startup.'], 404);
        }
        
        // Remove worker from the group
        $group->workers()->detach($worker->id);
        
        return response()->json([
            'message' => 'Worker removed from group successfully',
            'group' => $group->load('workers')
        ]);
    }

    /**
     * Get all workers for the authenticated startup.
     * 
     * @return \Illuminate\Http\Response
     */
    public function getWorkersForGroup()
    {
        // Get the authenticated startup
        $startup = Auth::user();
        
        // Check if the authenticated user is a startup
        if ($startup->role !== 'startup') {
            return response()->json(['message' => 'Unauthorized. Only startups can view workers.'], 403);
        }
        
        // Get all workers associated with this startup
        $workers = User::where('startup_id', $startup->id)
                      ->where('role', 'worker')
                      ->get();
        
        return response()->json(['workers' => $workers]);
    }
}