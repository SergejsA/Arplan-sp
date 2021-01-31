<template>
    <div class="container-fluid p-0">
        <nav class="navbar navbar-expand-lg navbar-dark bg-sitera">
            <router-link to="/" class="navbar-brand">Sitera</router-link>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item" :class="{'active':($route.name=='home')}" v-if="app.user">
                        <router-link to="/" class="nav-link">Stundu uzskaite</router-link>
                    </li>
                    <li class="nav-item" :class="{'active':($route.name=='Kopsavilkums')}" v-if="app.user">
                        <router-link to="/kopsavilkums" class="nav-link">Stundu kopsavilkumi</router-link>
                    </li>
                    <li class="nav-item" :class="{'active':($route.name=='Lietotaji')}" v-if="app.user && (app.user.tips == 'admin' || app.user.tips == 'system_admin')">
                        <router-link to="/lietotaji" class="nav-link">Pārvaldīt lietotājus</router-link>
                    </li>
                    <li class="nav-item" :class="{'active':($route.name=='Projekti')}" v-if="app.user && (app.user.tips == 'admin' || app.user.tips == 'system_admin')">
                        <router-link to="/projekti" class="nav-link">Pārvaldīt Projektus un Darbus</router-link>
                    </li>
                    <li class="nav-item" :class="{'active':($route.name=='Kontrole')}" v-if="app.user && (app.user.tips == 'admin' || app.user.tips == 'system_admin')">
                        <router-link to="/kontrole" class="nav-link">Lietotāju kontrole</router-link>
                    </li>
                </ul>
                <ul class="navbar-nav ml-auto" v-if="app.user">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        {{ app.user.vards }} {{ app.user.uzvards }}
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a @click="changePass" href="javascript:;" class="dropdown-item">Mainīt Paroli</a>
                            <a @click="logout" href="javascript:;" class="dropdown-item">Iziet</a>
                        </div>
                    </li>
                </ul>
            </div>
        </nav>
        <div class="modal fade" id="changePassModal" tabindex="-1" aria-labelledby="changePassModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Mainīt paroli</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-danger mb-2" v-if="errors.length">
                            <ul class="mb-0">
                                <li v-for="(error, index) in errors" :key="index">
                                    {{error}}
                                </li>
                            </ul>
                        </div>
                        <div class="mb-3">
                            <label for="vParole" class="col-form-label">Vecā parole:</label>
                            <input type="password" class="form-control" id="vParole" v-model="veca">
                        </div>
                        <div class="mb-3">
                            <label for="jParole" class="col-form-label">Jaunā parole:</label>
                            <input type="password" class="form-control" id="jParole" v-model="jauna">
                        </div>
                        <div class="mb-3">
                            <label for="jaParole" class="col-form-label">Atkārtoti jaunā parole:</label>
                            <input type="password" class="form-control" id="jaParole" v-model="jauna_again">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Atcelt</button>
                        <button type="button" class="btn btn-success" @click="mainitParoli">Mainīt</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "navbar",
    props: ['app'],
    data () {
        return {
            errors: [],
            veca: '',
            jauna: '',
            jauna_again: ''
        }
    },
    methods: {
        changePass(){
            this.errors = [];
            this.veca = '';
            this.jauna = '';
            this.jauna_again = '';
            $("#changePassModal").modal('show');
        },
        mainitParoli(){
            this.errors = [];
            if(this.veca == '' || this.jauna == '' || this.jauna_again == ''){
                this.errors.push('Visi lauciņi jāaizpilda');
            }
            if(this.jauna != this.jauna_again){
                this.errors.push('Paroles nesakrīt');
            }
            if(!this.errors.length){
                const data = {
                    veca: this.veca,
                    jauna: this.jauna
                };
                this.app.req.post('auth/changePass', data).then(response => {
                    if(response.data.mes == 'nav_veca'){
                        this.errors.push('Nepareiza vecā parole ievadīta');
                    }else{
                        $("#changePassModal").modal('hide');
                    }
                });
            }
        },
        logout(){
            this.app.req.post('auth/logout').then(() => {
                this.app.user = null;
                this.$router.push('/login');
            });
        }
    }
}
</script>
