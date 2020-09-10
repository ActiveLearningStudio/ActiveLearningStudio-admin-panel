<?php

namespace App\Repositories\User;

use App\Exceptions\GeneralException;
use App\Repositories\BaseRepository;
use App\User;

/**
 * Class UserRepository.
 */
class UserRepository extends BaseRepository
{
    /**
     * UserRepository constructor.
     *
     * @param User $model
     */
    public function __construct(User $model)
    {
        $this->model = $model;
    }

    /**
     * @return mixed
     */
    public function getUsers()
    {
        return $this->model->get();
    }

    /**
     * @param $data
     * @return mixed
     */
    public function createUser($data)
    {
        return $this->model->create($data);
    }

    /**
     * @param $id
     * @return mixed
     * @throws GeneralException
     */
    public function destroyUser($id)
    {
        if ((int)$id === \Auth::user()->id) {
            throw new GeneralException('You cannot delete your own user');
        }
        if ($user = User::find($id)) {
            return $user->delete();
        }
        abort(404);
    }
}
