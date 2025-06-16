<?php

namespace App\Console\Commands;

use App\Models\Player;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FetchPlayersCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'players:fetch {--api=api-football : API provider to use}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch players data from football API and update database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔄 Futbol oyuncu verilerini çekiliyor...');

        $apiProvider = $this->option('api');

        try {
            switch ($apiProvider) {
                case 'api-football':
                    $this->fetchFromApiFootball();
                    break;
                case 'football-data':
                    $this->fetchFromFootballData();
                    break;
                default:
                    $this->fetchFromApiFootball();
                    break;
            }

            $this->info('✅ Oyuncu verileri başarıyla güncellendi!');
            $this->info('📊 Toplam oyuncu sayısı: ' . Player::count());

        } catch (\Exception $e) {
            $this->error('❌ Hata: ' . $e->getMessage());
            Log::error('Player fetch error: ' . $e->getMessage());
        }
    }

    /**
     * Fetch data from API-Football
     */
    private function fetchFromApiFootball()
    {
        $this->info('📡 API-Football\'dan veri çekiliyor...');

        $apiKey = env('API_FOOTBALL_KEY');

        if (!$apiKey) {
            $this->warn('⚠️ API_FOOTBALL_KEY bulunamadı, örnek veriler kullanılıyor...');
            $this->fetchSampleData();
            return;
        }

        // Türkiye Süper Lig ID'si (API-Football'da 203)
        $leagueId = 203;
        $season = 2024; // 2024-2025 sezonu

        try {
            // Önce ligdeki takımları çek
            $teamsResponse = Http::withHeaders([
                'x-rapidapi-host' => 'v3.football.api-sports.io',
                'x-rapidapi-key' => $apiKey
            ])->get("https://v3.football.api-sports.io/teams", [
                'league' => $leagueId,
                'season' => $season
            ]);

            // Debug: API yanıtını kontrol et
            $this->info('🔍 API Yanıt Durumu: ' . $teamsResponse->status());

            if (!$teamsResponse->successful()) {
                $this->warn('⚠️ Takım verileri çekilemedi, örnek veriler kullanılıyor...');
                $this->warn('API Yanıt: ' . $teamsResponse->body());
                $this->fetchSampleData();
                return;
            }

            $teamsData = $teamsResponse->json();

            // Debug: API yanıtını logla
            $this->info('🔍 API Yanıt Anahtarları: ' . implode(', ', array_keys($teamsData)));

            // Debug: Response içeriğini kontrol et
            if (isset($teamsData['response'])) {
                $this->info('🔍 Response tipi: ' . gettype($teamsData['response']));
                $this->info('🔍 Response uzunluğu: ' . count($teamsData['response']));
            }

            // Debug: Errors varsa göster
            if (isset($teamsData['errors']) && !empty($teamsData['errors'])) {
                $this->warn('⚠️ API Hataları: ' . json_encode($teamsData['errors']));
            }

            $teams = $teamsData['response'] ?? [];

            $this->info('🏆 ' . count($teams) . ' takım bulundu');

            if (count($teams) === 0) {
                $this->warn('⚠️ Hiç takım bulunamadı, farklı sezon deneyelim...');

                // 2023 sezonunu deneyelim
                $this->fetchFromApiFootballWithSeason(2023);
                return;
            }

            $allPlayers = [];
            $bar = $this->output->createProgressBar(count($teams));
            $bar->start();

            foreach ($teams as $team) {
                $teamId = $team['team']['id'];
                $teamName = $team['team']['name'];

                // Her takımın oyuncularını çek
                $playersResponse = Http::withHeaders([
                    'x-rapidapi-host' => 'v3.football.api-sports.io',
                    'x-rapidapi-key' => $apiKey
                ])->get("https://v3.football.api-sports.io/players", [
                    'team' => $teamId,
                    'season' => $season
                ]);

                if ($playersResponse->successful()) {
                    $playersData = $playersResponse->json();
                    $players = $playersData['response'] ?? [];

                    foreach ($players as $player) {
                        $playerInfo = $player['player'];
                        $statistics = $player['statistics'][0] ?? [];

                        $allPlayers[] = [
                            'first_name' => $playerInfo['firstname'] ?? '',
                            'last_name' => $playerInfo['lastname'] ?? '',
                            'team' => $teamName,
                            'position' => $this->mapApiFootballPosition($statistics['games']['position'] ?? ''),
                            'age' => $playerInfo['age'] ?? null,
                            'height' => $playerInfo['height'] ?? null,
                            'weight' => $playerInfo['weight'] ?? null,
                            'nationality' => $playerInfo['nationality'] ?? '',
                            'api_id' => $playerInfo['id'] ?? null
                        ];
                    }
                }

                $bar->advance();

                // API rate limit'e takılmamak için kısa bekleme
                usleep(100000); // 0.1 saniye
            }

            $bar->finish();
            $this->newLine();
            $this->info('👥 ' . count($allPlayers) . ' oyuncu bulundu');

            $this->updatePlayers($allPlayers);

        } catch (\Exception $e) {
            $this->warn('⚠️ API hatası: ' . $e->getMessage() . ', örnek veriler kullanılıyor...');
            $this->fetchSampleData();
        }
    }

    /**
     * Fetch data from API-Football with specific season
     */
    private function fetchFromApiFootballWithSeason($season)
    {
        $this->info('📡 ' . $season . ' sezonu için veri çekiliyor...');

        $apiKey = env('API_FOOTBALL_KEY');
        $leagueId = 203;

        try {
            $teamsResponse = Http::withHeaders([
                'x-rapidapi-host' => 'v3.football.api-sports.io',
                'x-rapidapi-key' => $apiKey
            ])->get("https://v3.football.api-sports.io/teams", [
                'league' => $leagueId,
                'season' => $season
            ]);

            if (!$teamsResponse->successful()) {
                $this->warn('⚠️ ' . $season . ' sezonu için veri çekilemedi, örnek veriler kullanılıyor...');
                $this->fetchSampleData();
                return;
            }

            $teamsData = $teamsResponse->json();

            // Debug: API yanıtını logla
            $this->info('🔍 API Yanıt Anahtarları: ' . implode(', ', array_keys($teamsData)));

            // Debug: Response içeriğini kontrol et
            if (isset($teamsData['response'])) {
                $this->info('🔍 Response tipi: ' . gettype($teamsData['response']));
                $this->info('🔍 Response uzunluğu: ' . count($teamsData['response']));
            }

            // Debug: Errors varsa göster
            if (isset($teamsData['errors']) && !empty($teamsData['errors'])) {
                $this->warn('⚠️ API Hataları: ' . json_encode($teamsData['errors']));
            }

            $teams = $teamsData['response'] ?? [];

            $this->info('🏆 ' . count($teams) . ' takım bulundu (' . $season . ' sezonu)');

            if (count($teams) === 0) {
                $this->warn('⚠️ Hiç takım bulunamadı, örnek veriler kullanılıyor...');
                $this->fetchSampleData();
                return;
            }

            $allPlayers = [];
            $bar = $this->output->createProgressBar(count($teams));
            $bar->start();

            foreach ($teams as $team) {
                $teamId = $team['team']['id'];
                $teamName = $team['team']['name'];

                $playersResponse = Http::withHeaders([
                    'x-rapidapi-host' => 'v3.football.api-sports.io',
                    'x-rapidapi-key' => $apiKey
                ])->get("https://v3.football.api-sports.io/players", [
                    'team' => $teamId,
                    'season' => $season
                ]);

                if ($playersResponse->successful()) {
                    $playersData = $playersResponse->json();
                    $players = $playersData['response'] ?? [];

                    foreach ($players as $player) {
                        $playerInfo = $player['player'];
                        $statistics = $player['statistics'][0] ?? [];

                        $allPlayers[] = [
                            'first_name' => $playerInfo['firstname'] ?? '',
                            'last_name' => $playerInfo['lastname'] ?? '',
                            'team' => $teamName,
                            'position' => $this->mapApiFootballPosition($statistics['games']['position'] ?? ''),
                            'age' => $playerInfo['age'] ?? null,
                            'height' => $playerInfo['height'] ?? null,
                            'weight' => $playerInfo['weight'] ?? null,
                            'nationality' => $playerInfo['nationality'] ?? '',
                            'api_id' => $playerInfo['id'] ?? null
                        ];
                    }
                }

                $bar->advance();
                usleep(100000);
            }

            $bar->finish();
            $this->newLine();
            $this->info('👥 ' . count($allPlayers) . ' oyuncu bulundu');

            $this->updatePlayers($allPlayers);

        } catch (\Exception $e) {
            $this->warn('⚠️ API hatası: ' . $e->getMessage() . ', örnek veriler kullanılıyor...');
            $this->fetchSampleData();
        }
    }

    /**
     * Fetch data from Football-Data.org
     */
    private function fetchFromFootballData()
    {
        $this->info('📡 Football-Data.org\'dan veri çekiliyor...');

        $apiKey = env('FOOTBALL_DATA_API_KEY');

        if (!$apiKey) {
            $this->warn('⚠️ FOOTBALL_DATA_API_KEY bulunamadı, örnek veriler kullanılıyor...');
            $this->fetchSampleData();
            return;
        }

        // Türkiye Süper Lig endpoint
        $url = 'http://api.football-data.org/v4/competitions/TR1/teams';

        try {
            $response = Http::withHeaders([
                'X-Auth-Token' => $apiKey
            ])->get($url);

            if ($response->successful()) {
                $data = $response->json();
                $this->processFootballDataResponse($data);
            } else {
                $this->warn('⚠️ API yanıt vermedi, örnek veriler kullanılıyor...');
                $this->fetchSampleData();
            }
        } catch (\Exception $e) {
            $this->warn('⚠️ API bağlantı hatası, örnek veriler kullanılıyor...');
            $this->fetchSampleData();
        }
    }

    /**
     * Fetch sample data when API is not available
     */
    private function fetchSampleData()
    {
        $this->info('📡 Örnek veriler kullanılıyor...');

        $players = $this->getSamplePlayers();
        $this->updatePlayers($players);
    }

    /**
     * Get sample players data (for demonstration)
     */
    private function getSamplePlayers()
    {
        return [
            // Galatasaray
            ['first_name' => 'Mauro', 'last_name' => 'Icardi', 'team' => 'Galatasaray', 'position' => 'Forvet'],
            ['first_name' => 'Wilfried', 'last_name' => 'Zaha', 'team' => 'Galatasaray', 'position' => 'Forvet'],
            ['first_name' => 'Dries', 'last_name' => 'Mertens', 'team' => 'Galatasaray', 'position' => 'Orta Saha'],
            ['first_name' => 'Hakim', 'last_name' => 'Ziyech', 'team' => 'Galatasaray', 'position' => 'Orta Saha'],
            ['first_name' => 'Sergio', 'last_name' => 'Oliveira', 'team' => 'Galatasaray', 'position' => 'Orta Saha'],
            ['first_name' => 'Günay', 'last_name' => 'Güvenç', 'team' => 'Galatasaray', 'position' => 'Kaleci'],
            ['first_name' => 'Victor', 'last_name' => 'Nelsson', 'team' => 'Galatasaray', 'position' => 'Defans'],
            ['first_name' => 'Sacha', 'last_name' => 'Boey', 'team' => 'Galatasaray', 'position' => 'Defans'],

            // Fenerbahçe
            ['first_name' => 'Edin', 'last_name' => 'Džeko', 'team' => 'Fenerbahçe', 'position' => 'Forvet'],
            ['first_name' => 'Joshua', 'last_name' => 'King', 'team' => 'Fenerbahçe', 'position' => 'Forvet'],
            ['first_name' => 'Dusan', 'last_name' => 'Tadic', 'team' => 'Fenerbahçe', 'position' => 'Orta Saha'],
            ['first_name' => 'İrfan', 'last_name' => 'Can Kahveci', 'team' => 'Fenerbahçe', 'position' => 'Orta Saha'],
            ['first_name' => 'Fred', 'last_name' => '', 'team' => 'Fenerbahçe', 'position' => 'Orta Saha'],
            ['first_name' => 'Dominik', 'last_name' => 'Livakovic', 'team' => 'Fenerbahçe', 'position' => 'Kaleci'],
            ['first_name' => 'Alexander', 'last_name' => 'Djiku', 'team' => 'Fenerbahçe', 'position' => 'Defans'],
            ['first_name' => 'Bright', 'last_name' => 'Osayi-Samuel', 'team' => 'Fenerbahçe', 'position' => 'Defans'],

            // Beşiktaş
            ['first_name' => 'Cenk', 'last_name' => 'Tosun', 'team' => 'Beşiktaş', 'position' => 'Forvet'],
            ['first_name' => 'Vincent', 'last_name' => 'Aboubakar', 'team' => 'Beşiktaş', 'position' => 'Forvet'],
            ['first_name' => 'Al-Musrati', 'last_name' => '', 'team' => 'Beşiktaş', 'position' => 'Orta Saha'],
            ['first_name' => 'Mert', 'last_name' => 'Günok', 'team' => 'Beşiktaş', 'position' => 'Kaleci'],
            ['first_name' => 'Valentin', 'last_name' => 'Rosier', 'team' => 'Beşiktaş', 'position' => 'Defans'],
            ['first_name' => 'Daniel', 'last_name' => 'Amartey', 'team' => 'Beşiktaş', 'position' => 'Defans'],

            // Trabzonspor
            ['first_name' => 'Uğurcan', 'last_name' => 'Çakır', 'team' => 'Trabzonspor', 'position' => 'Kaleci'],
            ['first_name' => 'Mahmoud', 'last_name' => 'Trezeguet', 'team' => 'Trabzonspor', 'position' => 'Forvet'],
            ['first_name' => 'Anastasios', 'last_name' => 'Bakasetas', 'team' => 'Trabzonspor', 'position' => 'Orta Saha'],
            ['first_name' => 'Abdülkadir', 'last_name' => 'Ömür', 'team' => 'Trabzonspor', 'position' => 'Orta Saha'],
            ['first_name' => 'Eren', 'last_name' => 'Elmalı', 'team' => 'Trabzonspor', 'position' => 'Defans'],

            // Adana Demirspor
            ['first_name' => 'Mario', 'last_name' => 'Balotelli', 'team' => 'Adana Demirspor', 'position' => 'Forvet'],
            ['first_name' => 'Benjamin', 'last_name' => 'Stambouli', 'team' => 'Adana Demirspor', 'position' => 'Orta Saha'],
            ['first_name' => 'Gökhan', 'last_name' => 'Akkan', 'team' => 'Adana Demirspor', 'position' => 'Kaleci'],

            // Antalyaspor
            ['first_name' => 'Fernando', 'last_name' => '', 'team' => 'Antalyaspor', 'position' => 'Orta Saha'],
            ['first_name' => 'Güray', 'last_name' => 'Vural', 'team' => 'Antalyaspor', 'position' => 'Defans'],

            // Konyaspor
            ['first_name' => 'Amir', 'last_name' => 'Hadžiahmetović', 'team' => 'Konyaspor', 'position' => 'Orta Saha'],
            ['first_name' => 'Francisco', 'last_name' => 'Calvo', 'team' => 'Konyaspor', 'position' => 'Defans'],

            // Kayserispor
            ['first_name' => 'Carlos', 'last_name' => 'Mané', 'team' => 'Kayserispor', 'position' => 'Forvet'],

            // Alanyaspor
            ['first_name' => 'Eren', 'last_name' => 'Derdiyok', 'team' => 'Alanyaspor', 'position' => 'Forvet'],

            // Sivasspor
            ['first_name' => 'Max', 'last_name' => 'Gradel', 'team' => 'Sivasspor', 'position' => 'Forvet'],
        ];
    }

    /**
     * Update players in database
     */
    private function updatePlayers($players)
    {
        $this->info('💾 Veritabanı güncelleniyor...');

        $bar = $this->output->createProgressBar(count($players));
        $bar->start();

        foreach ($players as $playerData) {
            // Oyuncu zaten var mı kontrol et
            $existingPlayer = Player::where('first_name', $playerData['first_name'])
                ->where('last_name', $playerData['last_name'])
                ->where('team', $playerData['team'])
                ->first();

            if ($existingPlayer) {
                // Mevcut oyuncuyu güncelle
                $existingPlayer->update([
                    'position' => $playerData['position']
                ]);
            } else {
                // Yeni oyuncu ekle
                Player::create($playerData);
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
    }

    /**
     * Process Football-Data.org API response
     */
    private function processFootballDataResponse($data)
    {
        $players = [];

        if (isset($data['teams'])) {
            foreach ($data['teams'] as $team) {
                if (isset($team['squad'])) {
                    foreach ($team['squad'] as $player) {
                        $players[] = [
                            'first_name' => $player['name'] ?? '',
                            'last_name' => '',
                            'team' => $team['name'] ?? '',
                            'position' => $this->mapPosition($player['position'] ?? '')
                        ];
                    }
                }
            }
        }

        $this->updatePlayers($players);
    }

    /**
     * Map position from API to our format
     */
    private function mapPosition($apiPosition)
    {
        $positionMap = [
            'Goalkeeper' => 'Kaleci',
            'Defender' => 'Defans',
            'Midfielder' => 'Orta Saha',
            'Attacker' => 'Forvet',
            'Forward' => 'Forvet'
        ];

        return $positionMap[$apiPosition] ?? 'Orta Saha';
    }

    /**
     * Map API-Football position to our format
     */
    private function mapApiFootballPosition($apiPosition)
    {
        $positionMap = [
            'G' => 'Kaleci',
            'D' => 'Defans',
            'M' => 'Orta Saha',
            'F' => 'Forvet',
            'Goalkeeper' => 'Kaleci',
            'Defender' => 'Defans',
            'Midfielder' => 'Orta Saha',
            'Attacker' => 'Forvet',
            'Forward' => 'Forvet'
        ];

        return $positionMap[$apiPosition] ?? 'Orta Saha';
    }
}
