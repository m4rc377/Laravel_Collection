<?php

namespace App;

use App\Models\Contact;
use App\Models\ContactStatus;
use App\Models\Document;
use App\Models\Task;
use App\Models\TaskStatus;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use Notifiable;

    use HasRoles;

    protected $guard_name = 'web';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'position_title', 'phone', 'image', 'is_admin', 'is_active'
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
     * get all contacts assigned to user
     *
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function contacts()
    {
        return $this->hasMany(Contact::class, 'assigned_user_id');
    }


    /**
     * get all leads assigned to user
     */
    public function leads()
    {
        return $this->hasMany(Contact::class, 'assigned_user_id')->where('status', ContactStatus::where('name', config('seed_data.contact_status')[0])->first()->id);
    }


    /**
     * get all opportunities assigned to user
     */
    public function opportunities()
    {
        return $this->hasMany(Contact::class, 'assigned_user_id')->where('status', ContactStatus::where('name', config('seed_data.contact_status')[1])->first()->id);
    }


    /**
     * get all customers assigned to user
     */
    public function customers()
    {
        return $this->hasMany(Contact::class, 'assigned_user_id')->where('status', ContactStatus::where('name', config('seed_data.contact_status')[2])->first()->id);
    }


    /**
     * get all closed/archives customers assigned to user
     */
    public function archives()
    {
        return $this->hasMany(Contact::class, 'assigned_user_id')->where('status', ContactStatus::where('name', config('seed_data.contact_status')[3])->first()->id);
    }


    /**
     * get all documents assigned to user
     */
    public function documents()
    {
        return $this->hasMany(Document::class, 'assigned_user_id');
    }


    /**
     * get all tasks assigned to user
     */
    public function tasks()
    {
        return $this->hasMany(Task::class, 'assigned_user_id');
    }


    /**
     * get all completed tasks assigned to user
     */
    public function completedTasks()
    {
        return $this->hasMany(Task::class, 'assigned_user_id')->where('status', TaskStatus::where('name', config('seed_data.task_statuses')[2])->first()->id);
    }


    /**
     * get all pending tasks assigned to user
     */
    public function pendingTasks()
    {
        return $this->hasMany(Task::class, 'assigned_user_id')->where('status', TaskStatus::whereIn('name', [config('seed_data.task_statuses')[0], config('seed_data.task_statuses')[1]])->first()->id);
    }
}
