import AuthorizationWidget from './AuthorizationWidget.vue';

document.addEventListener('DOMContentLoaded', function() {
    if(document.getElementById('authorization_widget')) {
        const generator = new Vue({
            el: '#authorization_widget',
            template: '<AuthorizationWidget/>',
            components: {
                AuthorizationWidget
            },
        });
    }
});
