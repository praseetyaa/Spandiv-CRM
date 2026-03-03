<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Company extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'email', 'phone', 'address', 'logo'];

    protected static function booted(): void
    {
        static::creating(function (Company $company) {
            if (empty($company->slug)) {
                $company->slug = Str::slug($company->name);
            }
        });
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }
    public function leads()
    {
        return $this->hasMany(Lead::class);
    }
    public function clients()
    {
        return $this->hasMany(Client::class);
    }
    public function services()
    {
        return $this->hasMany(Service::class);
    }
    public function projects()
    {
        return $this->hasMany(Project::class);
    }
    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }
    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
    public function activities()
    {
        return $this->hasMany(Activity::class);
    }
}
