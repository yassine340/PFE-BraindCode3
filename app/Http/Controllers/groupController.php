<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class GroupController extends Controller
{
    /**
     * Display a listing of the groups for the authenticated startup.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Get the authenticated startup
        $startup = Auth::user();
        
        // Check if the authenticated user is a startup
        if ($startup->role !== 'startup') {
            return response()->json(['message' => 'Unauthorized. Only startups can view groups.'], 403);
        }
        
        // Get all groups associated with this startup
        $groups = Group::where('startup_id', $startup->id)
                      ->with('workers')
                      ->get();
        
        return response()->json(['groups' => $groups]);
    }

    /**
     * Store a newly created group in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
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
        ]);
        
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        
        // Create the group
        $group = Group::create([
            'name' => $request->name,
            'description' => $request->description,
            'startup_id' => $startup->id,
        ]);
        
        return response()->json([
            'message' => 'Group created successfully',
            'group' => $group
        ], 201);
    }

    /**
     * Display the specified group.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
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
     * Update the specified group in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
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
        
        $group->save();
        
        return response()->json([
            'message' => 'Group updated successfully',
            'group' => $group
        ]);
    }

    /**
     * Remove the specified group from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
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
    public function addWorker(Request $request)
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
    public function removeWorker(Request $request)
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
    public function getWorkers()
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