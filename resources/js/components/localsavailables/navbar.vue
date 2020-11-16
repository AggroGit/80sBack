<template>

  <div class="navbarInferior">
    <div class="row">
      <div class="container text-center contieneLinea">
        <div v-on:click="openFilterView()" class="linea"></div>
      </div>
    </div>
    <div class="row">
      <div class="container contieneTitiulo">
          <h2>Locales disponibles</h2>
      </div>
    </div>
    <div class="row">
      <div class="col-xs-4 text-center ">
        <div class="" v-on:click="openMap()">
          <router-link v-if='!this.map' to="/available-locals/map"  class="ContieneIcon" >
            <img v-if="!this.map" src="/iconos/map.svg" alt="">
            <p v-if="!this.map">Mapa</p>
          </router-link>
          <router-link v-if='this.map' to="/available-locals/"  class="ContieneIcon" >
            <img v-if="this.map" src="/iconos/list.svg" alt="">
            <p v-if="this.map">Lista</p>
          </router-link>
        </div>
      </div>


      <div class="col-xs-4 text-center ">
          <router-link to="/available-locals/for-my-business" class="ContieneIcon">
            <img src="/iconos/tag.svg" alt="">
            <p>Para Mi Negocio</p>
          </router-link>
      </div>

      <div class="col-xs-4 text-center ">
          <div v-on:click="openFilterView()" class="ContieneIcon">
            <img src="/iconos/filter.svg" alt="">
            <p>Filtrar</p>
          </div>
      </div>
    </div>


    <div class="parteOculta contieneFiltro" v-bind:class="{ closed: !filterOpen }">
      <div class="row justify-content-center">
        <div class="col-md-6">
          <h4 data-toggle="collapse" data-target="#demo">Sector Activitat   <span class="glyphicon glyphicon-chevron-down"></span></h4>
          <div id="demo" class="collapse">
            <div class="checkbox">
              <label><input type="checkbox" value="">Option 1</label>
            </div>
            <div class="checkbox">
              <label><input type="checkbox" name="optionnn" value="">Option 1</label>
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
  data(){
    return {
      map:false,
      mydata: this.data,
      filterOpen:false
    }
  },
  watch: {
  mydata: function(newVal, oldVal) { // watch it
    console.log('Prop changed: ', newVal, ' | was: ', oldVal)

  }
},


  mounted() {
    console.log('->'+this.InputData)
  },
  methods: {
    openMap() {
      this.map = !this.map
      this.mydata = "nop";
      // this.$store.state.name = "juan"
    },
    openFilterView() {
      console.log('open')
      this.filterOpen = !this.filterOpen;
    }
  }
};
</script>
