<x-idp-layout>
    <form action="" method="post">
        @csrf
        @samlidp

        <div class="grid grid-cols-1 gap-4">

            @foreach(\App\Models\User::all() as $user)
            <div>

                <label class="relative flex items-center px-4 py-3 space-x-3 bg-white border border-gray-300 rounded-lg shadow-sm cursor-pointer group focus-within:ring-2 focus-within:ring-indigo-500 focus-within:ring-offset-2 hover:border-gray-400">
                  <div class="flex-shrink-0">
                    <input type="radio" name="user_id" id="user_id_{{ $user->id }}" value="{{ $user->id }}">
                  </div>
                  <div class="flex-1 min-w-0">
                      <p class="text-sm font-medium text-gray-900">
                        {{ $user->display }}
                    </p>
                    <p class="text-sm text-gray-500 truncate">
                        {{ $user->email }}
                    </p>
                  </div>
                </label>
            </div>
            @endforeach

            <button type="submit" class="w-full text-center bg-gray-950 px-3 py-1.5 text-white rounded-lg hover:bg-gray-800 transition">
                Login
            </button>
        </div>
    </form>
</x-idp-layout>
