<template>
    <div>
      <!-- navbar -->


      <div class="container-fluid">
          asd
      </div>
    </div>
</template>



<script>

export default {

  mounted() {
    console.log(window.name);
    this.channel = Echo.join("1.User.1");

    this.channel.listen('NotificationEvent',(event)=>{
      console.log(event)
    })

  }
};
</script>
