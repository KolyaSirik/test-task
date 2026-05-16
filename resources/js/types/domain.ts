export interface Domain {
    id: number;
    user_id: number;
    url: string;
    check_interval: number;
    request_timeout: number;
    check_method: 'GET' | 'HEAD';
    is_up: boolean | null;
    last_checked_at: string | null;
    created_at: string;
    updated_at: string;
}

export interface DomainCheck {
    id: number;
    domain_id: number;
    status_code: number | null;
    response_time: number | null;
    is_successful: boolean;
    error_message: string | null;
    created_at: string;
}
