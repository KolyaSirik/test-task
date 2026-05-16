export type User = {
    id: number;
    name: string;
    email: string;
    avatar?: string;
    email_verified_at: string | null;
    created_at: string;
    updated_at: string;
    [key: string]: unknown;
};

export type Notification = {
    id: string;
    type: string;
    data: {
        domain_id: number;
        url: string;
        is_up: boolean;
        message: string;
        [key: string]: any;
    };
    read_at: string | null;
    created_at: string;
    updated_at: string;
};

export type Auth = {
    user: User;
    notifications: Notification[];
};

export type TwoFactorConfigContent = {
    title: string;
    description: string;
    buttonText: string;
};
