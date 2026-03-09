<x-layouts.auth-layout subtitle="{{ empty($subtitle) ? 'Bundles' : $subtitle }}">
   <div class="main-card overflow-auto">
       <div class="flex justify-between items-center">
          <p class="title-3">{{ $queue->description }}</p>
          <p class="title-3">{{ $queue->description }}</p>
       </div>
       <hr class="my-4">

       <div class="flex gap-6 mb-4">
          <p class="title-3" >Serviço: <strong>{{ $queue->service_name}}</strong></p>
          <p class="title-3" >Balcão: <strong>{{ $queue->service_desk}}</strong></p>
       </div>


        <div class="flex gap-6 mb-4">
          <p class="title-3" >Última senha chamada:</p>
          @if(empty($lastTicket))
             <p class="tex-slate-400 my-4">Não existe senha anterior</p>
          @else 
             <p class="text-6xl font-bold">{{ $lastTicket->queue_ticket_number }}</p>
          @endif
         </div>

          <div class="flex gap-6 mb-4">
          <p class="title-3" >Proxima senha:</p>
          @if(empty($lastTicket))
             <p class="tex-slate-400 my-4">Não existe proxima senha</p>
          @else 
             <p class="text-6xl font-bold">{{ $nextTicket->queue_ticket_number }}</p>
          @endif
         </div>




</x-layouts.auth-layout >
