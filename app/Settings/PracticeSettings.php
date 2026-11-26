<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class PracticeSettings extends Settings
{
    public string $practice_name;

    public string $tagline;

    public string $hero_badge;

    public string $hero_title;

    public string $hero_description;

    public string $hero_primary_cta;

    public string $hero_secondary_cta;

    public ?string $hero_image;

    public ?string $logo;

    public ?string $favicon;

    public string $doctor_name;

    public string $doctor_role;

    public string $doctor_bio;

    public string $doctor_notes;

    public ?string $doctor_photo;

    public ?string $sip_number;

    public string $phone;

    public string $whatsapp;

    public string $whatsapp_default_message;

    public string $email;

    public string $address;

    public string $service_area_title;

    public string $service_area_text;

    public string $maps_url;

    public string $maps_embed_url;

    public string $latitude;

    public string $longitude;

    public ?string $google_rating_text;

    public string $medical_disclaimer;

    public string $default_meta_title;

    public string $default_meta_description;

    public ?string $instagram_url;

    public ?string $facebook_url;

    public static function group(): string
    {
        return 'practice';
    }
}
