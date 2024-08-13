<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    // Define the table name if it differs from the default 'expenses'
    protected $table = 'expenses';

    // Specify the primary key if it's not 'id'
    protected $primaryKey = 'id';

    // Define which attributes are mass assignable
    protected $fillable = [
        'description',
        'amount',
        'expense_date',
        'category_id',
        'user_id',
        'created_by',
        'code',  // Add this line
    ];

    // Define which attributes should be hidden for arrays
    protected $hidden = [
        // Add attributes that should be hidden, if any
    ];

    // Define which attributes should be cast to native types
    protected $casts = [
        'expense_date' => 'datetime',
    ];

    /**
     * Get the category that owns the expense.
     */


    /**
     * Get the user that owns the expense.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function contribution()
    {
        return $this->belongsTo(Contribution::class);
    }


    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
