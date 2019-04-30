<?php

namespace App\Repository;

use Beaver\Repository\AbstractRepository;
use PDO;

class PathologyRepository extends AbstractRepository
{
    /**
     * @param array $keywords
     *
     * @return array
     */
    public function findByKeyWords(array $keywords): array
    {
        // complex code to manage list with pdo, purposely not factorised
        $inKeywords = [];
        $in = '';
        foreach ($keywords as $id => $keyword) {
            $key = "keyword$id";
            $in .= ":$key,";
            $inKeywords[$key] = $keyword;
        }
        $in = rtrim($in, ',');

        $stmt = $this->db->prepare("
            SELECT DISTINCT s.desc as symptDesc, p.type as pathoType, p.desc as pathoDesc, m.nom as merNom, m.yin as yin
            FROM keywords k
            JOIN keySympt ks ON k.idK = ks.idK
            JOIN symptome s ON s.idS = ks.idS
            JOIN symptPatho sp ON s.idS = sp.idS
            JOIN patho p ON sp.idP = p.idP
            JOIN meridien m ON m.code = p.mer
            WHERE k.name IN ($in);
        ");

        $stmt->execute($inKeywords);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $rows;
    }
}
