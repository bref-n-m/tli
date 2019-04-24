<?php

namespace App\Repository;

use Beaver\Repository\AbstractMultiPrimaryKeyRepository;

class SymptomPathologyRepository extends AbstractMultiPrimaryKeyRepository
{
    const IDS_TYPE = 'idS';
    const IDP_TYPE = 'idP';

    /**
     * @param int $idP
     *
     * @return array|null
     *
     * @throws \Exception
     */
    public function getByIdP(int $idP): ?array
    {
        return $this->getByRow(self::IDP_TYPE, $idP);
    }

    /**
     * @param int $idS
     *
     * @return array|null
     *
     * @throws \Exception
     */
    public function getByIdS(int $idS): ?array
    {
        return $this->getByRow(self::IDS_TYPE, $idS);
    }
}
