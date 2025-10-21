<template>
    <div class="w-full max-w-lg mx-auto text-center">
        <p v-if="loading">Loading chart...</p>
        <canvas v-else ref="canvas"></canvas>
    </div>
</template>
  
  <script>
    import { Modal, Ripple, Select, initTE, Input } from "tw-elements";
    import { getApiData, postApiData, deleteApiData } from '../../utilities/ajax-helpers';
    import { mapGetters } from "vuex";
    import TableSkeleton from "../Common/TableSkeleton.vue";
    // import { Chart, registerables } from 'chart.js'
  
    // Chart.register(...registerables)
  
  export default {
    data() {
      return {
        chart: null,
        loading: true,
        refreshInterval: null,
      }
    },
  
    methods: {
        ...mapGetters(['getToken', 'getFeature', 'getFeature']),

        async fetchChartData(){
            let response = await getApiData({url: `/api/report/get-total-ktv-customers`, token: this.getToken()});
            if(response.data){
                this.roomList = response.data;

                const labels = response.data.labels
                const values = response.data.values

                if (!this.chart) {
                    // 🆕 Create the chart
                    this.chart = new Chart(this.$refs.canvas, {
                        type: 'line',
                        data: {
                            labels,
                            datasets: [
                                {
                                    label: 'Sales ($)',
                                    data: values,
                                    borderColor: 'rgba(54, 162, 235, 1)',
                                    backgroundColor: 'rgba(54, 162, 235, 0.3)',
                                    borderWidth: 2,
                                    tension: 0.3,
                                    fill: true,
                                },
                            ],
                        },
                        options: {
                            responsive: true,
                            animation: false,
                            scales: { y: { beginAtZero: true } },
                        },
                    })
                } else {
                    // 🔄 Update existing chart
                    this.chart.data.labels = labels
                    this.chart.data.datasets[0].data = values
                    this.chart.update()
                }
            }
        },
    //     async fetchChartData() {
    //     try {
    //       const res = await fetch('/api/report/get-total-ktv-customers')
    //       const data = await res.json()
  
          
    //     } catch (err) {
    //       console.error('Error fetching chart data:', err)
    //     } finally {
    //       this.loading = false
    //     }
    //   },
    },
  
    mounted() {
        this.fetchChartData()
    
        // ⏰ Refresh every 10 seconds
        this.refreshInterval = setInterval(this.fetchChartData, 10000)
    },
  
    beforeUnmount() {
        if (this.refreshInterval) clearInterval(this.refreshInterval)
        if (this.chart) this.chart.destroy()
    },
  }
  </script>
  
  <style scoped>
  canvas {
    max-width: 100%;
  }
  </style>
  