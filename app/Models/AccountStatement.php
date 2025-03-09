<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountStatement extends Model
{
    use HasFactory;

    protected $table = 'account_statement';
    protected $fillable = [
        'total_sales',
        'total_expense',
        'type', 
        'notes'
    ];
    
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
