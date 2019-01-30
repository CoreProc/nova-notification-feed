Nova.booting((Vue, router) => {
  // Load components
  Vue.component('notification-feed', require('./components/NotificationFeed'))
  Vue.component('notifications-panel', require('./components/NotificationsPanel'))
  Vue.component('notification-message', require('./components/NotificationMessage'))
})
