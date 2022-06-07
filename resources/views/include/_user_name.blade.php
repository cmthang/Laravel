<span class="name-user">{{ $item->name }}</span><br>
<a href="{{ route('user.detail', ['email' => $item->email]) }}"><b>{{ $item->email }}</b></a>