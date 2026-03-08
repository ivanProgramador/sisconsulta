<x-layouts.auth-layout subtitle="{{ empty($subtitle) ? 'Bundles' : $subtitle }}">
   <div class="main-card overflow-auto">
       <div class="flex justify-between items-center">
          <p class="title-2">Chamador</p>
       </div>
       <hr class="my-4">
    @if(empty($queues))
       <p class="text-center text-slate-400 ">Não foram encontradas filas</p>
    @else
       <table id="table-queues">
         <thead class="bg-black text-white">
             <tr>
               <th class="text-xs w-2/14">Nome</th>
               <th class="text-xs w-2/14">Serviço</th>
               <th class="text-xs w-2/14">Balcão</th>
               <th class="text-xs text-center w-1/14">Estado</th>
               <th class="text-xs text-center w-1/14">Tickets</th>
               <th class="text-xs text-center w-1/14">Ignorados</th>
               <th class="text-xs text-center w-1/14">Não atendidos</th>
               <th class="text-xs text-center w-1/14">Atendidos</th>
               <th class="text-xs text-center w-1/14">Em espera</th>
               <th class="text-xs text-center w-1/14"></th>
             </tr>
         </thead>
         <tbody>
             @foreach ($queues as $queue)
                  <tr class="{{ $queue->deleted_at ? 'text-red-300' : '' }}">
                     <td>{{ $queue->name }}</td>
                     <td>{{ $queue->service_name }}</td>
                     <td>{{ $queue->service_desk }}</td>

                     @if($queue->deleted_at === null)
                        <td>{!! getQueueStateIcon($queue->status) !!}</td>
                     @else
                        <td><i class="fa-regular fa-trash-can"></i></td>
                     @endif

                     <td>{{ $queue->total_tickets }}</td>
                     <td>{{ $queue->total_dismissed }}</td>
                     <td>{{ $queue->total_not_attended }}</td>
                     <td>{{ $queue->total_called }}</td>
                     <td>{{ $queue->total_waiting }}</td>

                     <td class="text-end">
                        <a href="{{ route('caller.queue.details',['id'=>Crypt::encrypt($queue->id)]) }}" class="btn">
                           <i class="fa-solid fa-share-from-square me-2"></i> Chamadas
                        </a>
                     </td>
                  </tr>
                  @endforeach
         </tbody>
       </table>
    @endif
    </div>

    <script>
       
       $(document).ready(function(){

            $('#table-queues').DataTable({
               //traduzindo os componetes pra portugues

               language:{
                  url:"{{ asset('assets/datatables/pt-PT.json') }}"
              }
            }
        );
          
        });


    </script>
</x-layouts.auth-layout>
