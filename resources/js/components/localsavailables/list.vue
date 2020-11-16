<template>
    <div class="ContieneElementos">
      <elemento
        href="/available-locals/"
        :key="index"
        v-for="(m, index) in this.data"
         v-bind:data = m
        />
    </div>
</template>

<script>



export default {
  props:['data'],
  // data() {
  //
  // },
  mounted() {
    // console.log(this.data)
    // console.log('COMERCIOS'+this.data)

  }
};
</script>
