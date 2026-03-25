<x-layouts.auth-layout subtitle="{{ empty($subtitle) ? 'Bundles' : $subtitle }}">
   <div class="main-card overflow-auto">

     <p class="text-center text-2xl mb-6">

        Fila de espera: <span class="font-bold text-slate-900 " >{{ $queue->id }}</span>
     </p>

     <div class="flex justify-center gap-4 mb-6">

        <div class="main-card">
            <p class="text-center" >Total de tickets</p>
            <p class="text-center text-bold !text-4xl text-black">{{ $queue->total_tickets}}</p>
        </div>

         <div class="main-card">
            <p class="text-center" >Total em espera</p>
            <p class="text-center text-bold !text-4xl text-black">{{ $queue->total_waiting}}</p>
        </div>

        <div class="main-card">
            <p class="text-center" >Total chamados</p>
            <p class="text-center text-bold !text-4xl text-black">{{ $queue->total_called}}</p>
        </div>

         <div class="main-card">
            <p class="text-center" >Total não atendidos </p>
            <p class="text-center text-bold !text-4xl text-black">{{ $queue->total_not_attended}}</p>
        </div>

         <div class="main-card">
            <p class="text-center" >Total de desistencias </p>
            <p class="text-center text-bold !text-4xl text-black">{{ $queue->total_dismissed}}</p>
        </div>
     </div>

     @if($queue->total_waiting === 0)

       <p class="text-center text-1xl text-slate-400 mb-6">Não há tickets parar dispensar</p>
       
       <div class="text-center">
          <a href="{{ route('caller.home') }}" class="btn !px-8">Voltar</a>
       </div>

       
     @else
       <p class="text-center text-2xl">Existem <span class="text-bold text-red-500">{{ $queue->total_waiting }}</span> Tickets em espera</p>
       


     @endif

   </div>
</x-layouts.auth-layout>
