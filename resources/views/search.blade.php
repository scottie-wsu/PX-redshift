
@extends('layouts.app')
@section('title','History')
@section('content')


<body style="background-image: url({{ asset('images/bg1.jpg') }})">

<div class="overflow-auto">
 @if(isset($details))

<table class="table table-light">
  <thead class="thead-dark">
    <tr>
      <th scope="col"><a href='/history?galaxy_id'>Galaxy ID</th>
      <th scope="col"><a href='/history?optical_u'>Optical u</th>
        <th scope="col"><a href='/history?optical_v'>Optical v</th>
        <th scope="col"><a href='/history?optical_g'>Optical g</th>
        <th scope="col"><a href='/history?optical_r'>Optical r</th>
         <th scope="col"><a href='/history?optical_i'>Optical i</th>
          <th scope="col"><a href='/history?optical_z'>Optical z</th>
          <th scope="col"><a href='/history?infrared_three_six'>Infrared 3.6</th>
          <th scope="col"><a href='/history?infrared_four_five'>Infrared 4.5</th>
          <th scope="col"><a href='/history?infrared_five_eight'>Infrared 5.8</th>
          <th scope="col"><a href='/history?infrared_eight_zero'>Infrared 8.0</th>
          <th scope="col"><a href='/history?infrared_J'>Infrared j</th>
        <th scope="col"><a href='/history?infrared_H'>Infrared h</th>
        <th scope="col"><a href='/history?infrared_K'>Infrared k</th>
          <th scope="col"><a href='/history?radio_1.4'>Radio 1.4</th>
          <th scope="col"><a href='/history?redshift_result'>Redshift Result</th>


    </tr>
  </thead>
  <tbody>

        @foreach($user as $calculation)
         <tr>
    <th scope="row">{{ $calculation->assigned_calc_ID }}</th>
      <td>{{ $calculation->optical_u }}</td>
     <td>{{ $calculation->optical_v }}</td>
      <td>{{ $calculation->optical_g }}</td>
     <td>{{ $calculation->optical_r }}</td>
    <td>{{ $calculation->optical_i }}</td>
      <td>{{ $calculation->optical_z }}</td>
      <td>{{ $calculation->infrared_three_six }}</td>
      <td>{{ $calculation->infrared_four_five }}</td>
      <td>{{ $calculation->infrared_five_eight }}</td>
      <td>{{ $calculation->infrared_eight_zero }}</td>
      <td>{{ $calculation->infrared_J }}</td>
     <td>{{ $calculation->infrared_H }}</td>
      <td>{{ $calculation->infrared_K }}</td>
      <td>{{ $calculation->radio_one_four }}</td>
      <td>{{ $calculation->redshift_result }}</td>
 </tr>
        @endforeach

  </tbody>
</table>
{{ $user->links()}}
 @endif
</div>

</body>
         @endsection
