<?php

if (! function_exists('isRole')) {
    function isRole(string|array $roles): bool
    {
        if (! auth()->check()) {
            return false;
        }

        $userRole = auth()->user()->role;

        if (is_string($roles)) {
            $roles = array_map('trim', explode(',', $roles));
        }

        return in_array($userRole, $roles);
    }
}