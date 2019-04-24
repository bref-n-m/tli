<?php

namespace App\Repository;

use Beaver\Repository\AbstractMultiPrimaryKeyRepository;

class KeywordSymptomRepository extends AbstractMultiPrimaryKeyRepository
{
    const IDK_TYPE = 'idK';
    const IDS_TYPE = 'idS';

    /**
     * @param int $idK
     *
     * @return array|null
     *
     * @throws \Exception
     */
    public function getByIdK(int $idK): ?array
    {
        return $this->getByRow(self::IDK_TYPE, $idK);
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
