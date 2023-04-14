<?php

namespace App\Repositories;

use App\Models\Producer;
use App\Models\User;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

/**
 *
 */
class ProducerRepository extends AbstractRepository
{

    /**
     * @return mixed
     */
    public function model(): mixed
    {
        return Producer::class;
    }

    /**
     * @return string
     */
    public function messageErrorNotFound(): string
    {
        return 'Produtor não encontrado';
    }


    /**
     * @param int $id
     * @param User $user
     *
     * @return mixed
     */
    public function customShow(int $id, User $user): mixed
    {
        $objectModel = $this->query($user);

        $producer = $objectModel->find($id);

        if (!$producer) {

            throw new Exception($this->messageErrorNotFound(), Response::HTTP_NOT_FOUND);
        }

        return $producer;
    }

    /**
     * Função que busca produtores pela localização, volume e termos de busca.
     *
     * @param User $user
     * @param $minDistance
     * @param $maxDistance
     * @param $minVolume
     * @param $maxVolume
     * @param $city
     * @param $state
     * @param $col
     * @param $order
     * @param $offset
     * @param $searchTerm
     * @return mixed
     */
    public function searchProducersByLocation(
        User $user, //Parâmetro obrigatório da classe User
        $minDistance = null, //Distância mínima, pode ser nula
        $maxDistance = null, //Distância máxima, pode ser nula
        $minVolume = null, //Volume mínimo, pode ser nulo
        $maxVolume = null, //Volume máximo, pode ser nulo
        $city = null, //Cidade, pode ser nula
        $state = null, //Estado, pode ser nulo
        $col, //Coluna para ordenação
        $order, //Ordem da ordenação
        $offset, //Número de elementos para a paginação
        $searchTerm //Termo buscado
    ): mixed {

        //Inicializa o valor das variáveis
        $distance = null;
        $volumeInLiters = null;
        $newMinDistance = $minDistance;
        $newMaxDistance = $maxDistance;

        //Verifica se as distâncias mínima e máxima foram informadas
        if ($minDistance && $maxDistance) {

            //Se a distância mínima for maior que a máxima, inverte os valores das variáveis
            if ($minDistance > $maxDistance) {
                $newMinDistance = $maxDistance;
                $newMaxDistance = $minDistance;
            }

            //Armazena o intervalo entre as distâncias mínima e máxima
            $distance = [$newMinDistance, $newMaxDistance];
        }

        //Verifica se os volumes mínimo e máximo foram informados
        if ($minVolume && $maxVolume) {

            //Armazena o intervalo entre os volumes mínimo e máximo
            $volumeInLiters = [$minVolume, $maxVolume];
        }

        //Query inicial de seleção de dados da tabela, selecionando algumas colunas específicas
        $producers = $this->query($user, $newMaxDistance);

        //Realiza condições na query baseado nas informações fornecidas ou não pelo usuário
        return $producers
            ->when($distance, function ($query, $distance) {

                //Faz um filtro para consultar produtores cuja distância está dentro do intervalo informado
                $query->havingRaw('distance BETWEEN ? AND ?', $distance);
            })
            ->when($volumeInLiters, function ($query, $volumeInLiters) {

                //Faz um filtro para consultar produtores cujo volume está dentro do intervalo informado
                $query->whereBetween('volume_in_liters', $volumeInLiters);
            })
            ->when($city, function ($query, $city) {

                //Faz um filtro para consultar produtores de uma determinada cidade
                $query->where('city', $city);
            })
            ->when($state, function ($query, $state) {

                //Faz um filtro para consultar produtores de um determinado estado
                $query->where('state', $state);
            })
            ->when($searchTerm, function ($query, $searchTerm) {

                //Faz um filtro para consultar produtores cujos nome, cidade, estado ou número de telefone contenham o termo buscado
                $query->where(function ($query) use ($searchTerm) {
                    $query->orWhere('producer_name', 'LIKE', '%' . $searchTerm . '%')
                        ->orWhere('city', 'LIKE', '%' . $searchTerm . '%')
                        ->orWhere('state', 'LIKE', '%' . $searchTerm . '%')
                        ->orWhere('whatsapp_phone', 'LIKE', '%' . $searchTerm . '%');
                });
            })
            ->orderBy($col, $order)
            ->paginate($offset);
    }

    /**
     * @param User $user
     * @param null $newMaxDistance
     *
     * @return mixed
     */
    public function query(User $user, $newMaxDistance = null): mixed
    {
        //Query inicial de seleção de dados da tabela, selecionando algumas colunas específicas
        $producers = $this->model()::select(
            'id',
            'producer_name',
            'city',
            'state',
            'whatsapp_phone',
            'volume_in_liters',
        );

        //Verifica se o usuário é admin
        if ($user->is_admin) {

            //Adiciona as colunas latitude e longitude à query
            $producers->addSelect('latitude', 'longitude');
        }

        //Adiciona uma coluna na query com o cálculo da distância entre a localização do produtor e a do usuário
        $producers->addSelect(
            DB::raw('ROUND(6371 * acos(cos(radians(' . $user->latitude . ')) * cos(radians(latitude)) * cos(radians(longitude) - radians(' . $user->longitude . ')) + sin(radians(' . $user->latitude . ')) * sin(radians(latitude))), 2) AS distance')
            //DB::raw('ROUND(ST_DISTANCE_SPHERE(coordinates, POINT(' . $user->latitude . ', ' . $user->longitude . ')) / 1000, 2) AS distance')
        );

        return $producers->when(!$user->is_admin, function ($query) use ($newMaxDistance) {

            //Se o usuário não for admin, filtra pela distância máxima permitida
            $query->having('distance', '<=', min($newMaxDistance ?? 500, 500));
        });
    }
}
