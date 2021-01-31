<template>
    <div class="conatiner-fluid mx-auto pt-3 px-3">
        <span id="spanbacktoMenesis" @click="backToThis">Atpakaļ uz šo mēnesi</span>
        <div class="row">
            <div class="col d-flex justify-content-center align-items-center">
                <span @click="menesisPaKreisi" style="cursor:pointer;"><i class="fas fa-arrow-left fa-2x"></i></span>
            </div>
            <div class="col d-flex justify-content-center align-items-center">
                <h2>{{ menesisGadsFormat(datums) }}</h2>
            </div>
            <div class="col d-flex justify-content-center align-items-center">
                <span @click="menesisPaLabi" style="cursor:pointer;"><i class="fas fa-arrow-right fa-2x"></i></span>
            </div>
        </div>
        <table id="tableKotrole" class="table">
            <tr>
                <th>Lietotājs</th>
                <th v-for="i in getDienuSkaits()" :key="i" class="text-center">{{i}}</th>
                <th>Kopā</th>
            </tr>
            <tr v-for="u in lietotaji" :key="'user'+u.id">
                <td>{{ u.vards + ' ' + u.uzvards }}</td>
                <td v-for="i in getDienuSkaits()" :key="i" class="text-center" :class="{'zalasDienas':data[u.id][i-1][1] != 0 && data[u.id][i-1][0] != 6 && data[u.id][i-1][0] != 7, 'sarkanasDienas':data[u.id][i-1][1] == 0 && data[u.id][i-1][0] != 6 && data[u.id][i-1][0] != 7}">
                    {{ data[u.id][i-1][1] }}
                </td>
                <td class="text-center">{{ data[u.id][data[u.id].length-1] }}</td>
            </tr>
        </table>
    </div>
</template>

<script>
export default {
    name: 'kontrole',
    props: ['app'],
    data(){
        return {
            datums: new Date(new Date().setDate(1)),
            lietotaji: [],
            data: []
        }
    },
    mounted(){
        this.init();
    },
    methods:{
        init(){
            const data = {
                datums: this.datums.getFullYear()+'-'+this.dateFormat(this.datums.getMonth()+1)+'-'+this.dateFormat(this.datums.getDate())
            }
            this.app.req.post('data/getAll', data).then(response => {
                this.lietotaji = response.data.lietotaji;
                this.data = response.data.data;
            });
        },
        dateFormat(a){
            if(parseInt(a) < 10){
                return '0'+a;
            }else{
                return a;
            }
        },
        menesisGadsFormat(datums){
            return this.dateFormat(datums.getMonth()+1)+"."+datums.getFullYear();
        },
        backToThis(){
            this.datums = new Date(new Date().setDate(1));
            this.init();
        },
        menesisPaKreisi(){
            let menesis = this.datums.getMonth();
            if(menesis == 0){
                this.datums.setMonth(11);
                this.datums.setFullYear(this.datums.getFullYear()-1);
            }else{
                this.datums.setMonth(this.datums.getMonth()-1);
            }
            const data = {
                datums: this.datums.getFullYear()+'-'+this.dateFormat(this.datums.getMonth()+1)+'-'+this.dateFormat(this.datums.getDate())
            }
            this.app.req.post('data/getAll', data).then(response => {
                this.lietotaji = response.data.lietotaji;
                this.data = response.data.data;
            });
        },
        menesisPaLabi(){
            let menesis = this.datums.getMonth();
            if(menesis == 11){
                this.datums.setMonth(0);
                this.datums.setFullYear(this.datums.getFullYear()+1);
            }else{
                this.datums.setMonth(this.datums.getMonth()+1);
            }
            const data = {
                datums: this.datums.getFullYear()+'-'+this.dateFormat(this.datums.getMonth()+1)+'-'+this.dateFormat(this.datums.getDate())
            }
            this.app.req.post('data/getAll', data).then(response => {
                this.lietotaji = response.data.lietotaji;
                this.data = response.data.data;
            });
        },
        getDienuSkaits(){
            let menesis = this.datums.getMonth()+1;
            let gads = this.datums.getFullYear();
            if(menesis == 2){
                if(gads % 4 == 0){
                    return 29;
                }else{
                    return 28;
                }
            }else{
                if(menesis < 8){
                    if(menesis % 2 == 0){
                        return 30;
                    }else{
                        return 31;
                    }
                }else{
                    if(menesis % 2 == 0){
                        return 31;
                    }else{
                        return 30;
                    }
                }
            }
        }
    },
    computed:{

    }
}
</script>
