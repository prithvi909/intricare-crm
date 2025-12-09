<?php

namespace Database\Seeders;

use App\Models\Contact;
use Illuminate\Database\Seeder;

class ContactSeeder extends Seeder
{
    public function run(): void
    {
        $contacts = [
            [
                'name' => 'Rajesh Kumar',
                'email' => 'rajesh.kumar@infosys.com',
                'phone' => '+91 98765 43210',
                'gender' => 'male',
                'status' => 'active',
                'custom_field_values' => [
                    'company_name' => 'Infosys Technologies',
                    'birthday' => '1985-03-15',
                    'address' => '123 MG Road, Bangalore, Karnataka 560001',
                    'lead_source' => 'Website',
                ],
            ],
            [
                'name' => 'Priya Sharma',
                'email' => 'priya.sharma@tcs.com',
                'phone' => '+91 98765 43211',
                'gender' => 'female',
                'status' => 'active',
                'custom_field_values' => [
                    'company_name' => 'Tata Consultancy Services',
                    'birthday' => '1990-07-22',
                    'address' => '456 Bandra Kurla Complex, Mumbai, Maharashtra 400051',
                    'lead_source' => 'Referral',
                ],
            ],
            [
                'name' => 'Amit Patel',
                'email' => 'amit.patel@wipro.com',
                'phone' => '+91 98765 43212',
                'gender' => 'male',
                'status' => 'active',
                'custom_field_values' => [
                    'company_name' => 'Wipro Limited',
                    'birthday' => '1988-05-10',
                    'address' => '789 Electronic City, Bangalore, Karnataka 560100',
                    'lead_source' => 'Social Media',
                ],
            ],
            [
                'name' => 'Anjali Reddy',
                'email' => 'anjali.reddy@hcl.com',
                'phone' => '+91 98765 43213',
                'gender' => 'female',
                'status' => 'active',
                'custom_field_values' => [
                    'company_name' => 'HCL Technologies',
                    'birthday' => '1992-11-30',
                    'address' => '321 Sector 126, Noida, Uttar Pradesh 201313',
                    'lead_source' => 'Website',
                ],
            ],
            [
                'name' => 'Vikram Singh',
                'email' => 'vikram.singh@techmahindra.com',
                'phone' => '+91 98765 43214',
                'gender' => 'male',
                'status' => 'active',
                'custom_field_values' => [
                    'company_name' => 'Tech Mahindra',
                    'birthday' => '1987-09-18',
                    'address' => '654 Hinjewadi, Pune, Maharashtra 411057',
                    'lead_source' => 'Cold Call',
                ],
            ],
            [
                'name' => 'Kavita Desai',
                'email' => 'kavita.desai@accenture.com',
                'phone' => '+91 98765 43215',
                'gender' => 'female',
                'status' => 'active',
                'custom_field_values' => [
                    'company_name' => 'Accenture Solutions',
                    'birthday' => '1991-05-18',
                    'address' => '987 DLF Cyber City, Gurgaon, Haryana 122002',
                    'lead_source' => 'Website',
                ],
            ],
            [
                'name' => 'Rahul Gupta',
                'email' => 'rahul.gupta@cognizant.com',
                'phone' => '+91 98765 43216',
                'gender' => 'male',
                'status' => 'active',
                'custom_field_values' => [
                    'company_name' => 'Cognizant Technology Solutions',
                    'birthday' => '1989-12-04',
                    'address' => '147 IT Park, Chennai, Tamil Nadu 600113',
                    'lead_source' => 'Referral',
                ],
            ],
            [
                'name' => 'Sneha Iyer',
                'email' => 'sneha.iyer@capgemini.com',
                'phone' => '+91 98765 43217',
                'gender' => 'female',
                'status' => 'active',
                'custom_field_values' => [
                    'company_name' => 'Capgemini India',
                    'birthday' => '1993-08-25',
                    'address' => '258 Whitefield, Bangalore, Karnataka 560066',
                    'lead_source' => 'Social Media',
                ],
            ],
            [
                'name' => 'Arjun Nair',
                'email' => 'arjun.nair@mindtree.com',
                'phone' => '+91 98765 43218',
                'gender' => 'male',
                'status' => 'active',
                'custom_field_values' => [
                    'company_name' => 'Mindtree Limited',
                    'birthday' => '1990-02-14',
                    'address' => '369 Outer Ring Road, Bangalore, Karnataka 560103',
                    'lead_source' => 'Website',
                ],
            ],
            [
                'name' => 'Meera Joshi',
                'email' => 'meera.joshi@lntinfotech.com',
                'phone' => '+91 98765 43219',
                'gender' => 'female',
                'status' => 'active',
                'custom_field_values' => [
                    'company_name' => 'L&T Infotech',
                    'birthday' => '1992-06-20',
                    'address' => '741 Andheri East, Mumbai, Maharashtra 400069',
                    'lead_source' => 'Cold Call',
                ],
            ],
            [
                'name' => 'Suresh Menon',
                'email' => 'suresh.menon@mphasis.com',
                'phone' => '+91 98765 43220',
                'gender' => 'male',
                'status' => 'active',
                'custom_field_values' => [
                    'company_name' => 'Mphasis Limited',
                    'birthday' => '1986-04-12',
                    'address' => '852 Bagmane Tech Park, Bangalore, Karnataka 560093',
                    'lead_source' => 'Referral',
                ],
            ],
            [
                'name' => 'Divya Krishnan',
                'email' => 'divya.krishnan@zoho.com',
                'phone' => '+91 98765 43221',
                'gender' => 'female',
                'status' => 'active',
                'custom_field_values' => [
                    'company_name' => 'Zoho Corporation',
                    'birthday' => '1994-10-08',
                    'address' => '963 Estancia IT Park, Chennai, Tamil Nadu 600119',
                    'lead_source' => 'Website',
                ],
            ],
        ];

        foreach ($contacts as $contact) {
            Contact::create($contact);
        }
    }
}
