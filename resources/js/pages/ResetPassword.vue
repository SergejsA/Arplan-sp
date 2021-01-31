<template>
    <div class="container mt-3">
        <div class="card">
            <div class="card-header">
                Atjaunot paroli
            </div>
            <div class="card-body">
                <div class="alert alert-primary">
                    <form v-on:submit.prevent="sendToken">
                        <div class="form-group">
                            <label>E-pasts</label>
                            <input type="email" class="form-control" :class="{'is-invalid': errorEmail, 'is-valid': infoEmail}" :placeholder="'e-pasts...'" v-model="email">
                            <div class="invalid-feedback">
                                {{errorEmail}}
                            </div>
                            <div class="valid-feedback">
                                {{infoEmail}}
                            </div>
                        </div>

                        <button class="btn btn-primary" type="submit">Nosūtīt kodu</button>
                    </form>
                </div>

                <div class="alert alert-secondary">
                    <form v-on:submit.prevent="validateToken">
                        <div class="form-group">
                            <label>Kods</label>
                            <input class="form-control" :class="{'is-invalid': errorToken, 'is-valid': infoToken}" :placeholder="'kods...'" v-model="token">
                            <div class="invalid-feedback">
                                {{errorToken}}
                            </div>
                            <div class="valid-feedback">
                                {{infoToken}}
                            </div>
                        </div>

                        <button class="btn btn-secondary" type="submit">Pārbaudīt</button>
                    </form>
                </div>

                <div class="alert alert-success" v-if="tokenValid">
                    <form v-on:submit.prevent="changePassword">
                        <div class="form-group">
                            <label>Jaunā parole</label>
                            <input type="password" class="form-control" :class="{'is-invalid': errorNewPassword}" v-model="newPassword">
                            <div class="invalid-feedback">
                                {{errorNewPassword}}
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Atkārtoti jaunā parole</label>
                            <input type="password" class="form-control" :class="{'is-invalid': errorPasswordAgain}" v-model="passwordAgain">
                            <div class="invalid-feedback">
                                {{errorPasswordAgain}}
                            </div>
                        </div>

                        <button class="btn btn-success" type="submit">Mainīt paroli</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'password-reset',
    props: ['app'],
    data(){
        return {
            email: '',
            errorEmail: null,
            infoEmail: null,
            token: '',
            errorToken: null,
            infoToken: null,
            newPassword: '',
            errorNewPassword: null,
            passwordAgain: '',
            errorPasswordAgain: null,
            tokenValid: false,
            user: null
        }
    },
    methods:{
        sendToken(){
            this.errorEmail = null;
            if(!this.email){
                this.errorEmail = "E-pasts jāievada";
            }
            if(!this.errorEmail){
                const data = {
                    email: this.email
                };
                this.app.req.post('auth/send-token', data).then(response => {
                    this.infoEmail = "E-pasts nosūtīts";
                }).catch(error => {
                    this.errorEmail = "Notika kļūda";
                });
            }
        },
        validateToken(){
            this.errorToken = null;
            if(!this.token){
                this.errorToken = "Kods jāievada";
            }
            if(!this.errorToken){
                const data = {
                    token: this.token
                };
                this.app.req.post('auth/validate-token', data).then(response => {
                    if(response.data.id){
                        this.tokenValid = true;
                        this.infoToken = "Pareizs kods";
                        this.user = response.data;
                    }
                }).catch(error => {
                    this.errorToken = "Notika kļūda";
                });
            }
        },
        changePassword(){
            this.errorNewPassword = null;
            this.errorPasswordAgain = null;
            if(!this.newPassword){
                this.errorNewPassword = "Parole jāievada";
            }
            if(!this.passwordAgain){
                this.errorPasswordAgain = "Atkārtotā parole jāievada";
            }
            if(this.newPassword != this.passwordAgain){
                this.errorPasswordAgain = "Paroles nesakrīt";
            }
            if(!this.errorNewPassword && !this.errorPasswordAgain){
                const data = {
                    password: this.newPassword,
                    user_id: this.user.id
                };
                console.log(data);
                this.app.req.post('auth/reset-password', data).then(response => {
                    this.$router.push("/login");
                    // console.log(response.data);
                });
            }
        }
    }
}
</script>
