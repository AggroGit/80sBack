<template>
    <div class="container-fluid element">
      <a v-bind:href="'/available-locals/'+this.data.ID_Bcn_2019">
          <div class="col-xs-5">
            <div class="QuadroImagen">
              <h2 class="text-center">{{this.getName()}}</h2>
              <p class="text-center">Id: {{this.data.ID_Bcn_2019}}</p>
            </div>
          </div>
          <div class="col-xs-7 infoElement">
            <div class="container-fluid ">
                <div class="col-7-xs info">
                  <p>{{this.data.Direccio_Unica}}</p>
                  <p>{{this.data.Nom_Local}}</p>
                  <p>{{this.data.Nom_Districte}}, {{this.data.Nom_Barri}}</p>
                </div>
            </div>
          </div>
        </a>
    </div>
</template>

<script>



export default {
  props:['data'],
  mounted() {
    // console.log(this.data);
  },
  methods: {
    getName(){
      // return "";
      return this.data.Nom_Local.split(' ')[0][0]?? ""  +this.data.Nom_Local.split(' ')[1][0]?? "";
    },

  }
};
</script>
