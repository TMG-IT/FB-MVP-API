<?php

namespace App\DataFixtures\Faker\Provider;

class CoachProvider
{
    /**
     * @return array
     */
    public static function coach(): array
    {
        $coaches = [
            [
                'first_name' => 'John',
                'last_name' => 'Doe',
            ],
            [
                'first_name' => 'Mary',
                'last_name' => 'White',
            ],
            [
                'first_name' => 'Jack',
                'last_name' => 'Russell',
            ],
        ];

        $key = array_rand($coaches);

        return $coaches[$key];
    }
}
