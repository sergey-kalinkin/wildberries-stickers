import Generator from './Generator.vue';

document.addEventListener('DOMContentLoaded', function() {
    if(document.getElementById('generator')) {
        const generator = new Vue({
            el: '#generator',
            components: {
                Generator
            },
        });
    }
});
