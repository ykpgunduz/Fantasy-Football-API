@extends('layouts.app')

@section('title', 'Oyuncular - Futbol Dashboard')

@section('content')
<div class="px-4 py-6 sm:px-0">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">👥 Türkiye Süper Lig Oyuncuları</h1>
            <p class="text-gray-600 mt-2">Tüm oyuncuları görüntüleyin ve filtreleyin</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                <span class="mr-2">🏠</span>
                Ana Sayfa
            </a>
        </div>
    </div>

    <!-- Filtreler -->
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <h2 class="text-lg font-medium text-gray-900 mb-4">Filtreler</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label for="team-filter" class="block text-sm font-medium text-gray-700 mb-2">Takım</label>
                <select id="team-filter" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500">
                    <option value="">Tüm Takımlar</option>
                    @foreach(\App\Models\Player::distinct('team')->pluck('team') as $team)
                        <option value="{{ $team }}">{{ $team }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="position-filter" class="block text-sm font-medium text-gray-700 mb-2">Mevki</label>
                <select id="position-filter" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500">
                    <option value="">Tüm Mevkiler</option>
                    @foreach(\App\Models\Player::distinct('position')->pluck('position') as $position)
                        <option value="{{ $position }}">{{ $position }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Arama</label>
                <input type="text" id="search" placeholder="Oyuncu adı..." class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500">
            </div>
        </div>
    </div>

    <!-- İstatistikler -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-green-500 rounded-md flex items-center justify-center">
                            <span class="text-white text-sm font-bold">{{ $players->count() }}</span>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Toplam Oyuncu</dt>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Oyuncular Tablosu -->
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <div class="px-4 py-5 sm:p-6">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200" id="players-table">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100" onclick="sortTable(0)">
                                Oyuncu Adı <span class="ml-1">↕</span>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100" onclick="sortTable(1)">
                                Takım <span class="ml-1">↕</span>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100" onclick="sortTable(2)">
                                Mevki <span class="ml-1">↕</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200" id="players-tbody">
                        @forelse($players as $player)
                        <tr class="player-row hover:bg-gray-50" data-team="{{ $player->team }}" data-position="{{ $player->position }}" data-name="{{ strtolower($player->full_name) }}">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <div class="h-10 w-10 rounded-full bg-green-100 flex items-center justify-center">
                                            <span class="text-green-600 font-medium text-sm">{{ substr($player->first_name, 0, 1) }}{{ substr($player->last_name, 0, 1) }}</span>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $player->full_name }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $player->team }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                    @if($player->position == 'Kaleci') bg-blue-100 text-blue-800
                                    @elseif($player->position == 'Defans') bg-green-100 text-green-800
                                    @elseif($player->position == 'Orta Saha') bg-yellow-100 text-yellow-800
                                    @elseif($player->position == 'Forvet') bg-red-100 text-red-800
                                    @else bg-gray-100 text-gray-800
                                    @endif">
                                    {{ $player->position }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="px-6 py-4 text-center text-sm text-gray-500">
                                Henüz oyuncu eklenmemiş
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
// Filtreleme fonksiyonu
function filterPlayers() {
    const teamFilter = document.getElementById('team-filter').value.toLowerCase();
    const positionFilter = document.getElementById('position-filter').value.toLowerCase();
    const searchFilter = document.getElementById('search').value.toLowerCase();

    const rows = document.querySelectorAll('.player-row');

    rows.forEach(row => {
        const team = row.getAttribute('data-team').toLowerCase();
        const position = row.getAttribute('data-position').toLowerCase();
        const name = row.getAttribute('data-name');

        const teamMatch = !teamFilter || team.includes(teamFilter);
        const positionMatch = !positionFilter || position.includes(positionFilter);
        const nameMatch = !searchFilter || name.includes(searchFilter);

        if (teamMatch && positionMatch && nameMatch) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

// Event listeners
document.getElementById('team-filter').addEventListener('change', filterPlayers);
document.getElementById('position-filter').addEventListener('change', filterPlayers);
document.getElementById('search').addEventListener('input', filterPlayers);

// Sıralama fonksiyonu
function sortTable(columnIndex) {
    const table = document.getElementById('players-table');
    const tbody = table.getElementsByTagName('tbody')[0];
    const rows = Array.from(tbody.getElementsByTagName('tr')).filter(row => row.style.display !== 'none');

    rows.sort((a, b) => {
        const aValue = a.cells[columnIndex].textContent.trim();
        const bValue = b.cells[columnIndex].textContent.trim();
        return aValue.localeCompare(bValue, 'tr');
    });

    rows.forEach(row => tbody.appendChild(row));
}
</script>
@endsection
