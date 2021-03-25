<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laratrust\Traits\LaratrustUserTrait;

/**
 * @property integer $id
 * @property string $name
 * @property string $email
 *
 * @property array $searchableFields
 *
 * @property Builder $sort
 * @property Builder $filter
 * @property Builder $search
 */
class User extends Authenticatable implements MustVerifyEmail
{
    use LaratrustUserTrait;
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * @var array
     */
    public $searchableFields = [
        'id',
        'name',
        'email'
    ];

    /**
     * @param Builder $query
     * @param string $sort
     * @param string $order
     * @return Builder
     */
    public function scopeSort(Builder $query, string $sort, string $order): Builder
    {
        return $query->orderBy($sort, $order);
    }

    /**
     * @param Builder $query
     * @param string|null $searchQuery
     * @param array $filters
     * @return Builder
     */
    public function scopeSearch(Builder $query, ?string $searchQuery, array $filters): Builder
    {
        if (is_null($searchQuery)) {
            return $query;
        }

        return $query->where(function ($query) use (&$filters, &$searchQuery) {

            $fields = array_diff(
                $this->searchableFields,
                array_keys( array_filter($filters) )
            );

            foreach ($fields as $key => $fieldName) {
                $methodName = $key ? 'orWhere' : 'where';
                $query = $query->$methodName($fieldName, 'like', '%' . $searchQuery . '%');
            }

            return $query;
        });
    }

    /**
     * @param Builder $query
     * @param array $filters
     * @return Builder
     */
    public function scopeFilter(Builder $query, array $filters): Builder
    {
        $filters = array_filter($filters);

        foreach ($filters as $name => $value) {
            $query = $query->where($name, 'like', '%' . $value . '%');
        }

        return $query;
    }

}
