<template>
  <figure>
    <vue-high-charts ref="chart" v-bind:options="options"></vue-high-charts>
  </figure>
</template>

<script>
  import moment        from 'moment';
  import VueHighCharts from 'vue2-highcharts';

  export default {
    name: 'TicketsChart',

    components: {
      VueHighCharts
    },

    props: {
      tickets: {
        type:     Array,
        required: true
      }
    },

    data () {
      return {
        options: {
          chart:       {
            type: 'spline'
          },
          plotOptions: {
            series: {
              dataLabels: {
                enabled: false
              }
            }
          },
          title:       {
            text: 'Downloads'
          },
          subtitle:    {
            text: 'My Untitled Chart'
          },
          exporting:   {
            enabled: false
          },
          series:      [],
          yAxis:       {
            type:   'linear',
            title:  {
              text: 'Downloads'
            },
            labels: {}
          },
          xAxis:       {
            title:             {
              text: ''
            },
            labels:            {
              format: '{value:%A %H:%M}'
            },
            type:              'datetime',
            tickInterval:      86400000,
            tickPixelInterval: 100
          },
          legend:      {
            enabled: false
          },
          tooltip: {
            borderRadius: 4,
            shared: false,
            borderWidth: 2,
            backgroundColor: "rgba(247,247,247,0.85)",
            borderColor: "#4db6ac",
            enabled: true,
            dateTimeLabelFormats: {
              day: "%A, %b %e %Y"
            }
          },
        }
      };
    },

    mounted () {
      const seriesData = Object.entries(
        this.tickets
            .map( ticket => moment( ticket.created_at ).startOf( 'hour' ).valueOf() )
            .reduce( ( acc, curr ) => {
              if ( !acc.hasOwnProperty( curr ) ) {
                acc[ curr ] = 1;
              } else {
                acc[ curr ]++;
              }

              return acc;
            }, {} )
      );
      console.log( this.tickets, seriesData );

      this.$refs.chart.addSeries(
        {
          name: `Downloads`,
          data: seriesData
        }
      );
    },

    methods: {
      load () {
        const chart = this.$refs.chart;
        chart.delegateMethod( 'showLoading', 'Loading...' );
        setTimeout( () => {
          chart.addSeries( [] );
          chart.hideLoading();
        }, 2000 );
      }
    }
  };
</script>

<style scoped>

</style>
