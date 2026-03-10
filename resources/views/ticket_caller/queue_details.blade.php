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

      <div class="w-1/4 rouded-xl border-1 border-slate-400 text-center p-4" >
         <div class="flex gap-6 mb-4">
            <p class="title-3">Última senha chamada:</p>
            @if(empty($lastTicket))
               <p class="text-slate-400 my-4">Não existe senha anterior</p>
            @else 
               <p class="text-6xl font-bold">{{ $lastTicket->queue_ticket_number }}</p>
            @endif
            </div>
      </div>

      <div class="w-1/4 rouded-xl border-1 border-slate-400 text-center p-4">
            <div class="flex gap-6 mb-4">
            <p class="title-3">Próxima senha:</p>
            @if(empty($nextTicket)) 
               <p class="text-slate-400 my-4">Não existe próxima senha</p>
            @else 
               <p class="text-6xl font-bold">{{ $nextTicket->queue_ticket_number }}</p>
            @endif
            </div>
       </div>



</x-layouts.auth-layout >
