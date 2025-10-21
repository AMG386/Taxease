<?php

if (!function_exists('settings')) {
    /**
     * Get GST settings for the authenticated user
     */
    function settings(string $key = null)
    {
        $profile = \App\Models\GstProfile::where('user_id', auth()->id() ?? 1)->first();
        
        if (!$profile) {
            return $key ? null : [];
        }

        $settings = [
            'gst_type' => $profile->gst_type ?? 'regular',
            'filing_frequency' => $profile->filing_frequency ?? 'monthly',
            'composition_rate' => $profile->composition_rate ?? 1.0,
            'gstin' => $profile->gstin,
            'legal_name' => $profile->legal_name,
            'trade_name' => $profile->trade_name,
            'registration_date' => $profile->registration_date,
            'state_code' => $profile->state_code,
            'address' => $profile->address,
            'pincode' => $profile->pincode,
            'mobile' => $profile->mobile,
            'email' => $profile->email,
        ];

        return $key ? ($settings[$key] ?? null) : $settings;
    }
}