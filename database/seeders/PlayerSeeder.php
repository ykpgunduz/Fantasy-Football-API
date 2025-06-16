<?php

namespace Database\Seeders;

use App\Models\Player;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PlayerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $players = [
            // Galatasaray
            ['first_name' => 'Mauro', 'last_name' => 'Icardi', 'team' => 'Galatasaray', 'position' => 'Forvet'],
            ['first_name' => 'Wilfried', 'last_name' => 'Zaha', 'team' => 'Galatasaray', 'position' => 'Forvet'],
            ['first_name' => 'Dries', 'last_name' => 'Mertens', 'team' => 'Galatasaray', 'position' => 'Orta Saha'],
            ['first_name' => 'Hakim', 'last_name' => 'Ziyech', 'team' => 'Galatasaray', 'position' => 'Orta Saha'],
            ['first_name' => 'Sergio', 'last_name' => 'Oliveira', 'team' => 'Galatasaray', 'position' => 'Orta Saha'],
            ['first_name' => 'Wilfried', 'last_name' => 'Kanga', 'team' => 'Galatasaray', 'position' => 'Forvet'],
            ['first_name' => 'Günay', 'last_name' => 'Güvenç', 'team' => 'Galatasaray', 'position' => 'Kaleci'],
            ['first_name' => 'Victor', 'last_name' => 'Nelsson', 'team' => 'Galatasaray', 'position' => 'Defans'],
            ['first_name' => 'Sacha', 'last_name' => 'Boey', 'team' => 'Galatasaray', 'position' => 'Defans'],
            ['first_name' => 'Angeliño', 'last_name' => '', 'team' => 'Galatasaray', 'position' => 'Defans'],

            // Fenerbahçe
            ['first_name' => 'Edin', 'last_name' => 'Džeko', 'team' => 'Fenerbahçe', 'position' => 'Forvet'],
            ['first_name' => 'Joshua', 'last_name' => 'King', 'team' => 'Fenerbahçe', 'position' => 'Forvet'],
            ['first_name' => 'Dusan', 'last_name' => 'Tadic', 'team' => 'Fenerbahçe', 'position' => 'Orta Saha'],
            ['first_name' => 'İrfan', 'last_name' => 'Can Kahveci', 'team' => 'Fenerbahçe', 'position' => 'Orta Saha'],
            ['first_name' => 'Fred', 'last_name' => '', 'team' => 'Fenerbahçe', 'position' => 'Orta Saha'],
            ['first_name' => 'Dominik', 'last_name' => 'Livakovic', 'team' => 'Fenerbahçe', 'position' => 'Kaleci'],
            ['first_name' => 'Alexander', 'last_name' => 'Djiku', 'team' => 'Fenerbahçe', 'position' => 'Defans'],
            ['first_name' => 'Jayden', 'last_name' => 'Oosterwolde', 'team' => 'Fenerbahçe', 'position' => 'Defans'],
            ['first_name' => 'Bright', 'last_name' => 'Osayi-Samuel', 'team' => 'Fenerbahçe', 'position' => 'Defans'],
            ['first_name' => 'Ferdi', 'last_name' => 'Kadıoğlu', 'team' => 'Fenerbahçe', 'position' => 'Defans'],

            // Beşiktaş
            ['first_name' => 'Cenk', 'last_name' => 'Tosun', 'team' => 'Beşiktaş', 'position' => 'Forvet'],
            ['first_name' => 'Vincent', 'last_name' => 'Aboubakar', 'team' => 'Beşiktaş', 'position' => 'Forvet'],
            ['first_name' => 'Al-Musrati', 'last_name' => '', 'team' => 'Beşiktaş', 'position' => 'Orta Saha'],
            ['first_name' => 'Rachid', 'last_name' => 'Ghezzal', 'team' => 'Beşiktaş', 'position' => 'Orta Saha'],
            ['first_name' => 'Al-Musrati', 'last_name' => '', 'team' => 'Beşiktaş', 'position' => 'Orta Saha'],
            ['first_name' => 'Mert', 'last_name' => 'Günok', 'team' => 'Beşiktaş', 'position' => 'Kaleci'],
            ['first_name' => 'Valentin', 'last_name' => 'Rosier', 'team' => 'Beşiktaş', 'position' => 'Defans'],
            ['first_name' => 'Daniel', 'last_name' => 'Amartey', 'team' => 'Beşiktaş', 'position' => 'Defans'],
            ['first_name' => 'Onur', 'last_name' => 'Bulut', 'team' => 'Beşiktaş', 'position' => 'Defans'],
            ['first_name' => 'Valentin', 'last_name' => 'Rosier', 'team' => 'Beşiktaş', 'position' => 'Defans'],

            // Trabzonspor
            ['first_name' => 'Uğurcan', 'last_name' => 'Çakır', 'team' => 'Trabzonspor', 'position' => 'Kaleci'],
            ['first_name' => 'Mahmoud', 'last_name' => 'Trezeguet', 'team' => 'Trabzonspor', 'position' => 'Forvet'],
            ['first_name' => 'Anastasios', 'last_name' => 'Bakasetas', 'team' => 'Trabzonspor', 'position' => 'Orta Saha'],
            ['first_name' => 'Abdülkadir', 'last_name' => 'Ömür', 'team' => 'Trabzonspor', 'position' => 'Orta Saha'],
            ['first_name' => 'Batista', 'last_name' => 'Mendes', 'team' => 'Trabzonspor', 'position' => 'Orta Saha'],
            ['first_name' => 'Eren', 'last_name' => 'Elmalı', 'team' => 'Trabzonspor', 'position' => 'Defans'],
            ['first_name' => 'Batista', 'last_name' => 'Mendes', 'team' => 'Trabzonspor', 'position' => 'Defans'],
            ['first_name' => 'Jens', 'last_name' => 'Stryger Larsen', 'team' => 'Trabzonspor', 'position' => 'Defans'],
            ['first_name' => 'Batista', 'last_name' => 'Mendes', 'team' => 'Trabzonspor', 'position' => 'Defans'],
            ['first_name' => 'Batista', 'last_name' => 'Mendes', 'team' => 'Trabzonspor', 'position' => 'Defans'],

            // Adana Demirspor
            ['first_name' => 'Mario', 'last_name' => 'Balotelli', 'team' => 'Adana Demirspor', 'position' => 'Forvet'],
            ['first_name' => 'Benjamin', 'last_name' => 'Stambouli', 'team' => 'Adana Demirspor', 'position' => 'Orta Saha'],
            ['first_name' => 'Jonas', 'last_name' => 'Svensson', 'team' => 'Adana Demirspor', 'position' => 'Defans'],
            ['first_name' => 'Gökhan', 'last_name' => 'Akkan', 'team' => 'Adana Demirspor', 'position' => 'Kaleci'],
            ['first_name' => 'Jonas', 'last_name' => 'Svensson', 'team' => 'Adana Demirspor', 'position' => 'Defans'],

            // Antalyaspor
            ['first_name' => 'Fernando', 'last_name' => '', 'team' => 'Antalyaspor', 'position' => 'Orta Saha'],
            ['first_name' => 'Güray', 'last_name' => 'Vural', 'team' => 'Antalyaspor', 'position' => 'Defans'],
            ['first_name' => 'Fernando', 'last_name' => '', 'team' => 'Antalyaspor', 'position' => 'Defans'],
            ['first_name' => 'Fernando', 'last_name' => '', 'team' => 'Antalyaspor', 'position' => 'Defans'],
            ['first_name' => 'Fernando', 'last_name' => '', 'team' => 'Antalyaspor', 'position' => 'Defans'],

            // Konyaspor
            ['first_name' => 'Amir', 'last_name' => 'Hadžiahmetović', 'team' => 'Konyaspor', 'position' => 'Orta Saha'],
            ['first_name' => 'Francisco', 'last_name' => 'Calvo', 'team' => 'Konyaspor', 'position' => 'Defans'],
            ['first_name' => 'Francisco', 'last_name' => 'Calvo', 'team' => 'Konyaspor', 'position' => 'Defans'],
            ['first_name' => 'Francisco', 'last_name' => 'Calvo', 'team' => 'Konyaspor', 'position' => 'Defans'],
            ['first_name' => 'Francisco', 'last_name' => 'Calvo', 'team' => 'Konyaspor', 'position' => 'Defans'],

            // Kayserispor
            ['first_name' => 'Carlos', 'last_name' => 'Mané', 'team' => 'Kayserispor', 'position' => 'Forvet'],
            ['first_name' => 'Carlos', 'last_name' => 'Mané', 'team' => 'Kayserispor', 'position' => 'Orta Saha'],
            ['first_name' => 'Carlos', 'last_name' => 'Mané', 'team' => 'Kayserispor', 'position' => 'Defans'],
            ['first_name' => 'Carlos', 'last_name' => 'Mané', 'team' => 'Kayserispor', 'position' => 'Kaleci'],
            ['first_name' => 'Carlos', 'last_name' => 'Mané', 'team' => 'Kayserispor', 'position' => 'Defans'],

            // Alanyaspor
            ['first_name' => 'Eren', 'last_name' => 'Derdiyok', 'team' => 'Alanyaspor', 'position' => 'Forvet'],
            ['first_name' => 'Eren', 'last_name' => 'Derdiyok', 'team' => 'Alanyaspor', 'position' => 'Orta Saha'],
            ['first_name' => 'Eren', 'last_name' => 'Derdiyok', 'team' => 'Alanyaspor', 'position' => 'Defans'],
            ['first_name' => 'Eren', 'last_name' => 'Derdiyok', 'team' => 'Alanyaspor', 'position' => 'Kaleci'],
            ['first_name' => 'Eren', 'last_name' => 'Derdiyok', 'team' => 'Alanyaspor', 'position' => 'Defans'],

            // Sivasspor
            ['first_name' => 'Max', 'last_name' => 'Gradel', 'team' => 'Sivasspor', 'position' => 'Forvet'],
            ['first_name' => 'Max', 'last_name' => 'Gradel', 'team' => 'Sivasspor', 'position' => 'Orta Saha'],
            ['first_name' => 'Max', 'last_name' => 'Gradel', 'team' => 'Sivasspor', 'position' => 'Defans'],
            ['first_name' => 'Max', 'last_name' => 'Gradel', 'team' => 'Sivasspor', 'position' => 'Kaleci'],
            ['first_name' => 'Max', 'last_name' => 'Gradel', 'team' => 'Sivasspor', 'position' => 'Defans'],
        ];

        foreach ($players as $player) {
            Player::create($player);
        }
    }
}
