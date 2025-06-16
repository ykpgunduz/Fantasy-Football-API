import './bootstrap';

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
    toast.className = `fixed top-4 right-4 z-50 px-6 py-3 rounded-lg shadow-lg transition-all duration-300 transform translate-x-full ${
        type === 'success' ? 'bg-green-500 text-white' :
        type === 'error' ? 'bg-red-500 text-white' :
        'bg-blue-500 text-white'
    }`;
    toast.textContent = message;

    document.body.appendChild(toast);

    setTimeout(() => {
        toast.classList.remove('translate-x-full');
    }, 100);

    setTimeout(() => {
        toast.classList.add('translate-x-full');
        setTimeout(() => {
            document.body.removeChild(toast);
        }, 300);
    }, 3000);
};
