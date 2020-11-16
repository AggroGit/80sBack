<template>
    <div class="container-fluid element">
      <div class="contieneCerrar">
        <a href="/available-locals/">
          <span class="glyphicon glyphicon-remove"></span>
        </a>

        <br>
      </div>
      <div v-if="this.loading"class="ContieneLoader text-center">
        <div class="loader"></div>
      </div>
      <GmapMap
        id="mapaPeke"
        :center="this.LatAndLong()"
        :zoom="15"
        map-type-id="roadmap"
        style="width: 100%; height: 300px"
        >
        <GmapMarker
            :position="this.LatAndLong()"
            :clickable="false"
            :draggable="false"

          />

      </GmapMap>
      <div class="container-fluid element">
          <div class="col-xs-12 text-center">
            <div class="QuadroImagen">
              <h2 class="text-center">{{this.comercio.Nom_CComercial}}</h2>
              <p class="text-center">Id: {{this.comercio.ID_Bcn_2019}}</p>
            </div>
            <br>
          </div>
          <div class="col-xs-12 infoElement">
            <div class="container-fluid ">
                <div class="col-7-xs info">
                  <div   :key="index" v-for="(m, index) in this.comercio" class="">
                    <p>{{index}}: {{m}}</p>
                  </div>
                </div>
            </div>
          </div>
      </div>
    </div>
</template>

<script>



export default {
  props:['data'],
  data() {
    return {
      comercio:this.data,
      loading:true
    }
  },
  mounted() {
    console.log(this.data);
    this.getComerce();
  },
  methods: {
    getComerce(){

      var searchSearver ={};
      searchSearver['ID_Bcn_2019']=this.data


      const params = {
        q:searchSearver
      }
      axios.get("/api/open-data", {params}, {

        }).then(res => {
          this.loading = false;
          this.comercio = res.data.data.result.records[0]
          console.log(this.comercio);
        }).catch(err => {
          console.log(err.response);
        });
    },
    LatAndLong() {
      // return { lat: 40.034038, lng: -75.145223 };
      return {
        lat:parseFloat(this.comercio.Latitud),
        lng:parseFloat(this.comercio.Longitud)
      }
    }

  }
};
</script>
