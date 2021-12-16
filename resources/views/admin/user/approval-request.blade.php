@extends('layouts.email')

@section('welcome', ucfirst($requests_type).' Request')

@section('image')
    @if(ucfirst($requests_descision) == 'Approved')
        <img align="center" border="0"
            src="{{ asset('images\svg\approved.svg')}}" alt="Apollo Dispose Asset"
            title="Password"
            style="outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;clear: both;display: inline-block !important;border: none;height: auto;float: none;width: 100%;max-width: 460px;"
            width="460" />
    @else    
        <img align="center" border="0"
            src="{{ asset('images\svg\denied.svg')}}" alt="Apollo Dispose Asset"
            title="Password"
            style="outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;clear: both;display: inline-block !important;border: none;height: auto;float: none;width: 100%;max-width: 460px;"
            width="460" />
    @endif
@endsection

 @section('title')
 {{ $requests_title}}
  @endsection

 @section('content')
<p style="font-size: 14px; line-height: 160%;">
    <span style="font-size: 18px; line-height: 28.8px;display:block; margin-bottom: 20px;">
        Hi {{ $user->name}},
    </span>
    <span style="font-size: 18px; line-height: 28.8px;display:block; margin-bottom: 20px;">
        {{ $requests_message}}
    </span>
    <span style="font-size: 18px; line-height: 28.8px;display:block; margin-bottom: 20px;">
        You can login to your account to view more details of the decision by clicking on the button below: 
    </span>
    <!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0" style="border-spacing: 0; border-collapse: collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;font-family:'Cabin',sans-serif;"><tr><td style="font-family:'Cabin',sans-serif;" align="center"><v:roundrect xmlns:v="urn:schemas-microsoft-com:vml" xmlns:w="urn:schemas-microsoft-com:office:word" href="" style="height:50px; v-text-anchor:middle; width:252px;" arcsize="110%" stroke="f" fillcolor="#ffffff"><w:anchorlock/><center style="color:#463b9d;font-family:'Cabin',sans-serif;"><![endif]-->
        <span style="display: block; margin-top: 30px">
        <!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0" style="border-spacing: 0; border-collapse: collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;font-family:'Cabin',sans-serif;"><tr><td style="font-family:'Cabin',sans-serif;" align="center"><v:roundrect xmlns:v="urn:schemas-microsoft-com:vml" xmlns:w="urn:schemas-microsoft-com:office:word" href="" style="height:50px; v-text-anchor:middle; width:252px;" arcsize="110%" stroke="f" fillcolor="#ffffff"><w:anchorlock/><center style="color:#463b9d;font-family:'Cabin',sans-serif;"><![endif]-->
            <a href="https://apollo.clpt.co.uk/requests" target="_blank" class="v-size-width"
            style=" margin:30px; 
                    box-sizing: border-box;
                    display: inline-block;
                    font-family:'Cabin',sans-serif;
                    text-decoration: none;
                    -webkit-text-size-adjust: none;
                    text-align: center;
                    color: #463b9d; 
                    background-color: #ffffff; 
                    border-radius: 55px; 
                    -webkit-border-radius: 55px; 
                    -moz-border-radius: 55px; 
                    width:40%; 
                    max-width:100%; 
                    overflow-wrap: break-word; 
                    word-break: break-word; 
                    word-wrap:break-word; 
                    mso-border-alt: none;
                    border-top-color: #463b9d; 
                    border-top-style: solid; 
                    border-top-width: 0px; 
                    border-left-color: #463b9d; 
                    border-left-style: solid; 
                    border-left-width: 0px; 
                    border-right-color: #463b9d; 
                    border-right-style: solid; 
                    border-right-width: 0px; 
                    border-bottom-color: #463b9d; 
                    border-bottom-style: solid; border-bottom-width: 6px;
                    ">
            <span
                style="display:block;padding: 20px 30px;line-height:120%;"><strong><span
                        style="font-size: 20px; line-height: 24px;">Login to Apollo</span></strong></span>
        </a>
        </span>
    <!--[if mso]></center></v:roundrect></td></tr></table><![endif]-->
</p>
 @endsection