<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email','phone',
        'password',"firstname", "lastname", "address1","city","state","pincode","country_id","company_id","user_type","user_role","status" 

    ];
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
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

    public static function getUserArray(){
        $user = User::where('status', '1')
		->orderBy("firstname")
		->get(['id','firstname','lastname']);	
		//dd($user);
        $userArr = array();
		foreach($user as $k => $v)
			$userArr[$v->id] = $v->firstname.' '.$v->lastname;
       return $userArr;	
    }
}
