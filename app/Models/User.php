<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;


class User extends Authenticatable
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

   
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    
    public function permissions()
    {
        return $this->hasMany(UserPermission::class);
    }
    /**
     * التحقق من وجود صلاحية معينة.
     */
    public function hasPermission($ability)
    {
        return $this->permissions->where('Authority_Name', $ability)->isNotEmpty();
    }

    /**
     * التحقق من إذن القراءة.
     */
    public function canRead($ability)
    {
        $permission = $this->permissions->where('Authority_Name', $ability)->first();
        return $permission ? $permission->Readability : false;
    }

    /**
     * التحقق من إذن الكتابة.
     */
    public function canWrite($ability)
    {
        $permission = $this->permissions->where('Authority_Name', $ability)->first();
        return $permission ? $permission->Writing_ability : false;
    }

    /**
     * التحقق من إذن الحذف.
     */
    public function canDelete($ability)
    {
        $permission = $this->permissions->where('Authority_Name', $ability)->first();
        return $permission ? $permission->Deletion_authority : false;
    }

    /**
     * التحقق من إذن التعديل.
     */
    public function canModify($ability)
    {
        $permission = $this->permissions->where('Authority_Name', $ability)->first();
        return $permission ? $permission->Ability_modify : false;
    }

}
