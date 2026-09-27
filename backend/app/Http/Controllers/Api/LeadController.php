<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLeadRequest;
use App\Http\Requests\UpdateLeadRequest;
use App\Http\Resources\LeadResource;
use App\Models\Lead;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LeadController extends Controller
{
    /**
     * Display a listing of leads with search & pagination.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Lead::with(['assignedUser', 'customer']);

        // Search by name, email, or company
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('company', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        // Filter by source
        if ($source = $request->input('source')) {
            $query->where('source', $source);
        }

        // Filter by assigned user
        if ($assignedTo = $request->input('assigned_to')) {
            $query->where('assigned_to', $assignedTo);
        }

        $perPage = (int) $request->input('per_page', 10);
        $leads = $query->latest()->paginate($perPage);

        return response()->json([
            'data' => LeadResource::collection($leads->items()),
            'meta' => [
                'current_page' => $leads->currentPage(),
                'last_page' => $leads->lastPage(),
                'per_page' => $leads->perPage(),
                'total' => $leads->total(),
            ],
        ]);
    }

    /**
     * Store a newly created lead in storage.
     */
    public function store(StoreLeadRequest $request): JsonResponse
    {
        $lead = Lead::create($request->validated());
        $lead->load(['assignedUser', 'customer']);

        return response()->json([
            'message' => 'Lead created successfully.',
            'data' => new LeadResource($lead),
        ], 201);
    }

    /**
     * Display the specified lead.
     */
    public function show(Lead $lead): JsonResponse
    {
        $lead->load(['assignedUser', 'customer']);

        return response()->json([
            'data' => new LeadResource($lead),
        ]);
    }

    /**
     * Update the specified lead in storage.
     */
    public function update(UpdateLeadRequest $request, Lead $lead): JsonResponse
    {
        $lead->update($request->validated());
        $lead->load(['assignedUser', 'customer']);

        return response()->json([
            'message' => 'Lead updated successfully.',
            'data' => new LeadResource($lead),
        ]);
    }

    /**
     * Remove the specified lead from storage (Admin only).
     */
    public function destroy(Request $request, Lead $lead): JsonResponse
    {
        if (! $request->user()->isAdmin()) {
            return response()->json([
                'message' => 'Unauthorized. Only Admins can delete leads.',
            ], 403);
        }

        $lead->delete();

        return response()->json([
            'message' => 'Lead deleted successfully.',
        ]);
    }
}
