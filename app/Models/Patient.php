<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Patient extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'dob', 'gender', 'disease_history', 'family_history', 'symptoms', 'risk_level'];

    public function calculateRisk()
    {
        $age = Carbon::parse($this->dob)->age;
        $risk = 'Rendah';

        // Logika risiko
        if ($age > 60) {
            $risk = 'Tinggi';
        } elseif ($age > 40) {
            $risk = 'Sedang';
        }

        if ($this->family_history) {
            $risk = 'Tinggi';
        }

        if (strpos($this->symptoms, 'nyeri') !== false) {
            $risk = 'Tinggi';
        }

        return $risk;
    }
}
