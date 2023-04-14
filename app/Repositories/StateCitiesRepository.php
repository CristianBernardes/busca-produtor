<?php

namespace App\Repositories;

use App\Models\State;

/**
 *
 */
class StateCitiesRepository
{

    /**
     * @param $uf
     * @return mixed
     */
    public function index($uf = null): mixed
    {
        return State::select('id', 'title', 'letter')->with([
            'cities' => function ($query) {
                $query->select('id', 'state_id', 'title', 'slug');
            }
        ])->when($uf, function ($query, $uf) {
            $query->where('letter', $uf);
        })->get();
    }
}
