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

    public ?string $hero_image = null;

    public ?string $logo = null;

    public bool $logo_only = false;

    public ?string $favicon = null;

    public string $doctor_name;

    public string $doctor_role;

    public string $doctor_bio;

    public string $doctor_notes;

    public ?string $doctor_photo = null;

    public ?string $sip_number = null;

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

    public ?string $google_rating_text = null;

    public string $medical_disclaimer;

    public string $default_meta_title;

    public string $default_meta_description;

    public ?string $instagram_url = null;

    public ?string $facebook_url = null;

    public static function group(): string
    {
        return 'practice';
    }

    public function save(): self
    {
        $config = $this->settingsConfig();
        $this->toCollection();

        $repository = $this->getRepository();

        foreach ($config->getDefaultValueLoadedProperties() as $propertyName) {
            if (! $repository->checkIfPropertyExists(static::group(), $propertyName)) {
                $repository->createProperty(static::group(), $propertyName, $this->{$propertyName});
            }
        }

        $config->resetDefaultValueLoadedProperties();

        return parent::save();
    }
}
