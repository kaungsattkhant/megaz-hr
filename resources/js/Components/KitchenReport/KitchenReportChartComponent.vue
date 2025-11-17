<template>
    <div class="margin-bg">
        <div class=" grid grid-cols-2 gap-x-8 gap-y-4">
            <div class="card-shadow p-8">
                <p class=" text-black mb-2 font-semibold">
                    Kitchen For Sky
                </p>
                <p v-if="loading">Loading chart...</p>

                <canvas v-show="!loading" ref="sky"></canvas>

                <div class="box-container-table !shadow-none">
                    <div class="overflow-x-auto">
                        <div class="table-container">
                            <table class="primary-table">
                                <thead v-html="skyTable"></thead>
                                <tbody>
                                    <div class="contents" v-for="(item, index) in kitchenDataForSky" :key="index">
                                        <tr class="" v-show="index < 4">
                                            <td class=" font-medium ">
                                                {{item.month_name}}
                                            </td>
                                            <td class="whitespace-nowrap">
                                                {{ item.total_kitchen_sale }}
                                            </td>
                                        </tr>
                                    </div>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-shadow p-8">
                <p class=" text-black mb-2 font-semibold">
                    Kitchen For KTV
                </p>
                <p v-if="loading">Loading chart...</p>
                <canvas v-show="!loading" ref="ktv"></canvas>
                <div class="box-container-table !shadow-none">
                    <div class="overflow-x-auto">
                        <div class="table-container">
                            <table class="primary-table">
                                <thead v-html="ktvTable"></thead>
                                <tbody>
                                    <div class="contents" v-for="(item, index) in kitchenDataForKtv" :key="index">
                                        <tr class="" v-show="index < 4">
                                            <td class=" font-medium ">
                                                {{item.month_name}}
                                            </td>
                                            <td class="whitespace-nowrap">
                                                {{ item.total_kitchen_sale }}
                                            </td>
                                        </tr>
                                    </div>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-shadow p-8">
                <p class=" text-black mb-2 font-semibold">
                    Kitchen For SKY & KTV
                </p>
                <p v-if="loading">Loading chart...</p>
                <canvas v-show="!loading" ref="skyAndKtv"></canvas>
                <div class="box-container-table !shadow-none">
                    <div class="overflow-x-auto">
                        <div class="table-container">
                            <table class="primary-table">
                                <thead v-html="skyAndKtvTable"></thead>
                                <tbody>
                                    <div class="contents" v-for="(item, index) in kitchenDataForSkyAndKtv" :key="index">
                                        <tr class="" v-show="index < 4">
                                            <td class=" font-medium ">
                                                {{item.month_name}}
                                            </td>
                                            <td class="whitespace-nowrap">
                                                {{ item.total_kitchen_sale }}
                                            </td>
                                        </tr>
                                    </div>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-shadow p-8">
                <p class=" text-black mb-2 font-semibold">
                    Kitchen Department Expense
                </p>
                <p v-if="loading">Loading chart...</p>
                <canvas v-show="!loading" ref="expense"></canvas>
                <div class="box-container-table !shadow-none">
                    <div class="overflow-x-auto">
                        <div class="table-container">
                            <table class="primary-table">
                                <thead v-html="expenseTable"></thead>
                                <tbody>
                                    <div class="contents" v-for="(item, index) in kitchenExpense" :key="index">
                                        <tr class="" v-show="index < 4">
                                            <td class=" font-medium ">
                                                {{item.month_name}}
                                            </td>
                                            <td class="whitespace-nowrap">
                                                {{ item.kitchen_total_expense }}
                                            </td>
                                        </tr>
                                    </div>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- <div class="w-full max-w-lg mx-auto text-center">
        <p v-if="loading">Loading chart...</p>
        <canvas v-show="!loading" ref="canvas"></canvas>
    </div> -->
</template>
  
<script>
import { Modal, Ripple, Select, initTE, Input } from "tw-elements";
import { getApiData } from '../../utilities/ajax-helpers';
import { mapGetters } from "vuex";
import TableSkeleton from "../Common/TableSkeleton.vue";
import { Chart, registerables } from 'chart.js';

Chart.register(...registerables);

export default {
  components: { TableSkeleton },

  data() {
    return {
      skyChart: null,
      kitchenDataForSky: [],
      ktvChart: null,
      kitchenDataForKtv: [],
      skyAndKtvChart: null,
      kitchenDataForSkyAndKtv: [],
      expenseChart: null,
      kitchenExpense: [],
      loading: true,
      refreshInterval: null,

      skyTable: '<tr><th scope="col" class="">Month</th><th scope="col" class="">Amount</th></tr>',
      ktvTable: '<tr><th scope="col" class="">Month</th><th scope="col" class="">Amount</th></tr>',
      skyAndKtvTable: '<tr><th scope="col" class="">Month</th><th scope="col" class="">Amount</th></tr>',
      expenseTable: '<tr><th scope="col" class="">Month</th><th scope="col" class="">Amount</th></tr>',
    };
  },

//   computed: {
//     ...mapGetters(['getToken', 'getFeature']),
//   },

methods: {
    ...mapGetters(['getToken', 'getFeature']),
    async getKitchenDataForSky() {
      try {
        const response = await getApiData({url: `/api/report/kitchen-for-sky-and-ktv?area_type=restaurant`,token: this.getToken()});
        if (response.data) {
            this.barDataForSky = response.data;
            const labels = response.data.map(item => item.month_name);
            const values = response.data.map(item => item.total_kitchen_sale);
            this.$nextTick(() => {
                if (!this.skyChart) {
                    const ctx = this.$refs.sky?.getContext('2d');
                    if (!ctx) {
                        console.error('Canvas not found!');
                        return;
                    }
                    this.skyChart = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels,
                            datasets: [
                                {
                                    label: 'Total Amount',
                                    data: values,
                                    borderColor: 'rgba(54, 162, 235, 1)',
                                    backgroundColor: 'rgba(54, 162, 235, 0.6)',
                                    borderWidth: 2,
                                    tension: 0.3,
                                    fill: true,
                                    pointRadius: 0, // hides points,
                                },
                            ],
                        },
                        options: {
                        responsive: true,
                        animation: false,
                        scales: {
                            x: {
                                grid: {
                                    drawBorder: false, // hides the axis line itself
                                    drawOnChartArea: false, // optional: hide grid lines
                                    drawTicks: false, // optional: hide tick marks
                                },
                                ticks: {
                                    color: '#666', // keep labels if you want
                                },
                            },
                            y: {
                                beginAtZero: true,
                            },
                        },
                    },
                });
            } else {
              this.skyChart.data.labels = labels;
              this.skyChart.data.datasets[0].data = values;
              this.skyChart.update();
            }
          });
        }
      } catch (error) {
        console.error('Error fetching Sky chart data:', error);
      } finally {
        this.loading = false;
      }
    },
    async getKitchenDataForKtv() {
      try {
        const response = await getApiData({url: `/api/report/kitchen-for-sky-and-ktv?area_type=ktv`,token: this.getToken()});
        if (response.data) {
            this.barDataForKtv = response.data;
            const labels = response.data.map(item => item.month_name);
            const values = response.data.map(item => item.total_kitchen_sale);
            this.$nextTick(() => {
                if (!this.ktvChart) {
                    const ctx = this.$refs.ktv?.getContext('2d');
                    if (!ctx) {
                        console.error('Canvas not found!');
                        return;
                    }
                    this.ktvChart = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels,
                            datasets: [
                                {
                                    label: 'Total Amount',
                                    data: values,
                                    borderColor: 'rgba(22, 163, 74, 1)',
                                    backgroundColor: 'rgba(22, 163, 74, 0.6)',
                                    borderWidth: 2,
                                    tension: 0.3,
                                    fill: true,
                                    pointRadius: 0, // hides points,
                                },
                            ],
                        },
                        options: {
                        responsive: true,
                        animation: false,
                        scales: {
                            x: {
                                grid: {
                                    drawBorder: false, // hides the axis line itself
                                    drawOnChartArea: false, // optional: hide grid lines
                                    drawTicks: false, // optional: hide tick marks
                                },
                                ticks: {
                                    color: '#666', // keep labels if you want
                                },
                            },
                            y: {
                                beginAtZero: true,
                            },
                        },
                    },
                });
            } else {
              this.ktvChart.data.labels = labels;
              this.ktvChart.data.datasets[0].data = values;
              this.ktvChart.update();
            }
          });
        }
      } catch (error) {
        console.error('Error fetching KTV chart data:', error);
      } finally {
        this.loading = false;
      }
    },
    async getKitchenDataForSkyAndKtv() {
      try {
        const response = await getApiData({url: `/api/report/kitchen-for-sky-and-ktv`,token: this.getToken()});
        if (response.data) {
            this.barDataForSkyAndKtv = response.data;
            const labels = response.data.map(item => item.month_name);
            const values = response.data.map(item => item.total_kitchen_sale);
            this.$nextTick(() => {
                if (!this.skyAndKtvChart) {
                    const ctx = this.$refs.skyAndKtv?.getContext('2d');
                    if (!ctx) {
                        console.error('Canvas not found!');
                        return;
                    }
                    this.skyAndKtvChart = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels,
                            datasets: [
                                {
                                    label: 'Total Amount',
                                    data: values,
                                    borderColor: 'rgba(126, 34, 206, 1)',
                                    backgroundColor: 'rgba(126, 34, 206, 0.7)',
                                    borderWidth: 2,
                                    tension: 0.3,
                                    fill: true,
                                    pointRadius: 0, // hides points,
                                },
                            ],
                        },
                        options: {
                        responsive: true,
                        animation: false,
                        scales: {
                            x: {
                                grid: {
                                    drawBorder: false, // hides the axis line itself
                                    drawOnChartArea: false, // optional: hide grid lines
                                    drawTicks: false, // optional: hide tick marks
                                },
                                ticks: {
                                    color: '#666', // keep labels if you want
                                },
                            },
                            y: {
                                beginAtZero: true,
                            },
                        },
                    },
                });
            } else {
              this.skyAndKtvChart.data.labels = labels;
              this.skyAndKtvChart.data.datasets[0].data = values;
              this.skyAndKtvChart.update();
            }
          });
        }
      } catch (error) {
        console.error('Error fetching Room chart data:', error);
      } finally {
        this.loading = false;
      }
    },
    async getKitchenExpense() {
        try {
            const response = await getApiData({url: `/api/report/kitchen/total-expenses`,token: this.getToken()});
            if (response.data) {
                this.barExpense = response.data;
                const labels = response.data.map(item => item.month_name);
                const values = response.data.map(item => item.kitchen_total_expense);
                this.$nextTick(() => {
                    if (!this.expenseChart) {
                        const ctx = this.$refs.expense?.getContext('2d');
                        if (!ctx) {
                            console.error('Canvas not found!');
                            return;
                        }
                        this.expenseChart = new Chart(ctx, {
                            type: 'line',
                            data: {
                                labels,
                                datasets: [
                                    {
                                        label: 'Total Amount',
                                        data: values,
                                        borderColor: 'rgba(249, 115, 22, 1)',
                                        backgroundColor: 'rgba(249, 115, 22, 0.6)',
                                        borderWidth: 2,
                                        tension: 0.3,
                                        fill: true,
                                        pointRadius: 0, // hides points,
                                    },
                                ],
                            },
                            options: {
                                responsive: true,
                                animation: false,
                                scales: {
                                    x: {
                                        grid: {
                                            drawBorder: false, // hides the axis line itself
                                            drawOnChartArea: false, // optional: hide grid lines
                                        },
                                        ticks: {
                                            color: '#666', // keep labels if you want
                                        },
                                    },
                                    y: {
                                        beginAtZero: true,
                                    },
                                },
                            },
                        });
                    } 
                    else {
                        this.expenseChart.data.labels = labels;
                        this.expenseChart.data.datasets[0].data = values;
                        this.expenseChart.update();
                    }
                });
            }
        } 
        catch (error) {
            console.error('Error fetching Sale chart data:', error);
        } 
        finally {
            this.loading = false;
        }
    },
},

  mounted() {
    this.getKitchenDataForSky();
    this.getKitchenDataForKtv();
    this.getKitchenDataForSkyAndKtv();
    this.getKitchenExpense();

    // ⏰ Optional auto refresh
    // this.refreshInterval = setInterval(this.fetchChartData, 10000);
  },

  beforeUnmount() {
    // if (this.refreshInterval) clearInterval(this.refreshInterval);
    // if (this.chart) this.chart.destroy();
  },
};
</script>
