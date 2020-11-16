<template>

  <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
    <div class="left-menu">
      <div class="container contieneItemsMenu">
        <div class="row">
            <li class="nav-item col-sm-12">
              <a class="nav-link blanco" href="#">INICIO</a>
            </li>
            <li class="nav-item col-sm-12">
              <a class="nav-link blanco" href="#">PRODUCTOS</a>
            </li>
            <li class="nav-item col-sm-12">
              <a class="nav-link blanco " href="#">PEDIDOS</a>
            </li>
        </div>
      </div>
    </div>



      <div class="container menu-superior">
          <div class="navbar" id="">
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="asd">
                <span class="navbar-toggler-icon"></span>
            </button>
            <a class="navbar-brand" href="/">
                Enterprise-Vue
            </a>
              <!-- Left Side Of Navbar -->
              <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a class="nav-link" href="#">stripe</a>
                </li>
              </ul>

              <!-- Right Side Of Navbar -->
              <ul class="navbar-nav ml-auto">
                  <!-- Authentication Links -->
                      <li class="nav-item">
                          <a class="nav-link" href="#">login</a>
                      </li>
              </ul>
          </div>
      </div>
  </nav>
</template>

<script>



export default {
  mounted() {
    console.log(window.name);
  }
};
</script>
