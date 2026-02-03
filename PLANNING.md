# POL Footprint System - Planning Document

## System Overview

The POL Footprint system manages events, VIPs, and ASC participation with three distinct user roles: Superadmin, POL Admin, and VIP Users.

---

## User Roles & Permissions

### 1. Superadmin

- **Full system access**
- View all events created by any POL admin
- Manage POL admins
- Access all VIP records
- View all ASC directives and participation records
- System configuration and settings

### 2. POL Admin

- Create new events (POL Deployment or W ASC Deployment)
- View/edit only events they created
- Check if VIP exists in the system
- Add VIPs to their events
- Add ASC directives to their events
- Record ASC participation for their events
- Cannot see other POL admins' events

### 3. VIP (Not a user role - data entity only)

- VIPs are contact records, not system users
- No login/authentication
- Completely managed by POL admins and superadmins
- Just basic contact information stored in separate `vips` table

---

## Database Schema

### Users Table

```
users
- id (PK)
- name
- email (unique)
- password
- role (enum: 'superadmin', 'pol_admin')
- email_verified_at
- remember_token
- timestamps
```

### VIPs Table

```
vips
- id (PK)
- first_name
- last_name
- contact_number
- email (nullable)
- birth_date
- created_by (FK: users.id)
- timestamps
- soft_deletes
```

### Events Table

```
events
- id (PK)
- title
- event_type (enum: 'pol_deployment', 'w_asc_deployment')
- description (text, nullable)
- event_date
- location
- created_by (FK: users.id) - POL admin who created it
- status (enum: 'planned', 'ongoing', 'completed', 'cancelled')
- timestamps
- soft_deletes
```

### Event_VIPs Table (Pivot)

```
event_vips
- id (PK)
- event_id (FK: events.id)
- vip_id (FK: vips.id)
- remarks (text, nullable)
- timestamps
```

### ASC_Directives Table

```
asc_directives
- id (PK)
- event_id (FK: events.id)
- directive_text (text)
- issued_by
- issued_date
- created_by (FK: users.id)
- timestamps
```

### ASC_Participations Table

```
asc_participations
- id (PK)
- event_id (FK: events.id)
- participation_details (text)
- personnel_count (integer, nullable)
- resources_deployed (text, nullable)
- remarks (text, nullable)
- created_by (FK: users.id)
- timestamps
```

---

## API Endpoints Structure

### Authentication

```
POST   /api/v1/auth/login
POST   /api/v1/auth/logout
GET    /api/v1/auth/me
POST   /api/v1/auth/refresh
```

### Events

```
GET    /api/v1/events                    # List (filtered by role)
POST   /api/v1/events                    # Create (POL Admin only)
GET    /api/v1/events/{id}               # Show (role-based access)
PUT    /api/v1/events/{id}               # Update (creator only)
DELETE /api/v1/events/{id}               # Delete (creator only)
GET    /api/v1/events/{id}/vips          # List VIPs for event
POST   /api/v1/events/{id}/vips          # Add VIP to event
DELETE /api/v1/events/{id}/vips/{vipId}  # Remove VIP from event
```

### VIPs

```
GET    /api/v1/vips                      # List all VIPs
POST   /api/v1/vips                      # Create VIP
GET    /api/v1/vips/{id}                 # Show VIP details
PUT    /api/v1/vips/{id}                 # Update VIP
DELETE /api/v1/vips/{id}                 # Delete VIP
GET    /api/v1/vips/check-exists         # Check if VIP exists (by name/contact)
```

### ASC Directives

```
GET    /api/v1/events/{eventId}/asc-directives        # List directives
POST   /api/v1/events/{eventId}/asc-directives        # Create directive
GET    /api/v1/asc-directives/{id}                    # Show directive
PUT    /api/v1/asc-directives/{id}                    # Update directive
DELETE /api/v1/asc-directives/{id}                    # Delete directive
```

### ASC Participation

```
GET    /api/v1/events/{eventId}/asc-participation     # List participation
POST   /api/v1/events/{eventId}/asc-participation     # Create participation
GET    /api/v1/asc-participation/{id}                 # Show participation
PUT    /api/v1/asc-participation/{id}                 # Update participation
DELETE /api/v1/asc-participation/{id}                 # Delete participation
```

### Admin Management (Superadmin only)

```
GET    /api/v1/admins                    # List POL admins
POST   /api/v1/admins                    # Create POL admin
GET    /api/v1/admins/{id}               # Show admin
PUT    /api/v1/admins/{id}               # Update admin
DELETE /api/v1/admins/{id}               # Delete admin
```

---

## Feature Breakdown

### Phase 1: Core System

- [ ] User authentication (Laravel Sanctum)
- [ ] Role-based access control (Policies)
- [ ] User management for superadmin
- [ ] VIP CRUD operations
- [ ] Basic event management

### Phase 2: Event Management

- [ ] Event creation (POL/W ASC deployment types)
- [ ] Event-VIP associations
- [ ] Event filtering by creator (POL Admin)
- [ ] Event overview for superadmin

### Phase 3: ASC Features

- [ ] ASC Directives management
- [ ] ASC Participation tracking
- [ ] Linking directives and participation to events

### Phase 4: Enhanced Features

- [ ] VIP existence check (search functionality)
- [ ] Event status workflow
- [ ] Reporting and analytics
- [ ] Export functionality

---

## Business Rules

### Authorization Rules

1. **Superadmin**
    - Can perform any action on any resource
    - Can see all events across all POL admins

2. **POL Admin**
    - Can only view/edit events they created
    - Can create VIPs and check if VIP exists
    - Can manage ASC directives and participation for their own events
    - Cannot access other POL admins' events

3. **VIP Users**
    - No direct system access (managed by admins)
    - May have future read-only portal

### Validation Rules

- VIP contact information should be validated
- Event dates must be valid
- Event type must be either 'pol_deployment' or 'w_asc_deployment'
- Required fields must be enforced at FormRequest level

### Data Access Rules

- All queries must respect role-based filtering
- Use Laravel Policies for authorization
- Implement Query Scopes for role-based data filtering
    - `Event::forUser($user)` - returns events user can access
    - `Event::byCreator($userId)` - filters by creator

---

## Security Considerations

1. **Authentication**
    - Use Laravel Sanctum for API token authentication
    - Implement token expiration
    - Secure password requirements

2. **Authorization**
    - Implement Laravel Policies for each model
    - Use middleware for role checking
    - Apply policies in controllers

3. **Data Protection**
    - Sanitize all inputs
    - Use FormRequest validation
    - Never expose sensitive data in responses
    - Implement soft deletes for audit trail

---

## Standard Response Format

All API responses follow this structure:

### Success Response

```json
{
    "success": true,
    "message": "Operation successful",
    "data": {
        // Response data here
    }
}
```

### Error Response

```json
{
    "success": false,
    "message": "Error description",
    "errors": {
        // Validation errors if applicable
    }
}
```

---

## Implementation Priority

### Sprint 1: Foundation (Week 1)

1. Database migrations
2. Models and relationships
3. Seeders for testing
4. Authentication setup

### Sprint 2: Core Features (Week 2)

1. User management (superadmin)
2. VIP CRUD
3. Basic event CRUD
4. Role-based access control

### Sprint 3: Event Features (Week 3)

1. Event-VIP associations
2. VIP existence check
3. Event filtering by role
4. Event type handling

### Sprint 4: ASC Features (Week 4)

1. ASC Directives CRUD
2. ASC Participation CRUD
3. Event-ASC associations
4. Complete testing

---

## Testing Strategy

1. **Unit Tests**
    - Model relationships
    - Business logic in services
    - Validation rules

2. **Feature Tests**
    - API endpoint responses
    - Authorization checks
    - Role-based access

3. **Integration Tests**
    - Complete user workflows
    - Multi-step operations
    - Edge cases

---

## Notes

- All dates should be stored in UTC
- Use soft deletes for data integrity
- Implement comprehensive logging for audit
- Consider pagination for list endpoints (default: 15 items per page)
- Add search/filter capabilities to list endpoints
- Use Laravel Resources for consistent API responses
