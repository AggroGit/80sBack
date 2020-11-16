@extends('mails.email')


@section('cssExtra')
<style type="text/css">
/* -------------------------------------
  GLOBAL
  A very basic CSS reset
------------------------------------- */
* {
  margin: 0;
  padding: 0;
  font-family: "Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif;
  box-sizing: border-box;
  font-size: 14px;
}

img {
  max-width: 100%;
}

body {
  -webkit-font-smoothing: antialiased;
  -webkit-text-size-adjust: none;
  width: 100% !important;
  height: 100%;
  line-height: 1.6;
}

/* Let's make sure all tables have defaults */
table td {
  vertical-align: top;
}

/* -------------------------------------
  BODY & CONTAINER
------------------------------------- */
body {
  background-color: #f6f6f6;
}

.body-wrap {
  background-color: #f6f6f6;
  width: 100%;
}

.container {
  display: block !important;
  max-width: 600px !important;
  margin: 0 auto !important;
  /* makes it centered */
  clear: both !important;
}

.content {
  max-width: 600px;
  margin: 0 auto;
  display: block;
  padding: 20px;
}

/* -------------------------------------
  HEADER, FOOTER, MAIN
------------------------------------- */
.main {
  background: #fff;
  border: 1px solid #e9e9e9;
  border-radius: 3px;
}

.content-wrap {
  padding: 20px;
}

.content-block {
  padding: 0 0 20px;
}

.header {
  width: 100%;
  margin-bottom: 20px;
}

.footer {
  width: 100%;
  clear: both;
  color: #999;
  padding: 20px;
}
.footer a {
  color: #999;
}
.footer p, .footer a, .footer unsubscribe, .footer td {
  font-size: 12px;
}

/* -------------------------------------
  TYPOGRAPHY
------------------------------------- */
h1, h2, h3 {
  font-family: "Helvetica Neue", Helvetica, Arial, "Lucida Grande", sans-serif;
  color: #000;
  margin: 40px 0 0;
  line-height: 1.2;
  font-weight: 400;
}

h1 {
  font-size: 32px;
  font-weight: 500;
}

h2 {
  font-size: 24px;
}

h3 {
  font-size: 18px;
}

h4 {
  font-size: 14px;
  font-weight: 600;
}

p, ul, ol {
  margin-bottom: 10px;
  font-weight: normal;
}
p li, ul li, ol li {
  margin-left: 5px;
  list-style-position: inside;
}

/* -------------------------------------
  LINKS & BUTTONS
------------------------------------- */
a {
  color: #1ab394;
  text-decoration: underline;
}

.btn-primary {
  text-decoration: none;
  color: #FFF;
  background-color: #1ab394;
  border: solid #1ab394;
  border-width: 5px 10px;
  line-height: 2;
  font-weight: bold;
  text-align: center;
  cursor: pointer;
  display: inline-block;
  border-radius: 5px;
  text-transform: capitalize;
}

/* -------------------------------------
  OTHER STYLES THAT MIGHT BE USEFUL
------------------------------------- */
.last {
  margin-bottom: 0;
}

.first {
  margin-top: 0;
}

.aligncenter {
  text-align: center;
}

.alignright {
  text-align: right;
}

.alignleft {
  text-align: left;
}

.clear {
  clear: both;
}

/* -------------------------------------
  ALERTS
  Change the class depending on warning email, good email or bad email
------------------------------------- */
.alert {
  font-size: 16px;
  color: #fff;
  font-weight: 500;
  padding: 20px;
  text-align: center;
  border-radius: 3px 3px 0 0;
}
.alert a {
  color: #fff;
  text-decoration: none;
  font-weight: 500;
  font-size: 16px;
}
.alert.alert-warning {
  background: #f8ac59;
}
.alert.alert-bad {
  background: #ed5565;
}
.alert.alert-good {
  background: #1ab394;
}

/* -------------------------------------
  INVOICE
  Styles for the billing table
------------------------------------- */
.invoice {
  margin: 40px auto;
  text-align: left;
  width: 80%;
}
.invoice td {
  padding: 5px 0;
}
.invoice .invoice-items {
  width: 100%;
}
.invoice .invoice-items td {
  border-top: #eee 1px solid;
}
.invoice .invoice-items .total td {
  border-top: 2px solid #333;
  border-bottom: 2px solid #333;
  font-weight: 700;
}

/* -------------------------------------
  RESPONSIVE AND MOBILE FRIENDLY STYLES
------------------------------------- */
@media only screen and (max-width: 640px) {
  h1, h2, h3, h4 {
      font-weight: 600 !important;
      margin: 20px 0 5px !important;
  }

  h1 {
      font-size: 22px !important;
  }

  h2 {
      font-size: 18px !important;
  }

  h3 {
      font-size: 16px !important;
  }

  .container {
      width: 100% !important;
  }

  .content, .content-wrap {
      padding: 10px !important;
  }

  .invoice {
      width: 100% !important;
  }
}

</style>
@endsection
@section('content')


<div class="container">

  <table border="0" width="100%" cellpadding="0" cellspacing="0" bgcolor="ffffff" class="bg_color">

      <tr>
          <td align="center">
              <table border="0" align="center" width="590" cellpadding="0" cellspacing="0" class="container590">
                  <tr>
                      <td align="center">
                          <table border="0" width="400" align="center" cellpadding="0" cellspacing="0" class="container590">
                              <tr>
                                  <td align="center" style="color: #888888; font-size: 16px; font-family: 'Work Sans', Calibri, sans-serif; line-height: 24px;">
                                      <div style="line-height: 24px">
                                        {{$text?? ""}}
                                      </div>
                                  </td>
                              </tr>
                          </table>
                      </td>
                  </tr>

                  <tr>
                      <td height="25" style="font-size: 25px; line-height: 25px;">&nbsp;</td>
                  </tr>

                  @if(isset($option))
                    @include('mails.layouts.button')
                  @endif



              </table>

          </td>
      </tr>

  </table>



  <table class="body-wrap">
    <tbody><tr>
        <td></td>
        <td class="container" width="600">
            <div class="content">
                <table class="main" width="100%" cellpadding="0" cellspacing="0">
                    <tbody><tr>
                        <td class="content-wrap aligncenter">
                            <table width="100%" cellpadding="0" cellspacing="0">
                                <tbody><tr>
                                    <td class="content-block">
                                        <h2>{{$ticket?? "Tu pedido"}} 🧾</h2>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="content-block">
                                        <table class="invoice">
                                            <tbody>
                                            <tr>
                                                <td>
                                                    <table class="invoice-items" cellpadding="0" cellspacing="0">
                                                        <tbody>
                                                          @foreach($orders as $order)
                                                          <tr>
                                                            <td>
                                                              {{$order->quantity}} x
                                                              {{$order->product->name}}
                                                              ({{$order->product->price }} € /  {{$order->price_per}} )
                                                              @if($order->price_per !== "unit" && $order->price_per !=="pack_of_units")
                                                                x {{$order->howmuch}} {{$order->price_per}}
                                                              @endif

                                                            </td>
                                                            <td class="alignright">€ {{$order->price}}</td>
                                                          </tr>
                                                          @endforeach

                                                        <tr class="total">
                                                            <td class="" width="80%">  Total</td>
                                                            <td class="alignright">€ {{$orders->sum('price')}}</td>
                                                        </tr>
                                                        <tr>
                                                          <td>
                                                            @if($purchase->pay_in_hand)
                                                              Pago en mano 💰
                                                            @else
                                                              Se cobrará con tarjeta 💳
                                                            @endif
                                                          </td>
                                                        </tr>
                                                    </tbody>
                                                  </table>
                                                </td>
                                            </tr>
                                        </tbody>
                                      </table>
                                    </td>
                                </tr>
                                <!-- <tr>
                                    <td class="content-block">
                                        <a href="#">View in browser</a>
                                    </td>
                                </tr> -->
                                <!-- <tr>
                                    <td class="content-block">
                                        Company Inc. 123 Van Ness, San Francisco 94102
                                    </td>
                                </tr> -->
                            </tbody></table>
                        </td>
                    </tr>
                </tbody></table>
                <!-- <div class="footer">
                    <table width="100%">
                        <tbody><tr>
                            <td class="aligncenter content-block">Questions? Email <a href="mailto:">support@company.inc</a></td>
                        </tr>
                    </tbody></table>
                </div></div> -->
        </td>
        <td></td>
    </tr>
</tbody></table>
</div>
@endsection
