<x-layouts.auth-layout subtitle="{{ $subtitle }}">

    <div class="main-card overflow-auto">

        <div class="flex justify-between">
            <p class="title-3">Clientes</p>
        </div>

        <hr class="my-4">

        <div class="mb-4">
            <a href="#" class="btn"><i class="far fa-plus me-2"></i>Novo cliente...</a>
        </div>

        @if($clients->count() === 0)

    <div class="text-center my-12 text-gray-500">
        <p class="text-lg">Não existem clientes.</p>
        <p class="text-sm">Clique no botão acima para criar um novo cliente</p>
    </div>

        @else

            <table id="table_clients" >
                <thead class="bg-black text-white">
                    <tr>
                        <th class="text-xs">Logo</th>
                        <th class="text-xs">Nome do cliente</th>
                        <th class="text-xs">Email</th>
                        <th class="text-xs">Telefone</th>
                        <th class="text-xs">Estado</th>
                        <th class="text-xs">Usuários</th>
                        <th class="text-xs">Cliente desde</th>
                        <th class="text-xs">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($clients as $client)
                        <tr class="{{ ( $client->status === 'inactive' || $client->deleted_at) ? 'bg-red-500 opacity-25' : '' }}" >
                            <td class="w-5/100"> <img src="{{ getCompanyLogo($client->company_logo) }}" class="h-10 w-10 {{ ( $client->status === 'inactive' || $client->deleted_at) ? 'grayscale-100' : '' }}"></td>
                            <td class="w-25/100">{{ $client->company_name }}</td>
                            <td class="w-15/100"> <i class="fa-solid fa-envelope me-2" ></i> {{ $client->email }}</td>
                            <td class="w-10/100"> <i class="fa-solid fa-phone me-2" ></i>  {{ $client->phone }}</td>
                            <td class="w-10/100 text-center">{!! getClientStatusIcon(($client)) !!}</td>
                            <td class="w-10/100"><i class="fa-solid fa-users me-2" ></i> {{ $client->users_count }}</td>
                            <td class="w-10/100">{{ $client->created_at }}</td>
                            <td class="w-15/100">[Ações]</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

        @endif
    </div>

    <script>
      
      document.addEventListener('DOMContentLoaded',function(){
         const messageElement = document.querySelector("#home_message");
         if(messageElement){
            setTimeout(() => {
               messageElement.remove();
            }, 3000);
         }
      });

      //apontando o datatabela pra minha tabela 

      $('#table_clients').DataTable({
         //traduzindo os componetes pra portugues

         language:{
             url:"{{ asset('assets/datatables/pt-PT.json') }}"
         }
      }
     );

   </script>

</x-layouts.auth-layout>
