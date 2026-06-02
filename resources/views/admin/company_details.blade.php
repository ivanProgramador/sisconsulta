<x-layouts.auth-layout subtitle="{{ $subtitle }}">

        <div class="main-card overflow-auto">

            <div class="flex justify-between">
                <p class="title-3">Detalhes de cliente</p>
                <a href="{ route('admin.home') }" class="btn"><i class="fa-solid fa-arrow-left me-2"></i>Voltar</a>
            </div>

            <hr class="my-4">

            {{-- Company Details --}}
            <div class="flex justify-between items-center">
                <p class="title-2">{{ $company->company_name }}</p>
                <p><i class="fa-solid fa-phone me-2"></i>{{ $company->phone }}</p>
                <p><i class="fa-solid fa-envelope me-2"></i>{{ $company->email }}</p>
            </div>

            <hr class="my-4">

            {{-- Users --}}

            <p class="title-3 mb-4">Usuários da empresa</p>

             @if($company->users->count() === 0)

                <div class="text-center my-12 text-gray-500">
                    <p class="text-lg">Não existem usuários para este cliente.</p>
                </div>
              
              @else


                <table id="table-users">
                    <thead class="bg-black text-white">
                        <tr>
                            <th class="text-xs">Email</th>
                            <th class="text-xs">Estado</th>
                            <th class="text-xs">Perfil</th>
                        </tr>
                    </thead>
                    <tbody>

                    @foreach($company->users as $user){

                        <tr>
                            <td class="w-50/100">{{ $user->role  }}</td>
                            <td class="w-25/100">{{ $user->status }}</td>
                            <td class="w-25/100">{{ $user->email }}</td>
                        </tr>

                    }
                    @endforeach

                        

                    </tbody>
                </table>
              
            @endif


            {{-- Queues --}}

            <p class="title-3 mb-4">Filas de espera</p>

                <div class="text-center my-12 text-gray-500">
                    <p class="text-lg">Não existem filas para este cliente.</p>
                </div>

                <table id="table-queues">
                    <thead class="bg-black text-white">
                    <tr>
                        <th class="text-xs">Nome</th>
                        <th class="text-xs">Serviço</th>
                        <th class="text-xs">Total</th>
                        <th class="text-xs">Dispensadas</th>
                        <th class="text-xs">Não atendidas</th>
                        <th class="text-xs">Em espera</th>
                        <th class="text-xs">Chamadas</th>

                    </tr>
                    </thead>
                    <tbody>

                        <tr>
                            <td class="w-25/100">[Nome]</td>
                            <td class="w-25/100">[Serviço]</td>
                            <td class="w-10/100">[Total]</td>
                            <td class="w-10/100">[Dispensadas]</td>
                            <td class="w-10/100">[Não atendidas]</td>
                            <td class="w-10/100">[Em espera]</td>
                            <td class="w-10/100">[Chamadas]</td>
                        </tr>

                    </tbody>
                </table>

        </div>
   

</x-layouts.auth-layout>