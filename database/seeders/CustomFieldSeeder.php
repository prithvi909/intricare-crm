<?php

namespace Database\Seeders;

use App\Models\CustomField;
use Illuminate\Database\Seeder;

class CustomFieldSeeder extends Seeder
{
    public function run(): void
    {
        $fields = [
            [
                'field_name' => 'Company Name',
                'field_key' => 'company_name',
                'field_type' => 'text',
                'options' => null,
                'is_active' => true,
            ],
            [
                'field_name' => 'Birthday',
                'field_key' => 'birthday',
                'field_type' => 'date',
                'options' => null,
                'is_active' => true,
            ],
            [
                'field_name' => 'Address',
                'field_key' => 'address',
                'field_type' => 'textarea',
                'options' => null,
                'is_active' => true,
            ],
            [
                'field_name' => 'Lead Source',
                'field_key' => 'lead_source',
                'field_type' => 'select',
                'options' => ['Website', 'Referral', 'Social Media', 'Cold Call', 'Other'],
                'is_active' => true,
            ],
        ];

        foreach ($fields as $field) {
            CustomField::create($field);
        }
    }
}
