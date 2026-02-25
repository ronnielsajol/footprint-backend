<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddVipToEventRequest;
use App\Http\Requests\StoreEventRequest;
use App\Http\Requests\UpdateEventRequest;
use App\Http\Resources\EventResource;
use App\Http\Resources\VipResource;
use App\Models\Event;
use App\Services\EventService;
use App\Traits\ApiResponse;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EventController extends Controller
{
    use ApiResponse, AuthorizesRequests;

    protected EventService $eventService;

    public function __construct(EventService $eventService)
    {
        $this->eventService = $eventService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $this->authorize('viewAny', Event::class);

        $filters = $request->only([
            'search',
            'event_type',
            'status',
            'event_date_from',
            'event_date_to',
            'sort_by',
            'sort_order',
            'per_page'
        ]);

        /** @var \App\Models\User $user */
        $user = $request->user();
        $events = $this->eventService->getEvents($user, $filters);

        return $this->successResponse(
            EventResource::collection($events)->response()->getData(true),
            'Events retrieved successfully'
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEventRequest $request): JsonResponse
    {
        $this->authorize('create', Event::class);

        /** @var \App\Models\User $user */
        $user = $request->user();
        $event = $this->eventService->createEvent($user, $request->validated());

        return $this->successResponse(
            new EventResource($event),
            'Event created successfully',
            201
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event): JsonResponse
    {
        $this->authorize('view', $event);

        $event = $this->eventService->getEventById($event->id);

        if (!$event) {
            return $this->notFoundResponse('Event not found');
        }

        return $this->successResponse(
            new EventResource($event),
            'Event retrieved successfully'
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEventRequest $request, Event $event): JsonResponse
    {
        $this->authorize('update', $event);

        $updatedEvent = $this->eventService->updateEvent($event, $request->validated());

        return $this->successResponse(
            new EventResource($updatedEvent),
            'Event updated successfully'
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event): JsonResponse
    {
        $this->authorize('delete', $event);

        $this->eventService->deleteEvent($event);

        return $this->successResponse(
            null,
            'Event deleted successfully'
        );
    }

    /**
     * Get VIPs associated with the event
     */
    public function getVips(Event $event): JsonResponse
    {
        $this->authorize('view', $event);

        $vips = $this->eventService->getEventVips($event);

        return $this->successResponse(
            VipResource::collection($vips),
            'Event VIPs retrieved successfully'
        );
    }

    /**
     * Add VIP to event
     */
    public function addVip(AddVipToEventRequest $request, Event $event): JsonResponse
    {
        $this->authorize('update', $event);

        $this->eventService->addVipToEvent(
            $event,
            $request->vip_id,
            $request->remarks
        );

        return $this->successResponse(
            null,
            'VIP added to event successfully'
        );
    }

    /**
     * Remove VIP from event
     */
    public function removeVip(Event $event, int $vipId): JsonResponse
    {
        $this->authorize('update', $event);

        $this->eventService->removeVipFromEvent($event, $vipId);

        return $this->successResponse(
            null,
            'VIP removed from event successfully'
        );
    }
}
