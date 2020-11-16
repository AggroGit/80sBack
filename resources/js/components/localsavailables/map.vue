<template>
    <div class="">
      <GmapMap
        id="Mapa"
        :center="{lat:41.387331, lng:2.169571}"
        :zoom="13"
        map-type-id="roadmap"
        style="width: 100vw; height: 100vh"
        >
        <!-- <GmapCluster/> -->
        <GmapMarker
            :key="index"
            v-for="(m, index) in this.data"
            :position="LatAndLong(m)"
            :clickable="true"
            :draggable="false"
            v-on:click="IrA(m)"
          />
      </GmapMap>

    </div>
</template>

<script>



export default {
  props:['data'],
  data() {
    return {
      markers:[
        {
          label: "200",
          position: { lat: 39.9695601, lng: -75.1395161 }
        },
        {
          label: "30",
          position: { lat: 40.034038, lng: -75.145223 }
        }
      ],
    }
  },
  mounted() {
    console.log(window.name);


  },
  methods: {
    LatAndLong(element) {
      // return { lat: 40.034038, lng: -75.145223 };
      return {
        lat:parseFloat(element.Latitud),
        lng:parseFloat(element.Longitud)
      }
    },
    IrA(m) {
      
      window.location.replace("/available-locals/"+m.ID_Bcn_2019);
    }
  }
};
</script>
