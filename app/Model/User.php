<?php

namespace App\Model;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;

class User extends Authenticatable
{
    const NOTI_SHARE_LESSON = 1;

    const TYPE_ADMIN = 1;
    const TYPE_COACHING = 2;
    const TYPE_STUDENT = 3;

    const ACTIVE = 1;
    const UN_ACTIVE = 0;
    const ACTIVE_COMMON = 2; // status ma tai khoan co the dang nhap binh thuong

    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'full_name', 'email', 'password', 'phone'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login' => 'datetime',
        'date_join_sys' => 'datetime'
    ];

    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function setLastNameAttribute($value)
    {
        $this->attributes['last_name'] = ucfirst($value);
    }

    public function setFirstNameAttribute($value)
    {
        $this->attributes['first_name'] = ucfirst($value);
    }

    public function isAdmin()
    {
        return Auth::user()->type === self::TYPE_ADMIN;
    }

    public function isCoach()
    {
        return Auth::user()->type === self::TYPE_COACHING;
    }

    public function isStudent()
    {
        return Auth::user()->type === self::TYPE_STUDENT;
    }

    public static function renderTextActive($status)
    {
        if ($status === self::ACTIVE_COMMON) {
            return '<span class="badge badge-success">Đã kích hoạt</span>';
        }
        return '<span class="badge badge-danger">Chưa kích hoạt</span>';
    }

    public function courseComplete()
    {
        return $this->hasMany('App\Model\CourseStudent', 'student_id', 'id');
    }

    public static function getNameCoachOwner($owner_id)
    {
        $user_owner = self::query()->where('id', $owner_id)->first();
        if (empty($user_owner)) {
            return '<span class="badge badge-danger">Chưa thuộc coach</span>';
        }
        return $user_owner->name;
    }

    public static function studentOfCoach($coach_id)
    {
        $countStudent = User::query()->where('user_owner', $coach_id)->count();
        return $countStudent;
    }
}
