<?php

namespace App\Services;

use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class EventService
{
  /**
   * Get all events based on user role and filters
   */
  public function getEvents(User $user, array $filters = []): LengthAwarePaginator
  {
    $query = Event::with(['creator', 'vips'])
      ->forUser($user);

    // Apply search
    if (!empty($filters['search'])) {
      $query->search($filters['search']);
    }

    // Apply filters
    if (!empty($filters['event_type'])) {
      $query->filterByType($filters['event_type']);
    }

    if (!empty($filters['status'])) {
      $query->filterByStatus($filters['status']);
    }

    if (!empty($filters['event_date_from']) || !empty($filters['event_date_to'])) {
      $query->filterByDateRange(
        $filters['event_date_from'] ?? null,
        $filters['event_date_to'] ?? null
      );
    }

    // Apply sorting
    $query->applySorting($filters['sort_by'] ?? 'created_at', $filters['sort_order'] ?? 'desc');

    return $query->paginate($filters['per_page'] ?? 15);
  }

  /**
   * Create a new event
   */
  public function createEvent(User $user, array $data): Event
  {
    $data['created_by'] = $user->id;
    $data['status'] = $data['status'] ?? 'planned';

    return Event::create($data);
  }

  /**
   * Update an existing event
   */
  public function updateEvent(Event $event, array $data): Event
  {
    $event->update($data);
    return $event->fresh();
  }

  /**
   * Delete an event
   */
  public function deleteEvent(Event $event): bool
  {
    return $event->delete();
  }

  /**
   * Get event by ID with relationships
   */
  public function getEventById(int $id): ?Event
  {
    return Event::with(['creator', 'vips', 'ascDirectives', 'ascParticipations'])
      ->find($id);
  }

  /**
   * Add VIP to event
   */
  public function addVipToEvent(Event $event, int $vipId, ?string $remarks = null): void
  {
    $event->vips()->syncWithoutDetaching([
      $vipId => ['remarks' => $remarks]
    ]);
  }

  /**
   * Remove VIP from event
   */
  public function removeVipFromEvent(Event $event, int $vipId): void
  {
    $event->vips()->detach($vipId);
  }

  /**
   * Get VIPs for an event
   */
  public function getEventVips(Event $event): Collection
  {
    return $event->vips;
  }
}
