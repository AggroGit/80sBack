@extends('mails.email')

@section('content')



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



@endsection
