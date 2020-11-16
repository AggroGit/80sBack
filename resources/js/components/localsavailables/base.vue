<template>
    <div class="container">
      <div class="ContieneInput">
        <input
          @keyup.enter="Test()"
          v-model = "mySearch"
          type="text"
          name="message"
          placeholder="Nombre de local o barrio"
          class="form-control fijo"
          value="">
      </div>
     <router-view v-bind:data="this.comercios"></router-view>
     <div v-if="this.loading"  v-on:click="changeName()" class="text-center">
       Cargando Datos...
     </div>



     <div v-if="this.comercios.length  == 0 || this.loading==true"  v-on:click="changeName()" class="text-center">
       No hay resultados para su busqueda
     </div>
     <br>

     <div v-if="this.loading" class="ContieneLoader text-center">
       <div class="loader"></div>
     </div>

     <navBar v-bind:data='this.test'></navBar>
    </div>
</template>

<script>


export default {
  data() {

    return {
      comercios:[],
      loading:true,
      mapa: false,
      name: "testeoname",
      test: this.$store.getters.firstName,
      mySearch :"",
      params:{

      }

    }
  },
  mounted() {
    console.log(this.$store.state.name);
    this.Search();


  },
  methods:{
    changeName() {
      this.test = "juan"

    },
    Search() {


      this.loading = true;
      var searchSearver ={};
      if(this.mySearch!=="") {
        searchSearver['Nom_Barri']=this.mySearch
      }

      const params = {
        q:searchSearver
      }
      axios.get("/api/open-data", {params}, {

        }).then(res => {
          this.loading = false;
          this.comercios = res.data.data.result.records
          console.log(this.comercios);
        }).catch(err => {
          console.log(err.response);
        });

      // axios.defaults.headers.common['Access-Control-Allow-Origin'] = '*';
      // axios.post("https://opendata-ajuntament.barcelona.cat/data/api/action/datastore_search?resource_id=c897c912-0f3c-4463-bdf2-a67ee97786ac").then(response => {
      //   console.log(response)
      //
      // })
   //
 },
 Test() {
      console.log('test')

 }


  }

};
</script>
