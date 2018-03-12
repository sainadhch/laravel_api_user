<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserTokensModel extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'token', 'user_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];
	
	
    /**
     * Tokens from a user.
     * One To Many Relasion
     * @var array
     */
    public function user()
    {

		return $this->BelongsTo('App\User');

    }
}
