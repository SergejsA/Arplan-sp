<template>
    <div class="container-fluid">
        <spinner v-if="loading"></spinner>
        <div class="container mt-5" v-else>
            <div class="row">
                <div class="col">
                    <span style="font-weight:bold;">No:</span><input type="date" id="startDateInput" v-model="no" class="form-control" @change="refreshChart">
                </div>
                <div class="col">
                    <span style="font-weight:bold;">Līdz:</span><input type="date" id="endDateInput" v-model="lidz" class="form-control" @change="refreshChart">
                </div>
            </div>
            <div style="margin-top:5px;overflow:hidden;">
                <div class="row">
                    <div class="col">
                        <span style="margin-right:27px;font-weight:bold;">Projekts:</span>
                        <select id="SelectDatiProjektiBox" style="margin-right:7px;" class="form-control" v-model="project" @change="refreshChart">
                            <option value="0">-</option>
                            <option v-for="p in projekti" :key="p.id" :value="p.id">{{ p.nosaukums }}</option>
                        </select>
                    </div>
                    <div class="col" v-if="app.user.tips == 'admin' || app.user.tips == 'system_admin'">
                        <span style="margin-right:70px;font-weight:bold;">Darbnieks:</span>
                        <select style="margin-right:7px;" class="form-control" v-model="user" @change="refreshChart">
                            <option value="visi">Visi</option>
                            <option v-for="u in lietotaji" :key="u.id" :value="u.id">{{ u.vards + ' ' + u.uzvards }}</option>
                        </select>
                    </div>
                    <div class="col">
                        <span style="font-weight:bold;">Grafika tips:</span>
                        <select id="SelectGraphType" class="form-control" v-model="type" @change="refreshChart">
                            <option value="bar">Stabiņu</option>
                            <option value="pie">Aplis</option>
                            <option value="doughnut">Virtulis</option>
                            <option value="horizontalBar">Joslu</option>
                            <option value="line">Līnijas</option>
                            <option value="radar">Radars</option>
                        </select>
                    </div>
                </div>
                <!-- <div id="CSVLinks" style="padding:10px 0;float:left;"><a href="scripts/iegustCSV.csv" download="iegutCSV.csv" style="text-decoration:none;color:blue;">Iegūt .csv datni</a></div> -->
            </div>
            <div class="row mt-3">
                <div class="col">
                    <button class="btn btn-outline-primary" @click="exportData">Eksportēt uz Excel</button>
                    <table id="darbiProjekta" class="table">
                        <tr><th>Nr</th><th>Darbs</th><th>Stundas</th></tr>
                        <tr v-for="i in labels.length" :key="i">
                            <th>{{i}}</th>
                            <td>{{labels[i-1]}}</td>
                            <td>{{values[i-1]}}</td>
                        </tr>
                        <tr v-if="values.length"><td></td><td><b>Kopā:</b></td><td>{{ sum }}</td></tr>
                    </table>
                </div>
            </div>
            <div class="row mt-5">
                <div class="col">
                    <div id="canvasContainer">
                    <canvas id="myChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'kopsavilkums',
    props: ['app'],
    data(){
        return {
            projekti: [],
            loading: false,
            lietotaji: [],
            no: null,
            lidz: null,
            user: this.app.user.tips == 'user' ? this.app.user.id : 'visi',
            project: '0',
            type: 'bar',
            labels: [],
            values: []
        }
    },
    mounted(){
        this.init();
        // console.log(this.app.user);
        // this.user = this.app.user.tips == 'user' ? this.app.user.id : 'visi';
    },
    methods: {
        exportData(){
            var wb = XLSX.utils.book_new();
            wb.SheetNames.push("Dati");
            let vm = this;
            let lietotajs = this.user == 'visi' ? 'Visi' : this.lietotaji.filter(function(l){
                return l.id == vm.user;
            })[0];
            let l_text = lietotajs == 'Visi' ? "Par visiem lietotājiem " : "Par lietotāju " + lietotajs.vards + " " + lietotajs.uzvards + " ";
            let projekts = this.project == '0' ? '-' : this.projekti.filter(function(p){
                return p.id == parseInt(vm.project);
            })[0];
            let p_text = projekts == '-' ? "-" : projekts.nosaukums;
            var ws_data = [[l_text+"Projektā "+p_text+" periodā "+(this.no == null ? "-" : this.formatDate(this.no))+":"+(this.lidz == null ? "-" : this.formatDate(this.lidz))], ['Nr', 'Darbs', 'Stundas']];
            let max_garums = 0;
            for(let i = 0; i < this.labels.length; i++){
                ws_data.push([(i+1), this.labels[i], this.values[i]]);
                max_garums = this.labels[i].length > max_garums ? this.labels[i].length : max_garums;
            }
            var ws = XLSX.utils.aoa_to_sheet(ws_data);
            wb.Sheets["Dati"] = ws;

            var wscols = [
                { width: 10 },
                { width: max_garums },
                { width: 10 },
            ];

            wb.Sheets["Dati"]["!cols"] = wscols;

            XLSX.writeFile(wb, 'dati.xlsx');

        },
        formatDate(date){
            let d = date.split("-");
            return d[2]+"."+d[1]+"."+d[0];
        },
        init(){
            this.loading = true;
            let loading1 = true, loading2 = true;
            this.app.req.get('projekti/getAll').then(response => {
                this.projekti = response.data.projekti;
                this.defaultJobs = response.data.default;
                this.parastiJobs = response.data.parasti;
                loading1 = false;
                this.loading = loading1 || loading2;
                if(!this.loading){
                    let v = this;
                    $(document).ready(function(){
                        v.refreshChart();
                    });
                }
            });
            this.app.req.get('lietotaji/getAll').then(response => {
                this.lietotaji = response.data.lietotaji.filter(function(l){
                    return l.tips != 'system_admin';
                });
                loading2 = false;
                this.loading = loading1 || loading2;
                if(!this.loading){
                    let v = this;
                    $(document).ready(function(){
                        v.refreshChart();
                    });
                }
            });
        },
        getRandomColor() {
            var letters = '0123456789ABCDEF'.split('');
            var color = '#';
            for (var i = 0; i < 6; i++ ) {
                color += letters[Math.floor(Math.random() * 16)];
            }
            return color;
        },
        refreshChart(){
            let ctx = document.getElementById('myChart');
            var chartC = ctx.getContext("2d");
            ctx.remove();
            $('#canvasContainer').append('<canvas id="myChart"><canvas>');
            var canvas = document.getElementById('myChart');
            var context = canvas.getContext('2d');
            var grafs;
            const data = {
                no: this.no,
                lidz: this.lidz,
                user: this.user,
                project: parseInt(this.project)
            }
            this.app.req.post('data/getChartInfo', data).then(response => {
                let labels = response.data.labels;
                let values = response.data.values;
                this.labels = labels;
                this.values = values;
                var krasas = new Array();
                for(var i = 0; i < labels.length; i++){
                    krasas[i] = this.getRandomColor();
                }
                grafs = new Chart(context, {
                    type:this.type,
                    data:{
                        labels: labels,
                        datasets:[{
                            data:values,
                            backgroundColor: krasas
                        }]
                    },
                    options:{
                        title:{
                            text:"Darbu sadalījums projektā"
                        },
                        scales: {
                            yAxes: [{
                                ticks: {
                                    beginAtZero: true
                                }
                            }],
                            xAxes: [{
                                ticks: {
                                    beginAtZero: true
                                }
                            }]
                        }
                    }
                });
                if(this.type == "pie" || this.type == "doughnut"){
                    grafs.options.legend.display = true;
                }else{
                    grafs.options.legend.display = false;
                }
            });
        }
    },
    computed:{
        sum(){
            if(this.values.length == 0){
                return 0;
            }
            let sum = 0;
            for(let i = 0; i < this.values.length; i++){
                sum += this.values[i];
            }
            return sum;
        }
    }
}
</script>
