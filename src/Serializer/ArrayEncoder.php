<?php

namespace App\Serializer;

use Symfony\Component\Serializer\Encoder\DecoderInterface;
use Symfony\Component\Serializer\Encoder\EncoderInterface;
use Symfony\Component\Serializer\Exception\UnexpectedValueException;

class ArrayEncoder implements EncoderInterface, DecoderInterface
{
    /**
     * @param array  $data    Data to encode
     * @param string $format  Format name
     * @param array  $context Options that normalizers/encoders have access to
     *
     * @return array
     *
     * @throws UnexpectedValueException
     */
    public function encode($data, $format, array $context = []): array
    {
        if (!\is_array($data)) {
            throw new UnexpectedValueException('Expected array.');
        }

        return $data;
    }

    /**
     * @param string $format Format name
     *
     * @return bool
     */
    public function supportsEncoding($format): bool
    {
        return 'array' === $format;
    }

    /**
     * @param array  $data    Data to decode
     * @param string $format  Format name
     * @param array  $context Options that decoders have access to
     *
     * @return array
     *
     * @throws UnexpectedValueException
     */
    public function decode($data, $format, array $context = []): array
    {
        if (!\is_array($data)) {
            throw new UnexpectedValueException('Expected array.');
        }

        // change \DateTime objects into strings that can be decoded
        array_walk_recursive($data, [$this, 'transformDateTime']);

        return $data;
    }

    /**
     * @param string $format Format name
     *
     * @return bool
     */
    public function supportsDecoding($format): bool
    {
        return 'array' === $format;
    }

    /**
     * @param mixed $item
     */
    private function transformDateTime(&$item): void
    {
        if (is_object($item) && $item instanceof \DateTime) {
            $item = $item->format('Y-m-d\TH:i:s.u\Z');
        }
    }
}
