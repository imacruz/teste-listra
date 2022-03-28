<script setup>
    import { Head, Link } from '@inertiajs/inertia-vue3';
    import axios from "axios";
    import {VMoney} from 'v-money';
</script>

<template>
    <Head title="Meus Veículos" />
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                   <h1>Meus Veículos</h1>
                </div>
                <div class="p-6">
                    <div class="mb-2">
                        <select class="w-auto" v-model="selected" :disabled="(vehicles.length == 0) ? true : false" v-on:change="clearData()">
                            <option disabled value="Selecione um veículo" selected>Selecione um veículo</option>
                            <option v-for="vehicle in vehicles" :value="vehicle" v-bind:key="vehicle.id">
                                {{ vehicle.description }}
                            </option>
                        </select>
                    </div>

                    <div class="mt-4">
                        <ul v-if="selected !== 'Selecione um veículo'">
                            <li> <b>></b> {{selected.description}} </li>
                            <li> <b>></b> R$ {{formatPrice(selected.value)}}</li>
                        </ul>
                    </div>

                    <div class="mt-5" v-if="selected !== 'Selecione um veículo'">
                        <h3 class="mb-1">Simulação de Financiamento</h3>
                        <div class="flex-col mt-2">
                            <input class="p-2 border border-black focus:target:rounded-none"  v-model="value" v-money="money" placeholder="Valor de entrada">
                            <button class="p-2 ml-5 bg-blue-200" :disabled="(value == 'R$ 0,00') ? true : false" v-on:click="simulate()">Simular</button>
                        </div>
                        <div class="mt-1">
                            <span class="text-red-400">{{error}}</span>
                        </div>
                    </div>

                    <div class="mt-5">
                        <ul>
                           <li class="mt-2" v-for="(installment, key) in installments" v-bind:key="key">
                              {{key}}x R$ {{formatPrice(installment)}}
                           </li> 
                        </ul>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                vehicles: [],
                selected: "Selecione um veículo",
                value: '',
                money: {
                    decimal: ',',
                    thousands: '.',
                    prefix: 'R$ ',
                    precision: 2,
                    masked: false /* doesn't work with directive */
                }, 
                installments: [],
                error: ''
            }
        },
        directives: {money: VMoney},
        mounted() {
            axios.get("http://localhost/api/vehicles")
                .then((res) => {
                    this.vehicles = res.data.data;
                })
                .catch((error) => {
                    console.log(error)
                });

        },
        methods: {
            async simulate() {
                this.error = '';
                this.installments  = [];
                
                axios.post("http://localhost/api/simulate",{
                    'vehicle_id': this.selected.id,
                    'entry_amount': this.value.replace(/[^0-9]/g, "")
                })
                .then((res) => {
                    this.installments = res.data.installments;
                })
                .catch((error) => {

                    this.error = error.response.data.entry_amount
                });
            },
            formatPrice(value)  {
                let val = (value/1).toFixed(2).replace('.', ',');
                return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            },
            clearData() {
                this.installments = []
            }
        }
    }
</script>
<style scoped>
   .container {
        display: flex; /* or inline-flex */
        flex-direction: column;
        align-content: center;
        justify-content: space-around;
    }
</style>
