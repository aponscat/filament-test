
@if ($getState()!='' && strtotime($getState())<time())
<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="red" stroke-width="2">
  <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
</svg>
@else
{{ \Carbon\Carbon::parse($getState())->format('j F, Y') }}
@endif