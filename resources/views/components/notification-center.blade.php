@php
    // Safely get notifications and unread count
    $userNotifications = collect();
    $unreadNotificationCount = 0;
    
    try {
        // Try to get Laravel notifications first
        if (auth()->user() && method_exists(auth()->user(), 'notifications')) {
            $userNotifications = auth()->user()->notifications()->take(10)->get();
            $unreadNotificationCount = auth()->user()->notifications()->whereNull('read_at')->count();
        }
    } catch (Exception $e) {
        // Fallback to empty collections if there's an error
        $userNotifications = collect();
        $unreadNotificationCount = 0;
    }
@endphp

<div x-data="{ 
    open: false, 
    notifications: @json($userNotifications),
    unreadCount: {{ $unreadNotificationCount }}
}" 
class="relative">
    
    <!-- Notification Bell -->
    <button @click="open = !open" class="relative p-2 bg-background-hover dark:bg-background-hover-dark hover:bg-gray-300 dark:hover:bg-gray-600 rounded-full transition-all duration-300 group">
        <i class="fas fa-bell text-text-primary dark:text-gray-300 group-hover:scale-110 transition-transform"></i>
        
        <!-- Notification Badge -->
        <div x-show="unreadCount > 0" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-0"
             x-transition:enter-end="opacity-1 scale-100"
             class="absolute -top-1 -right-1 w-5 h-5 bg-gradient-to-r from-red-500 to-pink-500 text-white text-xs rounded-full flex items-center justify-center font-bold shadow-lg animate-pulse">
            <span x-text="unreadCount > 99 ? '99+' : unreadCount"></span>
        </div>
    </button>
    
    <!-- Notification Dropdown -->
    <div x-show="open" 
         @click.away="open = false"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 transform scale-95 translate-y-2"
         x-transition:enter-end="opacity-1 transform scale-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-1 transform scale-100"
         x-transition:leave-end="opacity-0 transform scale-95"
         class="absolute right-0 top-full mt-2 w-96 bg-white dark:bg-background-card-dark rounded-2xl shadow-facebook-hover dark:shadow-facebook-dark border border-gray-100 dark:border-border-dark py-4 z-50 max-h-96 overflow-hidden">
        
        <!-- Header -->
        <div class="px-6 pb-4 border-b border-gray-100 dark:border-border-dark">
            <div class="flex items-center justify-between">
                <h3 class="text-xl font-bold text-text-primary dark:text-text-primary-dark flex items-center">
                    <div class="w-8 h-8 bg-gradient-to-br from-facebook-500 to-purple-600 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-bell text-white text-sm"></i>
                    </div>
                    Notifications
                </h3>
                <button x-show="unreadCount > 0" @click="markAllAsRead()" class="text-facebook-500 hover:text-facebook-600 text-sm font-medium hover:bg-facebook-50 dark:hover:bg-facebook-900/20 px-3 py-1 rounded-lg transition-all duration-200">
                    Tout marquer comme lu
                </button>
            </div>
        </div>
        
        <!-- Notification List -->
        <div class="max-h-80 overflow-y-auto">
            <template x-if="notifications && notifications.length > 0">
                <div>
                    <template x-for="notification in notifications" :key="notification.id">
                        <div @click="markAsRead(notification.id)" 
                             :class="!notification.read_at ? 'bg-facebook-50 dark:bg-facebook-900/20 border-l-4 border-facebook-500' : ''"
                             class="px-6 py-4 hover:bg-background-hover dark:hover:bg-background-hover-dark cursor-pointer transition-all duration-200 group">
                            
                            <div class="flex items-start space-x-3">
                                <!-- Avatar -->
                                <div class="flex-shrink-0 relative">
                                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-facebook-400 to-facebook-600 flex items-center justify-center shadow-lg">
                                        <i class="fas fa-bell text-white text-sm"></i>
                                    </div>
                                    
                                    <!-- Type Indicator -->
                                    <div class="absolute -bottom-1 -right-1 w-6 h-6 rounded-full border-2 border-white dark:border-background-card-dark flex items-center justify-center text-xs bg-facebook-500 shadow-lg">
                                        <i class="fas fa-info text-white"></i>
                                    </div>
                                </div>
                                
                                <!-- Content -->
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <p class="text-text-primary dark:text-text-primary-dark text-sm font-medium leading-relaxed" x-text="notification.data?.message || notification.data?.title || 'Nouvelle notification'"></p>
                                            <p class="text-text-muted dark:text-text-muted-dark text-xs mt-1" x-text="formatTime(notification.created_at)"></p>
                                        </div>
                                        
                                        <!-- Unread Indicator -->
                                        <div x-show="!notification.read_at" class="w-3 h-3 bg-facebook-500 rounded-full ml-2 animate-pulse"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </template>
            
            <!-- Empty State -->
            <template x-if="!notifications || notifications.length === 0">
                <div class="px-6 py-12 text-center">
                    <div class="w-16 h-16 mx-auto mb-4 bg-gradient-to-br from-gray-100 to-gray-200 dark:from-gray-700 dark:to-gray-600 rounded-2xl flex items-center justify-center">
                        <i class="fas fa-bell-slash text-gray-400 dark:text-gray-500 text-xl"></i>
                    </div>
                    <h4 class="text-lg font-semibold text-text-primary dark:text-text-primary-dark mb-2">Aucune notification</h4>
                    <p class="text-text-secondary dark:text-text-secondary-dark text-sm">Vous êtes à jour ! Aucune nouvelle notification pour le moment.</p>
                </div>
            </template>
        </div>
        
        <!-- Footer -->
        <template x-if="notifications && notifications.length > 0">
            <div class="px-6 pt-4 border-t border-gray-100 dark:border-border-dark">
                <button class="w-full text-facebook-500 hover:text-facebook-600 text-sm font-medium py-3 hover:bg-facebook-50 dark:hover:bg-facebook-900/20 rounded-xl transition-all duration-200">
                    <i class="fas fa-external-link-alt mr-2"></i>
                    Voir toutes les notifications
                </button>
            </div>
        </template>
    </div>
</div>

<script>
function markAsRead(notificationId) {
    // Send to server (you'll need to implement these routes)
    fetch(`/notifications/${notificationId}/read`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    }).then(() => {
        // Update local state
        this.notifications = this.notifications.map(notification => {
            if (notification.id === notificationId && !notification.read_at) {
                this.unreadCount = Math.max(0, this.unreadCount - 1);
                return { ...notification, read_at: new Date().toISOString() };
            }
            return notification;
        });
    }).catch(error => {
        console.log('Error marking notification as read:', error);
    });
}

function markAllAsRead() {
    fetch('/notifications/mark-all-read', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    }).then(() => {
        this.notifications = this.notifications.map(notification => ({ 
            ...notification, 
            read_at: notification.read_at || new Date().toISOString() 
        }));
        this.unreadCount = 0;
    }).catch(error => {
        console.log('Error marking all notifications as read:', error);
    });
}

function formatTime(timestamp) {
    const date = new Date(timestamp);
    const now = new Date();
    const diff = now - date;
    
    const minutes = Math.floor(diff / 60000);
    const hours = Math.floor(diff / 3600000);
    const days = Math.floor(diff / 86400000);
    
    if (minutes < 1) return 'À l\'instant';
    if (minutes < 60) return `${minutes}m`;
    if (hours < 24) return `${hours}h`;
    if (days < 7) return `${days}j`;
    
    return date.toLocaleDateString('fr-FR', { month: 'short', day: 'numeric' });
}
</script> 