<x-layouts.guest-layout subtitle="{{ $subtitle }}">
  
    <div class="flex flex-col justify-center h-screen items-center">

         <div class="flex items-center mb-4" >
            <img src="{{ asset('assets/images/ticket_dispenser_logo.png')}}" class="w-20 h-20">
             <p class="text-5xl">Dispensador</p>
         </div>
         <div class="main-card w-200">
            <form action="{{ route('dispenser.credentials.submit') }}" method="post" class="flex flex-col gap-4">

                @csrf

                    <div class="mb-4">
                        <label for="credential_username" class="label">credential username</label>
                        <input type="text" class="input w-full" id="credential_username" name="credential_username" value="{{ old('credential_username')}}">
                        {{-- a funcção que vou chamar mostra uma html pronto então esse tipo de retorno deve ser passado usando
                            essa diretiva  1 dupla de chave 2 duplas de exclamações {!! !!}
                        --}}

                        {!! ShowValidationError('credential_username',$errors)  !!}
                        {!! ShowServerError()  !!}

                    </div>

                    <div class="mb-4">
                        <label for="credential_password" class="label">credential password</label>
                        <input type="credential_password" class="input w-full" id="credential_password" name="credential_password"  value="{{ old('credential_password')}}">
                        {!! ShowValidationError('credential_password',$errors)  !!}
                        {!! ShowValidationError('credential_password',$errors)  !!}
                        {!! ShowServerError()  !!}
                    </div>

                    <div class="text-center mb-4">
                        <button type="submit" class="btn w-full"><i class="fa-solid fa-up-right-from-square me-2"></i>Apresentar filas</button>
                    </div>


            </form>
         </div>
        
    </div>

</x-layouts.guest-layout>
