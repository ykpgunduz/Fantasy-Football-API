<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Futbol Dashboard')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Custom styles */
        .player-avatar {
            background: linear-gradient(135deg, #10b981, #059669);
        }

        .team-badge {
            transition: all 0.2s ease-in-out;
        }

        .team-badge:hover {
            transform: scale(1.05);
        }

        .position-badge {
            transition: all 0.2s ease-in-out;
        }

        .position-badge:hover {
            transform: scale(1.05);
        }

        /* Smooth transitions */
        .transition-all {
            transition: all 0.2s ease-in-out;
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb {
            background: #10b981;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #059669;
        }

        /* Loading animation */
        .animate-spin {
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            from {
                transform: rotate(0deg);
            }
            to {
                transform: rotate(360deg);
            }
        }

        /* Toast notification */
        .toast {
            position: fixed;
            top: 1rem;
            right: 1rem;
            z-index: 50;
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease-in-out;
            transform: translateX(100%);
        }

        .toast.show {
            transform: translateX(0);
        }

        .toast.success {
            background-color: #10b981;
            color: white;
        }

        .toast.error {
            background-color: #ef4444;
            color: white;
        }

        .toast.info {
            background-color: #3b82f6;
            color: white;
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen">
    <nav class="bg-green-600 shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <h1 class="text-white text-xl font-bold">⚽ Futbol Dashboard</h1>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('dashboard') }}" class="text-white hover:text-green-200 px-3 py-2 rounded-md text-sm font-medium">Ana Sayfa</a>
                    <a href="{{ route('players.index') }}" class="text-white hover:text-green-200 px-3 py-2 rounded-md text-sm font-medium">Oyuncular</a>
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        @yield('content')
    </main>

    <footer class="bg-gray-800 text-white py-4 mt-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <p>&copy; 2024 Futbol Dashboard. Tüm hakları saklıdır.</p>
        </div>
    </footer>

    <script>
        // Global utility functions
        window.formatPlayerName = function(firstName, lastName) {
            return firstName + ' ' + lastName;
        };

        // Add loading states
        window.showLoading = function(element) {
            element.classList.add('opacity-50', 'pointer-events-none');
            element.innerHTML = '<div class="animate-spin rounded-full h-4 w-4 border-b-2 border-green-600"></div>';
        };

        window.hideLoading = function(element, originalContent) {
            element.classList.remove('opacity-50', 'pointer-events-none');
            element.innerHTML = originalContent;
        };

        // Toast notification system
        window.showToast = function(message, type = 'success') {
            const toast = document.createElement('div');
            toast.className = `toast ${type}`;
            toast.textContent = message;

            document.body.appendChild(toast);

            setTimeout(() => {
                toast.classList.add('show');
            }, 100);

            setTimeout(() => {
                toast.classList.remove('show');
                setTimeout(() => {
                    if (document.body.contains(toast)) {
                        document.body.removeChild(toast);
                    }
                }, 300);
            }, 3000);
        };
    </script>
</body>
</html>
