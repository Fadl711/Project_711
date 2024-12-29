<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;

class UserPermission extends Model
{
    use HasFactory;
    protected $table = 'user_permissions';
    protected $primaryKey = 'permission_id'; // Use the actual primary key column

    protected $fillable  = [
        'Authority_Name',
        'Readability',
        'Writing_ability',
        'Ability_modify',
        'Deletion_authority',
        'User_id',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
