<?php

namespace App\Repositories;

use App\Models\Client;
use App\Models\PasswordResetToken;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

/**
 *
 */
class UserRepository extends AbstractRepository
{
    /**
     * @return mixed
     */
    public function model(): mixed
    {
        return User::class;
    }

    /**
     * @return string
     */
    public function messageErrorNotFound(): string
    {
        return 'Usuário não encontrado';
    }

    /**
     * @param string $col
     * @param string $order
     * @param int $offset
     * @param $searchTerm
     * @param $profileFilter
     * @param $clientName
     * @return mixed
     * @throws Exception
     */
    public function customIndex(
        string $col,
        string $order,
        int $offset,
        $searchTerm,
        $profileFilter = null,
        $clientName = null
    ): mixed {
        return $this->query()
            ->when($searchTerm, function ($query, $searchTerm) {
                $query->where(function ($query) use ($searchTerm) {
                    $query->orWhere('users.name', 'LIKE', '%' . $searchTerm . '%')
                        ->orWhere('users.email', 'LIKE', '%' . $searchTerm . '%')
                        ->orWhere('users.city', 'LIKE', '%' . $searchTerm . '%')
                        ->orWhere('users.state', 'LIKE', '%' . $searchTerm . '%')
                        ->orWhere('clients.client_name', 'LIKE', '%' . $searchTerm . '%');
                });
            })
            ->when($profileFilter, function ($query, $profileFilter) {
                $query->having('profile', '=', $profileFilter);
            })
            ->when($clientName, function ($query, $clientName) {
                $query->where('clients.client_name', $clientName);
            })
            ->orderBy($col, $order)->paginate($offset);
    }

    /**
     * @param $id
     * @return mixed
     * @throws Exception
     */
    public function show($id): mixed
    {
        $objectModel = $this->query()->find($id);

        if (!$objectModel) {
            throw new Exception($this->messageErrorNotFound(), Response::HTTP_NOT_FOUND);
        }

        return $objectModel;
    }

    /**
     * @return mixed
     */
    public function query(): mixed
    {
        return $this->model()::select(
            'users.id',
            'users.client_id',
            'clients.client_name',
            'users.name',
            'users.email',
            'users.password',
            'users.city',
            'users.state',
            'users.latitude',
            'users.longitude',
            DB::raw('(CASE WHEN users.client_id IS NULL THEN "administrator" ELSE "client" END) as profile')
        )
            ->leftJoin('clients', 'clients.id', 'users.client_id');
    }

    /**
     * store
     *
     * @param array $data
     * @return mixed
     * @throws Exception
     */
    public function store(array $data): mixed
    {
        if (array_key_exists('client_id', $data)) {

            if (!Client::find($data['client_id'])) {

                throw new Exception('Cliente Não encontrado', Response::HTTP_NOT_FOUND);
            }
        }

        return $this->model()::create($data);
    }

    /**
     * @param $id
     * @param array $data
     * @return mixed
     * @throws Exception
     */
    public function update($id, array $data): mixed
    {
        if (array_key_exists('client_id', $data)) {

            if (!Client::find($data['client_id'])) {

                throw new Exception('Cliente Não encontrado', Response::HTTP_NOT_FOUND);
            }
        } else {

            $data['client_id'] = null;
        }

        $user = $this->model()::find($id);

        if (!$user) {
            throw new Exception($this->messageErrorNotFound(), Response::HTTP_NOT_FOUND);
        }

        $update =  $user->update($data);

        if ($update) {

            return $this->model()::find($id);
        } else {
            throw new Exception('Não foi possível atualizar os dados.', Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * @param $id
     * @return string
     * @throws Exception
     */
    public function destroy($id): string
    {
        $objectModel = $this->model()::find($id);

        if (!$objectModel) {
            throw new Exception($this->messageErrorNotFound(), Response::HTTP_NOT_FOUND);
        }

        $delete =  $objectModel->delete();

        if ($delete) {

            $passwordResetToken = PasswordResetToken::where('email', $objectModel->email)->first();

            if ($passwordResetToken) {

                $passwordResetToken->delete();
            }

            return 'Dado deletado com sucesso';
        } else {
            throw new Exception('Não foi possível atualizar os dados.', Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * @param string $email
     * @param int $hours
     * @return void
     * @throws Exception
     */
    public function createAPasswordResetToken(string $email, int $hours = 24): void
    {
        $user = $this->model()::where('email', $email)->first();

        if ($user) {
            $token = Crypt::encryptString(Carbon::now()->addHours($hours)->format('Y-m-d H:i:s'));

            if (PasswordResetToken::where('email', $email)->first()) {

                throw new Exception("Já existe um pedido de redefinição de senha para o e-mail $email", Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            $passwordResetToken = new PasswordResetToken();
            $passwordResetToken->email = $email;
            $passwordResetToken->token = $token;
            $passwordResetToken->save();
        }
    }

    /**
     * @param string $email
     * @param string $password
     * @param string $token
     * @return string
     * @throws Exception
     */
    public function passwordReset(string $email, string $password, string $token): string
    {
        $user = $this->model()::where('email', $email)->first();

        if (!$user) {
            throw new Exception($this->messageErrorNotFound(), Response::HTTP_NOT_FOUND);
        }

        $this->checkPasswordResetToken($email, $token);

        $user->password = $password;

        if ($user->first_access == 1) {

            $user->first_access =  0;
        }

        $user->save();

        $passwordResetToken = PasswordResetToken::where('email', $email)->where('token', $token)->first();
        $passwordResetToken->delete();

        return 'Password atualizado com sucesso';
    }

    /**
     * @param string $email
     * @param string $token
     * @return void
     * @throws Exception
     */
    public function checkPasswordResetToken(string $email, string $token): void
    {
        $passwordResetToken = PasswordResetToken::where('email', $email)->where('token', $token)->first();

        if (!$passwordResetToken) {
            throw new Exception("Não existe um pedido para redefinição de senha para o e-mail $email ou o token de redefinição de senha inválido!", Response::HTTP_NOT_FOUND);
        }

        if (Carbon::now()->greaterThan(Crypt::decryptString($token))) {
            $passwordResetToken->delete();
            throw new Exception("Token de redefinição de senha expirado!", Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * @param User $user
     * @return array
     */
    public static function getPermissionsUser(User $user): array
    {
        return User::getPermissionsUser($user);
    }
}
