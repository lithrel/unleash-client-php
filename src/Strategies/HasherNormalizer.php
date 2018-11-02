<?php
namespace C5A\Unleash\Strategies;

use lastguest\Murmur;
use function sprintf;

class HasherNormalizer
{
    /**
     * @param string $id
     * @param string $groupId
     * @return int
     */
    public function hashAndNormalize(string $id, string $groupId): int
    {
        return $this->normalize($this->murmur3(sprintf('%s:%s', $groupId, $id)));
    }

    /**
     * @param string $key
     * @return int
     */
    private function murmur3(string $key): int
    {
        return Murmur::hash3_int($key);
    }

    /**
     * @param int $value
     * @param int $normalizerLevel
     * @return int
     */
    private function normalize(int $value, int $normalizerLevel = 100): int
    {
        return $value % $normalizerLevel + 1;
    }
}
