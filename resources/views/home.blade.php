@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <input id="card-holder-name" type="text">

                    <!-- Stripe Elements Placeholder -->
                    <div id="card-element"></div>

                    <button id="card-button">
                        Process Payment
                    </button>

                    <script type="application/javascript">
                        const stripe = Stripe('{{env('STRIPE_KEY')}}');

                        const elements = stripe.elements();
                        const cardElement = elements.create('card');

                        cardElement.mount('#card-element');

                        const cardHolderName = document.getElementById('card-holder-name');
                        const cardButton = document.getElementById('card-button');

                        cardButton.addEventListener('click', async (e) => {
                            const { paymentMethod, error } = await stripe.createPaymentMethod(
                                'card', cardElement, {
                                    billing_details: { name: cardHolderName.value }
                                }
                            );

                            if (error) {
                              alert('error')
                                // Display "error.message" to the user...
                            } else {
                                      // The card has been verified successfully...
                                      alert('se ha añadido la targeta correctamente')
                                      //window.location = "{{url('/test&params=')}}"+JSON.stringify(paymentMethod);
                                      console.log('aqui');
                                      console.log(typeof(paymentMethod));
                                      console.log(JSON.stringify(paymentMethod))
                                      // window.location = "{{url('/stripe/test')}}?p="+JSON.stringify(paymentMethod);
                                      var http = new XMLHttpRequest();
                                      var url = "{{url('stripe/test')}}";
                                      var params = JSON.stringify(paymentMethod);

                                      console.log("enviamos")
                                      console.log(params)
                                      var token = document.getElementById('token').value
                                      http.open('POST', url, true);
                                      //Send the proper header information along with the request
                                      http.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');
                                      http.setRequestHeader('X-CSRF-TOKEN', token);
                                      http.onreadystatechange = function() {//Call a function when the state changes.
                                          if(http.readyState == 4 && http.status == 200) {
                                              // alert(http.responseText);
                                              console.log(http.responseText)
                                          }
                                      }
                                      http.send(params);
                            }
                        });
                    </script>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
