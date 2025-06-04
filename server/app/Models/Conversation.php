<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    protected $fillable = [
        'customer_id',
        'staff_id',
        'status',
        'is_read_by_customer',
        'is_read_by_staff',
        'last_message_at'
    ];

    protected $appends = ['unread_count_by_customer', 'unread_count_by_staff'];

    public function getUnreadCountByCustomerAttribute() {
        return $this->messages()->where('sender_type', 'staff')->where('is_read', false)->count();
    }

    public function getUnreadCountByStaffAttribute() {
        return $this->messages()->where('sender_type', 'customer')->where('is_read', false)->count();
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function staff()
    {
        return $this->belongsTo(User::class, 'staff_id');
    }
} 