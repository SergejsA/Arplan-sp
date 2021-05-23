<template>
    <div class="p-0 m-0">
        <navbar :app="this"></navbar>
        <spinner v-if="loading"></spinner>
        <div v-else-if="initiated">
            <router-view :app="this" />
        </div>
    </div>
</template>

<script>
import Navbar from './components/Navbar';

export default {
    name: 'app',
    components: {
        Navbar
    },
    data() {
        return {
            user: null,
            loading: false,
            initiated: false,
            req: axios.create({
                baseUrl: BASE_URL
            }),
            base_url: BASE_URL,
            firma: 'Bez nosakuma'
        }
    },
    mounted(){
        this.init();
    },
    methods: {
        init(){
            this.loading = true;
            const data = {
                a: 'test'
            };
            this.req.post('auth/init', data).then(response => {
                if(response.data.need_init){
                    this.loading = false;
                    this.initiated = true;
                    this.$router.push('/first-init');
                }else{
                    this.firma = response.data.firma;
                    this.user = response.data.user;
                    if(response.data.ip == 'nav'){
                        window.location.href = "https://google.com";
                    }
                    if(this.user == null){
                        this.$router.push('/login');
                    }
                    this.loading = false;
                    this.initiated = true;
                }
            })
        },
    }
}
</script>
