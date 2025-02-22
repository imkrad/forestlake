<?php

namespace App\Http\Resources;

use Crypt;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
       
        $name = $this->profile->name;
        $sex = $this->profile->gender;
        $contact_no = $this->profile->mobile;
        $profile_id = $this->profile->id;
        $avatar = ($this->profile->avatar === 'avatar.jpg') ? '/images/avatars/'.$this->profile->avatar : '/storage/'.$this->profile->avatar;
        $username = $this->username;
        $status = null;
    
        return [
            'id' => $this->id,
            'username' => $username,
            'email' => $this->email,
            'role' => $this->role,
            'avatar' => $avatar,
            'name' => $name,
            'gender' => $sex,
            'mobile' => $contact_no,
            'profile_id' => $profile_id,
            'is_active' => $this->is_active,
            'is_new' => $this->is_new,
            'two_factor_enabled' => ($this->two_factor_secret) ? true : false,
            'two_factor_confirmed' => ($this->two_factor_confirmed_at) ? true : false,
            'password_changed_at' => $this->password_changed_at,
            'password_confirmed_at' => session('auth'),
            'status' => $status, 
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
