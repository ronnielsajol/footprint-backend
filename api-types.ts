/**
 * TypeScript Type Definitions for Footprint Backend API
 *
 * Copy this file to your Next.js project's types directory
 * Usage: import { PolDeployment, CreatePolDeploymentPayload, etc. } from '@/types/api-types'
 */

// ============================================================================
// STANDARD API RESPONSE
// ============================================================================

export interface ApiResponse<T = any> {
    success: boolean;
    message: string;
    data: T | null;
    errors?: Record<string, string[]>; // Only present in validation errors
}

export interface ValidationError {
    success: false;
    message: string;
    errors: Record<string, string[]>;
}

// ============================================================================
// PAGINATION
// ============================================================================

export interface PaginatedResponse<T> {
    data: T[];
    links: {
        first: string | null;
        last: string | null;
        prev: string | null;
        next: string | null;
    };
    meta: {
        current_page: number;
        from: number | null;
        last_page: number;
        links: Array<{
            url: string | null;
            label: string;
            active: boolean;
        }>;
        path: string;
        per_page: number;
        to: number | null;
        total: number;
    };
}

// ============================================================================
// USER TYPES
// ============================================================================

export type UserRole = "superadmin" | "pol_admin";

export interface User {
    id: number;
    name: string;
    email: string;
    role: UserRole;
    created_at: string; // ISO 8601 format
    updated_at: string; // ISO 8601 format
}

export interface UserWithAuth extends User {
    email_verified_at: string | null;
}

export interface UserCreator {
    id: number;
    name: string;
}

// ============================================================================
// AUTHENTICATION
// ============================================================================

export interface LoginPayload {
    email: string;
    password: string;
}

export interface LoginResponse {
    user: {
        id: number;
        name: string;
        email: string;
        role: UserRole;
    };
    token: string;
}

export interface RefreshTokenResponse {
    user: {
        id: number;
        name: string;
        email: string;
        role: UserRole;
    };
    token: string;
}

// ============================================================================
// VIP TYPES
// ============================================================================

export interface Vip {
    id: number;
    first_name: string;
    last_name: string;
    full_name: string;
    contact_number: string;
    email: string | null;
    birth_date: string; // YYYY-MM-DD format
    created_by: UserCreator;
    events_count?: number;
    pivot?: {
        remarks: string | null;
    };
    created_at: string; // ISO 8601 format
    updated_at: string; // ISO 8601 format
}

export interface CreateVipPayload {
    first_name: string;
    last_name: string;
    contact_number: string;
    email?: string;
    birth_date: string; // YYYY-MM-DD, must be before today
}

export interface UpdateVipPayload extends Partial<CreateVipPayload> {}

export interface CheckVipExistsParams {
    first_name: string;
    last_name: string;
    birth_date: string; // YYYY-MM-DD
}

export interface CheckVipExistsResponse {
    exists: boolean;
    vip: Vip | null;
}

export interface VipFilters {
    search?: string; // Searches in name, email, contact_number
    per_page?: number;
}

// ============================================================================
// POL DEPLOYMENT TYPES
// ============================================================================

export type SourceType =
    | "TESDA"
    | "DSWD-AICS"
    | "DOLE-DILP"
    | "DOLE-TUPAD"
    | "DOH-MAIFIP"
    | "Private";
export type AscType = "virtual" | "actual";

export interface PolDeployment {
    id: number;
    event_name: string;
    exact_venue: string;
    lgu: string | null;
    barangay: string | null;
    region: string | null;
    district: string | null;
    province: string | null;
    deployment_month: number; // 1-12
    deployment_year: number;
    turnover_date: string | null; // YYYY-MM-DD
    pol_officer: string | null;
    category: string | null;
    asc_type: AscType | null;
    llc: string | null;
    psc: string | null;
    proponent: string | null;
    sector_recipient: string | null;
    count: number | null;
    unit: string | null;
    donation_summary: string | null;
    amount: number | null;
    source: SourceType | null;
    remarks: string | null;
    created_by: number;
    created_at: string; // ISO 8601
    updated_at: string; // ISO 8601

    // Relationships (conditionally loaded)
    creator?: User;
    vips?: Vip[];
    asc_directives?: AscDirective[];
    asc_participations?: AscParticipation[];
}

export interface CreatePolDeploymentPayload {
    event_name: string;
    exact_venue: string;
    lgu?: string;
    barangay?: string;
    region?: string;
    district?: string;
    province?: string;
    deployment_month: number; // 1-12
    deployment_year: number; // 2020-2100
    turnover_date?: string; // YYYY-MM-DD
    pol_officer?: string;
    category?: string;
    asc_type?: AscType;
    llc?: string;
    psc?: string;
    proponent?: string;
    sector_recipient?: string;
    count?: number; // min: 0
    unit?: string;
    donation_summary?: string;
    amount?: number; // min: 0
    source?: SourceType;
    remarks?: string;
}

export interface UpdatePolDeploymentPayload extends Partial<CreatePolDeploymentPayload> {}

export interface PolDeploymentFilters {
    search?: string;
    year?: number;
    month?: number; // 1-12
    source?: SourceType;
    category?: string;
    asc_type?: AscType;
    sort_by?:
        | "deployment_month"
        | "deployment_year"
        | "event_name"
        | "created_at";
    sort_order?: "asc" | "desc";
    per_page?: number;
}

export interface AddVipToPolDeploymentPayload {
    vip_id: number;
    remarks?: string;
}

// ============================================================================
// W ASC DEPLOYMENT TYPES
// ============================================================================

export type SectorType = "PTK" | "Kababaihan" | "MSMEs" | "Youth" | "BHW";

export interface WAscDeployment {
    id: number;
    exact_venue: string;
    barangay: string | null;
    city_municipality: string | null;
    region: string | null;
    district: string | null;
    province: string | null;
    deployment_month: number; // 1-12
    deployment_year: number;
    exact_date: string; // YYYY-MM-DD
    event_tagging: string | null;
    has_socials: boolean;
    has_sortie: boolean;
    asc_attended: boolean;
    llc_attended: boolean;
    psc_attended: boolean;
    pol_activities: string[] | null;
    sector: SectorType | null;
    remarks: string | null;
    created_by: number;
    created_at: string; // ISO 8601
    updated_at: string; // ISO 8601

    // Relationships (conditionally loaded)
    creator?: User;
    officers?: WAscDeploymentOfficer[];
    vips?: Vip[];
    asc_directives?: AscDirective[];
    asc_participations?: AscParticipation[];
}

export interface WAscDeploymentOfficer {
    id: number;
    w_asc_deployment_id: number;
    name: string;
    rank: string | null;
    position: string | null;
    unit: string | null;
    created_at: string; // ISO 8601
    updated_at: string; // ISO 8601
}

export interface CreateWAscDeploymentPayload {
    exact_venue: string;
    barangay?: string;
    city_municipality?: string;
    region?: string;
    district?: string;
    province?: string;
    deployment_month: number; // 1-12
    deployment_year: number; // 2020-2100
    exact_date: string; // YYYY-MM-DD
    event_tagging?: string;
    has_socials?: boolean;
    has_sortie?: boolean;
    asc_attended?: boolean;
    llc_attended?: boolean;
    psc_attended?: boolean;
    pol_activities?: string[]; // max 500 chars per item
    sector?: SectorType;
    remarks?: string;
}

export interface UpdateWAscDeploymentPayload extends Partial<CreateWAscDeploymentPayload> {}

export interface WAscDeploymentFilters {
    search?: string;
    year?: number;
    month?: number;
    sector?: SectorType;
    sort_by?:
        | "deployment_month"
        | "deployment_year"
        | "exact_date"
        | "created_at";
    sort_order?: "asc" | "desc";
    per_page?: number;
}

export interface AddOfficerPayload {
    name: string;
    rank?: string;
    position?: string;
    unit?: string;
}

export interface UpdateOfficerPayload extends Partial<AddOfficerPayload> {}

export interface AddVipToWAscDeploymentPayload {
    vip_id: number;
    remarks?: string;
}

// ============================================================================
// ASC DIRECTIVE TYPES
// ============================================================================

export type DeploymentType = "pol-deployment" | "w-asc-deployment";

export interface AscDirective {
    id: number;
    event_id: number; // Polymorphic - can be POL or W ASC deployment
    directive_text: string;
    issued_by: string;
    issued_date: string; // YYYY-MM-DD
    created_by: UserCreator;
    created_at: string; // ISO 8601
    updated_at: string; // ISO 8601
}

export interface CreateAscDirectivePayload {
    event_id: number;
    directive_text: string;
    issued_by: string;
    issued_date: string; // YYYY-MM-DD
}

export interface UpdateAscDirectivePayload extends Partial<CreateAscDirectivePayload> {}

// ============================================================================
// ASC PARTICIPATION TYPES
// ============================================================================

export interface AscParticipation {
    id: number;
    event_id: number; // Polymorphic - can be POL or W ASC deployment
    participant_name: string;
    participant_role: string;
    participation_date: string; // YYYY-MM-DD
    remarks: string | null;
    created_by: UserCreator;
    created_at: string; // ISO 8601
    updated_at: string; // ISO 8601
}

export interface CreateAscParticipationPayload {
    event_id: number;
    participant_name: string;
    participant_role: string;
    participation_date: string; // YYYY-MM-DD
    remarks?: string;
}

export interface UpdateAscParticipationPayload extends Partial<CreateAscParticipationPayload> {}

// ============================================================================
// ADMIN MANAGEMENT TYPES
// ============================================================================

export interface CreateAdminPayload {
    name: string;
    email: string;
    password: string;
    password_confirmation: string;
}

export interface UpdateAdminPayload {
    name?: string;
    email?: string;
    password?: string;
    password_confirmation?: string;
}

export interface AdminFilters {
    per_page?: number;
}

// ============================================================================
// TYPE GUARDS (OPTIONAL UTILITY FUNCTIONS)
// ============================================================================

export function isSuccessResponse<T>(
    response: ApiResponse<T>,
): response is ApiResponse<T> & { success: true } {
    return response.success === true;
}

export function isErrorResponse(
    response: ApiResponse<any>,
): response is ValidationError {
    return response.success === false;
}

export function isPaginatedResponse<T>(
    data: any,
): data is PaginatedResponse<T> {
    return (
        data &&
        Array.isArray(data.data) &&
        data.meta &&
        typeof data.meta.current_page === "number"
    );
}
