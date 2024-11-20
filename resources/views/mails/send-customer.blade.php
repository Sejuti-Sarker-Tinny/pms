@component('mail::message')

{{ $details['title'] }}

<h3 style="color: #3b7ddd; font-size:20px">Dear {{ $details['name'] }}, </h3>
<span style="color: #100257; font-size:15px">{{ $details['detailinfo'] }}</span>

Thanks,<br>
Food Melody

@endcomponent



