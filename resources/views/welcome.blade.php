{{--  @props(['tasks', 'dateFormat', 'usingOhDear'])  --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<div class="space-y-1">
    <x-schedule-monitor::title>Monitored Tasks</x-schedule-monitor::title>
<h1>Email logs</h1>
<table class="table">
    <thead>
      <tr>
        <th scope="col">#</th>
          <th scope="col">To Email</th>
        <th scope="col">Send at</th>
        <th scope="col">Sent</th>
        <th scope="col">User Id</th>
      </tr>
    </thead>
    <tbody>
        @foreach ($users = DB::table('email_logs')->get(); as $user)
      <tr>
        <td>{{ $loop->iteration }}</td>
          <td>{{ $user->to_email_address }}</td>
        <td>{{ $user->send_date }}</td>
        <td> @if ($user->is_send == 1) true @else false @endif</td>
        <td>{{ $user->user_id }}</td>
      </tr>
@endforeach

    </tbody>
  </table>
