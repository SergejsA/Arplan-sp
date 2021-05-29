<template>
    <div class="container mx-auto p-0" v-if="render">
        <table id="tableLaiki" class="table">
            <tr>
                <td id="switchnedelanr" colspan=8>
                    <div class="row">
                        <div class="col d-flex justify-content-center align-items-center">
                            <span @click="nedelaPaKreisi" style="cursor:pointer;"><i class="fas fa-arrow-left fa-2x"></i></span>
                        </div>
                        <div class="col d-flex justify-content-center align-items-center">
                            <h2>Nedēļa Nr {{ nedelas_nr }}</h2>
                        </div>
                        <div class="col d-flex justify-content-center align-items-center">
                            <span @click="nedelaPaLabi" style="cursor:pointer;"><i class="fas fa-arrow-right fa-2x"></i></span>
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <th style="min-width:300px;">Darbu veidi</th>
                <th v-for="i in 7" :key="i" :class="{'brivdienacol6':i == 6, 'brivdienacol7':i == 7, 'sodienacol':i==currentDay && nedelas_nr == todayWeek && i != 6 && i != 7}" style="text-align:center;">
                    {{ getDateMonth(i-1) }}
                </th>
            </tr>
            <tr>
                <td>
                    <div class="row">
                        <div class="col">
                            <select id="chooseProjectSelectbox" class="form-control" @change="changeProject" v-model="project">
                                <option value="-">-</option>
                                <option v-for="(p, i) in projekti" :key="i" :value="p.id">{{ p.nosaukums }}</option>
                            </select>
                        </div>
                        <div class="col text-right">
                            <select id="chooseDarbiSelectbox" class="form-control" v-model="job">
                                <option v-for="(j, i) in darbi" :key="i" :value="j">{{ j }}</option>
                            </select>
                        </div>
                    </div>
                </td>
                <td v-for="i in 7" :key="i" :class="{'brivdienacol6':i == 6, 'brivdienacol7':i == 7, 'sodienacol':i==currentDay && nedelas_nr == todayWeek && i != 6 && i != 7}" style="text-align:center;">
                    <input type='text' :id="'ilgums'+(i-1)" class='text-center form-control' v-model="addRow[i-1]" style="width:50px;margin:0 auto;">
                </td>
                <td><button id="addDatiButton" class="btn btn-success btn-sm" @click="addIlgumi">Pievienot</button></td>
            </tr>
            <tr v-for="(d, i) in nedelasData" :key="i">
                <td>
                    <label v-if="i==0 || nedelasData[i-1].project.nosaukums != d.project.nosaukums"><b>{{ d.project.nosaukums }}</b></label>
                    <label style="float:right;">{{ d.darbs }}</label>
                </td>
                <td v-for="(ilgums, j) in ilgumi(d.ilgums)" :key="'data'+j" :class="{'brivdienacol6':j == 5, 'brivdienacol7':j == 6, 'sodienacol':j==currentDay-1 && nedelas_nr == todayWeek && j != 5 && j != 6}" style="text-align:center;">
                    <input type='text' class='text-center form-control' v-model="editRow[j]" style="width:50px;margin:0 auto;" v-if="editId == d.id">
                    <span v-else>{{ ilgums }}</span>
                </td>
                <td>
                    <button class='btn btn-sm btn-success' @click="saveData" v-if="editId == d.id">Saglabāt</button>
                    <button class='redigetierakstu btn btn-sm btn-secondary' @click="editData(d.id)" v-else>Rediģēt</button>
                </td>
                <td>
                    <button class='dzestierakstu btn btn-sm btn-danger' @click="deleteData(d.id)">Dzēst</button>
                </td>

            </tr>
            <tr>
                <td style="text-align:right;"><b>Kopā:</b></td>
                <td v-for="(ilgums, j) in ilgumiKopa" :key="'datakopa'+j" :class="{'brivdienacol6':j == 5, 'brivdienacol7':j == 6, 'sodienacol':j==currentDay-1 && nedelas_nr == todayWeek && j != 5 && j != 6}" class="beigas" style="text-align:center;">
                    <b>{{ ilgums }}</b>
                </td>
            </tr>
        </table>
        <div class="modal fade" id="tooMuchModal" tabindex="-1" aria-labelledby="tooMuchModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Kļūda</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>Dienā ir tikai 24 stundas. Ievadīts vairāk par 24 stundām vienā dienā.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Labi</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="incorrectModal" tabindex="-1" aria-labelledby="incorrectModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Kļūda</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>Ievadītās stundas neatbilst skaitļu formātam!</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Labi</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'home',
    props: ['app'],
    data(){
        return {
            loading: false,
            currentDay: new Date().getDay() == 0 ? 7 : new Date().getDay(),
            datums: new Date(new Date().setDate(new Date().getDate() - (new Date().getDay() == 0 ? 7 : new Date().getDay()) + 1)),
            todayWeek: this.getWeekNumber(new Date(new Date().setDate(new Date().getDate() - (new Date().getDay() == 0 ? 7 : new Date().getDay()) + 1))),
            projekti: [],
            darbi: [],
            data: [],
            project: '-',
            job: '',
            darbiParasti: [],
            addRow: ['', '', '', '', '', '', ''],
            editRow: ['', '', '', '', '', '', ''],
            editId: -1,
            render: true,
            backdoor: 0
        }
    },
    mounted() {
        this.init();
    },
    methods: {
        init() {
            if(this.app.user == null){
                this.$router.push('/login');
            }
            this.loading = true;
            let loading1 = true, loading2 = true;
            this.app.req.get('projekti/getActive').then(response => {
                this.projekti = response.data.projekti;
                this.darbiParasti = response.data.parasti.map(function(d){
                    return d.name;
                });
                this.darbi = this.darbiParasti;
                this.job = this.darbi[0];
                loading1 = false;
                this.loading = loading1 || loading2;
            });
            this.app.req.get('data/getforUser').then(response => {
                this.data = response.data.data;
                loading2 = false;
                this.loading = loading1 || loading2;
            });
        },
        nedelaPaKreisi(){
            this.datums.setDate(this.datums.getDate() - 7);
            this.backdoor++;
            this.render = false;
            this.$nextTick(() => {
                this.render = true;
            });
        },
        nedelaPaLabi(){
            this.datums.setDate(this.datums.getDate() + 7);
            this.backdoor++;
            this.render = false;
            this.$nextTick(() => {
                this.render = true;
            });
        },
        dateFormat(a){
            if(parseInt(a) < 10){
                return '0'+a;
            }else{
                return a;
            }
        },
        getWeekNumber(d) {
            d = new Date(Date.UTC(d.getFullYear(), d.getMonth(), d.getDate()));
            d.setUTCDate(d.getUTCDate() + 4 - (d.getUTCDay()||7));
            var yearStart = new Date(Date.UTC(d.getUTCFullYear(),0,1));
            var weekNo = Math.ceil(( ( (d - yearStart) / 86400000)+1)/7);
            return weekNo;
        },
        getDateMonth(i){
            let d = new Date(Date.UTC(this.datums.getFullYear(), this.datums.getMonth(), this.datums.getDate()));
            d.setDate(d.getDate() + i);
            return this.dateFormat(d.getDate())+'.'+this.dateFormat(d.getMonth()+1);
        },
        ilgumi(s){
            return s.split(";").map(function(string){
                return string.split("=")[1];
            }).slice(0, 7);
        },
        changeProject(){
            let project_id = this.project;
            if(project_id == '-'){
                this.darbi = this.darbiParasti;
                this.job = this.darbi[0];
            }else{
                this.darbi = this.projekti.filter(function(p){
                    return p.id == project_id;
                })[0].darbi.split(";");
                this.job = this.darbi[0];
            }
        },
        getIlgumsString(a){
            let s = '';
            let kopa = this.ilgumiKopa;
            for(let i = 0; i < a.length; i++){
                if(isNaN(a[i])){
                    return "error";
                }
                if(parseFloat(a[i])+kopa[i] > 24){
                    return "too_much";
                }
                if(isNaN(a[i]) || parseFloat(a[i])+kopa[i] > 24){
                    a[i] = '0';
                }
                s += (i+1)+"="+(a[i] == '' ? '0' : parseFloat(a[i]))+";";
            }
            return s;
        },
        addIlgumi(){
            if(this.getIlgumsString(this.addRow) == "too_much"){
                $("#tooMuchModal").modal('show');
                return;
            }
            if(this.getIlgumsString(this.addRow) == "error"){
                $("#incorrectModal").modal('show');
                return;
            }
            const data = {
                project_id: this.project == '-' ? 0 : this.project,
                job: this.job,
                ilgums: this.getIlgumsString(this.addRow),
                datums: this.datums.getFullYear()+'-'+this.dateFormat(this.datums.getMonth()+1)+'-'+this.dateFormat(this.datums.getDate())
            };

            this.app.req.post('data/add', data).then(response => {
                this.data = response.data.data;
                this.addRow = ['', '', '', '', '', '', ''];
                $("#addDatiButton").blur();
            });
        },
        deleteData(id){
            const data = {
                id: id
            };
            this.app.req.post('data/delete', data).then(response => {
                this.data = response.data.data;
            });
        },
        editData(id){
            if(this.editId == -1){
                this.editId = id;
                this.editRow = this.ilgumi(this.nedelasData.filter(function(d){
                    return d.id == id;
                })[0].ilgums);
            }
        },
        saveData(){
            if(this.getIlgumsString(this.editRow) == "too_much"){
                $("#tooMuchModal").modal('show');
                return;
            }
            if(this.getIlgumsString(this.editRow) == "error"){
                $("#incorrectModal").modal('show');
                return;
            }
            const data = {
                id: this.editId,
                ilgums: this.getIlgumsString(this.editRow)
            }
            this.app.req.post('data/edit', data).then(response => {
                this.editId = -1;
                this.data = response.data.data;

            });
        }
    },
    computed:{
        nedelasData(){
            this.backdoor;
            if(this.data.length == 0){
                return [];
            }
            let datums = this.datums.getFullYear()+'-'+this.dateFormat(this.datums.getMonth()+1)+'-'+this.dateFormat(this.datums.getDate());
            return this.data.filter(function(d){
                return d.datums == datums;
            }).sort(function(a,b){
                return a.project.nosaukums < b.project.nosaukums ? -1 : 1;
            });
        },
        ilgumiKopa(){
            if(this.nedelasData.length == 0){
                return [0, 0, 0, 0, 0, 0, 0];
            }
            let sum = [0, 0, 0, 0, 0, 0, 0];
            let data = this.nedelasData;
            let nedela = [];
            for(let i = 0; i < data.length; i++){
                nedela = this.ilgumi(data[i].ilgums);
                for(let j = 0; j < nedela.length; j++){
                    sum[j] += parseFloat(nedela[j]);
                }
            }
            return sum;
        },
        nedelas_nr(){
            this.backdoor;
            return this.getWeekNumber(this.datums);
        }
    }
}
</script>
