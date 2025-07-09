<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sample extends Model
{
    protected $fillable = ['lot_no', 'sample_date', 'courier_id', 'pod_no', 'no_of_samples', 'customer_id', 'company_id', 'cus_mobile', 'cus_email', 'cus_site_contact_person_id', 'cus_site_contact_mobile', 'cus_site_contact_email', 'expected_report_date', 'work_order', 'work_order_date', 'additional_info', 'site_sample_dispacted_date', 'collection_center_sample_received_date', 'collection_center_sample_collected_date', 'lab_sample_received_date', 'freight_charges'];
    //
}
