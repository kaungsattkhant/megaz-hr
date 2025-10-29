<template>
    <div class="mt-4 bg-white">
        <div class=" grid grid-cols-2 gap-x-8 gap-y-4">
            <div class="card-shadow p-8">
                <p class=" text-black mb-2 font-semibold">
                    Total No of Pax KTV Compare
                </p>
                <p v-if="loading">Loading chart...</p>

                <canvas v-show="!loading" ref="customer"></canvas>

                <div class="box-container-table !shadow-none">
                    <div class="overflow-x-auto">
                        <div class="table-container">
                            <table class="primary-table">
                                <thead v-html="cusTable"></thead>
                                <tbody>
                                    <div class="contents" v-for="(cus, index) in customerData" :key="index">
                                        <tr class="" v-show="index < 4">
                                            <td class=" font-medium ">
                                                {{cus.month_name}}
                                            </td>
                                            <td class="whitespace-nowrap">
                                                {{ cus.amount }}
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
                    Total No of Section KTV Compare
                </p>
                <p v-if="loading">Loading chart...</p>
                <canvas v-show="!loading" ref="session"></canvas>
                <div class="box-container-table !shadow-none">
                    <div class="overflow-x-auto">
                        <div class="table-container">
                            <table class="primary-table">
                                <thead v-html="sessionTable"></thead>
                                <tbody>
                                    <div class="contents" v-for="(ses, index) in sessionData" :key="index">
                                        <tr class="" v-show="index < 4">
                                            <td class=" font-medium ">
                                                {{ses.month_name}}
                                            </td>
                                            <td class="whitespace-nowrap">
                                                {{ ses.total_ktv_sessions }}
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
                    Total No of Room Charges KTV Compare
                </p>
                <p v-if="loading">Loading chart...</p>
                <canvas v-show="!loading" ref="room"></canvas>
                <div class="box-container-table !shadow-none">
                    <div class="overflow-x-auto">
                        <div class="table-container">
                            <table class="primary-table">
                                <thead v-html="roomTable"></thead>
                                <tbody>
                                    <div class="contents" v-for="(room, index) in roomData" :key="index">
                                        <tr class="" v-show="index < 4">
                                            <td class=" font-medium ">
                                                {{room.month_name}}
                                            </td>
                                            <td class="whitespace-nowrap">
                                                {{ room.total_ktv_room_charges }}
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
                    Total No of Sale KTV Compare
                </p>
                <p v-if="loading">Loading chart...</p>
                <canvas v-show="!loading" ref="sale"></canvas>
                <div class="box-container-table !shadow-none">
                    <div class="overflow-x-auto">
                        <div class="table-container">
                            <table class="primary-table">
                                <thead v-html="saleTable"></thead>
                                <tbody>
                                    <div class="contents" v-for="(sale, index) in saleData" :key="index">
                                        <tr class="" v-show="index < 4">
                                            <td class=" font-medium ">
                                                {{sale.month_name}}
                                            </td>
                                            <td class="whitespace-nowrap">
                                                {{ sale.total_ktv_sales }}
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
      customerChart: null,
      customerData: [],
      sessionChart: null,
      sessionData: [],
      roomChart: null,
      roomData: [],
      saleChart: null,
      saleData: [],
      loading: true,
      refreshInterval: null,

      cusTable: '<tr><th scope="col" class="">Month</th><th scope="col" class="">Pax</th></tr>',
      sessionTable: '<tr><th scope="col" class="">Month</th><th scope="col" class="">KTV Section</th></tr>',
      roomTable: '<tr><th scope="col" class="">Month</th><th scope="col" class="">Room Charges</th></tr>',
      saleTable: '<tr><th scope="col" class="">Month</th><th scope="col" class="">KTV Sales</th></tr>',
    };
  },

//   computed: {
//     ...mapGetters(['getToken', 'getFeature']),
//   },

methods: {
    ...mapGetters(['getToken', 'getFeature']),
    async getCustomerChartData() {
      try {
        const response = await getApiData({url: `/api/report/get-total-ktv-customers`,token: this.getToken()});
        if (response.data) {
            this.customerData = response.data;
            const labels = response.data.map(item => item.month_name);
            const values = response.data.map(item => item.amount);
            this.$nextTick(() => {
                if (!this.customerChart) {
                    const ctx = this.$refs.customer?.getContext('2d');
                    if (!ctx) {
                        console.error('Canvas not found!');
                        return;
                    }
                    this.customerChart = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels,
                            datasets: [
                                {
                                    label: 'Total Pax',
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
              this.customerChart.data.labels = labels;
              this.customerChart.data.datasets[0].data = values;
              this.customerChart.update();
            }
          });
        }
      } catch (error) {
        console.error('Error fetching Customer chart data:', error);
      } finally {
        this.loading = false;
      }
    },
    async getSessionChartData() {
      try {
        const response = await getApiData({url: `/api/report/get-total-ktv-sessions`,token: this.getToken()});
        if (response.data) {
            this.sessionData = response.data;
            const labels = response.data.map(item => item.month_name);
            const values = response.data.map(item => item.total_ktv_sessions);
            this.$nextTick(() => {
                if (!this.sessionChart) {
                    const ctx = this.$refs.session?.getContext('2d');
                    if (!ctx) {
                        console.error('Canvas not found!');
                        return;
                    }
                    this.sessionChart = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels,
                            datasets: [
                                {
                                    label: 'KTV Sessions',
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
              this.sessionChart.data.labels = labels;
              this.sessionChart.data.datasets[0].data = values;
              this.sessionChart.update();
            }
          });
        }
      } catch (error) {
        console.error('Error fetching Session chart data:', error);
      } finally {
        this.loading = false;
      }
    },
    async getRoomChartData() {
      try {
        const response = await getApiData({url: `/api/report/get-total-ktv-room-charges`,token: this.getToken()});
        if (response.data) {
            this.roomData = response.data;
            const labels = response.data.map(item => item.month_name);
            const values = response.data.map(item => item.total_ktv_room_charges);
            this.$nextTick(() => {
                if (!this.roomChart) {
                    const ctx = this.$refs.room?.getContext('2d');
                    if (!ctx) {
                        console.error('Canvas not found!');
                        return;
                    }
                    this.roomChart = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels,
                            datasets: [
                                {
                                    label: 'Room Charges',
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
              this.roomChart.data.labels = labels;
              this.roomChart.data.datasets[0].data = values;
              this.roomChart.update();
            }
          });
        }
      } catch (error) {
        console.error('Error fetching Room chart data:', error);
      } finally {
        this.loading = false;
      }
    },
    async getSaleChartData() {
        try {
            const response = await getApiData({url: `/api/report/get-total-ktv-sales`,token: this.getToken()});
            if (response.data) {
                this.saleData = response.data;
                const labels = response.data.map(item => item.month_name);
                const values = response.data.map(item => item.total_ktv_sales);
                this.$nextTick(() => {
                    if (!this.saleChart) {
                        const ctx = this.$refs.sale?.getContext('2d');
                        if (!ctx) {
                            console.error('Canvas not found!');
                            return;
                        }
                        this.saleChart = new Chart(ctx, {
                            type: 'line',
                            data: {
                                labels,
                                datasets: [
                                    {
                                        label: 'KTV Sales',
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
                        this.saleChart.data.labels = labels;
                        this.saleChart.data.datasets[0].data = values;
                        this.saleChart.update();
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

    async getRoomList(){
            let response = await getApiData({url: `/api/report/bar/total-expense`, token: this.getToken()});
            if(response.data){
            }
        },

},

  mounted() {
    this.getCustomerChartData();
    this.getSessionChartData();
    this.getRoomChartData();
    this.getSaleChartData();

    this.getRoomList();
    // ⏰ Optional auto refresh
    // this.refreshInterval = setInterval(this.fetchChartData, 10000);
  },

  beforeUnmount() {
    // if (this.refreshInterval) clearInterval(this.refreshInterval);
    // if (this.chart) this.chart.destroy();
  },
};
</script>
