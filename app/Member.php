<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Collections\OwnCollection;
use Illuminate\Notifications\Notifiable;
use App\Events\MemberCreated;
use App\Relations\HasSameRelation;

class Member extends Model
{
    use Notifiable;
    use HasSameRelation;

    public $fillable = ['firstname', 'lastname', 'nickname', 'other_country', 'birthday', 'joined_at', 'keepdata', 'sendnewspaper', 'address', 'further_address', 'zip', 'city', 'phone', 'mobile', 'business_phone', 'fax', 'email', 'email_parents', 'nami_id', 'active', 'letter_address'];

    public $dates = ['joined_at', 'birthday'];

    public $casts = [
        'active' => 'boolean',
        'keepdata' => 'boolean',
        'sendnewspaper' => 'boolean',
        'gender_id' => 'integer',
        'way_id' => 'integer',
        'country_id' => 'integer',
        'region_id' => 'integer',
        'confession_id' => 'integer',
        'nami_id' => 'integer',
    ];

    public function newCollection(array $models = [])
    {
        return new OwnCollection($models);
    }

    public function routeNotificationForMail()
    {
        return ($this->email_parents) ? $this->email_parents : $this->email;
    }

    //----------------------------------- Getters -----------------------------------
    public function getStrikesAttribute()
    {
        return $this->paymentsNotPaid->map(function ($p) {
            return $p->subscription->amount;
        })->sum();
    }

    public function getRealAddressAttribute()
    {
        return is_null($this->letter_address)
            ? ['Familie '.$this->lastname, $this->address, $this->zip.' '.$this->city]
            : explode("\n", $this->letter_address);
    }

    //---------------------------------- Relations ----------------------------------
    public function country()
    {
        return $this->belongsTo(\App\Country::class);
    }

    public function gender()
    {
        return $this->belongsTo(\App\Gender::class);
    }

    public function region()
    {
        return $this->belongsTo(\App\Region::class);
    }

    public function confession()
    {
        return $this->belongsTo(\App\Confession::class);
    }

    public function payments()
    {
        return $this->hasMany(\App\Payment::class)->orderBy('nr');
    }

    public function paymentsNotPaid()
    {
        return $this->payments()->whereIn('status_id', [1,2]);
    }

    public function way()
    {
        return $this->belongsTo(Way::class);
    }

    public function nationality()
    {
        return $this->belongsTo(Nationality::class);
    }

    public function memberships()
    {
        return $this->hasMany(Membership::class);
    }

    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }

    public function familyMembers()
    {
        return $this->hasSame(['lastname', 'city', 'plz', 'city']);
    }

    //----------------------------------- Scopes ------------------------------------
    public function scopeFamily($q, $member)
    {
        return $q
            ->where('lastname', $member->lastname)
            ->where('zip', $member->zip)
            ->where('city', $member->city)
            ->where('address', $member->address);
    }

    public function scopeActive($q)
    {
        return $q->where('active', true);
    }

    /**
     * Filter by members that are synched with nami id
     */
    public function scopeNami($q, $id)
    {
        return $q->where('nami_id', $id);
    }
}
